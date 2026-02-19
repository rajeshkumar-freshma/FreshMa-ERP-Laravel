<?php

namespace App\Http\Controllers\Admin\PushNotification;

use App\Http\Controllers\Controller;
use App\Models\PushNotification;
use App\Models\User;
use Illuminate\Http\Request;
use App\Jobs\PushNotification as JobsPushNotification;
use App\Models\Helper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PushNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('pages.push_notifications.index');
    }
    // public function test_notification()
    // {
    //     dd(Helper::sendPushToNotification('testing notifications'));
    // }

    // public function storeToken(Request $request)
    // {
    //     auth()->user()->update(['device_key' => $request->token]);
    //     return response()->json(['Token successfully stored.']);
    // }

    // public function sendWebNotification(Request $request)
    // {
    //     $url = 'https://fcm.googleapis.com/fcm/send';
    //     $FcmToken = User::whereNotNull('device_key')->pluck('device_key')->all();

    //     $serverKey = 'server key goes here';

    //     $data = [
    //         "registration_ids" => $fcm_token,
    //         "notification" => [
    //             "title" => $request->title,
    //             "body" => $request->body,
    //         ]
    //     ];
    //     $encodedData = json_encode($data);

    //     $headers = [
    //         'Authorization:key=' . $serverKey,
    //         'Content-Type: application/json',
    //     ];

    //     $ch = curl_init();

    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    //     curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    //     // Disabling SSL Certificate support temporarly
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
    //     // Execute post
    //     $result = curl_exec($ch);
    //     if ($result === FALSE) {
    //         die('Curl failed: ' . curl_error($ch));
    //     }
    //     // Close connection
    //     curl_close($ch);
    //     // FCM response
    //     dd($result);
    // }
    public function create()
    {

        try {
            return view('admin.push-notification.create');
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            Log::info($request->all());
            $validator = \Validator::make(
                $request->all(),
                [
                    'subject' => 'required',
                    'message' => 'required',
                    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ],

            );
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $files = request()->file('image');
            $imagepath = '';

            if ($request->image != null) {
                $path = '/push-notification';
                $profileimage = file_get_contents($request->image);
                Storage::disk('admin')->put($path . $files->getClientOriginalName(), $profileimage);
                $imagepath = 'uploads' . $path . $files->getClientOriginalName();
            }

            PushNotification::create([
                'subject' => request('subject'),
                'message' => request('message'),
                'image' => $imagepath,
            ]);

            return redirect()->route('admin.pushnotification.index')->with('success', 'PushNotification Added Successfully');
        } catch (\Throwable $e) {

            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function send($id)
    {
        try {
            $notification = PushNotification::where('id', $id)->first();

            Log::info("notification");
            Log::info($notification);
            // Bus::batch([
            //     new JobsPushNotification($notification)
            // ])->dispatch();
            JobsPushNotification::dispatch($notification);
            return redirect()->route('admin.push-notification.index')->with('success', 'Notification Send Successfully');
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $notification = PushNotification::where('id', $id)->first();
            return view('admin.push-notification.edit', compact('notification'));
        } catch (\Throwable $e) {

            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            $validator = \Validator::make(
                $request->all(),
                [
                    'subject' => 'required',
                    'message' => 'required',
                ]
            );
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $notification = PushNotification::where('id', $id)->first();
            $files = request()->file('image');
            $imagepath = $notification->image;

            if ($request->image != null) {
                $path = '/push-notification';
                $profileimage = file_get_contents($request->image);
                Storage::disk('admin')->put($path . $files->getClientOriginalName(), $profileimage);
                $imagepath = 'uploads' . $path . $files->getClientOriginalName();
            }

            PushNotification::where('id', $id)->update([
                'subject' => request('subject'),
                'message' => request('message'),
                'image' => $imagepath,
            ]);

            return redirect()->route('admin.push-notification.index')->with('success', 'PushNotification Updated Successfully');
        } catch (\Throwable $e) {

            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
