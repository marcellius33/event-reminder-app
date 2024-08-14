<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EventCollection extends ResourceCollection
{

    public function toArray(Request $request): array
    {
        $data = [];
        foreach ($this->collection as $event) {
            $data[] = [
                'id' => $event->id,
                'code' => $event->code,
                'title' => $event->title,
                'description' => $event->description,
                'location' => $event->location,
                'start_time' => $event->start_time,
                'end_time' => $event->end_time,
                'created_by' => new UserResource($event->creator),
            ];
        }

        return $data;
    }
}
