<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\TenantFormRequest;
use Stancl\Tenancy\Database\Models\Domain;

class TenantController extends Controller
{
    
    public function dashboard()
    {
        return view('admin.dashboard',[
            'domains' => Domain::orderBy('created_at', 'desc')->paginate(15)
        ]);
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(TenantFormRequest $request)
    {
        $data = $request->validated();

        try {
            DB::transaction(function() use ($data){
                $tenant = Tenant::create($data);
                $tenant->domains()->create(['domain' => $data["company_website"] ]);
            });

            return redirect('/super-admin/dashboard')->with([
                'status' => 'Success',
                'message' => 'New customer has been created successfully'
            ]);            

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return back()->with([
                'status' => 'Failure', 
                'message' => 'Something went wrong, please contact your admin.'
            ]);
        }

    }

}
