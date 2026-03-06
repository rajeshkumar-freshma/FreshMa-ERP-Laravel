<?php

namespace Tests\Feature\Master;

use App\Models\Admin;
use App\Models\MachineData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * CRUD tests for MachineDetail (MachineData) master module.
 */
class MachineDetailCrudTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;
    protected string $prefix;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin  = Admin::factory()->create();
        $this->prefix = config('app.admin_prefix', 'rrkadminmanager');
        $this->actingAs($this->admin, 'admin');
    }

    // =====================================================================
    //  AUTH
    // =====================================================================

    /** @test */
    public function guest_cannot_access_machine_details_index()
    {
        $this->app['auth']->forgetGuards();

        $response = $this->get("/{$this->prefix}/master/machine-details");
        $response->assertRedirect();
    }

    // =====================================================================
    //  INDEX / CREATE / EDIT views
    // =====================================================================

    /** @test */
    public function admin_can_view_machine_details_index()
    {
        $response = $this->get("/{$this->prefix}/master/machine-details");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_machine_details_create_form()
    {
        $response = $this->get("/{$this->prefix}/master/machine-details/create");
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_machine_details_edit_form()
    {
        $machine = MachineData::factory()->create();

        $response = $this->get("/{$this->prefix}/master/machine-details/{$machine->id}/edit");
        $response->assertStatus(200);
    }

    // =====================================================================
    //  STORE
    // =====================================================================

    /** @test */
    public function admin_can_store_machine_detail()
    {
        $response = $this->post("/{$this->prefix}/master/machine-details", [
            'machine_name'    => 'Test Machine',
            'port'            => 5000,
            'ip_address'      => '192.168.1.1',
            'capacity'        => 500,
            'status'          => 1,
            'plu_master_code' => 'PLU001',
            'machine_status'  => 1,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.machine-details.index'));
        $this->assertDatabaseHas('machine_data', ['MachineName' => 'Test Machine']);
    }

    /** @test */
    public function store_machine_detail_sets_slno_to_own_id()
    {
        $this->post("/{$this->prefix}/master/machine-details", [
            'machine_name'    => 'Slno Machine',
            'port'            => 4000,
            'ip_address'      => '10.0.0.1',
            'capacity'        => 200,
            'status'          => 1,
            'plu_master_code' => 'PLU002',
            'machine_status'  => 1,
            'submission_type' => 1,
        ]);

        $machine = MachineData::where('MachineName', 'Slno Machine')->first();
        $this->assertNotNull($machine);
        $this->assertEquals($machine->id, $machine->Slno);
    }

    // =====================================================================
    //  UPDATE
    // =====================================================================

    /** @test */
    public function admin_can_update_machine_detail()
    {
        $machine = MachineData::factory()->create();

        $response = $this->put("/{$this->prefix}/master/machine-details/{$machine->id}", [
            'machine_name'    => 'Updated Machine',
            'port'            => 6000,
            'ip_address'      => '10.0.0.1',
            'capacity'        => 1000,
            'status'          => 1,
            'plu_master_code' => 'PLU002',
            'machine_status'  => 1,
            'submission_type' => 1,
        ]);

        $response->assertRedirect(route('admin.machine-details.index'));
        $this->assertDatabaseHas('machine_data', [
            'id'          => $machine->id,
            'MachineName' => 'Updated Machine',
            'Port'        => 6000,
        ]);
    }

    // =====================================================================
    //  DELETE
    // =====================================================================

    /** @test */
    public function admin_can_delete_machine_detail()
    {
        $machine = MachineData::factory()->create();

        $response = $this->deleteJson("/{$this->prefix}/master/machine-details/{$machine->id}");

        $response->assertOk();
        $response->assertJson(['status' => 200]);
        $this->assertSoftDeleted('machine_data', ['id' => $machine->id]);
    }

    // =====================================================================
    //  VALIDATION
    // =====================================================================

    /** @test */
    public function store_machine_detail_requires_machine_name()
    {
        $response = $this->post("/{$this->prefix}/master/machine-details", [
            'machine_name'    => '',
            'port'            => 5000,
            'capacity'        => 500,
            'status'          => 1,
            'plu_master_code' => 'PLU001',
            'machine_status'  => 1,
            'submission_type' => 1,
        ]);

        $response->assertSessionHasErrors('machine_name');
    }

    /** @test */
    public function store_machine_detail_requires_port()
    {
        $response = $this->post("/{$this->prefix}/master/machine-details", [
            'machine_name'    => 'Test Machine',
            'capacity'        => 500,
            'status'          => 1,
            'plu_master_code' => 'PLU001',
            'machine_status'  => 1,
            'submission_type' => 1,
        ]);

        $response->assertSessionHasErrors('port');
    }

    /** @test */
    public function store_machine_detail_requires_capacity()
    {
        $response = $this->post("/{$this->prefix}/master/machine-details", [
            'machine_name'    => 'Test Machine',
            'port'            => 5000,
            'status'          => 1,
            'plu_master_code' => 'PLU001',
            'machine_status'  => 1,
            'submission_type' => 1,
        ]);

        $response->assertSessionHasErrors('capacity');
    }
}
