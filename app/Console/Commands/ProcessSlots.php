<?php

namespace App\Console\Commands;

use App\Services\SlotService;
use Illuminate\Console\Command;

class ProcessSlots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-slots';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        new SlotService()->checkExpiredSlots();

        return self::SUCCESS;
    }
}
