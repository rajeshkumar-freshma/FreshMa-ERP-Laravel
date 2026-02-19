<?php

use Illuminate\Support\Facades\Facade;

return [
    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
     */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
     */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
     */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
     */

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
     */
    'timezone' => 'Asia/Kolkata',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
     */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
     */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
     */

    'faker_locale' => 'en_US',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
     */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
     */

    'maintenance' => [
        'driver' => 'file',
        // 'store'  => 'redis',
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
     */

    'providers' => [
        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\HelperServiceProvider::class,
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\MailServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        Maatwebsite\Excel\ExcelServiceProvider::class,
        Telegram\Bot\Laravel\TelegramServiceProvider::class,
        Barryvdh\DomPDF\ServiceProvider::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
     */

    'aliases' => Facade::defaultAliases()
        ->merge([
            'App' => Illuminate\Support\Facades\App::class,
            'Arr' => Illuminate\Support\Arr::class,
            'Artisan' => Illuminate\Support\Facades\Artisan::class,
            'Auth' => Illuminate\Support\Facades\Auth::class,
            'Blade' => Illuminate\Support\Facades\Blade::class,
            'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
            'Bus' => Illuminate\Support\Facades\Bus::class,
            'Cache' => Illuminate\Support\Facades\Cache::class,
            'Config' => Illuminate\Support\Facades\Config::class,
            'Cookie' => Illuminate\Support\Facades\Cookie::class,
            'Crypt' => Illuminate\Support\Facades\Crypt::class,
            'DB' => Illuminate\Support\Facades\DB::class,
            'Eloquent' => Illuminate\Database\Eloquent\Model::class,
            'Event' => Illuminate\Support\Facades\Event::class,
            'File' => Illuminate\Support\Facades\File::class,
            'Gate' => Illuminate\Support\Facades\Gate::class,
            'Hash' => Illuminate\Support\Facades\Hash::class,
            'Http' => Illuminate\Support\Facades\Http::class,
            'Lang' => Illuminate\Support\Facades\Lang::class,
            'Log' => Illuminate\Support\Facades\Log::class,
            'Mail' => Illuminate\Support\Facades\Mail::class,
            'Notification' => Illuminate\Support\Facades\Notification::class,
            'Password' => Illuminate\Support\Facades\Password::class,
            'Queue' => Illuminate\Support\Facades\Queue::class,
            'Redirect' => Illuminate\Support\Facades\Redirect::class,
            // 'Redis' => Illuminate\Support\Facades\Redis::class,
            'Request' => Illuminate\Support\Facades\Request::class,
            'Response' => Illuminate\Support\Facades\Response::class,
            'Route' => Illuminate\Support\Facades\Route::class,
            'Schema' => Illuminate\Support\Facades\Schema::class,
            'Session' => Illuminate\Support\Facades\Session::class,
            'Storage' => Illuminate\Support\Facades\Storage::class,
            'Str' => Illuminate\Support\Str::class,
            'URL' => Illuminate\Support\Facades\URL::class,
            'Validator' => Illuminate\Support\Facades\Validator::class,
            'View' => Illuminate\Support\Facades\View::class,
            'Excel' => Maatwebsite\Excel\Facades\Excel::class,
            'Telegram' => Telegram\Bot\Laravel\Facades\Telegram::class,
            'PDF' => Barryvdh\DomPDF\Facade\PDF::class,

        ])
        ->toArray(),

    'component_name' => 'freshma',

    'attachmentfilesize' => '2048',

    'attachmentfilesizeinmb' => '2mb',

    'attachmentfiletype' => 'jpeg,png,jpg,pdf',

    'imageattachmentfiletype' => 'jpeg,png,jpg',

    'profilepicturesize' => '1024',

    'profilepicturesizeinmb' => '1mb',

    'profilepicturetype' => 'jpeg,png,jpg',

    'submission_type' => [['value' => '1', 'name' => 'Submit'], ['value' => '2', 'name' => 'Submit & Stay']],

    'statusinactive' => [['value' => '0', 'name' => 'In-Active', 'color' => 'danger', 'color_code' => '#dc3545'], ['value' => '1', 'name' => 'Active', 'color' => 'success', 'color_code' => '#198754']],

    'defaultstatus' => [['value' => '0', 'name' => 'In-Active', 'color' => 'danger', 'color_code' => '#dc3545'], ['value' => '1', 'name' => 'Default', 'color' => 'success', 'color_code' => '#198754']],

    'incomeexpense' => [['value' => '1', 'name' => 'Income', 'color' => 'success'], ['value' => '2', 'name' => 'Expense', 'color' => 'danger']],

    'adjustment_status' => [
        ['value' => '0', 'name' => 'Adjustment Created', 'color' => 'warning'],
        ['value' => '1', 'name' => 'Verified', 'color' => 'info'],
        ['value' => '2', 'name' => 'Approved', 'color' => 'primary'],
        ['value' => '3', 'name' => 'Adjusted', 'color' => 'success'],
        ['value' => '4', 'name' => 'Cancelled', 'color' => 'danger'],
        ['value' => '5', 'name' => 'Rejected', 'color' => 'danger']],

    'yesorno' => [['value' => '0', 'name' => 'No', 'color' => 'danger'], ['value' => '1', 'name' => 'Yes', 'color' => 'success']],

    'income_expense_payment_status' => [
        ['value' => '1', 'name' => 'Paid', 'color' => 'success', 'usertype_code' => 'admin_code', 'prefix' => 'ADM-'],
        ['value' => '2', 'name' => 'Pending/Partially', 'color' => 'warning', 'usertype_code' => 'manager_code', 'prefix' => 'MAN-'],
        ['value' => '3', 'name' => 'Unpaid', 'color' => 'secondary', 'usertype_code' => 'partner_code', 'prefix' => 'PAR-'],
    ],
    'purchase_status' => [
        ['value' => '1', 'name' => 'Requested', 'color' => 'info', 'color_code' => '#0dcaf0'],
        ['value' => '2', 'name' => 'Pending', 'color' => 'warning', 'color_code' => '#ffc107'],
        ['value' => '3', 'name' => 'Approved', 'color' => 'success', 'color_code' => '#198754'],
        ['value' => '4', 'name' => 'Cancelled', 'color' => 'danger', 'color_code' => '#dc3545'],
        ['value' => '5', 'name' => 'Rejected', 'color' => 'danger', 'color_code' => '#dc3545'],
        ['value' => '6', 'name' => 'Accept by supplier', 'color' => 'primary', 'color_code' => '#0d6efd'],
        ['value' => '7', 'name' => 'item Departure', 'color' => 'dark', 'color_code' => '##212529'],
        ['value' => '8', 'name' => 'Received', 'color' => 'success', 'color_code' => '#198754'],
        ['value' => '9', 'name' => 'Partialy Received', 'color' => 'success', 'color_code' => '#198754'],
        ['value' => '10', 'name' => 'Received & Verified', 'color' => 'success', 'color_code' => '#198754'],
        ['value' => '11', 'name' => 'Returned', 'color' => 'success', 'color_code' => '#198754'],
    ],

    'purchase_received_status' => [8, 9, 10],

    'sales_ordered_status' => [2, 3],

    'purchase_cancelled_status' => [4, 5],

    'purchase_ordered_status' => [1, 2, 3, 6, 7],

    'rejected_status' => [4, 6],

    'returned_status' => 11,

    'created_at_dateformat' => 'd-m-Y H:i:s',

    'actual_dateformat' => 'd-m-Y',

    'paginate' => '10',

    'unit_oprators' => [['value' => '+', 'name' => 'Addition'], ['value' => '-', 'name' => 'Subtraction'], ['value' => '*', 'name' => 'Multiplication'], ['value' => '/', 'name' => 'Division']],
    'unit_oprators_carectors' => [['value' => '1', 'name' => '+'], ['value' => '2', 'name' => '-'], ['value' => '3', 'name' => '*'], ['value' => '4', 'name' => '/']],

    'product_box_mapping' => [
        ['value' => '1', 'name' => 'Addition', 'color' => 'success', 'color_code' => '#198754'],
        ['value' => '2', 'name' => 'Subtraction', 'color' => 'danger', 'color_code' => '#dc3545'],
    ],

    'tax_type' => [
        1 => 'inclusive',
        2 => 'exclisive',
    ],

    'user_of_user_type' => [
        ['value' => '1', 'name' => 'Customer', 'color' => 'success'],
        ['value' => '2', 'name' => 'Supplier', 'color' => 'warning'],
    ],
    'app_menu_mapping_type' => [
        ['value' => '1', 'name' => 'Partner/Manager', 'color' => 'success'],
        ['value' => '2', 'name' => 'Supplier', 'color' => 'warning'],
    ],

    'admin_of_user_type' => [
        ['value' => '1', 'name' => 'Super Admin', 'color' => 'success', 'usertype_code' => 'admin_code', 'prefix' => 'ADM-'],
        ['value' => '2', 'name' => 'Manager', 'color' => 'warning', 'usertype_code' => 'manager_code', 'prefix' => 'MAN-'],
        ['value' => '3', 'name' => 'Partner', 'color' => 'info', 'usertype_code' => 'partner_code', 'prefix' => 'PAR-'],
        ['value' => '4', 'name' => 'Staff', 'color' => 'primary', 'usertype_code' => 'staff_code', 'prefix' => 'EMP-'],
    ],

    'mail_key' => [
        ['value' => '1', 'name' => 'Successful Payments', 'color' => 'success', 'color_code' => '#198754'],
        ['value' => '2', 'name' => 'Payouts', 'color' => 'danger', 'color_code' => '#dc3545'],
        ['value' => '3', 'name' => 'Fee Collection', 'color' => 'warning', 'color_code' => '#ffc107'],
        ['value' => '4', 'name' => 'Receive a notification if a payment', 'color' => 'warning', 'color_code' => '#ffc107'],
    ],

    'mailer_type' => [
        ['value' => '1', 'name' => 'SMTP'],
    ],

    'encription_type' => [
        ['value' => '0', 'name' => 'None'],
        ['value' => '1', 'name' => 'SSL'],
        ['value' => '2', 'name' => 'TLS'],
    ],

    'accounting_method' => [
        ['value' => '1', 'name' => 'FIFO ( First in First Out)'],
        ['value' => '2', 'name' => 'LIFO ( Last in First Out)'],
        ['value' => '3', 'name' => 'AVCO (Average Cost Method)'],
    ],

    'default_customer_group' => [
        ['value' => '1', 'name' => 'General'],
        ['value' => '2', 'name' => 'Reseller'],
        ['value' => '3', 'name' => 'Ditributer'],
        ['value' => '4', 'name' => 'New User(+91)'],
    ],

    'default_price_group' => [
        ['value' => '1', 'name' => 'Couple'],
        ['value' => '2', 'name' => 'Compined'],
        ['value' => '3', 'name' => 'Single'],
    ],

    'maintenance_mode' => [
        ['value' => '0', 'name' => 'No'],
        ['value' => '1', 'name' => 'Yes'],
    ],

    'site_theme' => [
        ['value' => '0', 'name' => 'Default'],
        ['value' => '1', 'name' => 'Dark'],
        ['value' => '2', 'name' => 'Light'],
        ['value' => '3', 'name' => 'System'],
    ],

    'ableDisable' => [
        ['value' => '0', 'name' => 'Disable'],
        ['value' => '1', 'name' => 'Enable'],
    ],

    'pdf_library' => [
        ['value' => '1', 'name' => 'mPDF'],
        ['value' => '2', 'name' => 'Dompdf'],
    ],

    'time_zone' => [
        ['value' => '1', 'name' => 'Asia/Kolkata'],
    ],

    'date_format' => [
        ['value' => '1', 'name' => 'mm-dd-yyyy'],
        ['value' => '2', 'name' => 'mm/dd/yyyy'],
        ['value' => '3', 'name' => 'mm.dd.yyyy'],
        ['value' => '4', 'name' => 'dd-mm-yyyy'],
        ['value' => '5', 'name' => 'dd/mm/yyyy'],
        ['value' => '6', 'name' => 'dd.mm.yyyy'],
    ],

    'site_config_lables' => [
        ['value' => '1', 'name' => 'Site Name'],
        ['value' => '2', 'name' => 'Lanaguage'],
        ['value' => '3', 'name' => 'Default Currency'],
        ['value' => '4', 'name' => 'Accounting Method'],
        ['value' => '5', 'name' => 'Default Email'],
        ['value' => '6', 'name' => 'Default Customer Group'],
        ['value' => '7', 'name' => 'Default Price Group'],
        ['value' => '8', 'name' => 'Maintenance Mode'],
        ['value' => '9', 'name' => 'Theme'],
        ['value' => '10', 'name' => 'RTL Support'],
        ['value' => '11', 'name' => 'Login Captcha'],
        ['value' => '12', 'name' => 'Number of days to disable editing'],
        ['value' => '13', 'name' => 'Rows per page'],
        ['value' => '14', 'name' => 'Date Format'],
        ['value' => '15', 'name' => 'Timezone'],
        ['value' => '16', 'name' => 'Calender'],
        ['value' => '17', 'name' => 'Default Warehouse'],
        ['value' => '18', 'name' => 'Default Biller'],
        ['value' => '19', 'name' => 'PDF Library'],
        ['value' => '20', 'name' => 'APIs Feature'],
        ['value' => '21', 'name' => 'Use code for slug'],
    ],

    'salary_type' => [
        1 => 'Per Day',
        2 => 'Per Week',
        3 => 'Month',
        4 => 'Commission',
    ],

    'amount_type' => [
        1 => 'Fixed',
        2 => 'Percentage',
    ],

    'machine_status' => [['value' => '0', 'name' => 'Offline', 'color' => 'danger'], ['value' => '1', 'name' => 'Online', 'color' => 'success']],

    'payment_status' => [
        ['value' => '1', 'name' => 'Paid', 'color' => 'success', 'color_code' => '#198754'],
        ['value' => '2', 'name' => 'UnPaid', 'color' => 'danger', 'color_code' => '#dc3545'],
        ['value' => '3', 'name' => 'Pending/Due', 'color' => 'warning', 'color_code' => '#ffc107'],
    ],
    'related_to' => [
        ['value' => '1', 'name' => 'Store', 'color' => 'success', 'color_code' => '#198754'],
        ['value' => '2', 'name' => 'Warehouse', 'color' => 'danger', 'color_code' => '#dc3545'],
    ],
    'income_expense_category' => [
        ['value' => '1', 'name' => 'Food Cose', 'color' => 'success', 'color_code' => '#198754'],
        ['value' => '2', 'name' => 'Petrol', 'color' => 'danger', 'color_code' => '#dc3545'],
        ['value' => '3', 'name' => 'Bike Service', 'color' => 'warning', 'color_code' => '#ffc107'],
        ['value' => '4', 'name' => 'Other', 'color' => 'warning', 'color_code' => '#ffc107'],
    ],

    'payment_category' => [
        ['value' => '1', 'name' => 'Cash Sale', 'color' => 'success', 'color_code' => '#198754'],
        ['value' => '2', 'name' => 'Card Payment', 'color' => 'danger', 'color_code' => '#dc3545'],
        ['value' => '3', 'name' => 'UPI Payment', 'color' => 'warning', 'color_code' => '#ffc107'],
        ['value' => '4', 'name' => 'Online(FreshMa) Sale', 'color' => 'warning', 'color_code' => '#ffc107'],
    ],

    'attendance_type' => [
        ['value' => '0', 'name' => 'Absent', 'color' => 'warning', 'color_code' => '#ffc107', 'icon_name' => 'window-close'],
        ['value' => '1', 'name' => 'Present', 'color' => 'success', 'color_code' => '#198754', 'icon_name' => 'check'],
        ['value' => '2', 'name' => 'Half Day', 'color' => 'primary', 'color_code' => '#0d6efd', 'icon_name' => 'star-half'],
        ['value' => '3', 'name' => 'Holiday', 'color' => 'danger', 'color_code' => '#dc3545', 'icon_name' => 'palm-tree'],
        ['value' => '4', 'name' => 'Leave', 'color' => 'danger', 'color_code' => '#dc3545', 'icon_name' => 'palm-tree'],
        ['value' => '5', 'name' => 'Vacation', 'color' => 'warning', 'color_code' => '#ffc107', 'icon_name' => 'star-half'],
    ],
    'holiday_type' => [
        ['value' => '0', 'name' => 'Goverment', 'color' => 'info', 'color_code' => '#ffc107', 'icon_name' => 'window-close'],
        ['value' => '1', 'name' => 'Holiday', 'color' => 'primary', 'color_code' => '#198754', 'icon_name' => 'check'],

    ],
    'approved_status' => [
        ['value' => '1', 'name' => 'Accecpt', 'color' => 'info', 'icon_name' => 'window-close'],
        ['value' => '2', 'name' => 'Approved', 'color' => 'secondary', 'icon_name' => 'check'],
        ['value' => '3', 'name' => 'pending', 'color' => 'primary', 'icon_name' => 'check'],
        ['value' => '4', 'name' => 'Rejected', 'color' => 'danger', 'icon_name' => 'check'],
        ['value' => '5', 'name' => 'Canceled', 'color' => 'warning', 'icon_name' => 'check'],

    ],
    'is_half_day' => [
        ['value' => '0', 'name' => 'No', 'color' => 'info', 'color_code' => '#ffc107', 'icon_name' => 'window-close'],
        ['value' => '1', 'name' => 'Yes', 'color' => 'sucecss', 'color_code' => '#198754', 'icon_name' => 'check'],

    ],
    'is_stock_transferred' => [
        ['value' => '0', 'name' => 'No', 'color' => 'info', 'color_code' => '#ffc107', 'icon_name' => 'window-close'],
        ['value' => '1', 'name' => 'Yes', 'color' => 'sucecss', 'color_code' => '#198754', 'icon_name' => 'check'],

    ],
    'payroll_types' => [
        ['value' => '1', 'name' => 'Add', 'color' => 'primary', 'color_code' => '#198754', 'icon_name' => 'check'],
        ['value' => '2', 'name' => 'Deduct', 'color' => 'info', 'color_code' => '#198604', 'icon_name' => 'window-close'],
    ],
    'account_type' => [
        ['value' => '0', 'name' => 'Savings', 'color' => 'info', 'color_code' => '#198604', 'icon_name' => 'window-close'],
        ['value' => '1', 'name' => 'Current', 'color' => 'primary', 'color_code' => '#198754', 'icon_name' => 'check'],

    ],
    'transaction_type' => [
        ['value' => '1', 'name' => 'Withdraw', 'color' => 'danger', 'color_code' => '#198754', 'icon_name' => 'check'],
        ['value' => '0', 'name' => 'Deposit', 'color' => 'primary', 'color_code' => '#198604', 'icon_name' => 'window-close'],

    ],
    'is_notification_send_to_admin' => [
        ['value' => '0', 'name' => 'No', 'color' => 'danger', 'color_code' => '#198604', 'icon_name' => 'window-close'],
        ['value' => '1', 'name' => 'Yes', 'color' => 'success', 'color_code' => '#198754', 'icon_name' => 'check'],

    ],
    'is_opened' => [
        ['value' => '1', 'name' => 'Opened', 'color' => 'success', 'color_code' => '#198604', 'icon_name' => 'window-close'],
        ['value' => '2', 'name' => 'Closed', 'color' => 'danger', 'color_code' => '#198754', 'icon_name' => 'check'],

    ],
    'cash_register_transaction_type' => [
        ['value' => '1', 'name' => 'Initial', 'color' => 'primary', 'color_code' => '#198604', 'icon_name' => 'window-close'],
        ['value' => '2', 'name' => 'Transfer', 'color' => 'success', 'color_code' => '#198754', 'icon_name' => 'check'],
        ['value' => '3', 'name' => 'Refund', 'color' => 'info', 'color_code' => '#198754', 'icon_name' => 'check'],

    ],
    'payment_type' => [
        ['value' => '1', 'name' => 'Credit', 'color' => 'success', 'color_code' => '#198604', 'icon_name' => 'window-close'],
        ['value' => '2', 'name' => 'Debit', 'color' => 'danger', 'color_code' => '#198754', 'icon_name' => 'check'],

    ],
    'advance_amount_included' => [
        ['value' => '1', 'name' => 'Yes', 'color' => 'success', 'color_code' => '#198604', 'icon_name' => 'window-close'],
        ['value' => '2', 'name' => 'No', 'color' => 'danger', 'color_code' => '#198754', 'icon_name' => 'check'],

    ],

    'sales_from' => [
        ['value' => '1', 'name' => 'Store', 'color' => 'primary', 'color_code' => '#198604', 'icon_name' => 'window-close'],
        ['value' => '2', 'name' => 'Warehouse', 'color' => 'danger', 'color_code' => '#198754', 'icon_name' => 'check'],

    ],

    'sales_type' => [
        ['value' => '1', 'name' => 'Machine Sale', 'color' => 'primary', 'color_code' => '#198604', 'icon_name' => 'window-close'],
        ['value' => '2', 'name' => 'ERP Sale', 'color' => 'danger', 'color_code' => '#198754', 'icon_name' => 'check'],

    ],
    'payment_transactions_type' => [
        ['value' => '1', 'name' => 'Purchase', 'color' => 'warning', 'color_code' => '#ffc107'],
        ['value' => '2', 'name' => 'Sales', 'color' => 'success', 'color_code' => '#198754'],
        ['value' => '3', 'name' => 'Return', 'color' => 'primary', 'color_code' => '#0d6efd'],
        ['value' => '4', 'name' => 'Store', 'color' => 'danger', 'color_code' => '#dc1545'],
        ['value' => '5', 'name' => 'Expense', 'color' => 'info', 'color_code' => '#0dcaf0'],
        ['value' => '6', 'name' => 'Product Transfer', 'color' => 'secondry', 'color_code' => '#A9C648'],
        ['value' => '7', 'name' => 'User Advance', 'color' => 'secondry', 'color_code' => '#C93BD3'],
    ],
    'report_types' => [
        ['id' => 1, 'title' => "Purchase Report", 'route' => "Reports", 'icon' => 'user', 'type' => 'feather', 'func_name' => 'purchase_report'],
        ['id' => 2, 'title' => "Sales Report", 'route' => "Reports", 'icon' => 'user', 'type' => 'feather', 'func_name' => 'sales_report'],
        ['id' => 3, 'title' => "Supplier Invoice Report", 'route' => "Reports", 'icon' => 'user', 'type' => 'feather', 'func_name' => 'purchase_report'],
        ['id' => 4, 'title' => "Return Report", 'route' => "Reports", 'icon' => 'user', 'type' => 'feather', 'func_name' => 'purchase_report'],
        ['id' => 5, 'title' => "Spoilage Report", 'route' => "Reports", 'icon' => 'user', 'type' => 'feather', 'func_name' => 'purchase_report'],
        ['id' => 6, 'title' => "Re-Distribution Report", 'route' => "Reports", 'icon' => 'user', 'type' => 'feather', 'func_name' => 'purchase_report'],
        ['id' => 7, 'title' => "Distribution Report", 'route' => "Reports", 'icon' => 'user', 'type' => 'feather', 'func_name' => 'purchase_report'],
        ['id' => 8, 'title' => "Fish Cutting Details Report", 'route' => "Reports", 'icon' => 'user', 'type' => 'feather', 'func_name' => 'purchase_report'],
        ['id' => 9, 'title' => "Daily Fish Price Report", 'route' => "Reports", 'icon' => 'user', 'type' => 'feather', 'func_name' => 'purchase_report'],
        ['id' => 10, 'title' => "Indent Request Report", 'route' => "Reports", 'icon' => 'user', 'type' => 'feather', 'func_name' => 'purchase_report'],
        ['id' => 11, 'title' => "Statement Report", 'route' => "Reports", 'icon' => 'user', 'type' => 'feather', 'func_name' => 'purchase_report'],
        ['id' => 12, 'title' => "Cash Paid to Office Report", 'route' => "Reports", 'icon' => 'user', 'type' => 'feather', 'func_name' => 'purchase_report'],
        ['id' => 13, 'title' => "Staff Attendance Report", 'route' => "Reports", 'icon' => 'user', 'type' => 'feather', 'func_name' => 'staff_attendance_report'],
        ['id' => 14, 'title' => "Expense Report", 'route' => "Reports", 'icon' => 'user', 'type' => 'feather', 'func_name' => 'expense_report'],
        ['id' => 15, 'title' => "Stock Update Report", 'route' => "Reports", 'icon' => 'user', 'type' => 'feather', 'func_name' => 'store_stock_report'],
        ['id' => 16, 'title' => "Product Wise Sales Report", 'route' => "Reports", 'icon' => 'user', 'type' => 'feather', 'func_name' => 'product_wise_sale_report'],
    ],

    'interest_types' => [
        ['value' => '1', 'name' => 'Simple Interest', 'color' => 'warning', 'color_code' => '#ffc107', 'icon_name' => 'window-close'],
        ['value' => '2', 'name' => 'Compound Interest', 'color' => 'success', 'color_code' => '#198754', 'icon_name' => 'check'],
        ['value' => '3', 'name' => 'Fixed Interest', 'color' => 'primary', 'color_code' => '#0d6efd', 'icon_name' => 'star-half'],
        ['value' => '4', 'name' => 'Annual Percentage Rate', 'color' => 'danger', 'color_code' => '#dc3545', 'icon_name' => 'palm-tree'],
        ['value' => '5', 'name' => 'Effective Interest', 'color' => 'info', 'color_code' => '#dc3545', 'icon_name' => 'palm-tree'],
    ],
    'interest_frequency' => [
        ['value' => '1', 'name' => 'Day', 'color' => 'warning', 'color_code' => '#ffc107', 'icon_name' => 'window-close'],
        ['value' => '2', 'name' => 'Week', 'color' => 'success', 'color_code' => '#198754', 'icon_name' => 'check'],
        ['value' => '3', 'name' => 'Month', 'color' => 'primary', 'color_code' => '#0d6efd', 'icon_name' => 'star-half'],
        ['value' => '4', 'name' => 'Year', 'color' => 'danger', 'color_code' => '#dc3545', 'icon_name' => 'palm-tree'],
    ],
    'repayment_frequency' => [
        ['value' => '1', 'name' => 'Day', 'color' => 'warning', 'color_code' => '#ffc107', 'icon_name' => 'window-close'],
        ['value' => '2', 'name' => 'Week', 'color' => 'success', 'color_code' => '#198754', 'icon_name' => 'check'],
        ['value' => '3', 'name' => 'Month', 'color' => 'primary', 'color_code' => '#0d6efd', 'icon_name' => 'star-half'],
        ['value' => '4', 'name' => 'Year', 'color' => 'danger', 'color_code' => '#dc3545', 'icon_name' => 'palm-tree'],
    ],
    'loan_term_method' => [
        ['value' => '1', 'name' => 'Day', 'color' => 'warning', 'color_code' => '#ffc107', 'icon_name' => 'window-close'],
        ['value' => '2', 'name' => 'Week', 'color' => 'success', 'color_code' => '#198754', 'icon_name' => 'check'],
        ['value' => '3', 'name' => 'Month', 'color' => 'primary', 'color_code' => '#0d6efd', 'icon_name' => 'star-half'],
        ['value' => '4', 'name' => 'Year', 'color' => 'danger', 'color_code' => '#dc3545', 'icon_name' => 'palm-tree'],
    ],
    'charges' => [
        ['value' => '0', 'name' => 'No', 'color' => 'warning', 'color_code' => '#ffc107', 'icon_name' => 'window-close'],
        ['value' => '1', 'name' => 'Conversion Fees-3%', 'color' => 'warning', 'color_code' => '#ffc107', 'icon_name' => 'window-close'],
        ['value' => '2', 'name' => 'Late payment Charges-5%', 'color' => 'success', 'color_code' => '#198754', 'icon_name' => 'check'],
        ['value' => '3', 'name' => 'Pre payment Charges-2%', 'color' => 'primary', 'color_code' => '#0d6efd', 'icon_name' => 'star-half'],
        ['value' => '4', 'name' => 'Commions-1%', 'color' => 'danger', 'color_code' => '#dc3545', 'icon_name' => 'palm-tree'],
        ['value' => '5', 'name' => 'Other fees-2%', 'color' => 'danger', 'color_code' => '#dc3545', 'icon_name' => 'palm-tree'],
    ],
    'loan_status' => [
        ['value' => '1', 'name' => 'Pending', 'color' => 'warning', 'color_code' => '#0dcaf0'],
        ['value' => '2', 'name' => 'Approved', 'color' => 'success', 'color_code' => '#ffc107'],
        ['value' => '3', 'name' => 'Rejected', 'color' => 'danger', 'color_code' => '#198754'],
        ['value' => '4', 'name' => 'closed', 'color' => 'info', 'color_code' => '#138259'],
    ],
    'loan_type' => [
        ['value' => '1', 'name' => 'Income', 'color' => 'primary', 'color_code' => '#198604', 'icon_name' => 'window-close'],
        ['value' => '2', 'name' => 'Expensive', 'color' => 'danger', 'color_code' => '#198754', 'icon_name' => 'check'],

    ],
    'disburse_method' => [
        ['value' => '1', 'name' => 'Cash', 'color' => 'warning', 'color_code' => '#ffc107', 'icon_name' => 'window-close'],
        ['value' => '2', 'name' => 'Gpay', 'color' => 'success', 'color_code' => '#198754', 'icon_name' => 'check'],
        ['value' => '3', 'name' => 'Card', 'color' => 'primary', 'color_code' => '#0d6efd', 'icon_name' => 'star-half'],
        ['value' => '4', 'name' => 'Bank', 'color' => 'danger', 'color_code' => '#dc3545', 'icon_name' => 'palm-tree'],
    ],

    'fish_cutting_type' => [
        ['value' => '1', 'name' => 'Slice', 'slug' => 'slice'],
        ['value' => '2', 'name' => 'Head', 'slug' => 'head'],
        ['value' => '3', 'name' => 'Tail', 'slug' => 'tail'],
        ['value' => '4', 'name' => 'Egg', 'slug' => 'eggs'],
    ],
];
