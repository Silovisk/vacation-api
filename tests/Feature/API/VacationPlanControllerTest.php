<?php

namespace Tests\Feature\API;

use App\Exceptions\VacationPlanException;
use App\Models\User;
use App\Models\VacationPlan;
use App\Services\VacationPlanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class VacationPlanControllerTest extends TestCase
{
    use RefreshDatabase;

    private VacationPlanService $vacationPlanService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->vacationPlanService = Mockery::mock(VacationPlanService::class);
        $this->app->instance(VacationPlanService::class, $this->vacationPlanService);
    }

    /**
     * Autentica um usuário e retorna o token de autenticação.
     *
     * @return string
     */
    protected function authenticate(): string
    {
        $user = User::factory()->create();
        $token = $user->createToken('MyApp')->plainTextToken;
        $this->actingAs($user, 'api');
        return $token;
    }


    public function test_can_list_vacation_plans()
    {
        $vacationPlans = VacationPlan::factory()->count(3)->make();
        $paginator = new LengthAwarePaginator($vacationPlans, 3, 15, 1);

        $this->vacationPlanService
            ->shouldReceive('getAllVacationPlans')
            ->with(15)
            ->andReturn($paginator);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->authenticate(),
        ])->getJson('/api/vacation-plan');

        $response->assertStatus(SymfonyResponse::HTTP_OK)
            ->assertJsonCount(3, 'data');
    }


    public function test_can_store_a_vacation_plan()
    {
        $data = [
            'title' => 'Holiday in Hawaii',
            'description' => 'A beautiful holiday in Hawaii.',
            'date' => '2024-01-01',
            'location' => 'Hawaii',
            'participants' => ['John Doe', 'Jane Smith'],
        ];

        $vacationPlan = VacationPlan::factory()->make($data);
        $vacationPlan->id = 1;

        $this->vacationPlanService
            ->shouldReceive('createVacationPlan')
            ->with($data)
            ->andReturn($vacationPlan);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->authenticate(),
        ])->postJson('/api/vacation-plan', $data);

        $response->assertStatus(SymfonyResponse::HTTP_CREATED)
            ->assertJsonFragment($data);
    }


    public function test_can_show_a_vacation_plan()
    {
        $vacationPlan = VacationPlan::factory()->create();

        $this->vacationPlanService
            ->shouldReceive('getVacationPlanById')
            ->with($vacationPlan->id)
            ->andReturn($vacationPlan);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->authenticate(),
        ])->getJson('/api/vacation-plan/' . $vacationPlan->id);

        $response->assertStatus(SymfonyResponse::HTTP_OK)
            ->assertJsonFragment([
                'id' => $vacationPlan->id,
                'title' => $vacationPlan->title,
                'description' => $vacationPlan->description,
                'date' => $vacationPlan->date->toDateString(),
                'location' => $vacationPlan->location,
                'participants' => $vacationPlan->participants,
            ]);
    }


    public function test_can_update_a_vacation_plan()
    {
        $vacationPlan = VacationPlan::factory()->create();
        $data = [
            'title' => 'Updated Holiday in Hawaii',
            'description' => 'Updated description.',
            'date' => '2024-02-01',
            'location' => 'Updated Location',
            'participants' => ['Alice', 'Bob'],
        ];

        $updatedVacationPlan = $vacationPlan->fill($data);
        $this->vacationPlanService
            ->shouldReceive('updateVacationPlan')
            ->with($vacationPlan->id, $data)
            ->andReturn($updatedVacationPlan);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->authenticate(),
        ])->putJson('/api/vacation-plan/' . $vacationPlan->id, $data);

        $response->assertStatus(SymfonyResponse::HTTP_OK)
            ->assertJsonFragment($data);
    }


    public function test_can_delete_a_vacation_plan()
    {
        $vacationPlan = VacationPlan::factory()->create();

        $this->vacationPlanService
            ->shouldReceive('destroyVacationPlan')
            ->with($vacationPlan->id)
            ->andReturn(true);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->authenticate(),
        ])->deleteJson('/api/vacation-plan/' . $vacationPlan->id);

        $response->assertStatus(SymfonyResponse::HTTP_NO_CONTENT);
    }


    public function test_handles_no_data_available_error_when_listing_vacation_plans()
    {
        $this->vacationPlanService
            ->shouldReceive('getAllVacationPlans')
            ->andThrow(VacationPlanException::noDataAvailable());

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->authenticate(),
        ])->getJson('/api/vacation-plan');

        $response->assertStatus(SymfonyResponse::HTTP_NOT_FOUND)
            ->assertJson([
                'success' => false,
                'message' => 'No vacation plan data available.',
            ]);
    }

    public function test_handles_not_found_error_when_showing_vacation_plan()
    {
        $this->vacationPlanService
            ->shouldReceive('getVacationPlanById')
            ->andThrow(VacationPlanException::notFound());

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->authenticate(),
        ])->getJson('/api/vacation-plan/1');

        $response->assertStatus(SymfonyResponse::HTTP_NOT_FOUND)
            ->assertJson([
                'success' => false,
                'message' => 'Vacation plan not found.',
            ]);
    }

    public function test_handles_not_created_error_when_storing_vacation_plan()
    {
        $this->vacationPlanService
            ->shouldReceive('createVacationPlan')
            ->andThrow(VacationPlanException::notCreated());

        $data = [
            'title' => 'Summer Vacation',
            'description' => 'A nice summer vacation.',
            'date' => '2024-08-30',
            'location' => 'Paris',
            'participants' => ['John Doe']
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->authenticate(),
        ])->postJson('/api/vacation-plan', $data);

        $response->assertStatus(SymfonyResponse::HTTP_BAD_REQUEST)
            ->assertJson([
                'success' => false,
                'message' => 'Vacation plan not created.',
            ]);
    }


    public function test_handles_not_updated_error_when_updating_vacation_plan()
    {
        $this->vacationPlanService
            ->shouldReceive('updateVacationPlan')
            ->andThrow(VacationPlanException::notUpdated());

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->authenticate(),
        ])->putJson('/api/vacation-plan/1', [
                    // Dados necessários para atualizar o plano de férias
                ]);

        $response->assertStatus(SymfonyResponse::HTTP_BAD_REQUEST)
            ->assertJson([
                'success' => false,
                'message' => 'Vacation plan not updated.',
            ]);
    }

    public function test_handles_not_deleted_error_when_destroying_vacation_plan()
    {
        $this->vacationPlanService
            ->shouldReceive('destroyVacationPlan')
            ->andThrow(VacationPlanException::notDeleted());

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->authenticate(),
        ])->deleteJson('/api/vacation-plan/1');

        $response->assertStatus(SymfonyResponse::HTTP_BAD_REQUEST)
            ->assertJson([
                'success' => false,
                'message' => 'Vacation plan not deleted.',
            ]);
    }


    public function test_can_generate_pdf_for_vacation_plan()
    {
        $vacationPlan = VacationPlan::factory()->create();

        $this->vacationPlanService
            ->shouldReceive('getVacationPlanById')
            ->with($vacationPlan->id)
            ->andReturn($vacationPlan);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->authenticate(),
        ])->getJson('/api/vacation-plan/' . $vacationPlan->id . '/generate-pdf');

        $response->assertStatus(SymfonyResponse::HTTP_OK)
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertHeader('Content-Disposition', 'attachment; filename=vacation_plan_' . $vacationPlan->id . '.pdf');
    }

}
