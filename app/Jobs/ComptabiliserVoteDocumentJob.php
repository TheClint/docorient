<?php

namespace App\Jobs;

use App\Models\Document;
use App\Services\VoteService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ComptabiliserVoteDocumentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Document $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function handle(): void
    {
        VoteService::comptabiliserVoteDocument($this->document);
    }
}
