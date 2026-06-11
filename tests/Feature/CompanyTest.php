<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_company(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/companies', [
            'name' => 'テスト株式会社',
            'status' => 'エントリー',
        ]);

        $response->assertRedirect('/companies');
        $this->assertDatabaseHas('companies', [
            'user_id' => $user->id,
            'name' => 'テスト株式会社',
        ]);
    }

    public function test_user_sees_only_own_companies(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();
        Company::factory()->create(['user_id' => $me->id, 'name' => '自分の会社']);
        Company::factory()->create(['user_id' => $other->id, 'name' => '他人の会社']);

        $response = $this->actingAs($me)->get('/companies');

        $response->assertSee('自分の会社');
        $response->assertDontSee('他人の会社');
    }

    public function test_guest_cannot_access_companies(): void
    {
        $this->get('/companies')->assertRedirect('/login');
    }
}
