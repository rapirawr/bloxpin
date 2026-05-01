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
        $imagePath = $file->store('photos/originals', 'public');

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

        // Attach tags
        if (!empty($tags)) {
            $this->syncTags($photo, $tags);
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
        Storage::disk('public')->put($thumbnailName, file_get_contents($tempPath));

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
        Storage::disk('public')->delete($photo->image_path);
        Storage::disk('public')->delete($photo->thumbnail_path);

        // Delete the record (cascade will handle likes, pins, tags)
        $photo->delete();
    }
}
