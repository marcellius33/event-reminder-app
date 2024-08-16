<?php

use App\Console\Commands\SendEventReminder;
use Illuminate\Support\Facades\Schedule;

Schedule::command(SendEventReminder::class)->everyFifteenMinutes();
