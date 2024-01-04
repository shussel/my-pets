<?php

namespace Tests\Feature;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PetTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_pets_dashboard_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/pets');

        $response->assertOk();
    }

    public function test_pets_create_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/pets/create');

        $response->assertOk();
    }

    public function test_pets_can_be_created(): void
    {
        $user = User::factory()->create();
        $pet_count = count($user->pets);
        $pet = Pet::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post("/pets", $pet->toArray());

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/pets');

        $user->refresh();
        $new_pet_count = count($user->pets);

        $this->assertSame($new_pet_count, $pet_count + 1);
    }

    public function test_pets_can_be_read(): void
    {
        $user = User::factory()->create();
        $pet_id = $user->pets[0]['_id'];

        $response = $this
            ->actingAs($user)
            ->get("/pets/$pet_id");

        $response
            ->assertSessionHasNoErrors();
    }

    public function test_pets_edit_is_displayed(): void
    {
        $user = User::factory()->create();
        $pet_id = $user->pets[0]['_id'];

        $response = $this
            ->actingAs($user)
            ->get("/pets/$pet_id/edit");

        $response->assertOk();
    }

    public function test_pets_can_be_updated(): void
    {
        $user = User::factory()->create();
        $pet = $user->pets[0];

        $pet->name = 'Change Name';
        $pet->weight = 40;

        $response = $this
            ->actingAs($user)
            ->patch("/pets/$pet->id", $pet->toArray());

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/pets');

        $user->refresh();
        $pet = $user->pets[0];

        $this->assertSame('Change Name', $pet->name);
        $this->assertSame(40, $pet->weight);
    }

    public function test_pets_can_be_deleted(): void
    {
        $user = User::factory()->create();
        $pet_count = count($user->pets);
        $pet = $user->pets[0];

        $response = $this
            ->actingAs($user)
            ->delete("/pets/$pet->id");

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/pets');

        $user->refresh();
        $new_pet_count = count($user->pets);

        $this->assertSame($new_pet_count + 1, $pet_count);
    }
}
