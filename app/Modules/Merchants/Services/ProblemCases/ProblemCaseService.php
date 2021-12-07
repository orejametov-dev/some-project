<?php

namespace App\Modules\Merchants\Services\ProblemCases;

use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Modules\Merchants\DTO\ProblemCases\ProblemCaseDTO;
use App\Modules\Merchants\Models\ProblemCase;
use function Composer\Autoload\includeFile;

class ProblemCaseService
{
  public function create(ProblemCaseDTO $problemCasesDTO)
  {
      $problemCase = new ProblemCase();

      $problemCase->merchant_id = $problemCasesDTO->merchant_id;
      $problemCase->store_id = $problemCasesDTO->store_id;
      $problemCase->client_id = $problemCasesDTO->client_id;

      $problemCase->search_index = $problemCasesDTO->search_index;

      $problemCase->application_items = $problemCasesDTO->application_items;

      $problemCase->created_by_id = $problemCasesDTO->created_by_id;
      $problemCase->created_by_name = $problemCasesDTO->created_by_name;
      $problemCase->created_from_name = $problemCasesDTO->created_from_name;

      $problemCase->post_or_pre_created_by_id = $problemCasesDTO->post_or_pre_created_by_id;
      $problemCase->post_or_pre_created_by_name = $problemCasesDTO->post_or_pre_created_by_name;
      $problemCase->description = $problemCasesDTO->description;

      if ($problemCasesDTO->credit_number) {
          $problemCase->credit_number = $problemCasesDTO->credit_number;
          $problemCase->credit_contract_date =$problemCasesDTO->credit_contract_date;
      }
      if ($problemCasesDTO->application_id) {
          $problemCase->application_id = $problemCasesDTO->application_id;
          $problemCase->application_created_at = $problemCasesDTO->application_created_at;
      }

      $problemCase->setStatusNew();
      $problemCase->save();

      return $problemCase;
  }
}
