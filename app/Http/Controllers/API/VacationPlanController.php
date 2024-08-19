<?php

namespace App\Http\Controllers\API;

use App\Exceptions\VacationPlanException;
use App\Http\Requests\IndexVacationPlanRequest;
use App\Http\Resources\VacationPlanResource;
use App\Models\VacationPlan;
use App\Http\Requests\StoreVacationPlanRequest;
use App\Http\Requests\UpdateVacationPlanRequest;
use App\Services\VacationPlanService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use PDF;

class VacationPlanController extends BaseController
{
    public function __construct
    (
        private VacationPlanService $vacationPlanService,
    )
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexVacationPlanRequest $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $vacationPlans = $this->vacationPlanService->getAllVacationPlans($perPage);

            return VacationPlanResource::collection($vacationPlans);
        } catch (VacationPlanException $e) {
            Log::error($e->getMessage(), ['vacation-plan-exception' => $e]);

            return $this->sendError(
                $e->getMessage(),
                [],
                $e->getStatusCode()
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return $this->sendError(
                'An unexpected error occurred.',
                [],
                SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVacationPlanRequest $request)
    {
        try {
            $vacationPlan = $this->vacationPlanService->createVacationPlan($request->validated());

            return $this->sendResponse(
                new VacationPlanResource($vacationPlan),
                'Vacation Plan create successfully.',
                SymfonyResponse::HTTP_CREATED
            );
        } catch (VacationPlanException $e) {
            Log::error($e->getMessage(), ['vacation-plan-exception' => $e]);

            return $this->sendError(
                $e->getMessage(),
                [],
                $e->getStatusCode()
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return $this->sendError(
                'An unexpected error occurred.',
                [],
                SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $vacationPlan = $this->vacationPlanService->getVacationPlanById($id);

            return $this->sendResponse(
                new VacationPlanResource($vacationPlan),
                'Vacation plan retrieved successfully.'
            );
        } catch (VacationPlanException $e) {
            Log::error($e->getMessage(), ['vacation-plan-exception' => $e]);

            return $this->sendError(
                $e->getMessage(),
                [],
                $e->getStatusCode()
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return $this->sendError(
                'An unexpected error occurred.',
                [],
                SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVacationPlanRequest $request, $id)
    {
        try {
            $vacationPlan = $this->vacationPlanService->updateVacationPlan($id, $request->validated());

            return $this->sendResponse(
                new VacationPlanResource($vacationPlan),
                'Vacation Plan update successfully.'
            );
        } catch (VacationPlanException $e) {
            Log::error($e->getMessage(), ['vacation-plan-exception' => $e]);

            return $this->sendError(
                $e->getMessage(),
                [],
                $e->getStatusCode()
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return $this->sendError(
                'An unexpected error occurred.',
                [],
                SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $vacationPlan = $this->vacationPlanService->destroyVacationPlan($id);

            return $this->sendResponse(
                [],
                'Vacation Plan delete successfully.',
                SymfonyResponse::HTTP_OK
            );
        } catch (VacationPlanException $e) {
            Log::error($e->getMessage(), ['vacation-plan-exception' => $e]);

            return $this->sendError(
                $e->getMessage(),
                [],
                $e->getStatusCode()
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return $this->sendError(
                'An unexpected error occurred.',
                [],
                SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Generate a PDF for the specified resource.
     */
    public function generatePDF($id)
    {
        try {
            $vacationPlan = $this->vacationPlanService->getVacationPlanById($id);

            $pdf = PDF::loadView('API.pdf.vacation_plan', ['vacationPlan' => $vacationPlan]);

            return $pdf->download('vacation_plan_' . $id . '.pdf');
        } catch (VacationPlanException $e) {
            Log::error($e->getMessage(), ['vacation-plan-exception' => $e]);

            return $this->sendError(
                $e->getMessage(),
                [],
                $e->getStatusCode()
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return $this->sendError(
                'An unexpected error occurred.',
                [],
                SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

}
