<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_homepage_lists_published_posts(): void
    {
        $user = User::factory()->create();
        $post = BlogPost::factory()->create([
            'user_id' => $user->id,
            'title' => 'Belajar Laravel di Cloud',
            'slug' => 'belajar-laravel-di-cloud',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Web pribadi');
        $response->assertSee($post->title);
    }

    public function test_blog_detail_shows_published_post(): void
    {
        $post = BlogPost::factory()->create([
            'title' => 'Deploy Laravel ke Railway',
            'slug' => 'deploy-laravel-ke-railway',
        ]);

        $this->get(route('blog.show', $post))
            ->assertStatus(200)
            ->assertSee($post->title);
    }

    public function test_dashboard_requires_login(): void
    {
        $this->get(route('dashboard.posts.index'))
            ->assertRedirect(route('login'));
    }

    public function test_user_can_login_and_create_blog_post(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->post(route('login.store'), [
            'email' => 'admin@example.com',
            'password' => 'password',
        ])->assertRedirect(route('dashboard.posts.index'));

        $this->actingAs($user)->post(route('dashboard.posts.store'), [
            'title' => 'Tulisan Baru dari Dashboard',
            'excerpt' => 'Ringkasan tulisan baru dari dashboard.',
            'body' => 'Isi tulisan baru yang cukup panjang untuk melewati validasi minimal.',
            'is_published' => '1',
        ])->assertRedirect(route('dashboard.posts.index'));

        $this->assertDatabaseHas('blog_posts', [
            'title' => 'Tulisan Baru dari Dashboard',
            'slug' => 'tulisan-baru-dari-dashboard',
            'is_published' => true,
        ]);
    }

    public function test_health_endpoint_returns_json_response(): void
    {
        $response = $this->getJson('/health');

        $response->assertJsonStructure([
            'status',
            'app',
            'environment',
            'database' => [
                'connection',
                'status',
            ],
        ]);
    }
}
