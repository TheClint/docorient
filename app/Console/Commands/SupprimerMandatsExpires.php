<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Mandat;
use Carbon\Carbon;

class SupprimerMandatsExpires extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:supprimer-mandats-expires';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Supprime tous les mandats dont la date de fin est passée.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $nb = Mandat::whereNotNull('fin_at')
            ->where('fin_at', '<', $now)
            ->delete();

        $this->info("{$nb} mandats expirés supprimés à {$now->format('Y-m-d H:i:s')}.");
    }
}

