<?php

namespace App\Models;

use App\Models\Abstract\BaseUuidModel;

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

    public function getRules(): array
    {
        return [
            'code' => 'required|string|unique:events,code,' . $this->id,
            'title' => 'required|string',
            'description' => 'required|string',
            'location' => 'required|string',
            'start_time' => 'required|datetime',
            'end_time' => 'required|datetime',
            'created_by' => 'required|uuid|exists:users,id',
        ];
    }
}
