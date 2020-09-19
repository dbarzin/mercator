<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MercatorTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');
        $response->assertStatus(302);

        $response = $this->get('/admin');
        $response->assertStatus(302);

        $response = $this->get('/login');
        $response->assertStatus(200);

        $response=$this->json('POST', '/login', 
                ['email' => 'admin@admin.com',
                 'password' => 'password']);

    }
}
