<?php

declare(strict_types=1);

namespace App\Console\Commands\Settings;

use App\UseCases\ApplicationConditions\MassToggleApplicationConditionUseCase;
use Illuminate\Console\Command;

class DeactivationApplicationConditions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:deactivation_conditions';

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
    public function handle(MassToggleApplicationConditionUseCase $massToggleApplicationConditionUseCase)
    {
        $massToggleApplicationConditionUseCase->execute(false);

        return 0;
    }
}
