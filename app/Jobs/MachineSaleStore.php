<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\EasseMachineModel\MachineLiveSalesBill; // Live sale data from Easse machine Model
use App\Models\EasseMachineModel\MachineLiveSalesBillDetail; // Live sale data details from Easse machine Model
use App\Models\LiveSalesBill; // Live sale data details from Easse machine Model to store Our Database
use App\Models\LiveSalesBillDetail; // Live sale data details from Easse machine Model to store Our Database
use Carbon\Carbon;
use App\Jobs\MachineSaletoSaleOrderJob;
use App\Models\CronJobCompletionTime;
use Log;
use Illuminate\Support\Facades\DB;

class MachineSaleStore implements ShouldQueue
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
        Log::info('Machine Sales Stor Job Executing');
        DB::beginTransaction();
        try {
            // Looping Bill Details
            $from_date =  Carbon::now()->startOfDay()->format('Y-m-d 00:00:00');
            $to_date = Carbon::now()->endOfDay()->format('Y-m-d 23:59:59');

            $endOfTheDay = Carbon::now()->endOfDay()->format('d/m/Y H:i:s');
            Log::info("endOfTheDay");
            Log::info($endOfTheDay);
            $nowTime = Carbon::now();
            $cronJobCompletionData = CronJobCompletionTime::where('cron_job_name', 'MachineSaleStore')
                ->latest()->first();
            // Log::info("cronJobCompletionData");
            // Log::info($cronJobCompletionData);

            if ($cronJobCompletionData !== null) {
                $cronBeforegettime = Carbon::parse($cronJobCompletionData->end_time)->subMinutes(10)->format('d/m/Y H:i:s');
                // Log::info("cronBeforegettime if before 10");
                // Log::info($cronBeforegettime);
            } else {
                $cronBeforegettime = Carbon::now()->startOfDay()->format('d/m/Y H:i:s');
                // Log::info("cronBeforegettime else today now time");
                // Log::info($cronBeforegettime);
            }
            $machinelivesalebills = MachineLiveSalesBill::whereBetween('ItemsaleDateTime', [$cronBeforegettime, $endOfTheDay])->orderBy('ItemsaleDateTime', 'ASC')->get();
            // Log::info("machinelivesalebills");
            // Log::info($machinelivesalebills);

            // Log::info("from_date");
            // Log::info($from_date);
            // Log::info("to_date");
            // Log::info($to_date);
            // $now = Carbon::now();
            // $endOfDay = $now->copy()->endOfDay();
            // $cronJobCompletionData = CronJobCompletionTime::where('cron_job_name', 'MachineSaleStore')
            //     ->latest()->first();
            // $cronBeforegettime = $cronJobCompletionData ? Carbon::parse($cronJobCompletionData->end_time)->subMinutes(10) : $now->copy()->startOfDay();

            // $machinelivesalebills = MachineLiveSalesBill::whereBetween('ItemsaleDateTime', [$cronBeforegettime, $endOfDay])
            //     ->orderBy('ItemsaleDateTime', 'ASC')
            //     ->get();

            // $from_date = $now->startOfDay();
            // $to_date = $now->endOfDay();

            foreach ($machinelivesalebills as $key => $machinelivesalebill) {
                Log::info($machinelivesalebill);
                $liveSalesBillChecks = LiveSalesBill::where([['billNo', $machinelivesalebill->billNo], ['MachineName', $machinelivesalebill->MachineName]])->whereBetween('ItemsaleDateTime', [$from_date, $to_date])->first();

                $saleBillDetails = MachineLiveSalesBillDetail::where([['billNo', $machinelivesalebill->billNo], ['MachineName', $machinelivesalebill->MachineName]])->get();

                if (empty($liveSalesBillChecks) && count($saleBillDetails) > 0) {
                    $liveSalesBillStore = new LiveSalesBill();
                    $liveSalesBillStore->live_sales_bill_id = $machinelivesalebill->id;
                    $liveSalesBillStore->billNo = $machinelivesalebill->billNo;
                    $liveSalesBillStore->opLinkNo = $machinelivesalebill->opLinkNo;
                    $liveSalesBillStore->tDiscntType = $machinelivesalebill->tDiscntType;
                    $liveSalesBillStore->tDiscntVal = $machinelivesalebill->tDiscntVal;
                    $liveSalesBillStore->totalMoney = $machinelivesalebill->totalMoney;
                    $liveSalesBillStore->ItemsaleDateTime = Carbon::createFromFormat('d/m/Y H:i:s', $machinelivesalebill->ItemsaleDateTime)->format('Y-m-d H:i:s');
                    $liveSalesBillStore->refundCnt = $machinelivesalebill->refundCnt;
                    $liveSalesBillStore->refundAmt = $machinelivesalebill->refundAmt;
                    $liveSalesBillStore->refundRounding = $machinelivesalebill->refundRounding;
                    $liveSalesBillStore->byCash = $machinelivesalebill->byCash;
                    $liveSalesBillStore->byVoucher = $machinelivesalebill->byVoucher;
                    $liveSalesBillStore->byVoucherNo = $machinelivesalebill->byVoucherNo;
                    $liveSalesBillStore->byCheque = $machinelivesalebill->byCheque;
                    $liveSalesBillStore->byChequeNo = $machinelivesalebill->byChequeNo;
                    $liveSalesBillStore->byCreditCard = $machinelivesalebill->byCreditCard;
                    $liveSalesBillStore->byCreditCardNo = $machinelivesalebill->byCreditCardNo;
                    $liveSalesBillStore->MachineName = $machinelivesalebill->MachineName;
                    $liveSalesBillStore->Date = $machinelivesalebill->Date;
                    $liveSalesBillStore->reportName = $machinelivesalebill->reportName;
                    $liveSalesBillStore->kotreference = $machinelivesalebill->kotreference;
                    $liveSalesBillStore->save();

                    foreach ($saleBillDetails as $key => $saleBillDetail) {
                        $liveSalesBillDetail = new LiveSalesBillDetail();
                        $liveSalesBillDetail->live_sales_bill_id = $liveSalesBillStore->id;
                        $liveSalesBillDetail->billNo = $saleBillDetail->billNo;
                        $liveSalesBillDetail->pluName = $saleBillDetail->pluName;
                        $liveSalesBillDetail->wtQty = $saleBillDetail->wtQty;
                        $liveSalesBillDetail->price = $saleBillDetail->price;
                        $liveSalesBillDetail->taxLinkNo = $saleBillDetail->taxLinkNo;
                        $liveSalesBillDetail->discntType = $saleBillDetail->discntType;
                        $liveSalesBillDetail->discntVal = $saleBillDetail->discntVal;
                        $liveSalesBillDetail->uom = $saleBillDetail->uom;
                        $liveSalesBillDetail->opName = $saleBillDetail->opName;
                        $liveSalesBillDetail->MachineName = $saleBillDetail->MachineName;
                        $liveSalesBillDetail->Date = $saleBillDetail->Date;
                        $liveSalesBillDetail->reportName = $saleBillDetail->reportName;
                        $liveSalesBillDetail->pluNumber = $saleBillDetail->pluNumber;
                        $liveSalesBillDetail->pluCode = $saleBillDetail->pluCode;
                        $liveSalesBillDetail->save();

                        DB::commit();
                    }
                }
            }
            Log::info('cronJobCompletionData insert Start');
            $cronJobCompletionData = new CronJobCompletionTime();
            $cronJobCompletionData->cron_job_name = 'MachineSaleStore';
            $cronJobCompletionData->start_time = $nowTime;
            $cronJobCompletionData->end_time = Carbon::now();
            $cronJobCompletionData->status = 1; // 1 means success
            $cronJobCompletionData->save();
            Log::info("cronJobCompletionData");
            Log::info($cronJobCompletionData->get());
            Log::info('cronJobCompletionData insert End');
            Log::info('Machine Sales Stor Job Executing Completed');
        } catch (\Exception $e) {
            // Set status to 0 in case of failure
            Log::info("cronJobCompletionData failure start");
            $cronJobCompletionData = new CronJobCompletionTime();
            $cronJobCompletionData->cron_job_name = 'MachineSaleStore';
            $cronJobCompletionData->start_time = $nowTime;
            $cronJobCompletionData->end_time = Carbon::now();
            $cronJobCompletionData->status = 2; // 2 means failure
            $cronJobCompletionData->save();
            Log::info('cronJobCompletionData failure End');
            Log::error($e);
            DB::rollback();
        }
    }
}
