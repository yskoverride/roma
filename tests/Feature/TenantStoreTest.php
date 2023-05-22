<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\SuperAdmin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Stancl\Tenancy\Database\Models\Domain;

class TenantStoreTest extends TestCase
{
    protected $validData;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->validData = [
            'id' => 'tenant' . rand(0, 9999),
            'company_website' => 'tenant1website'. rand(0, 9999),
            'name' => 'Tenant'. rand(0, 9999),
            'email' => 'tenant1@mail.com',
            'phone' => '+919861424281'
        ];

        // Create new SuperAdmin
        SuperAdmin::create([
            'name' => fake()->name(),
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
            'password' => bcrypt('Password')
        ]);

        $this->user = SuperAdmin::where('email' , '=' , env('BONKER_EMAIL'))->get()[0];

    }

    protected function assertDatabaseExists($dbName, $connection = null)
    {
        $databaseExists = DB::connection($connection)
            ->select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$dbName]);

        $this->assertTrue(count($databaseExists) > 0, "Failed asserting that database '$dbName' exists.");
    }

    protected function assertDatabaseDoNotExists($dbName, $connection = null)
    {
        $databaseExists = DB::connection($connection)
            ->select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$dbName]);

        $this->assertTrue(count($databaseExists) <= 0);
    }

    /** @test */
    public function it_redirects_to_create_page_when_add_customer_button_is_clicked()
    {
        Auth::guard('super-admin')->login($this->user);

        // Visit the dashboard page
        $response = $this->get(route('super-admin.dashboard'));

        // Assert that the dashboard page contains a link to the create page
        $response->assertSeeText('Add New Customer');
        $response->assertSee(route('super-admin.tenants.create'));
    }


    /** @test */
    public function it_stores_a_tenant_with_valid_data()
    {
        
        Auth::guard('super-admin')->login($this->user);
        
        $response = $this->post('super-admin/tenants/store', $this->validData);

        $response->assertStatus(302);
        $this->assertDatabaseHas('tenants', [
            'id' => $this->validData['id']
        ]);
        $this->assertDatabaseHas('domains',[
            'domain' => $this->validData['company_website'] . "." . env("APP_DOMAIN") 
        ]);
    }

    /** @test */
    public function it_correctly_associates_a_tenant_with_a_domain_upon_creation()
    {
        Auth::guard('super-admin')->login($this->user);
        
        $this->post('super-admin/tenants/store', $this->validData);

        $tenant = Tenant::find($this->validData['id']);
        $domain = Domain::where('domain', $this->validData['company_website'] . "." . env("APP_DOMAIN"))->first();

        $this->assertEquals($domain->tenant_id, $tenant->id);
    }

    /** @test */
    public function it_redirects_to_dashboard_with_success_message_after_tenant_creation()
    {
        
        Auth::guard('super-admin')->login($this->user);
        $response = $this->post('super-admin/tenants/store', $this->validData);

        $response->assertStatus(302);
        $response->assertRedirect('super-admin/dashboard');
        $response->assertSessionHas('message','New customer has been created successfully');
        $response->assertSessionHas('status','Success');

        $response = $this->get(route('super-admin.dashboard'));
        // Check if the new domain is displayed on the dashboard
        $response->assertSeeText($this->validData['company_website'] . "." . env("APP_DOMAIN"));

    }

    /** @test */
    public function it_correctly_handles_and_logs_an_exception()
    {
        
        Auth::guard('super-admin')->login($this->user);
        
        $this->validData['phone'] = '98989878761';
        $response = $this->post('super-admin/tenants/store', $this->validData);

        $response->assertStatus(302);

        $errors = session('errors');
        $this->assertEquals($errors->get('phone')[0],"The phone field must be a valid number.");
    }

    /** @test */
    public function it_enforces_validation_rules()
    {
        Auth::guard('super-admin')->login($this->user);

        $this->validData['phone'] = 'not-an-email';
        $response = $this->post('super-admin/tenants/store', $this->validData);

        $response->assertSessionHasErrors('phone');
        $this->assertDatabaseMissing('tenants', [
            'id' => $this->validData['id']
        ]);
    }

    /** @test */
    public function it_stores_a_tenant_with_valid_data_and_creates_new_schema()
    {
        Auth::guard('super-admin')->login($this->user);

        $response = $this->post('super-admin/tenants/store', $this->validData);

        $response->assertStatus(302);
        
        $this->assertDatabaseHas('tenants', [
            'id' => $this->validData['id'],
        ]);

        // Then check the existence of the new schema...
        $databaseName = 'test_tenant' . $this->validData['id'];
        $this->assertDatabaseExists($databaseName, env('TENANT_DB_CONNECTION'));
    }

    /** @test */
    public function it_does_not_create_tenant_schema_if_db_transaction_fails()
    {
        Auth::guard('super-admin')->login($this->user);

        $this->validData['phone'] = 'not-an-email';

        $response = $this->post('super-admin/tenants/store', $this->validData);

        $response->assertStatus(302);

        // Then check the existence of the new schema...
        $databaseName = 'test_tenant' . $this->validData['id'];
        $this->assertDatabaseDoNotExists($databaseName, env('TENANT_DB_CONNECTION'));
    }

    /** @test */
    public function a_subscription_status_can_be_updated_to_inactive()
    {
                
        Auth::guard('super-admin')->login($this->user);
        
        $response = $this->post('super-admin/tenants/store', $this->validData);
        $response->assertStatus(302);

        $tenant = Tenant::where('id', $this->validData['id'])->first();

        // Update the subscription status
        $response = $this->patch(route('super-admin.tenants.updateSubscription', $tenant->id), [
            'subscription_status' => false
        ]);

        // Assert the response is redirected back to the previous page with success message
        $response->assertRedirect()
                ->assertSessionHas('message', 'Subscription status updated successfully');

        // Check the subscription status is updated in the database
        $this->assertFalse(Tenant::find($tenant->id)->subscription_status);

        $response = $this->get('http://' . $this->validData['company_website'] . "." . env('APP_DOMAIN'));
        $response->assertStatus(404);
    }

    /** @test */
    public function a_subscription_status_can_be_updated_to_active()
    {
                
        Auth::guard('super-admin')->login($this->user);

        $this->validData['subscription_status'] = false;
        
        $response = $this->post('super-admin/tenants/store', $this->validData);
        $response->assertStatus(302);

        $tenant = Tenant::where('id', $this->validData['id'])->first();

        // Update the subscription status
        $response = $this->patch(route('super-admin.tenants.updateSubscription', $tenant->id), [
            'subscription_status' => true
        ]);

        // Assert the response is redirected back to the previous page with success message
        $response->assertRedirect()
                ->assertSessionHas('message', 'Subscription status updated successfully');

        // Check the subscription status is updated in the database
        $this->assertTrue(Tenant::find($tenant->id)->subscription_status);

        $response = $this->get('http://' . $this->validData['company_website'] . "." . env('APP_DOMAIN'));
        $response->assertStatus(200);
    }


}
