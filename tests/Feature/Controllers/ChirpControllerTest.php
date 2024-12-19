<?php

namespace Tests\Feature\Controllers;

use App\Models\Chirp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
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

    #[Test]
    public function it_returns_a_successful_response_on_index(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('chirps.index'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('Chirps/Index')
        );
    }

    #[Test]
    public function it_displays_all_chirps_on_index(): void
    {
        $user = User::factory()->create();
        $chirp1 = Chirp::factory()->for($user)->create(['message' => 'First chirp']);
        $chirp2 = Chirp::factory()->for($user)->create(['message' => 'Second chirp']);

        $this->actingAs($user);

        $response = $this->get(route('chirps.index'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('Chirps/Index')
            ->has('chirps', 2)
            ->where('chirps.0.message', $chirp1->message)
            ->where('chirps.1.message', $chirp2->message)
        );
    }

    #[Test]
    public function it_updates_a_chirp_successfully_with_valid_data(): void
    {
        $user = User::factory()->create();
        $chirp = Chirp::factory()->for($user)->create(['message' => 'Initial message']);

        $this->actingAs($user);

        $response = $this->put(route('chirps.update', $chirp), [
            'message' => 'Updated message',
        ]);

        $response->assertRedirect(route('chirps.index'));
        $this->assertDatabaseHas('chirps', [
            'id' => $chirp->id,
            'message' => 'Updated message',
        ]);
    }

    #[Test]
    public function it_fails_to_update_a_chirp_when_message_is_missing(): void
    {
        $user = User::factory()->create();
        $chirp = Chirp::factory()->for($user)->create(['message' => 'Initial message']);

        $this->actingAs($user);

        $response = $this->put(route('chirps.update', $chirp), [
            'message' => '',
        ]);

        $response->assertSessionHasErrors(['message']);
        $this->assertDatabaseHas('chirps', [
            'id' => $chirp->id,
            'message' => 'Initial message', // Should remain unchanged
        ]);
    }

    #[Test]
    public function it_fails_to_update_a_chirp_when_message_exceeds_max_length(): void
    {
        $user = User::factory()->create();
        $chirp = Chirp::factory()->for($user)->create(['message' => 'Initial message']);

        $this->actingAs($user);

        $response = $this->put(route('chirps.update', $chirp), [
            'message' => str_repeat('a', 256),
        ]);

        $response->assertSessionHasErrors(['message']);
        $this->assertDatabaseHas('chirps', [
            'id' => $chirp->id,
            'message' => 'Initial message', // Should remain unchanged
        ]);
    }

    #[Test]
    public function it_prevents_updating_a_chirp_by_unauthorized_user(): void
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();
        $chirp = Chirp::factory()->for($author)->create(['message' => 'Initial message']);

        $this->actingAs($otherUser);

        $response = $this->put(route('chirps.update', $chirp), [
            'message' => 'Updated message',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('chirps', [
            'id' => $chirp->id,
            'message' => 'Initial message', // Should remain unchanged
        ]);
    }

    #[Test]
    public function it_redirects_to_login_when_unauthenticated_user_attempts_to_update_a_chirp(): void
    {
        $chirp = Chirp::factory()->create(['message' => 'Initial message']);

        $response = $this->put(route('chirps.update', $chirp), [
            'message' => 'Updated message',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('chirps', [
            'id' => $chirp->id,
            'message' => 'Initial message', // Should remain unchanged
        ]);
    }
}
