<?php

namespace App\Http\Controllers;

use App\Models\SuperAdmin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SuperAdminController extends Controller
{
    public function reset()
    {
        try {
            DB::transaction(function(){
                // Delete all SuperAdmins
                SuperAdmin::truncate();

                // Create new SuperAdmin
                SuperAdmin::create([
                    'name' => 'Bonker Bonzo',
                    'email' => env('BONKER_EMAIL'),
                    'phone' => env('BONKER_PHONE'),
                    'password' => bcrypt('Password')
                ]);

            });

            return("Info - New Admin has been reset");

        } catch (\Exception $e) {

            Log::critical('Admin is not getting created'. $e->getMessage());
            return("Failure - Admin reset failed!!");

        }
        
    }
}
