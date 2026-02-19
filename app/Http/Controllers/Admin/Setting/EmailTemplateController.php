<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Models\EmailTemplate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\EmailTemplateFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['templates'] = EmailTemplate::get();
        return view('pages.setting.email_template.index', $data);
    }

    public function update(EmailTemplateFormRequest $request)
    {
        DB::beginTransaction();
        // try {
            // return $request;
        EmailTemplate::where('id', $request->id)->update([
            'subject' => $request->subject,
            'body' => $request->body,
            'status' => $request->status
        ]);
        DB::commit();
        return to_route('admin.email-template.index')->with('success', 'Email Template Updated Successfully');
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return back()->withInput()->with('error', 'Adjustment Stored Fail');
        // }
    }

    public function getEmailTemplate($id)
    {
        Log::info($id);
        if ($id != '') {
            $template = EmailTemplate::find($id);
            $html = view('pages.setting.email_template.render-email-template', compact('template'))->render();
            return response()->json(['status' => TRUE, 'html' => $html]);
        } else {
            return response()->json(['status' => FALSE, 'html' => 'Something Went Wrong']);
        }
    }
}
