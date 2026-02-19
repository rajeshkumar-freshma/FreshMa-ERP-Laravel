<?php

namespace App\Providers;

use App\Models\MailSetting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class MailServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Fetch mail settings from the database
        // Log::info('Fetching mail settings...');

        // $mail = MailSetting::first();
        // if ($mail !== null) {
        //     Log::info('Mail settings retrieved successfully:');
        //     // Determine mailer type and encryption for updating .env file
        //     $mailType = ($mail->mailer_type == 1) ? 'smtp' : 'mail';
        //     $encryption = ($mail->smtp_encryption_type == 1) ? 'SSL' : 'TLS';

        //     // Configuration array for mail settings
        //     $config = [
        //         'driver'     => $mailType,
        //         'host'       => $mail->smtp_host,
        //         'port'       => $mail->smtp_port,
        //         'address'    => $mail->email,
        //         'encryption' => $encryption,
        //         'username'   => $mail->smtp_user_name,
        //         'password'   => $mail->smtp_password,
        //         'sendmail'   => '/usr/sbin/sendmail -bs',
        //         'pretend'    => false,
        //         'name'       => $mail->name,
        //     ];

        //     // Set mail configuration dynamically
        //     Config::set('mail', $config);
        // } else {
        //     // Log an error if mail settings couldn't be retrieved
        //     Log::error('Failed to fetch mail settings or no active mail settings found.');
        //     // You may want to provide a default mail configuration here or handle the absence of mail settings based on your application's requirements.
        // }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
