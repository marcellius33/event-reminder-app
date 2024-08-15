<?php

namespace App\Http\Controllers;

use App\Http\Helpers\RequestHelper;
use App\Http\Requests\ImportEventRequest;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventCollection;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\QueryBuilders\EventQueryBuilder;
use App\Services\EventService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group [User] Event
 */
class EventController extends Controller
{

    public function __construct(
        private EventQueryBuilder $eventQueryBuilder,
        private EventService $eventService,
    ) {}

    /**
     * List
     * 
     * @queryParam filter[type] string Example: Upcoming
     * @queryParam sort string Example: created_at
     */
    public function index(Request $request): EventCollection
    {
        $data = $this->eventQueryBuilder->getQueryBuilder();

        return (new EventCollection($data->paginate(RequestHelper::limit($request))))
            ->additional($this->eventQueryBuilder->getResource($request));
    }

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
            $this->eventService->updateEvent($event, $input, auth()->user());
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

    /**
     * Import
     * 
     * @bodyParam file file required
     */
    public function import(ImportEventRequest $request): JsonResponse
    {
        $input = $request->validated();

        DB::beginTransaction();
        try {
            $this->eventService->importData($input);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        return response()->json([
            'message' => __('success.import_data_success'),
        ]);
    }
}
