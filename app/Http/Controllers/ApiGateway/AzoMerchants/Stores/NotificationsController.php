<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Stores;

use App\Exceptions\BusinessException;
use App\Filters\CommonFilters\CreatedAtFilter;
use App\Filters\CommonFilters\CreatedByIdFilter;
use App\Filters\Notification\MerchantIdNotificationFilter;
use App\Filters\Notification\PublishedFilter;
use App\Filters\Notification\QNotificationFilter;
use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Notification;
use App\Modules\Merchants\Models\Store;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class NotificationsController extends ApiBaseController
{
    public function index(Request $request)
    {
        $notifications = Notification::query()
            ->filterRequest($request, [
                QNotificationFilter::class,
                CreatedAtFilter::class,
                CreatedByIdFilter::class,
                MerchantIdNotificationFilter::class,
                PublishedFilter::class,
            ])
            ->latest();

        if ($request->query('object') == true) {
            return $notifications->first();
        }

        if ($request->has('paginate') && $request->query('paginate') == false) {
            return $notifications->get();
        }

        return $notifications->paginate($request->query('per_page') ?? 15);
    }

    public function show($id)
    {
        $notification = Notification::query()->with('stores')->findOrFail($id);

        return $notification;
    }

    public function store(Request $request)
    {
        $validatedData = $this->validate($request, [
            'title_uz' => 'required|string',
            'title_ru' => 'required|string',
            'body_uz' => 'required|string',
            'body_ru' => 'required|string',
            'start_schedule' => 'nullable|date_format:Y-m-d H:i',
            'end_schedule' => 'nullable|date_format:Y-m-d H:i',
            'all_merchants' => 'required_without:recipients|boolean',
            'recipients' => 'required_without:all_merchants|array',
            'recipients.*.merchant_id' => 'required|integer',
            'recipients.*.store_ids' => 'nullable|array',
        ]);

        $notification = new Notification();
        $notification->fill($validatedData);
        $notification->setCreatedBy($this->user);
        $notification->start_schedule = Carbon::parse($request->input('start_schedule') ?? now());
        $notification->end_schedule = Carbon::parse($request->input('end_schedule') ?? now()->addDay());

        if ($request->has('all_merchants') && $request->input('all_merchants')) {
            DB::transaction(function () use ($notification) {
                $notification->setAllType();
                $notification->save();

                $stores = Store::get();
                $notification->stores()->attach($stores);
            });
        } elseif ($request->missing('all_merchants')) {
            DB::transaction(function () use ($notification, $request) {
                $notification->setCertainType();
                $notification->save();

                foreach ($request->input('recipients') as $recipient) {
                    $merchant = Merchant::findOrFail($recipient['merchant_id']);
                    if (array_key_exists('store_ids', $recipient) and !empty($recipient['store_ids'])) {
                        $all_store_ids = $merchant->stores()->pluck('id');
                        foreach ($recipient['store_ids'] as $store_id) {
                            $checker = $all_store_ids->contains($store_id);
                            if (!$checker) {
                                throw new BusinessException('Указан не правильный магазин ' . $merchant->name . ' мерчанта');
                            }
                        }
                        $notification->stores()->attach($recipient['store_ids']);
                    } else {
                        $stores = $merchant->stores;
                        $notification->stores()->attach($stores);
                    }
                }
            });
        }

        Cache::tags('notifications')->flush();

        return $notification;
    }

    public function update($id, Request $request)
    {
        $validatedData = $this->validate($request, [
            'title_uz' => 'required|string',
            'title_ru' => 'required|string',
            'body_uz' => 'required|string',
            'body_ru' => 'required|string',
            'start_schedule' => 'nullable|date_format:Y-m-d H:i',
            'end_schedule' => 'nullable|date_format:Y-m-d H:i',
        ]);

        $notification = Notification::query()->findOrFail($id);
        $notification->fill($validatedData);
        $notification->start_schedule = Carbon::parse($request->input('start_schedule') ?? now());
        $notification->end_schedule = Carbon::parse($request->input('end_schedule') ?? now()->addDay());

        $notification->save();

        Cache::tags('notifications')->flush();

        return $notification;
    }

    public function remove($id)
    {
        $notification = Notification::query()->findOrFail($id);
        $notification->stores()->detach();
        $notification->delete();
        Cache::tags('notifications')->flush();

        return response()->json(['message' => 'Уведомление удалено успешно']);
    }
}
