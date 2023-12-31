<?php

namespace App\Filters\Notification;

use App\Filters\AbstractFilters;
use App\Filters\CommonFilters\CreatedAtFilter;
use App\Filters\CommonFilters\CreatedByIdFilter;
use App\Filters\CommonFilters\DateFilter;

class NotificationFilters extends AbstractFilters
{
    protected array $filters = [
        QNotificationFilter::class,
        MerchantIdNotificationFilter::class,
        CreatedByIdFilter::class,
        CreatedAtFilter::class,
        PublishedFilter::class,
        FreshFilter::class,
        DateFilter::class,
    ];

    protected function getRequestBindings(): array
    {
        $bindings = [];

        foreach ($this->filters as $filterName) {
            $filterBindingName = (new $filterName)->getBindingName();

            $bindings[$filterBindingName] = $filterName;
        }

        return $bindings;
    }
}
