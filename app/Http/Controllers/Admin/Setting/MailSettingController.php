<?php

namespace App\Http\Controllers\Admin\Setting;


use App\Http\Controllers\Controller;
use App\DataTables\Setting\MailSettingDataTable;
use App\Http\Requests\Setting\MailSettingFormRequest;
use App\Mail\PaymentSuccessEmail;
use App\Models\ApiKeySetting;
use App\Models\Helper;
use App\Models\MailSetting;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;
use Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class MailSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['mail_setting_datas'] = MailSetting::first() ?? '';
        return view('pages.setting.mail_setting.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     return view('pages.setting.mail_setting.create');
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MailSettingFormRequest $request)
    {
        // Begin a database transaction
        DB::beginTransaction();

        // try {
        // Retrieve the validated request data
        $mailerType = $request->mailer_type;
        $fromName = $request->from_name;
        $fromEmail = $request->from_email;
        $smtpHost = $request->smtp_host;
        $smtpPort = $request->smtp_port;
        $smtpUsername = $request->smtp_user_name;
        $smtpPass = $request->smtp_password;
        $smtpEncryptionType = $request->smtp_encryption_type;
        $status = $request->status;
        // Map request data to the format required for updateOrCreate
        $mailSettingData = [
            'mailer_type' => $mailerType,
            'name' => $fromName,
            'email' => $fromEmail,
            'smtp_host' => $smtpHost,
            'smtp_port' => $smtpPort,
            'smtp_user_name' => $smtpUsername,
            'smtp_password' => $smtpPass,
            'smtp_encryption_type' => $smtpEncryptionType,
            'status' => $status,
        ];

        // Update or create the MailSetting record
        MailSetting::updateOrCreate([], $mailSettingData);


        // Determine mailer type and encryption for updating .env file
        $mailType = ($mailerType == 1) ? 'smtp' : 'mail';
        $encryption = ($smtpEncryptionType == 1) ? 'SSL' : 'TLS';

        // // Update .env variables
        // Helper::overWriteEnvFile('MAIL_MAILER', $mailType);
        // Helper::overWriteEnvFile('MAIL_FROM_ADDRESS', $fromEmail);
        // Helper::overWriteEnvFile('MAIL_FROM_NAME',  $fromName);
        // Helper::overWriteEnvFile('MAIL_HOST', $smtpHost);
        // Helper::overWriteEnvFile('MAIL_PORT', $smtpPort);
        // Helper::overWriteEnvFile('MAIL_ENCRYPTION', $encryption);
        // Helper::overWriteEnvFile('MAIL_USERNAME',  $smtpUsername);
        // Helper::overWriteEnvFile('MAIL_PASSWORD',  $smtpPass);

        // Update SMTP configurations dynamically
        Config::set('mail.mailers.smtp.host', $smtpHost);
        Config::set('mail.mailers.smtp.port', $smtpPort);
        Config::set('mail.mailers.smtp.encryption', $encryption);
        Config::set('mail.mailers.smtp.username', $smtpUsername);
        Config::set('mail.mailers.smtp.password', $smtpPass);
        Config::set('mail.from.address', $fromEmail);
        Config::set('mail.from.name', $fromName);

        // Optionally, you may also set the mailer type dynamically
        Config::set('mail.default', $mailType); // 'smtp' or 'mail'

        // // Optionally, reset the configuration after sending the email to avoid side effects
        // Config::set('mail', require base_path('config/mail.php'));

        // Commit the transaction
        DB::commit();

        // Redirect with success message
        return redirect()->route('admin.mail-setting.index')->with('success', 'SMTP Setup  Successfully');
        // } catch (\Exception $e) {
        //     // Rollback the transaction in case of exception
        //     DB::rollBack();

        //     // Redirect back with error message and input data
        //     return back()->with('error', 'Mail Setup Cannot be Created')->withInput();
        // }
    }





    /**
     * Display the specified resource.
     */
    public function show(ApiKeySetting $apiKeySetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $data['mail_setting_datas'] = MailSetting::findOrFail($id);
            return view('pages.setting.mail_setting.edit', $data);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(MailSettingFormRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            // return $request;
            $name = $request->name;
            $status = $request->status;
            $email = $request->email;
            $protocol = $request->protocol;
            $smtpHost = $request->smtp_host;
            $smtpUsername = $request->smtp_user_name;
            $smtpPass = $request->smtp_password;
            $smtpPort = $request->smtp_port;
            $smtpEncryption = $request->smtp_encryption;

            $mailSetting = MailSetting::find($id);
            $mailSetting->name = $name;
            $mailSetting->status = $status;
            $mailSetting->email = $email;
            $mailSetting->protocol = $protocol;
            $mailSetting->smtp_host = $smtpHost;
            $mailSetting->smtp_user_name = $smtpUsername;
            $mailSetting->smtp_password = $smtpPass;
            $mailSetting->smtp_port = $smtpPort;
            $mailSetting->smtp_encryption = $smtpEncryption;
            $mailSetting->save();
            // Redirect back with success message
            Log::info("mailSetting");
            Log::info("$mailSetting");
            DB::commit();
            return to_route('admin.mail-setting.index')->with('success', 'Mail Setting Updated  Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating Api Key')->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApiKeySetting $apiKeySetting)
    {
        //
    }
}
