<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Foundation\Testing\WithoutMiddleware;


class MercatorTest extends TestCase
{

    use WithoutMiddleware; 

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->withoutMiddleware();
        
        $response = $this->get('/');
        $response->assertStatus(302);

        $response = $this->get('/admin');
        $response->assertStatus(302);

        $response = $this->get('/login');
        $response->assertStatus(200);

        // login test
        $response = $this->post(config('app.url') . '/login', 
                ['email' => 'admin@admin.com',
                 'password' => 'password']);
        $response->assertStatus(200);
        $response->assertCookieNotExpired($cookieName);

        // Ecosystem
        $user="admin";
        $response = $this->actingAs($user)
            ->get(config('app.url') . '/admin/report/ecosystem');        
        $response->assertStatus(200);
    }
}
