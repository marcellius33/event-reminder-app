<?php

namespace App\Console\Commands;

use App\Jobs\SendEventReminderJob;
use App\Models\Attendee;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendEventReminder extends Command
{

    protected $signature = 'send:event-reminder';

    protected $description = 'To send event reminders';

    public function handle(): void
    {
        // Reminder will be sent 1 hour before event start
        $upcomingEvents = Event::with('attendees')
            ->whereBetween('start_time', [Carbon::now(), Carbon::now()->addHours(3)])
            ->get();

        foreach ($upcomingEvents as $upcomingEvent) {
            $attendees = $upcomingEvent->attendees;
            $scheduleReminders = $attendees->where('reminder_scheduled', false);
            $schedule_time = Carbon::make($upcomingEvent->start_time)->subHour();

            foreach ($scheduleReminders as $scheduleReminder) {
                $scheduleReminder->reminder_scheduled = true;

                SendEventReminderJob::dispatch($upcomingEvent, $scheduleReminder)
                    ->delay($schedule_time);
            }

            Attendee::upsert($scheduleReminders->map(function ($attendee) {
                return $attendee->getAttributes();
            })->toArray(), ['id'], ['reminder_scheduled']);
        }
    }
}
