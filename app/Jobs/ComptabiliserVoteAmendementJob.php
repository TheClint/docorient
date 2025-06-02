<?php

namespace App\Jobs;

use App\Models\Amendement;
use App\Services\VoteService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ComptabiliserVoteAmendementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Amendement $amendement;

    /**
     * Create a new job instance.
     */
    public function __construct(Amendement $amendement)
    {
        $this->amendement = $amendement;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        VoteService::comptabiliserVoteAmendement($this->amendement);
    }
}
