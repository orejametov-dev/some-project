<?php


namespace Tests\Unit;


use App\DTOs\Merchants\UpdateMerchantDTO;
use App\Exceptions\BusinessException;
use App\Modules\Merchants\Models\Merchant;
use App\UseCases\Cache\FlushCacheUseCase;
use App\UseCases\Merchants\UpdateMerchantUseCase;
use PHPUnit\Framework\TestCase;

class UpdateMerchantUseCaseTest extends TestCase
{
//    private UpdateMerchantUseCase $updateMerchantUseCase;
//    public function setUp(): void
//    {
//        $this->updateMerchantUseCase = new UpdateMerchantUseCase($flushCacheUseCase, );
//    }
//
//    public function testMerchantDoesntExists()
//    {
//        $new_mechant = Merchant::factory()->create([
//            'name' => 'fff',
//            'legal_name' => 'test',
//            'legal_name_prefix' => 'test',
//            'token' => 'test',
//            'alifshop_slug' => 'test',
//            'information' => 'test',
//            'min_application_price' => 12321312
//        ]);
//
//        $updated_name = 'hello_masha';
//
//        $dto = new UpdateMerchantDTO(
//            id: 999,
//            name: $updated_name,
//            legal_name: 'test',
//            legal_name_prefix: 'test',
//            token: 'test',
//            alifshop_slug: 'test',
//            information: 'test',
//            min_application_price: 12321312
//        );
//
//        $this->expectException(BusinessException::class);
//        $merchant = $this->updateMerchantUseCase->execute($dto);
//    }
//
//    public function testSuccess()
//    {
//        $new_mechant = Merchant::factory()->create([
//            'name' => 'fff',
//            'legal_name' => 'test',
//            'legal_name_prefix' => 'test',
//            'token' => 'test',
//            'alifshop_slug' => 'test',
//            'information' => 'test',
//            'min_application_price' => 12321312
//        ]);
//
//        $updated_name = 'hello_masha';
//
//        $dto = new UpdateMerchantDTO(
//            id: 999,
//            name: $updated_name,
//            legal_name: 'test',
//            legal_name_prefix: 'test',
//            token: 'test',
//            alifshop_slug: 'test',
//            information: 'test',
//            min_application_price: 12321312
//        );
//
//        $merchant = $this->updateMerchantUseCase->execute($dto);
//
//        $this->assertEquals($updated_name, $merchant->name);
//    }
}
