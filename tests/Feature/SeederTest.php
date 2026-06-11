<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_demo_data(): void
    {
        $this->seed();

        $this->assertDatabaseHas('users', ['email' => 'demo@example.com']);
        $this->assertTrue(Company::count() >= 1);
        $this->assertTrue(Event::count() >= 2); // 被りデモのため最低2件
    }
}
