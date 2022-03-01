<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait SortableByQueryParams
{
    public function scopeOrderRequest(Builder $query, Request $request, string $default_order_str = 'id:desc')
    {
        $order_str = $request->query('order') ?? $default_order_str;
        $orders_arr = explode(',', $order_str);
        foreach ($orders_arr as $order) {
            $exploded = explode(':', $order);
            $orderBy = $exploded[0];
            $orderHow = isset($exploded[1]) ? $exploded[1] : 'asc';
            if (!($orderBy == 'id' && $this->primaryKey == null)) {
                $query->orderBy($orderBy, $orderHow);
            }
        }

        return $query;
    }
}
