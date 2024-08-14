<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AttendeeCollection extends ResourceCollection
{

    public function toArray(Request $request): array
    {
        $data = [];
        foreach ($this->collection as $attendee) {
            $data[] = [
                'user' => new UserResource($attendee->user),
                'email' => $attendee->email,
            ];
        }

        return $data;
    }
}
