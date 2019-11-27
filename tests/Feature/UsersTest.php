<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAFirstUserIsAdmin()
    {
        //$this->withoutExceptionHandling();
        
        $this->assertTrue(\App\User::first()->isAdmin());
    }
}