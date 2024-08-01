<?php

namespace App\Notifications;

use App\Mail\OrderShipped;
use App\Models\Account;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class AccountFree extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable)
    {
        return (new WebPushMessage)
            ->title("Аккаунт {$this->account->login} освободился!")
            ->icon('favicon.ico')
            ->body('Время аренды аккаунта кончилось')
            ->action('Перейти к аккаунту', "/accounts/".$this->account->id."/edit");
    }
    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        //Mail::to(User::query()->first())->send(new OrderShipped());
        /*return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');*/
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
