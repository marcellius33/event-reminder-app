<?php

namespace App\Services;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\User;

class EventService
{
    public function storeEvent(array $input, User $user): void
    {
        $event = new Event($input);
        $event->code = Event::generateUniqueCode();
        $event->creator()->associate($user);
        $event->save();

        $attendees = collect();
        foreach ($input['attendees'] as $attendee_data) {
            $attendee = new Attendee($attendee_data);
            $attendee->user_id = $attendee_data['user_id'] ?? null;
            $attendee->reminder_sent = false;
            $attendee->reminder_scheduled = false;
            $attendee->event()->associate($event);
            $attendees->push($attendee);
        }

        $attendee = new Attendee();
        $attendee->email = $user->email;
        $attendee->user()->associate($user);
        $attendee->event()->associate($event);
        $attendee->reminder_sent = false;
        $attendee->reminder_scheduled = false;
        $attendees->push($attendee);

        Attendee::insert($attendees->map(function ($attendee) {
            $data = $attendee->getAttributes();
            $data['created_at'] = now();
            $data['updated_at'] = now();

            return $data;
        })->toArray());
    }

    public function updateEvent(Event $event, array $input, User $user): void
    {
        $event->fill($input);
        $event->save();

        $attendees = collect($input['attendees']);
        $existingAttendees = Attendee::where('event_id', $event->id)
            ->where('user_id', '!=', $user->id)
            ->whereIn('id', $attendees->pluck('id'))
            ->get()
            ->keyBy('id');

        Attendee::where('event_id', $event->id)
            ->when(!$existingAttendees->empty(), function ($query) use ($attendees) {
                $query->whereNotIn('id', $attendees->pluck('id'));
            })
            ->delete();

        $updated_attendees = collect();
        foreach ($attendees as $attendee) {
            if (array_key_exists('id', $attendee)) {
                $updatedAttendee = $existingAttendees[$attendee['id']];
                $updatedAttendee->fill($attendee);
                $updated_attendees->push($updatedAttendee);
            } else {
                $newAttendee = new Attendee($attendee);
                $newAttendee->user_id = $attendee_data['user_id'] ?? null;
                $newAttendee->reminder_sent = false;
                $newAttendee->reminder_scheduled = false;
                $newAttendee->event()->associate($event);
                $updated_attendees->push($newAttendee);
            }
        }

        Attendee::upsert($updated_attendees->map(function ($attendee) {
            return $attendee->getAttributes();
        })->toArray(), ['id'], ['event_id', 'user_id', 'email', 'reminder_sent', 'reminder_scheduled']);
    }

    public function deleteEvent(Event $event): void
    {
        Attendee::where('event_id', $event->id)->delete();
        $event->delete();
    }
}
