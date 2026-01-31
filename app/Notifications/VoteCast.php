<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Idea;

class VoteCast extends Notification
{
    use Queueable;

    public $voterName;
    public $ideaTitle;

    public function __construct(User $voter, Idea $idea)
    {
        $this->voterName = $voter->name;
        $this->ideaTitle = $idea->title;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // ذخیره اعلان در دیتابیس
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "{$this->voterName} به ایده «{$this->ideaTitle}» رأی داد.",
            'link' => route('ideas.index'),
        ];
    }
}