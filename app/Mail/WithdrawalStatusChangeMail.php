<?php

namespace App\Mail;

use App\Models\AffiliateWithdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WithdrawalStatusChangeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $withdrawal;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(AffiliateWithdrawal $withdrawal, string $oldStatus, string $newStatus)
    {
        $this->withdrawal = $withdrawal;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = 'Withdrawal Request ' . ucfirst($this->newStatus);
        
        return $this->subject($subject)
            ->view('emails.withdrawal_status_change')
            ->with([
                'withdrawal' => $this->withdrawal,
                'user' => $this->withdrawal->user,
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus
            ]);
    }
}
