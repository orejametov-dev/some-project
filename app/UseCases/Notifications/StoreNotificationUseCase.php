<?php

declare(strict_types=1);

namespace App\UseCases\Notifications;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\DTOs\Notifications\StoreNotificationDTO;
use App\Exceptions\BusinessException;
use App\Models\Merchant;
use App\Models\Notification;
use App\Models\Store;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StoreNotificationUseCase
{
    public function __construct(
        private GatewayAuthUser $gatewayAuthUser,
        private FindMerchantByIdUseCase $findMerchantByIdUseCase
    ) {
    }

    public function execute(StoreNotificationDTO $storeNotificationDTO): Notification
    {
        $notification = new Notification();
        $notification->title_ru = $storeNotificationDTO->getTitleRu();
        $notification->title_uz = $storeNotificationDTO->getTitleUz();
        $notification->body_ru = $storeNotificationDTO->getBodyRu();
        $notification->body_uz = $storeNotificationDTO->getBodyUz();
        $notification->created_by_id = $this->gatewayAuthUser->getId();
        $notification->created_by_name = $this->gatewayAuthUser->getName();
        $notification->start_schedule = $storeNotificationDTO->getStartSchedule() ?? Carbon::now();
        $notification->end_schedule = $storeNotificationDTO->getEndSchedule() ?? Carbon::now()->addDay();

        $notification->type = $storeNotificationDTO->getAllMerchants() !== null ? Notification::ALL_TYPE : Notification::CERTAIN_TYPE;
        $validated_ids = $this->getStoreIdsByMerchants($storeNotificationDTO);

        DB::transaction(function () use ($notification, $validated_ids) {
            $notification->save();

            $notification->stores()->attach($validated_ids);
        });

        Cache::tags('notifications')->flush();

        return $notification;
    }

    /**
     * @param StoreNotificationDTO $storeNotificationDTO
     * @return array
     * @throws BusinessException
     */
    private function getStoreIdsByMerchants(StoreNotificationDTO $storeNotificationDTO): array
    {
        if ($storeNotificationDTO->getAllMerchants() === null) {
            $ids = [];

            foreach ($storeNotificationDTO->getRecipients() as $recipient) {
                $merchant = $this->findMerchantByIdUseCase->execute($recipient->getMerchantId());
                $ids = array_merge($ids, $this->getStoreIdsByMerchant($merchant, $recipient->getStoreIds()));
            }

            return $ids;
        }

        return Store::query()->pluck('id')->all();
    }

    /**
     * @param array $store_ids
     * @return array
     * @throws BusinessException
     */
    private function getStoreIdsByMerchant(Merchant $merchant, array $store_ids): array
    {
        $merchant_stores = $merchant->stores()->pluck('id')->all();

        if (empty($store_ids) === false) {
            $diff_ids = array_diff($store_ids, $merchant_stores);
            if (empty($diff_ids) === false) {
                throw new BusinessException('Указан не правильный магазин ' . $merchant->name . ' мерчанта');
            }

            return $store_ids;
        }

        return $merchant_stores;
    }
}
