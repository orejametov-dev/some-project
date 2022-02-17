<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use function Composer\Autoload\includeFile;

/**
 * @property string[] $filters
 */
abstract class AbstractFilters
{
    protected array $filters = [];

    public function __construct(
        protected Request $request,
        protected Builder $builder
    ) {
    }

    abstract protected function getRequestBindings(): array;

    public function execute($filters = [])
    {
        $drainedFilters = $this->drainPassedFilters($filters);

        foreach ($drainedFilters as $filterName => $value) {
            if ($value !== null) {
                (new $filterName)->filter($this->builder, $value);
            }
        }

        return $this->builder;
    }

    /**
     * @property string[] $filters
     * @return string[]
     */
    private function drainPassedFilters(array $filters) : array
    {
        $intersected = array_intersect($this->filters, $filters);

        $bindingNames = array_map(fn ($filterName) => (new $filterName)->getBindingName(), $intersected);

        $request = $this->request->only($bindingNames);

        $result = [];

        foreach ($request as $key => $value) {
            $filterName = $this->getRequestBindings()[$key];

            $result[$filterName] = $value;
        }

        return $result;
    }
}
