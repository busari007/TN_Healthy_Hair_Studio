<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification
{
    use Queueable;

    protected $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function via($notifiable)
    {
        return ['database']; // This saves it to the notifications table
    }

    public function toArray($notifiable)
    {
        return [
            'title'   => $this->details['title'] ?? 'New Update',
            'message' => $this->details['message'] ?? '',
            'url'     => $this->details['url'] ?? '#',
            'by'      => $this->details['by'] ?? 'System',
            'amount'  => $this->details['amount'] ?? '',
        ];
    }
}
