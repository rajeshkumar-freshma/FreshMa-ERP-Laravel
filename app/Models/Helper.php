<?php

namespace App\Models;

use App\Jobs\ConfirmBookingQueue;
use App\Jobs\InvoiceQueue;
use App\Mail\InvoiceMail;
use Google\Client;
use Google\Service\FirebaseCloudMessaging;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceEmail;
use Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class Helper extends Model
{
    use HasFactory;
    use HasFactory;

    public static function downloadInvoice($pdf, $fileName)
    {
        // dd($pdfD);
        $pdfView = PDF::loadHTMl($pdf);
        // ini_set('max_execution_time', '1024');
        // return $pdf->download('billing-invoice1.pdf');

        // $path = public_path('pdf/');
        // $fileName =  $fileName . '.' . 'pdf';
        // $pdf->save($path . '/' . $fileName);

        // Storage::put('public/bills/bubla.pdf', $pdfView);
        return $pdfView->download($fileName . '.pdf');
    }
    // public static function upload_file($file_name_raw, $module_name = '')
    // {
    //     $path = public_path('media' . '/');

    //     $year_folder = $path . date('Y');
    //     $month_folder = $year_folder . '/' . date('m');
    //     $day_folder = $month_folder . '/' . date('d');

    //     !file_exists($year_folder) && mkdir($year_folder, 0777, true);
    //     !file_exists($month_folder) && mkdir($month_folder, 0777, true);
    //     !file_exists($day_folder) && mkdir($day_folder, 0777, true);

    //     if (empty($module_name)) {
    //         $file_name = $file_name_raw;
    //     } else {
    //         $file_name = $module_name . '_' . $file_name_raw;
    //     }

    //     $file_name = strtolower(str_replace(' ', '_', $file_name));

    //     $check_exist = $day_folder . '/' . $file_name;

    //     if (file_exists($check_exist)) {
    //         $ext = pathinfo($file_name, PATHINFO_EXTENSION);
    //         $base_name = pathinfo($file_name, PATHINFO_FILENAME);
    //         $file_name = $base_name . '_' . date('i_s') . '.' . $ext;
    //     }

    //     $db_path = 'media' . '/' . date('Y') . '/' . date('m') . '/' . date('d') . '/' . $file_name;

    //     return ['file_name' => $file_name, 'path' => $day_folder, 'db_path' => $db_path];
    // }

    public static function sendNotification($firebaseToken, $title, $content, $image, $user = null)
    {
        Log::info('SEND NOTIFICATION');
        $access_token = Self::getBearrertoken();
        // foreach ($firebaseToken as $token) {
        $data = [
            "message" => [
                // "topic" => "customers",
                "token" => $firebaseToken,
                "notification" => [
                    'title' => $title,
                    'body' => $content,
                ],
                "android" => [
                    "notification" => [
                        "image" => $image
                    ],
                    "priority" => "high"
                ],
                "apns" => [
                    "headers" => [
                        "apns-priority" => "5",
                    ],
                    "fcm_options" => [
                        "image" => $image
                    ],
                ]
            ]
        ];

        $dataString = json_encode($data);
        Log::info($dataString);
        $headers = ['Authorization: Bearer ' . $access_token, 'Content-Type: application/json'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/freshma-erp/messages:send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);

        if ($response === false) {
            die('Curl failed: ' . curl_error($ch));
        }
        Log::info("user data in send notification function");
        Log::info($user);
        Log::info($response);
        // }
    }
    // public static function createOrderId()
    // {
    //     return 'RRK' . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, 2) . date('y') . date('m') . '00' . substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, 2);
    // }

    public static function sendPushNotification($firebaseToken, $title, $content)
    {
        try {
            Log::info('entered sendPushNotification');
            $access_token = Self::getBearrertoken();
            Log::info("access_token");
            Log::info($access_token);
            $data = [
                "message" => [
                    "token" => $firebaseToken,
                    "notification" => [
                        'title' => 'FreshMaERP',
                        'body' => $content,
                    ],
                    "android" => [
                        "priority" => "high",
                    ],
                    "apns" => [
                        "headers" => [
                            "apns-priority" => "5",
                        ],
                    ]
                ]
            ];
            $dataString = json_encode($data);
            Log::info("sendPushNotification dataString");
            Log::info($dataString);

            $headers = ['Authorization: Bearer ' . $access_token, 'Content-Type: application/json'];
            Log::info("headers");
            Log::info($headers);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/rrk-erp/messages:send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);

            if ($response === false) {
                die('Curl failed: ' . curl_error($ch));
            }
            Log::info("sendPushNotification response");
            Log::info($response);
            Log::info('end sendPushNotification');
            Log::info('end sendPushNotification');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            // Handle the case where the record is not found
            return response()->view('errors.404', [], 404);
        }
    }

    public static function sendPushToNotification($content)
    {
        // try {

            //             if (Auth::guard('admin')->check()) {
            //                 $admins  = [Auth::guard('admin')->user()];
            //             } else if (Auth::guard('api')->check()) {
            //                 $admins  = [Auth::guard('api')->user()];

            //             } else {
            //                 $admins  = Admin::where('status', 1)
            //                              ->where('role_id', 1)
            //                             ->whereNotNull('fcm_token')->get();
            //             }
            //             // dd($admins);
            // if(count($admins) > 0) {

            //     Log::info('entered sendPushNotification');
            //     $access_token = Self::getBearrertoken();
            //     Log::info("access_token");
            //     Log::info($access_token);
            //     foreach($admins as $admin){
            //         $firebaseToken = $admin->fcm_token;
            //         $data = [
            //             "message" => [
            //                 "token" => $firebaseToken,
            //                 "notification" => [
            //                     'title' => 'FreshMaERP',
            //                     'body' => $content,
            //                 ],
            //                 "android" => [
            //                     "priority" => "high",
            //                 ],
            //                 "apns" => [
            //                     "headers" => [
            //                         "apns-priority" => "5",
            //                     ],
            //                 ]
            //             ]
            //         ];
            //         $dataString = json_encode($data);
            //         Log::info("sendPushNotification dataString ". $dataString);

            //         $headers = ['Authorization: Bearer ' . $access_token, 'Content-Type: application/json'];

            //         $ch = curl_init();
            //         curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/rrk-erp/messages:send');
            //         curl_setopt($ch, CURLOPT_POST, true);
            //         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            //         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //         curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            //         $response = curl_exec($ch);

            //         if ($response === false) {
            //             die('Curl failed: ' . curl_error($ch));
            //         }

            //        $admin_notification =  AdminNotification::create([
            //             'user_id' => $admin->id,
            //             'title' => 'Freshma ERP',
            //             'content' => $content,
            //             'response' => $response ?? 'testing',
            //         ]);
                    // return $response;
        //             Log::info("admin_notification ". json_encode($admin_notification));
        //             Log::info("sendPushNotification response ". $response);
        //             Log::info('end sendPushNotification');
        //         }

        //     } else {
        //         Log::info('user device_token for notification not found');
        //     }
        // } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
        //     // Handle the case where the record is not found
        //     return response()->view('errors.404', [], 404);
        // }
    }

    public static function getBearrertoken()
    {
        $access_token = NULL;
        $client = new Client();
        $client->setAuthConfig(public_path('client_secret.json'));
        // $client->setAuthConfig(Storage::disk('secret_json')->path('firebase_credentials/client_secret.json'));
        // $client->setAuthConfig(public_path('firebase_credentials/client_secret.json'));
        $client->addScope(FirebaseCloudMessaging::FIREBASE_MESSAGING);
        // $client->addScope(FirebaseCloudMessaging::CLOUD_PLATFORM);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('none');

        // if (file_exists(public_path('firebase_credentials/secret_token.json'))) {
        //     $json_file = file_get_contents(public_path('firebase_credentials/secret_token.json'));
        if (Storage::disk('secret_json')->exists('firebase_credentials/secret_token.json')) {
            $json_file = Storage::disk('secret_json')->get('firebase_credentials/secret_token.json');
            if ($json_file) {
                $token_details = json_decode($json_file, true);
                $client->setAccessToken($token_details);
            }
        }

        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $access_json_data = $client->getAccessToken();
                Log::info("newAccessToken");
                Log::info($access_json_data);
                $data = [];
                foreach ($access_json_data as $key => $value) {
                    $data[$key] = $value;
                }
                Log::info("newAccessToken format data");
                Log::info($data);
                Storage::disk('secret_json')->put('firebase_credentials/secret_token.json', json_encode($data, true));
                // file_put_contents(public_path('firebase_credentials/secret_token.json'), json_encode($data, true));
            }
        }

        // if (file_exists(public_path('firebase_credentials/secret_token.json'))) {
        //     $json_file = file_get_contents(public_path('firebase_credentials/secret_token.json'));
        if (Storage::disk('secret_json')->exists('firebase_credentials/secret_token.json')) {
            $json_file = Storage::disk('secret_json')->get('firebase_credentials/secret_token.json');
            if ($json_file) {
                $token_details = json_decode($json_file, true);
                $access_token = $token_details['access_token'];

                Log::info("newly created access_token");
                Log::info($access_token);
            }
        }
        return $access_token;
    }

    // public static function sendAdminPushNotification($firebaseToken, $title, $content)
    // {
    //     Log::info("Admin Helper File accessed");
    //     $access_token = Self::getBearrertoken();
    //     foreach ($firebaseToken as $key => $token) {
    //         Log::info($token);
    //         $data = [
    //             "message" => [
    //                 "token" => $token,
    //                 "notification" => [
    //                     'title' => $title,
    //                     'body' => $content,
    //                 ],
    //                 "android" => [
    //                     'priority' => 'high',
    //                     'notification' => [
    //                         'sound' => 'notification',
    //                         'channelId' => 'sound-channel-id',
    //                     ],
    //                 ],
    //                 "apns" => [
    //                     "payload" => [
    //                         "aps" => [
    //                             "sound" => "notification"
    //                         ],
    //                     ],
    //                 ],
    //             ],
    //         ];
    //         $dataString = json_encode($data);
    //         Log::info("Admin push notification dataString");
    //         Log::info($dataString);

    //         $headers = ['Authorization: Bearer ' . $access_token, 'Content-Type: application/json'];
    //         Log::info("headers");
    //         Log::info($headers);

    //         $ch = curl_init();
    //         curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/freshma-5dadc/messages:send');
    //         curl_setopt($ch, CURLOPT_POST, true);
    //         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //         curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
    //         $response = curl_exec($ch);

    //         if ($response === false) {
    //             die('Curl failed: ' . curl_error($ch));
    //         }

    //         Log::info("Admin push notification response");
    //         Log::info($response);
    //         Log::info("Admin push notification finish");
    //     }
    // }

    // public static function sendAdminPushNotificationfortesting($firebaseToken, $data)
    // {
    //     Log::info("admin Helper File accessed");
    //     $access_token = Self::getBearrertoken();
    //     foreach ($firebaseToken as $key => $token) {
    //         Log::info($token);
    //         $dataString = json_encode($data);
    //         Log::info("Admin push notification dataString");
    //         Log::info($dataString);

    //         $headers = ['Authorization: Bearer ' . $access_token, 'Content-Type: application/json'];

    //         Log::info("headers");
    //         Log::info($headers);

    //         $ch = curl_init();
    //         curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/freshma-5dadc/messages:send');
    //         curl_setopt($ch, CURLOPT_POST, true);
    //         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //         curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
    //         $response = curl_exec($ch);

    //         if ($response === false) {
    //             die('Curl failed: ' . curl_error($ch));
    //         }

    //         Log::info("Admin push notification response");
    //         Log::info($response);
    //         Log::info("Admin push notification finish");
    //         return $response;
    //     }
    // }
    public static function overWriteEnvFile($key, $value)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            Log::info("entered overWriteEnvFile");
            Log::info("key");
            Log::info($key);
            Log::info("path");
            Log::info($path);
            $value = '"' . trim($value) . '"';
            Log::info("value ");
            Log::info($value);
            if (is_numeric(strpos(file_get_contents($path), $key)) && strpos(file_get_contents($path), $key) >= 0) {
                Log::info("enetere overwite data");
                file_put_contents($path, str_replace(
                    $key . '="' . env($key) . '"',
                    $key . '=' . $value,
                    file_get_contents($path)
                ));
            } else {
                file_put_contents($path, file_get_contents($path) . "\r\n" . $key . '=' . $value);
            }
        }
    }
    public static function sendMail($template, $to_email, $orderNo, $body)
    {
        $email_template = EmailTemplate::where('code', $template)->first();
        Log::info("email_template");
        Log::info($email_template);
        $content  = null;
        $SettingGeneral = SystemSiteSetting::first();
        $table = false;
        $subject = $email_template['subject'];
        Log::info($body);
        // Artisan::call('queue:listen');
        if (!empty($email_template)) {

            $string = $email_template->body;
            if ($email_template->code == 'PO-CON') {

                $content = Str::replaceArray('{{customer_name}}', [$body['customer_name']], $string);
                $content = Str::replaceArray('{{order_no}}', [$body['order_no']], $content);
                $content = Str::replaceArray('{{tiny_url}}', [$body['tiny_url']], $content);
                $content = Str::replaceArray('{{from}}', [$SettingGeneral->site_name ?? 'FreshMa'], $content);
                $table = Str::contains($content, '{{table}}');
                Log::info($content);
                Log::info($table);
                // Dispatch the job
                // InvoiceQueue::dispatch($to_email, $subject, $content, $orderNo, $table, $body);
                Mail::send(new InvoiceMail($to_email, $subject, $content, $orderNo, $table, $body));
                Log::info("confirm mailqueue send");
            } elseif ($email_template->code == 'SO-CON') {
                $content = Str::replaceArray('{{customer_name}}', [$body['customer_name']], $string);
                $content = Str::replaceArray('{{order_no}}', [$body['order_no']], $content);
                $content = Str::replaceArray('{{tiny_url}}', [$body['tiny_url']], $content);
                $content = Str::replaceArray('{{from}}', [$SettingGeneral->site_name ?? 'FreshMa'], $content);
                $table = Str::contains($content, '{{table}}');
                Mail::send(new InvoiceMail($to_email, $subject, $content, $orderNo, $table, $body));
            }
        }
    }
}
