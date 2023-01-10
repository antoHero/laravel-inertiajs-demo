<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function test_category_screen_can_be_rendered()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/categories');

        $response->assertOk();
    }

    public function test_user_can_view_all_categories(): void
    {
        $response = $this->getJson('/categories');

        $this->assertEquals(1, count($response->json()));
    }
}
