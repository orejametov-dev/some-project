<?php

namespace App\Http\Controllers\ApiGate\Complaints;

use App\DTOs\Complaints\StoreComplaintDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiGate\Complaints\StoreComplaintRequest;
use App\UseCases\Complaints\StoreComplaintUseCase;

class ComplaintsController extends Controller
{
    public function store(StoreComplaintRequest $request, StoreComplaintUseCase $storeComplaintUseCase)
    {
        $storeComplaintDTO = StoreComplaintDTO::fromArray($request->validated());

        return $storeComplaintUseCase->execute($storeComplaintDTO);
    }
}
