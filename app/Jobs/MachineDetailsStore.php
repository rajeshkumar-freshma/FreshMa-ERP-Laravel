<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\MachineData;
use App\Models\PLUMaster;
use Log;
use Illuminate\Support\Facades\DB;

class MachineDetailsStore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Machine Details Data Job Executing');
        DB::beginTransaction();
        try {
            Log::info('machine_master_datas');
            $machine_master_datas = DB::connection('sqlsrv_ease')->table('PLUMaster')->get();
            Log::info($machine_master_datas);
            // Log::info($machine_master_datas);
            foreach ($machine_master_datas as $key => $machine_master_data) {
                $machine_master_data_exists = PLUMaster::where('plu_master_code', $machine_master_data->PLUMasterCode)->first();
                if ($machine_master_data_exists == null) {
                    $new_machine_master = new PLUMaster();
                } else {
                    $new_machine_master = $machine_master_data_exists;
                }

                $new_machine_master->plu_master_code = $machine_master_data->PLUMasterCode;
                $new_machine_master->plu_master_name = $machine_master_data->PLUMasterName;
                $new_machine_master->save();
                Log::info('new_machine_master');
                Log::info($new_machine_master->get());
                DB::commit();
            }

            $machine_datas = DB::connection('sqlsrv_ease')->table('MacSettings')->get();
            Log::info('machine_datas');
            Log::info($machine_datas);
            foreach ($machine_datas as $key => $machine_data) {
                $machine_data_check_exists = MachineData::where('Slno', $machine_data->Slno)->first();
                Log::info('machine_datas_exists');
                Log::info($machine_data_check_exists);
                if ($machine_data_check_exists == null) {
                    $new_machine_data = new MachineData();
                } else {
                    $new_machine_data = $machine_data_check_exists;
                }
                $new_machine_data->Slno = $machine_data->Slno;
                $new_machine_data->MachineName = $machine_data->MachineName;
                $new_machine_data->IPAddress = $machine_data->IPAddress;
                $new_machine_data->Port = $machine_data->Port;
                $new_machine_data->Status = $machine_data->Status == 'ENABLE' ? 1 : 0;
                $new_machine_data->Capacity = $machine_data->Capacity;
                $new_machine_data->PLUMasterCode = $machine_data->PLUMasterCode;
                $new_machine_data->SelectAll = $machine_data->SelectAll;
                $new_machine_data->Online = $machine_data->Online;
                $new_machine_data->save();
                Log::info('new_machine_data');
                Log::info($new_machine_data->get());
                DB::commit();
            }

            Log::info('Machine Data Job Completed');
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
        }
    }
}
