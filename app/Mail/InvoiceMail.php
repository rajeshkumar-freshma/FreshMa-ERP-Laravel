<?php

namespace App\Mail;

use App\Models\MailSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\SettingGeneral;
use App\Models\SettingBooking;
use App\Models\SystemSiteSetting;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;
    public $email;
    public $orderNo;
    public $subject;
    public $content;
    public $table;
    public $body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $subject, $content, $orderNo, $table, $body)
    {
        $this->email = $email;
        $this->orderNo = $orderNo;
        $this->subject = $subject;
        $this->content = $content;
        $this->table = $table;
        $this->body = $body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        Log::info("Mail coming sales orders");
        $orderNo = $this->orderNo;
        $to_email = $this->email;
        $subject = $this->subject;
        $content = $this->content;
        $table = $this->table;
        $invoice_url = session()->get('tiny_url');
        $SettingGeneral = SystemSiteSetting::first();
        $title = $SettingGeneral->title ?? 'FreshMa';
        $body = $this->body;
        $SettingBooking = MailSetting::first();
        $from_email = $SettingBooking->email ?? 'support@freshma.in';
        Log::info("from_email");
        Log::info($from_email);
        Log::info($to_email);
        Log::info($SettingGeneral);
        if (isset($SettingBooking->status) && $SettingBooking->status == 1) {
            Log::info("status 1 setting email");
            return $this->from($from_email)
                ->to($to_email)
                ->subject('Invoice for Purchase Order')
                // ->cc('support@freshma.in')
                ->markdown('emails_setup.Invoice', compact('orderNo', 'subject', 'content', 'table', 'invoice_url', 'title', 'body'));
        } else {
            return $this->from($from_email)
                ->to($to_email)
                ->subject('Invoice for Purchase Order')
                // ->cc('support@freshma.in')
                ->markdown('emails_setup.Invoice', compact('orderNo', 'subject', 'content', 'table', 'invoice_url', 'title', 'body'));
        }
    }
}
