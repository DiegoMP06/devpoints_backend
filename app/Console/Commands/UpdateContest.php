<?php

namespace App\Console\Commands;

use App\Models\Contest;
use App\Models\Team;
use Illuminate\Console\Command;

class UpdateContest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contests:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify the contest status and create the contest positions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $contests = Contest::where('ended_at', '<=', now())
            ->where('is_ended', 0)->get();

        $this->info(json_encode($contests));
    }
}
