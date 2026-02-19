<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Core\CommonComponent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\SystemSiteSettingFormRequest;
use App\Models\Country;
use App\Models\Currency;
use App\Models\MailCredential;
use App\Models\MailSetting;
use App\Models\PartnershipType;
use App\Models\Store;
use App\Models\SystemSetting;
use App\Models\SystemSiteSetting;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SystemSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['system_settings_data'] = SystemSetting::get();
        $data['system_site_data'] = SystemSiteSetting::first();
        $data['countries'] = Country::where('status', 1)->get();
        $data['currencies'] = Currency::where('status', 1)->get();
        $data['partnership_types'] = PartnershipType::where('status', 1)->get();
        $data['stores'] = Store::where('status', 1)->get();
        $data['warehouses'] = Warehouse::where('status', 1)->get();
        // $data['email_settings_data'] = $this->getEmailSettingsWithStatus(1);
        // $data['mail_credentials_data'] = $this->getEmailCredential(1);
        return view('pages.setting.system_setting.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Retrieve the values from the request
        $setting_values = $request->input('values');

        // Check if values are provided and if it's an array
        if (!empty($setting_values) && is_array($setting_values)) {
            foreach ($setting_values as $key => $value) {
                // Skip null values
                if ($value !== null) {
                    // Create or update the system setting
                    SystemSetting::updateOrCreate(
                        ['key' => $key],
                        ['value' => $value]
                    );
                }
            }
        }

        // Redirect back with success message
        return redirect()->route('admin.system-setting.index')->with('success', 'System Prefix Stored Successfully');
    }

    /**
     * Retrieve email settings with a specific status.
     *
     * @param int $status
     * @return \Illuminate\Database\Eloquent\Collection
     */
    // private function getEmailSettingsWithStatus($status)
    // {
    //     return MailSetting::where('status', $status)->get();
    // }
    // private function getEmailCredential($status)
    // {
    //     return MailCredential::where('status', $status)->with('mailSetting')->get();
    // }

    /**
     * Display the specified resource.
     */
    public function setEmailMethod(Request $request)
    {
        DB::beginTransaction();
        try {
            $mailCredentil = new  MailCredential();
            $mailCredentil->key = $request->key;
            $mailCredentil->value = $request->value;
            $mailCredentil->status = $request->status;
            $mailCredentil->save();
            DB::commit();
            return to_route('admin.system-setting.index')->with('success', 'Mail Setting Updated  Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating Api Key')->withInput();
        }
    }

    public function siteConfigStore(SystemSiteSettingFormRequest $request)
    {
        DB::beginTransaction();
        // try {
        // return $request;

        $siteName = $request->site_name;
        $lang = $request->language;
        $image = $request->image;
        $imagePath = null;
        $imageUrl = null;
        if ($request->hasFile('image') && $request->fill('image')) {
            $imageUpload = CommonComponent::s3BucketFileUpload($request->fill('image'), 'defaultImage');
            $imagePath = $imageUpload['filePath'];
            $imageUrl = $imageUpload['imageURL'];
        }

        $currency = $request->currency;
        $accountingMethod = $request->accounting_method;
        $email = $request->email;
        $customerGroup = $request->customer_group;
        $priceGroup = $request->price_group;
        $mmode = $request->mmode;
        $theme = $request->theme;
        $captcha = $request->captcha;
        $disablEditing = $request->disable_editing;
        $rowsPerPage = $request->rows_per_page;
        $dateformat = $request->dateformat;
        $timezone = $request->timezone;
        $warehouse = $request->warehouse;
        $pdfLib = $request->pdf_lib;
        $apis = $request->apis;
        $useCodeForSlug = $request->use_code_for_slug;
        // Map request data to the format required for updateOrCreate
        $siteSettingData = [
            'site_name' => $siteName,
            'language' => $lang,
            'currency' => $currency,
            'accounting_method' => $accountingMethod,
            'email' => $email,
            'customer_group' => $customerGroup,
            'price_group' => $priceGroup,
            'mmode' => $mmode,
            'theme' => $theme,
            'captcha' => $captcha,
            'disable_editing' => $disablEditing,
            'rows_per_page' => $rowsPerPage,
            'dateformat' => $dateformat,
            'timezone' => $timezone,
            'warehouse' => $warehouse,
            'pdf_lib' => $pdfLib,
            'apis' => $apis,
            'use_code_for_slug' => $useCodeForSlug,
            'image' => $imageUrl,
            'image_path' => $imagePath,
        ];

        // // Update or create the MailSetting record
        SystemSiteSetting::updateOrCreate([], $siteSettingData);
        // $SystemSiteSetting = new  SystemSiteSetting();
        // $SystemSiteSetting->key = $request->key;
        // $SystemSiteSetting->value = $request->value;
        // $SystemSiteSetting->status = $request->status;
        // $SystemSiteSetting->save();
        DB::commit();
        return to_route('admin.system-setting.index')->with('success', 'Site Setting Setting Updated  Successfully');
        // } catch (Exception $e) {
        //     DB::rollBack();
        //     return back()->with('error', 'Error updating Site Setting')->withInput();
        // }
    }

    public function autoCronRun()
    {
        try {
            //     // Run scheduled tasks
            Log::info("Run Started");
            $scheduleOutput = Artisan::call('queue:listen');
            Log::info("Run Ended");
            //     // Process queued jobs
            //     $queueOutput = Artisan::call('queue:work', ['--once' => true]);

            //     // Check if there are any errors in the output
            //     if ($scheduleOutput !== 0 || $queueOutput !== 0) {
            //         // If there are errors, handle them appropriately
            //         return redirect()->back()->with('error', 'An error occurred while executing cron jobs.');
            //     }

            // If everything executed successfully
            return redirect()->back()->with('success', 'All cron jobs executed successfully.');
        } catch (\Exception $e) {
            // Handle exception
            echo 'Error executing Artisan command: ' . $e->getMessage();
        }
    }
}
