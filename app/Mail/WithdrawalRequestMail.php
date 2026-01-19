<?php

namespace App\Mail;

use App\Models\AffiliateWithdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WithdrawalRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $withdrawal;

    /**
     * Create a new message instance.
     */
    public function __construct(AffiliateWithdrawal $withdrawal)
    {
        $this->withdrawal = $withdrawal;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Affiliate Withdrawal Request')
            ->view('emails.withdrawal_request')
            ->with([
                'withdrawal' => $this->withdrawal,
                'user' => $this->withdrawal->user
            ]);
    }
}
