<?php

namespace App\Models;

use App\Models\Abstract\BaseUuidModel;

class Reminder extends BaseUuidModel
{
    protected $fillable = [
        'attendee_id',
        'reminder_time',
        'sent_at',
    ];

    public function getRules(): array
    {
        return [
            'attendee_id' => 'required|uuid|exists:attendees,id',
            'reminder_time' => 'required|datetime',
            'sent_at' => 'nullable|datetime',
        ];
    }
}
