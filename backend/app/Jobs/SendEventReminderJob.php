<?php

namespace App\Jobs;

use App\Mail\EventReminderMail;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEventReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private Event $event,
        private Attendee $attendee,
    ) {}

    public function handle(): void
    {
        $this->attendee->reminder_sent = true;
        $this->attendee->save();

        Mail::to($this->attendee->email)
            ->send(new EventReminderMail($this->event, $this->attendee));
    }
}
