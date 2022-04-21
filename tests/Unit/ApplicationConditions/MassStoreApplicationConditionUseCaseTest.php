<?php

namespace Tests\Unit\ApplicationConditions;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\DTOs\Conditions\MassStoreConditionDTO;
use App\Exceptions\ApiBusinessException;
use App\Exceptions\BusinessException;
use App\HttpRepositories\Alifshop\AlifshopHttpRepository;
use App\Jobs\SendHook;
use App\Models\Condition;
use App\Models\ConditionTemplate;
use App\Models\Merchant;
use App\Models\Store;
use App\Repositories\ApplicationConditionRepository;
use App\Repositories\ConditionTemplateRepository;
use App\Repositories\MerchantRepository;
use App\Repositories\StoreRepository;
use App\UseCases\ApplicationConditions\CheckStartedAtAndFinishedAtConditionUseCase;
use App\UseCases\ApplicationConditions\MassStoreApplicationConditionUseCase;
use App\UseCases\Cache\FlushCacheUseCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class MassStoreApplicationConditionUseCaseTest extends TestCase
{
    private MerchantRepository $merchantRepository;
    private ConditionTemplateRepository $conditionTemplateRepository;
    private ApplicationConditionRepository $applicationConditionRepository;
    private StoreRepository $storeRepository;
    private MassStoreApplicationConditionUseCase $massStoreApplicationConditionUseCase;

    public function setUp(): void
    {
        parent::setUp();

        $alifshopHttpRepository = $this->createMock(AlifshopHttpRepository::class);
        $checkStartedAtAndFinishedAtConditionUseCase = $this->createMock(CheckStartedAtAndFinishedAtConditionUseCase::class);
        $flushCacheUseCase = $this->createMock(FlushCacheUseCase::class);
        $gatewayAuthUser = $this->createMock(GatewayAuthUser::class);
        $this->conditionTemplateRepository = $this->createMock(ConditionTemplateRepository::class);
        $this->applicationConditionRepository = $this->createMock(ApplicationConditionRepository::class);
        $this->merchantRepository = $this->createMock(MerchantRepository::class);
        $this->storeRepository = $this->createMock(StoreRepository::class);
        $this->massStoreApplicationConditionUseCase = new MassStoreApplicationConditionUseCase(
            alifshopHttpRepository: $alifshopHttpRepository,
            checkStartedAtAndFinishedAtConditionUseCase: $checkStartedAtAndFinishedAtConditionUseCase,
            flushCacheUseCase: $flushCacheUseCase,
            gatewayAuthUser: $gatewayAuthUser,
            merchantRepository: $this->merchantRepository,
            conditionTemplateRepository: $this->conditionTemplateRepository,
            applicationConditionRepository: $this->applicationConditionRepository,
            storeRepository: $this->storeRepository,
        );
    }

    public function testMerchantNotExists()
    {
        $merchant = new Merchant();
        $merchant->id = 1;
        $merchant->name = 'test';
        $merchant->legal_name = 'test';
        $merchant->legal_name_prefix = 'LLC';
        $merchant->token = 'test';
        $merchant->maintainer_id = 1;
        $merchant->company_id = 1;

        $merchant_2 = new Merchant();
        $merchant_2->id = 2;
        $merchant_2->name = 'test_2';
        $merchant_2->legal_name = 'test_2';
        $merchant_2->legal_name_prefix = 'LLC';
        $merchant_2->token = 'test_2';
        $merchant_2->maintainer_id = 2;
        $merchant_2->company_id = 2;

        $merchant_3 = new Merchant();
        $merchant_3->id = 3;
        $merchant_3->name = 'test3';
        $merchant_3->legal_name = 'test3';
        $merchant_3->legal_name_prefix = 'LLC';
        $merchant_3->token = 'test3';
        $merchant_3->maintainer_id = 3;
        $merchant_3->company_id = 3;

        $conditionDTO = MassStoreConditionDTO::fromArray([
            'merchant_ids' => [1, 2, 3, 4],
            'template_ids' => [1, 2],
            'special_offer' => 'test',
            'event_id' => 1,
            'post_merchant' => 1,
            'post_alifshop' => 1,
            'started_at' => null,
            'finished_at' => null,
        ]);

        $this->expectException(ApiBusinessException::class);
        $this->merchantRepository->method('getByIds')->willReturn(Collection::make([$merchant, $merchant_2, $merchant_3]));
        $this->massStoreApplicationConditionUseCase->execute($conditionDTO);
    }

    public function testTemplateNotExists()
    {
        $merchant = new Merchant();
        $merchant->id = 1;
        $merchant->name = 'test';
        $merchant->legal_name = 'test';
        $merchant->legal_name_prefix = 'LLC';
        $merchant->token = 'test';
        $merchant->maintainer_id = 1;
        $merchant->company_id = 1;

        $merchant_2 = new Merchant();
        $merchant_2->id = 2;
        $merchant_2->name = 'test_2';
        $merchant_2->legal_name = 'test_2';
        $merchant_2->legal_name_prefix = 'LLC';
        $merchant_2->token = 'test_2';
        $merchant_2->maintainer_id = 2;
        $merchant_2->company_id = 2;

        $merchant_3 = new Merchant();
        $merchant_3->id = 3;
        $merchant_3->name = 'test3';
        $merchant_3->legal_name = 'test3';
        $merchant_3->legal_name_prefix = 'LLC';
        $merchant_3->token = 'test3';
        $merchant_3->maintainer_id = 3;
        $merchant_3->company_id = 3;

        $template = new ConditionTemplate();
        $template->id = 1;
        $template->duration = 1;
        $template->commission = 1;

        $template_2 = new ConditionTemplate();
        $template_2->id = 2;
        $template_2->duration = 2;
        $template_2->commission = 2;

        $conditionDTO = MassStoreConditionDTO::fromArray([
            'merchant_ids' => [1, 2, 3],
            'template_ids' => [1, 2, 3],
            'special_offer' => 'test',
            'event_id' => 1,
            'post_merchant' => 1,
            'post_alifshop' => 1,
            'started_at' => null,
            'finished_at' => null,
        ]);

        $this->expectException(ApiBusinessException::class);
        $this->merchantRepository->method('getByIds')->willReturn(Collection::make([$merchant, $merchant_2, $merchant_3]));
        $this->conditionTemplateRepository->method('getByIds')->willReturn(Collection::make([$template, $template_2]));
        $this->massStoreApplicationConditionUseCase->execute($conditionDTO);
    }

    public function testMainStoreNotExists()
    {
        $merchant = new Merchant();
        $merchant->id = 1;
        $merchant->name = 'test';
        $merchant->legal_name = 'test';
        $merchant->legal_name_prefix = 'LLC';
        $merchant->token = 'test';
        $merchant->maintainer_id = 1;
        $merchant->company_id = 1;

        $merchant_2 = new Merchant();
        $merchant_2->id = 2;
        $merchant_2->name = 'test_2';
        $merchant_2->legal_name = 'test_2';
        $merchant_2->legal_name_prefix = 'LLC';
        $merchant_2->token = 'test_2';
        $merchant_2->maintainer_id = 2;
        $merchant_2->company_id = 2;

        $merchant_3 = new Merchant();
        $merchant_3->id = 3;
        $merchant_3->name = 'test3';
        $merchant_3->legal_name = 'test3';
        $merchant_3->legal_name_prefix = 'LLC';
        $merchant_3->token = 'test3';
        $merchant_3->maintainer_id = 3;
        $merchant_3->company_id = 3;

        $template = new ConditionTemplate();
        $template->id = 1;
        $template->duration = 1;
        $template->commission = 1;

        $template_2 = new ConditionTemplate();
        $template_2->id = 2;
        $template_2->duration = 2;
        $template_2->commission = 2;

        $conditionDTO = MassStoreConditionDTO::fromArray([
            'merchant_ids' => [1, 2, 3],
            'template_ids' => [1, 2],
            'special_offer' => 'test',
            'event_id' => 1,
            'post_merchant' => 1,
            'post_alifshop' => 1,
            'started_at' => null,
            'finished_at' => null,
        ]);

        $this->expectException(BusinessException::class);
        $this->merchantRepository->method('getByIds')->willReturn(Collection::make([$merchant, $merchant_2, $merchant_3]));
        $this->conditionTemplateRepository->method('getByIds')->willReturn(Collection::make([$template, $template_2]));
        $this->storeRepository->method('getByIsMainTrueMerchantId')->willReturn(null);
        $this->massStoreApplicationConditionUseCase->execute($conditionDTO);
    }

    public function testSuccess()
    {
        $merchant = new Merchant();
        $merchant->id = 1;
        $merchant->name = 'test';
        $merchant->legal_name = 'test';
        $merchant->legal_name_prefix = 'LLC';
        $merchant->token = 'test';
        $merchant->maintainer_id = 1;
        $merchant->company_id = 1;

        $merchant_2 = new Merchant();
        $merchant_2->id = 2;
        $merchant_2->name = 'test_2';
        $merchant_2->legal_name = 'test_2';
        $merchant_2->legal_name_prefix = 'LLC';
        $merchant_2->token = 'test_2';
        $merchant_2->maintainer_id = 2;
        $merchant_2->company_id = 2;

        $merchant_3 = new Merchant();
        $merchant_3->id = 3;
        $merchant_3->name = 'test3';
        $merchant_3->legal_name = 'test3';
        $merchant_3->legal_name_prefix = 'LLC';
        $merchant_3->token = 'test3';
        $merchant_3->maintainer_id = 3;
        $merchant_3->company_id = 3;

        $template = new ConditionTemplate();
        $template->id = 1;
        $template->duration = 1;
        $template->commission = 1;

        $template_2 = new ConditionTemplate();
        $template_2->id = 2;
        $template_2->duration = 2;
        $template_2->commission = 2;

        $conditionDTO = MassStoreConditionDTO::fromArray([
            'merchant_ids' => [1, 2, 3],
            'template_ids' => [1, 2],
            'special_offer' => 'test',
            'event_id' => 1,
            'post_merchant' => 1,
            'post_alifshop' => 1,
            'started_at' => null,
            'finished_at' => null,
        ]);

        $store = new Store();
        $store->id = 1;
        $store->name = 'test';
        $store->merchant_id = 1;
        $store->is_main = true;
        $store->address = 'test';
        $store->lat = 1;
        $store->long = 1;
        $store->phone = 1;
        $store->active = true;

        $condition_merchant = new Condition();
        $condition_merchant->id = 1;
        $condition_merchant->merchant_id = 1;
        $condition_merchant->store_id = 1;
        $condition_merchant->commission = 1;
        $condition_merchant->duration = 1;
        $condition_merchant->discount = 1;
        $condition_merchant->is_special = false;
        $condition_merchant->special_offer = 'test';
        $condition_merchant->event_id = 1;
        $condition_merchant->post_merchant = true;
        $condition_merchant->post_alifshop = true;
        $condition_merchant->active = true;

        $this->merchantRepository->method('getByIds')->willReturn(Collection::make([$merchant, $merchant_2, $merchant_3]));
        $this->conditionTemplateRepository->method('getByIds')->willReturn(Collection::make([$template, $template_2]));
        $this->storeRepository->method('getByIsMainTrueMerchantId')->willReturn($store);
        $this->applicationConditionRepository->method('getByActiveTruePostAlifshopTrueWithMerchantId')->willReturn(Collection::make([$condition_merchant]));
        $this->massStoreApplicationConditionUseCase->execute($conditionDTO);

        static::assertTrue(true);
        Bus::assertDispatched(SendHook::class);
    }
}
