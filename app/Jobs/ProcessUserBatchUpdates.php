<?php

namespace App\Jobs;

use App\Models\UserUpdateQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ProcessUserBatchUpdates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $updates;


    /**
     * Create a new job instance.
     */
    public function __construct($updates)
    {
        $this->updates = $updates;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Prepare the batch payload for the third-party API
        $batches = [
            'batches' => [
                'subscribers' => $this->updates->map(function ($update) {
                    return array_merge(['email' => $update->email], $update->changes);
                })->toArray(),
            ],
        ];

        // Instead of sending the batch request to the API, log the updates
        foreach ($batches['batches']['subscribers'] as $index => $subscriber) {
            $formattedChanges = collect($subscriber)->map(function ($value, $key) {
                return "$key: " . (is_string($value) ? "'$value'" : $value);
            })->implode(', ');

            Log::info("[$index] $formattedChanges");
        }
        Log::info('batch finished processing');
        // Assume successful processing and delete the updates from the queue
        UserUpdateQueue::whereIn('id', $this->updates->pluck('id'))->delete();
    }
}
