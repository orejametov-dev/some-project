<?php

namespace App\Http\Controllers\ApiGate\Complaints;

use App\DTOs\Complaints\StoreComplaintDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiGate\Complaints\StoreComplaintRequest;
use App\UseCases\Complaints\StoreComplaintUseCase;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintsController extends Controller
{
    public function store(StoreComplaintRequest $request, StoreComplaintUseCase $storeComplaintUseCase): JsonResource
    {
        $complaint = $storeComplaintUseCase->execute(StoreComplaintDTO::fromArray($request->validated()));

        return new JsonResource($complaint);
    }
}
