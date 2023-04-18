<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_user_login(){
        $user =[
            "email"=>"user@gmail.com",
            "password"=>"user_2023"
        ];

        $response = $this->post('/api/user/login',$user);
        $response->assertStatus('200');
        $this->assertRedirect('/');
    }
}
