<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Building;
use Laravel\Passport\Passport;

class UserTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        \Artisan::call('passport:install');  
    }

    use RefreshDatabase;

    /** @test */
    public function testShowUsersOk()
    {
        Passport::actingAs(
            factory(User::class)->create(),
            ['/api/building']
        );

        $user = User::create(['name' => 'test', 'email' => 'test@gmail.com', 'password' => '12345678', 'password_confirmation', '12345678']);

        $response = $this->get('/api/user');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'accessLog' => [
                        '*' => [
                            'id',
                            'building_id',
                            'building_name',
                            'block',
                            'date',
                            'type_name',
                        ],
                    ]
                ],
            ],
            'links' => [
                'first',
                'last',
                'next',
                'prev',
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'path',
                'per_page',
                'to',
                'total',
            ],
        ]);
    }

    /** @test */
    public function SuccessfulPost()
    {
        Passport::actingAs(
            factory(User::class)->create(),
            ['/api/building']
        );

        $response = $this->post('/api/user', ['name' => 'test', 'email' => 'test@gmail.com', 'password' => '12345678', 'password_confirmation' => '12345678']);

        $response->assertStatus(201);

        $id = $response->json('user.id');
        $user = User::find($id);
        $this->assertEquals('test', $user->name);
        $this->assertEquals('test@gmail.com', $user->email);
    }

    /** @test */
    public function testValidations()
    {
        Passport::actingAs(
            factory(User::class)->create(),
            ['/api/building']
        );

        $response = $this->postJson('/api/user', $this->invalidData());

        $response->assertStatus(422);
    }

    public function invalidData()
    {
        return [
            [['name' => null], 'name'],
            [['name' => ''], 'name'],
            [['name' => []], 'name'],
            [['name' => [673890]], 'name'],
            [['email' => null], 'email'],
            [['email' => ''], 'email'],
            [['email' => 'text'], 'email'],
            [['email' => []], 'email'],
            [['email' => [673890]], 'email'],
            [['password' => null], 'password'],
            [['password' => ''], 'password'],
            [['password' => []], 'password'],
            [['password_confirmation' => null], 'password_confirmation'],
            [['password_confirmation' => ''], 'password_confirmation'],
            [['password_confirmation' => []], 'password_confirmation'],
        ];
    }

    /** @test */
    public function SuccessfulUpdate()
    {
        Passport::actingAs(
            factory(User::class)->create(),
            ['/api/building']
        );
        $user = User::create(['name' => 'test', 'email' => 'test@gmail.com', 'password' => '12345678', 'password_confirmation', '12345678']);

        $response = $this->put('/api/user/'.$user->id.'?name=test2&email=test2@gmail.com&password=87654321&password_confirmation=87654321');
        $response->assertStatus(201);

        $id = $response->json('user.id');
        $user = User::find($id);
        $this->assertEquals('test2', $user->name);
        $this->assertEquals('test2@gmail.com', $user->email);
    }

    /** @test */
    public function SuccessfulAccessPost()
    {
        Passport::actingAs(
            factory(User::class)->create(),
            ['/api/building']
        );

        $user = User::create(['name' => 'test', 'email' => 'test@gmail.com', 'password' => '12345678', 'password_confirmation', '12345678']);
        $building = Building::create(['name' => 'building 1']);
        $response = $this->post('/api/user/'.$user->id.'/storeAccess', ['building_id' => $building->id, 'type' => 1, 'block' => 'sector B', 'date' => '2021-01-24 14:00:00']);
        $response->assertStatus(200);

        $data = $response->json('data.accessLog.0');
        $id = $response->json('data.id');
        $user = User::with('latestAccess')->find($id);

        $this->assertEquals($building->id, $user->latestAccess->building_id);
        $this->assertEquals(1, $user->latestAccess->building_id);
    }
    
    /** @test */
    public function InvalidAccessPost()
    {
        Passport::actingAs(
            factory(User::class)->create(),
            ['/api/building']
        );

        $user = User::create(['name' => 'test', 'email' => 'test@gmail.com', 'password' => '12345678', 'password_confirmation', '12345678']);
        $building = Building::create(['name' => 'building 1']);
        $user->accessLogs()->create(['building_id' => $building->id, 'type' => 1, 'block' => 'sector B', 'date' => '2021-01-24 14:00:00']);
        $response = $this->post('/api/user/'.$user->id.'/storeAccess', ['building_id' => $building->id, 'type' => 1, 'block' => 'sector B', 'date' => '2021-01-24 14:00:00']);
        $response->assertStatus(422);
        
    }
}

