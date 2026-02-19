<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ActivityLog\ActivityLogDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Controller
{
    public function ActivityLogList(ActivityLogDataTable $dataTable)
    {
        try {
            return $dataTable->render('pages.activity_log.index');
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 400,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
