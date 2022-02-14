<?php

namespace App\Console\Commands\OneOff;

use App\Modules\Merchants\Models\File;
use Illuminate\Console\Command;

class ChangeFileTypeName extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:file_type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        File::query()->where('file_type', 'scan_director_passport')->update(['file_type' => 'passport']);
        File::query()->where('file_type', 'certificate')->update(['file_type' => 'law_registration_doc']);
        File::query()->where('file_type', 'vat_certificate')->update(['file_type' => 'vat_registration']);
        File::query()->where('file_type', 'directors_order_copy')->update(['file_type' => 'director_order']);
        File::query()->where('file_type', 'product_conformity_certificate')->update(['file_type' => 'certificate_file']);

        return 0;
    }
}
