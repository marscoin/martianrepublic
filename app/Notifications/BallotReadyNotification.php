<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BallotReadyNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly int $proposalId,
        private readonly string $proposalTitle,
        private readonly string $ballotTxid
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Ballot is Ready — '.$this->proposalTitle)
            ->view('emails.ballot.ready', [
                'user' => $notifiable,
                'proposalId' => $this->proposalId,
                'proposalTitle' => $this->proposalTitle,
                'ballotTxid' => $this->ballotTxid,
                'voteUrl' => url('/congress/ballot/'.$this->proposalId),
            ]);
    }
}
