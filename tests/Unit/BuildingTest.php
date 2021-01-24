<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Building;
use Laravel\Passport\Passport;



class BuildingTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        \Artisan::call('passport:install');  
    }

    use RefreshDatabase;

    /** @test */
    public function testShowBuildingsOk()
    {
        Passport::actingAs(
            factory(User::class)->create(),
            ['/api/building']
        );

        $building1 = Building::create(['name' => 'building 1']);

        $response = $this->get('/api/building');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'accesLogsConuter'
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

        $response = $this->post('/api/building', ['name' => 'Building 1']);

        $response->assertStatus(201);

        $id = $response->json('data.id');
        $building = Building::find($id);
        $this->assertEquals('Building 1', $building->name);
    }

    /** @test */
    public function testValidation()
    {
        Passport::actingAs(
            factory(User::class)->create(),
            ['/api/building']
        );

        $response = $this->postJson('/api/building', $this->invalidData());

        $response->assertStatus(422);
    }

    public function invalidData()
    {
        return [
            [['name' => null], 'name'],
            [['name' => ''], 'name'],
            [['name' => []], 'name'],
            [['name' => [673890]], 'name'],
        ];
    }

    /** @test */
    public function SuccessfulUpdate()
    {
        Passport::actingAs(
            factory(User::class)->create(),
            ['/api/building']
        );
        $building = Building::create(['name' => '6hgff44g']);
        $response = $this->put('/api/building/'.$building->id.'?name=h6rhrt');

        $response->assertStatus(200);

        $id = $response->json('data.id');
        $building = Building::find($id);
        $this->assertEquals('h6rhrt', $building->name);
    }
}
