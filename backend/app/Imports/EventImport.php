<?php

namespace App\Imports;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EventImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    use Importable;

    public function chunkSize(): int
    {
        return 1000;
    }

    public function collection(Collection $collection): void
    {
        $creator = auth()->user();
        $events = collect();
        $attendees = collect();

        $users = User::whereIn('email', $collection->pluck('attendee_email'))
            ->get()
            ->keyBy('email');

        $data = $collection->groupBy(function ($item) {
            return $item['title'] . '|' . $item['description'] . '|' . $item['location'] . '|' . $item['start_time'] . '|' . $item['end_time'];
        });

        foreach ($data as $event_data) {
            $event = new Event($event_data[0]->toArray());
            $event->code = Event::generateUniqueCode();
            $event->creator()->associate($creator);
            $events->push($event);

            $attendee = new Attendee();
            $attendee->email = $creator->email;
            $attendee->reminder_sent = false;
            $attendee->reminder_scheduled = false;
            $attendee->user()->associate($creator);
            $attendee->event()->associate($event);
            $attendees->push($attendee);

            foreach ($event_data as $attendee_data) {
                $attendee = new Attendee();
                $attendee->user_id = $users[$attendee_data['attendee_email']]?->id ?? null;
                $attendee->email = $attendee_data['attendee_email'];
                $attendee->reminder_sent = false;
                $attendee->reminder_scheduled = false;
                $attendee->event()->associate($event);
                $attendees->push($attendee);
            }
        }

        Event::insert($events->map(function ($event) {
            $data = $event->getAttributes();
            $data['created_at'] = now();
            $data['updated_at'] = now();

            return $data;
        })->toArray());

        Attendee::insert($attendees->map(function ($attendee) {
            $data = $attendee->getAttributes();
            $data['created_at'] = now();
            $data['updated_at'] = now();

            return $data;
        })->toArray());
    }
}
