<?php

namespace Database\Seeders;

use App\Models\Board;
use App\Models\User;
use App\Services\PhotoService;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create a test user
        $user = User::factory()->create([
            'name' => 'Bloxpin Admin',
            'username' => 'admin',
            'email' => 'admin@bloxpin.com',
            'password' => Hash::make('password'),
            'bio' => 'A Roblox enthusiast and collector of aesthetic avatars.',
        ]);

        $user2 = User::factory()->create([
            'name' => 'Roblox Gamer',
            'username' => 'robloxgamer',
            'email' => 'gamer@bloxpin.com',
            'password' => Hash::make('password'),
            'bio' => 'Obby speedrunner & simulator player.',
        ]);

        // 2. Create some boards
        $board1 = Board::create([
            'user_id' => $user->id,
            'title' => 'Aesthetic Avatars',
            'description' => 'Collection of cool and trendy Roblox avatars.',
        ]);

        $board2 = Board::create([
            'user_id' => $user->id,
            'title' => 'Game Environments',
            'description' => 'Beautiful worlds and maps.',
        ]);

        // 3. Seed Photos using PhotoService
        $photoService = new PhotoService();
        $this->command->info('Uploading dummy photos... this might take a few seconds to generate thumbnails.');

        $dummyImages = [
            [
                'path' => 'photos/avatar1.png',
                'title' => 'Cool Streetwear Avatar',
                'desc' => 'Y2K fashion style roblox avatar.',
                'tags' => 'avatar, streetwear, y2k, cool',
                'board_id' => $board1->id,
                'user' => $user,
            ],
            [
                'path' => 'photos/avatar2.png',
                'title' => 'Kawaii Pastel Character',
                'desc' => 'Soft aesthetic, cute avatar with pastel themes.',
                'tags' => 'avatar, kawaii, cute, pastel, aesthetic',
                'board_id' => $board1->id,
                'user' => $user,
            ],
            [
                'path' => 'photos/env1.png',
                'title' => 'Neon Floating Obby',
                'desc' => 'An aesthetic obby game level with sunset lighting.',
                'tags' => 'game, environment, obby, sunset, neon',
                'board_id' => $board2->id,
                'user' => $user2,
            ],
            [
                'path' => 'photos/env2.png',
                'title' => 'Low Poly Simulator Town',
                'desc' => 'Vibrant town square for a simulator game.',
                'tags' => 'game, environment, simulator, lowpoly, town',
                'board_id' => $board2->id,
                'user' => $user2,
            ],
        ];

        // We will seed the 4 images, and then duplicate them once to make the gallery look fuller (total 8)
        foreach (array_merge($dummyImages, $dummyImages) as $index => $data) {
            $absPath = storage_path('app/public/' . $data['path']);
            
            if (file_exists($absPath)) {
                // Copy the original file to a temp location so UploadedFile can process it without moving the source file
                $tempFile = tempnam(sys_get_temp_dir(), 'seeder_img');
                copy($absPath, $tempFile);

                $uploadedFile = new UploadedFile(
                    $tempFile,
                    basename($absPath),
                    'image/png',
                    null,
                    true
                );

                $title = $data['title'];
                if ($index >= 4) {
                    $title .= ' (Copy)';
                }

                $photoService->upload(
                    user: $data['user'],
                    file: $uploadedFile,
                    title: $title,
                    description: $data['desc'],
                    tags: $data['tags'],
                    boardId: $data['board_id']
                );
            }
        }

        $this->command->info('Database seeded successfully!');
    }
}
