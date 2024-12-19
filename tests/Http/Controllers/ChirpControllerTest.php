<?php

namespace Tests\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ChirpControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_stores_a_chirp_successfully_with_valid_data(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('chirps.store'), [
            'message' => 'This is a test chirp.',
        ]);

        $response->assertRedirect(route('chirps.index'));
        $this->assertDatabaseHas('chirps', [
            'message' => 'This is a test chirp.',
            'user_id' => $user->id,
        ]);
    }

    #[Test]
    public function it_fails_to_store_a_chirp_when_message_is_missing(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('chirps.store'), [
            'message' => '',
        ]);

        $response->assertSessionHasErrors(['message']);
        $this->assertDatabaseMissing('chirps', [
            'user_id' => $user->id,
        ]);
    }

    #[Test]
    public function it_fails_to_store_a_chirp_when_message_exceeds_max_length(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('chirps.store'), [
            'message' => str_repeat('a', 256),
        ]);

        $response->assertSessionHasErrors(['message']);
        $this->assertDatabaseMissing('chirps', [
            'user_id' => $user->id,
        ]);
    }

    #[Test]
    public function it_redirects_to_login_if_unauthenticated_user_attempts_to_store_a_chirp(): void
    {
        $response = $this->post(route('chirps.store'), [
            'message' => 'This is a test chirp.',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('chirps', [
            'message' => 'This is a test chirp.',
        ]);
    }
}
