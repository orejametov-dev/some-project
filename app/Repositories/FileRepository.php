<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Condition;
use App\Models\File;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class FileRepository
{
//    private File|Builder $file;
//
//    public function __construct()
//    {
//        $this->file = File::query();
//    }

    /**
     * @param File $file
     * @return void
     */
    public function save(File $file): void
    {
        $file->save();
    }

    /**
     * @param File $file
     * @return void
     */
    public function delete(File $file): void
    {
        $file->delete();
    }

    /**
     * @param int $merchant_id
     * @param int $file_id
     * @return File|Collection|null
     */
    public function getByIdWithMerchantId(int $merchant_id, int $file_id): File|Collection|null
    {
        return File::query()->where('merchant_id', $merchant_id)->find($file_id);
    }
}
