<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\SuperAdminController;

class GenerateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes existing admin and generates new admin';

    /**
     * Execute the console command.
     */
    public function handle(SuperAdminController $admin): void
    {
        $message = $admin->reset();
        $this->info($message);
    }
}
