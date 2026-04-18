<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin Projek Cloud',
            'password' => Hash::make('password'),
        ]);

        $posts = [
            [
                'title' => 'Membangun Web Pribadi dengan Laravel dan Docker',
                'excerpt' => 'Catatan awal tentang membangun web pribadi yang mudah dijalankan secara lokal dan siap dideploy ke cloud.',
                'body' => "Laravel memberi struktur yang nyaman untuk membangun web pribadi, mulai dari routing, view, autentikasi, sampai koneksi database.\n\nDengan Docker, lingkungan pengembangan menjadi konsisten. Aplikasi dapat dijalankan di laptop, server, atau platform cloud dengan konfigurasi yang sama.\n\nPada proyek ini, blog digunakan sebagai fitur utama agar mahasiswa dapat memahami alur aplikasi web yang nyata: halaman publik, login, dashboard, dan manajemen konten.",
            ],
            [
                'title' => 'Deploy Laravel ke Railway dengan MySQL',
                'excerpt' => 'Railway mempermudah deployment aplikasi berbasis Docker dan penyediaan database MySQL untuk praktik cloud.',
                'body' => "Railway CLI dapat digunakan untuk membuat project, menambahkan database, mengatur variabel environment, dan melakukan deployment dari terminal.\n\nAplikasi Laravel ini membaca variabel MySQL dari Railway lalu memetakannya ke konfigurasi database Laravel.\n\nSetelah deployment berhasil, endpoint health check dapat dipakai untuk memastikan aplikasi dan database sudah terhubung.",
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::updateOrCreate([
                'slug' => Str::slug($post['title']),
            ], [
                ...$post,
                'user_id' => $admin->id,
                'slug' => Str::slug($post['title']),
                'is_published' => true,
                'published_at' => now(),
            ]);
        }
    }
}
