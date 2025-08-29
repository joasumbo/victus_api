<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url("http://localhost:5173/reset-password?token={$this->token}&email={$notifiable->email}");

        return (new MailMessage)
            ->subject('Redefinição de Palavra-Passe')
            ->greeting('Olá!')
            ->line('Recebemos uma solicitação para redefinir a sua palavra-passe.')
            ->action('Redefinir Palavra-Passe', $url)
            ->line('Se você não solicitou a redefinição, nenhuma ação adicional é necessária.')
            ->salutation('Atenciosamente, Equipa do Suporte');
    }
}