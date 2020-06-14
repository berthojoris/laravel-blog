<?php

use App\Models\Comment;
use App\Models\MediaLibrary;
use App\Models\Post;
use App\Models\Role;
use App\Models\Token;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Roles
        Role::firstOrCreate(['name' => Role::ROLE_EDITOR]);
        $role_admin = Role::firstOrCreate(['name' => Role::ROLE_ADMIN]);
        $role_user = Role::firstOrCreate(['name' => Role::ROLE_USER]);

        // MediaLibrary
        MediaLibrary::firstOrCreate([]);

        // Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('malaquena'),
                'email_verified_at' => now()
            ]
        );

        $user = User::firstOrCreate(
            ['email' => 'bertho@gmail.com'],
            [
                'name' => 'Bertho',
                'password' => Hash::make('malaquena'),
                'email_verified_at' => now()
            ]
        );

        $admin->roles()->sync([$role_admin->id]);
        $user->roles()->sync([$role_user->id]);

        // Posts
        $post = Post::firstOrCreate(
            [
                'title' => 'Hello World',
                'author_id' => $admin->id
            ],
            [
                'posted_at' => now(),
                'content' => "Welcome to Laravel-blog !<br><br>
                    Don't forget to read the README before starting.<br><br>
                    Feel free to add a star on Laravel-blog on Github !<br><br>
                    You can open an issue or (better) a PR if something went wrong."
            ]
        );

        // Comments
        Comment::firstOrCreate(
            [
                'author_id' => $admin->id,
                'post_id' => $post->id
            ],
            [
                'posted_at' => now(),
                'content' => "Hey ! I'm a comment as example."
            ]
        );

        // API tokens
        User::where('api_token', null)->get()->each->update([
            'api_token' => Token::generate()
        ]);
    }
}
