<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'location' => 'required|string',
            'start_time' => [
                'required',
                'date',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (Carbon::parse($value)->isPast()) {
                        $fail('The ' . $attribute . ' must be a date in the future.');
                    }
                },
                function (string $attribute, mixed $value, Closure $fail) {
                    if (Carbon::parse($value)->gt(Carbon::parse($this->end_time))) {
                        $fail('The ' . $attribute . ' must be before or equal to the end time.');
                    }
                },
            ],
            'end_time' => 'required|date|after_or_equal:start_time',
            'attendees' => 'required|array',
            'attendees.*.user_id' => 'nullable|uuid|exists:users,id',
            'attendees.*.email' => 'required|email',
        ];
    }
}
