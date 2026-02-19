<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate as EmailTemplates;
use Illuminate\Support\Facades\DB;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('email_templates')->delete();

        DB::beginTransaction();

        $datas = [
            [
                'subject' => 'New Sales Order',
                'code' => 'NO-MA',
                'Body' => 'Thanks for booking.Your Order number: {{order_no}} For more details click the link {{tiny_url}}',
                'status' => 1
            ],
            [
                'subject' => 'Order Confirmed',
                'code' => 'SO-CONFM',
                'Body' => 'Dear {{ customer_name }} your booking {{order_no}} is confirmed. For more details click the link {{tiny_url}}',
                'status' => 1
            ],
            [
                'subject' => 'Order Assigned',
                'code' => 'SO-ASS',
                'Body' => 'Dear {{customer_name}}, Your Driver Details . Name: {{driver_name}} Contact Number: {{support_number}}. For more details visit {{tiny_url}}',
                'status' => 1
            ],
            [
                'subject' => 'Order Cancelled',
                'code' => 'SO-CNCL',
                'Body' => 'Dear {{customer_name}}, Your Order {{order_no}} is Cancelled Contact Number: {{support_number}}. For more details visit {{tiny_url}}',
                'status' => 1
            ],
            [
                'subject' => 'Order Completed',
                'code' => 'SO-CMTD',
                'Body' => 'Dear {{customer_name}}, Your Order {{order_no}} is Completed. Contact Number: {{support_number}}. For more details visit {{tiny_url}}',
                'status' => 1
            ],
            [
                'subject' => 'Order Re-Assign',
                'code' => 'SO-REASS',
                'Body' => 'Dear {{customer_name}}, Your Order is Re-assigned Driver Details . Name: {{driver_name}} Contact Number: {{support_number}}. For more details visit {{tiny_url}}',
                'status' => 1
            ],
            [
                'subject' => 'Order Updated',
                'code' => 'SO-UPD',
                'Body' => 'Dear Customer {{customer_name}} you booking {{order_no}} is updated. For more details click the link {{tiny_url}}',
                'status' => 1
            ],
            [
                'subject' => 'Purchase Order',
                'code' => 'PO-CON',
                'Body' => 'Dear Customer {{customer_name}} you booking {{order_no}} is updated. For more details click the link {{tiny_url}}',
                'status' => 1
            ],
        ];
        foreach ($datas as $value) {
            $permissionGroup = new EmailTemplates(); // Create a new PermfissionGroup object
            $permissionGroup->subject = $value['subject']; // Set the 'subject' attribute
            $permissionGroup->code = $value['code']; // Set the 'code' attribute
            $permissionGroup->body = $value['Body']; // Set the 'Body' attribute
            $permissionGroup->status = $value['status']; // Set the 'Body' attribute
            $permissionGroup->save(); // Save the PermissionGroup object to the database
        }
        DB::commit();
    }
}
