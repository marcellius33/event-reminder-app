<?php

namespace App\Models;

use App\Models\Abstract\BaseUuidModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendee extends BaseUuidModel
{
    protected $fillable = [
        'event_id',
        'user_id',
        'email',
        'reminder_sent',
    ];

    public function getRules(): array
    {
        return [
            'event_id' => 'required|uuid|exists:events,id',
            'user_id' => 'nullable|uuid|exists:users,id',
            'email' => 'required|email',
            'reminder_sent' => 'required|boolean',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
