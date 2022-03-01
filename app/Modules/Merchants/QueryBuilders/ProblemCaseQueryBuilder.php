<?php

namespace App\Modules\Merchants\QueryBuilders;

use App\Modules\Merchants\Models\ProblemCase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProblemCaseQueryBuilder extends Builder
{
    /**
     * @param array $columns
     * @return ProblemCaseQueryBuilder|ProblemCase|object|null
     */
    public function first($columns = ['*'])
    {
        return parent::first();
    }

    /**
     * @param $id
     * @param array $columns
     * @return ProblemCaseQueryBuilder|ProblemCaseQueryBuilder[]|Collection|ProblemCase|null
     */
    public function find($id, $columns = ['*'])
    {
        return parent::find($id, $columns);
    }

    /**
     * @param array $columns
     * @return ProblemCase[]|Collection
     */
    public function get($columns = ['*'])
    {
        return parent::get($columns);
    }
}
