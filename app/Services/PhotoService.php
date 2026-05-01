<?php

namespace App\Services;

use App\Models\Photo;
use App\Models\Pin;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PhotoService
{
    /**
     * Upload a new photo with thumbnail generation.
     */
    public function upload(
        User $user,
        UploadedFile $file,
        string $title,
        ?string $description = null,
        ?string $tags = '',
        ?int $boardId = null,
    ): Photo {
        // Get image dimensions
        $imageInfo = getimagesize($file->getPathname());
        $width = $imageInfo[0] ?? 800;
        $height = $imageInfo[1] ?? 600;

        // Store original image
        $imagePath = $file->store('photos/originals');

        // Generate thumbnail
        $thumbnailPath = $this->generateThumbnail($file, $imagePath);

        // Create photo record
        $photo = $user->photos()->create([
            'title' => $title,
            'description' => $description,
            'image_path' => $imagePath,
            'thumbnail_path' => $thumbnailPath,
            'width' => $width,
            'height' => $height,
        ]);

        // Auto-tagging AI (suggested tags from title and description)
        $suggestedTags = $this->suggestTags($title, $description);
        $finalTags = !empty($tags) ? $tags . ',' . $suggestedTags : $suggestedTags;

        // Attach tags
        if (!empty($finalTags)) {
            $this->syncTags($photo, $finalTags);
        }

        // Pin to board if specified
        if ($boardId) {
            Pin::create([
                'user_id' => $user->id,
                'photo_id' => $photo->id,
                'board_id' => $boardId,
            ]);
            $photo->increment('pins_count');

            $board = \App\Models\Board::find($boardId);
            if ($board) {
                $board->increment('photos_count');
            }
        }

        return $photo;
    }

    /**
     * Generate a thumbnail for the uploaded image.
     * Uses GD library for basic thumbnail generation.
     */
    protected function generateThumbnail(UploadedFile $file, string $originalPath): string
    {
        $maxWidth = 400;
        $extension = $file->getClientOriginalExtension();
        $thumbnailName = 'photos/thumbnails/' . pathinfo($originalPath, PATHINFO_FILENAME) . '_thumb.' . $extension;

        // Get original dimensions
        $imageInfo = getimagesize($file->getPathname());
        $origWidth = $imageInfo[0];
        $origHeight = $imageInfo[1];

        // Calculate new dimensions (maintain aspect ratio)
        if ($origWidth > $maxWidth) {
            $ratio = $maxWidth / $origWidth;
            $newWidth = $maxWidth;
            $newHeight = (int) ($origHeight * $ratio);
        } else {
            $newWidth = $origWidth;
            $newHeight = $origHeight;
        }

        // Check if GD library exists
        if (!function_exists('imagecreatefromjpeg')) {
            // Fallback: use original as thumbnail if GD is missing
            return $originalPath;
        }

        // Create image from file
        $mimeType = $imageInfo['mime'];
        $sourceImage = match ($mimeType) {
            'image/jpeg' => imagecreatefromjpeg($file->getPathname()),
            'image/png' => imagecreatefrompng($file->getPathname()),
            'image/gif' => imagecreatefromgif($file->getPathname()),
            'image/webp' => imagecreatefromwebp($file->getPathname()),
            default => imagecreatefromjpeg($file->getPathname()),
        };

        if (!$sourceImage) {
            // Fallback: use original as thumbnail
            return $originalPath;
        }

        // Create thumbnail
        $thumbnail = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG and GIF
        if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail, true);
        }

        imagecopyresampled($thumbnail, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        // Save thumbnail to temp file, then store via Storage
        $tempPath = tempnam(sys_get_temp_dir(), 'thumb_');

        match ($mimeType) {
            'image/jpeg' => imagejpeg($thumbnail, $tempPath, 85),
            'image/png' => imagepng($thumbnail, $tempPath, 8),
            'image/gif' => imagegif($thumbnail, $tempPath),
            'image/webp' => imagewebp($thumbnail, $tempPath, 85),
            default => imagejpeg($thumbnail, $tempPath, 85),
        };

        // Store via Laravel Storage
        Storage::put($thumbnailName, file_get_contents($tempPath));

        // Cleanup
        imagedestroy($sourceImage);
        imagedestroy($thumbnail);
        unlink($tempPath);

        return $thumbnailName;
    }

    /**
     * Sync tags from comma-separated string.
     */
    public function syncTags(Photo $photo, ?string $tagsString): void
    {
        if (empty($tagsString)) {
            $photo->tags()->detach();
            return;
        }

        $tagNames = array_filter(
            array_map('trim', explode(',', $tagsString))
        );

        $tagIds = [];
        foreach ($tagNames as $name) {
            if (!empty($name)) {
                $tag = Tag::findOrCreateByName($name);
                $tagIds[] = $tag->id;
            }
        }

        $photo->tags()->sync($tagIds);
    }

    /**
     * Delete a photo and its files.
     */
    public function delete(Photo $photo): void
    {
        // Delete files from storage
        Storage::delete($photo->image_path);
        Storage::delete($photo->thumbnail_path);

        // Delete the record (cascade will handle likes, pins, tags)
        $photo->delete();
    }
    /**
     * Suggest tags based on title and description.
     */
    protected function suggestTags(string $title, ?string $description = null): string
    {
        $text = $title . ' ' . ($description ?? '');
        $text = strtolower($text);
        
        // Remove special chars except spaces
        $text = preg_replace('/[^a-z0-9\s]/', '', $text);
        
        // Split into words
        $words = explode(' ', $text);
        
        // Filter: min 4 chars, not common words (stop words)
        $stopWords = [
            'dan', 'yang', 'dari', 'untuk', 'pada', 'adalah', 'dengan', 'saya', 'anda', 'ini', 'itu', 
            'juga', 'akan', 'bisa', 'ada', 'tidak', 'ia', 'ke', 'the', 'and', 'for', 'with', 'this', 'that'
        ];
        
        $suggested = array_filter($words, function($word) use ($stopWords) {
            $word = trim($word);
            return strlen($word) >= 4 && !in_array($word, $stopWords);
        });
        
        // Unique and limited to 5 tags
        $suggested = array_unique($suggested);
        return implode(', ', array_slice($suggested, 0, 5));
    }
}
