<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class ApiLoginTest extends TestCase
{
    
    public function setUp(): void
    {
        parent::setUp();
        \Artisan::call('passport:install');  
    }
    /**
    * @group apilogintests
    */  
    use RefreshDatabase;

    public function testApiLoginFail() {
        $user = User::create(['name' => 'test', 'email' => 'test@gmail.com', 'password' => '12345678', 'password_confirmation', '12345678']);
        
        $body = [
            'email' => 'test@gmail.com',
            'password' => 'wrong'
        ];
        // dd($user, $this->json('POST','/api/login',$body,['Accept' => 'application/json']));
        
        $this->json('POST','/api/login',$body,['Accept' => 'application/json'])
            ->assertStatus(400);
    }

    /**
    * @group apilogintests
    */    
    public function testApiLogin() {
        $user = User::create(['name' => 'test', 'email' => 'test@gmail.com', 'password' => '12345678', 'password_confirmation', '12345678']);
        $body = [
            'email' => 'test@gmail.com',
            'password' => '12345678'
        ];
        $this->json('POST','/api/login',$body,['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(['user','access_token']);
    }
  
}
