<?php

namespace App\Http\Controllers\ApiComplianceGateway\Complaints;

use App\DTOs\Complaints\StoreComplaintDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\Complaints\StoreComplaintRequest;
use App\UseCases\Complaints\StoreComplaintUseCase;

class ComplaintsController extends Controller
{
    public function store(StoreComplaintRequest $request, StoreComplaintUseCase $storeComplaintUseCase)
    {
        $storeComplaintDTO = StoreComplaintDTO::fromArray($request->validated());

        return $storeComplaintUseCase->execute($storeComplaintDTO);
    }
}
