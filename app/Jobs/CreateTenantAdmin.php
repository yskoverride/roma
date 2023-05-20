<?php

namespace App\Jobs;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateTenantAdmin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    
    /**
     * Create a new job instance.
     */
    public function __construct(public Tenant $tenant)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {        
        if ($this->tenant->name !== null && $this->tenant->email !== null && $this->tenant->phone !== null) {
            $this->tenant->run(function($tenant){
                $user = User::make();
                $user->name = $tenant->name;
                $user->email = $tenant->email;
                $user->phone = $tenant->phone;
                $user->password = bcrypt('password');
    
                $user->save();
            });
        } 
    }
}
