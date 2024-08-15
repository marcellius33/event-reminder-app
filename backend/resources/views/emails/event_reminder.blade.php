<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Reminder</title>
</head>

<body>
    <h1>Hi {{ $attendee?->user?->name ?? $attendee->email }},</h1>
    <p>This is a reminder for the upcoming event: <strong>{{ $event->title }}</strong>.</p>
    <p>This event will be held at: <strong>{{ $event->location }}</strong>.</p>
    <p>The event is scheduled at <strong>{{ $event->start_time }}</strong> till <strong>{{ $event->end_time }}</strong>.</p>
    <p>We hope to see you there!</p>
</body>

</html>