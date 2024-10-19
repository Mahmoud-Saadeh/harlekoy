<?php

namespace App\Console\Commands;

use App\Jobs\ProcessUserBatchUpdates;
use App\Models\UserUpdateQueue;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessUserUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:user-updates';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process user updates and send them in batches';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Fetch updates in batches of 5000 users at a time
        $batchSize = 5000;

        // Get the next batch of updates
        $updates = UserUpdateQueue::take($batchSize)->get();

        if ($updates->isNotEmpty()) {

            $updates->chunk(1000)->each(function ($usersChunk) {
                // Dispatch the job for each chunk
                ProcessUserBatchUpdates::dispatch($usersChunk);
            });
        } else {
            Log::info("No more updates to process.");
        }
    }
}
