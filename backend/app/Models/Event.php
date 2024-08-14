<?php

namespace App\Models;

use App\Models\Abstract\BaseUuidModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends BaseUuidModel
{

    protected $fillable = [
        'code',
        'title',
        'description',
        'location',
        'start_time',
        'end_time',
        'created_by',
    ];

    protected static function generateUniqueCode(): string
    {
        do {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $code = 'TZU-' . substr(str_shuffle($characters), 0, 6);
        } while (static::where('code', $code)->exists());

        return $code;
    }

    public function getRules(): array
    {
        return [
            'code' => 'required|string|unique:events,code,' . $this->id,
            'title' => 'required|string',
            'description' => 'required|string',
            'location' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'created_by' => 'required|uuid|exists:users,id',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(Attendee::class);
    }
}
