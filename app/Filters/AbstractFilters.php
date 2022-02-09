<?php

namespace App\Filters;

use App\Exceptions\BusinessException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class AbstractFilters
{
    protected Request $request;
    protected array $filters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function filter(Builder $builder, $orderFilters = [])
    {
        if (!empty(array_diff($orderFilters, array_keys($this->filters)))) {
            throw new BusinessException('Фильтер не найден', 'object_not_found', 404);
        }

        foreach ($this->getOrderFilter($orderFilters) as $orderFilter => $value) {
            $this->resolveFilter($orderFilter)->filter($builder, $value);
        }

        return $builder;
    }

    public function add(array $filters)
    {
        $this->filters = array_merge($this->filters, $filters);

        return $this;
    }

    private function getOrderFilter($order)
    {
        return array_filter($this->request->only($order));
    }

//    private function getFilter()
//    {
//        return array_filter($this->request->only(array_keys($this->filters)));
//    }

    private function resolveFilter($filter)
    {
        return new $this->filters[$filter];
    }
}
