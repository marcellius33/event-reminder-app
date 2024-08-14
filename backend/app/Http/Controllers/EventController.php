<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Services\EventService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * @group [User] Event
 */
class EventController extends Controller
{

    public function __construct(
        private EventService $eventService,
    ) {}

    /**
     * List
     */
    public function index() {}

    /**
     * Detail
     * 
     * @urlParam id string required Example: xxxxxxxx-xxxx-xxxx-xxxxxxxxxxxx
     */
    public function show(Event $event): EventResource
    {
        return new EventResource($event->load('attendees.user'));
    }

    /**
     * Create
     * 
     * @bodyParam title string required Example: Meet Up
     * @bodyParam description string required Example: Jake Wedding Party
     * @bodyParam location string required Example: Pavillion, Kuala Lumpur
     * @bodyParam start_time string required Example: 2024-08-14 01:00:00
     * @bodyParam end_time string required Example: 2024-08-14 06:00:00
     * @bodyParam attendees object[] required
     * @bodyParam attendees[].user_id string Example: xxxxxxxx-xxxx-xxxx-xxxxxxxxxxxx
     * @bodyParam attendees[].email string Example: jake@gmail.com
     * @throws Exception
     */
    public function store(StoreEventRequest $request): JsonResponse
    {
        $input = $request->validated();

        DB::beginTransaction();
        try {
            $this->eventService->storeEvent($input, auth()->user());
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        return response()->json([
            'message' => __('success.store_event_success'),
        ]);
    }

    /**
     * Update
     * 
     * @urlParam id string required Example: xxxxxxxx-xxxx-xxxx-xxxxxxxxxxxx
     * @bodyParam title string required Example: Meet Up
     * @bodyParam description string required Example: Jake Wedding Party
     * @bodyParam location string required Example: Pavillion, Kuala Lumpur
     * @bodyParam start_time string required Example: 2024-08-14 01:00:00
     * @bodyParam end_time string required Example: 2024-08-14 06:00:00
     * @bodyParam attendees object[] required
     * @bodyParam attendees[].id string Example: xxxxxxxx-xxxx-xxxx-xxxxxxxxxxxx
     * @bodyParam attendees[].user_id string Example: xxxxxxxx-xxxx-xxxx-xxxxxxxxxxxx
     * @bodyParam attendees[].email string Example: jake@gmail.com
     * @throws Exception
     */
    public function update(Event $event, UpdateEventRequest $request): JsonResponse
    {
        $input = $request->validated();

        DB::beginTransaction();
        try {
            $this->eventService->updateEvent($event, $input);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        return response()->json([
            'message' => __('success.update_event_success'),
        ]);
    }

    /**
     * Delete
     * 
     * @urlParam id string required Example: xxxxxxxx-xxxx-xxxx-xxxxxxxxxxxx
     * @throws Exception
     */
    public function destroy(Event $event): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->eventService->deleteEvent($event);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        return response()->json([
            'message' => __('success.delete_event_success'),
        ]);
    }
}
