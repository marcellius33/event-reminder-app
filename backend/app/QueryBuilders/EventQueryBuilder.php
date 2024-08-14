<?php

namespace App\QueryBuilders;

use App\Models\Event;
use App\QueryBuilders\Base\BaseQueryBuilder;
use App\QueryBuilders\Filters\FiltersEvent;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

class EventQueryBuilder extends BaseQueryBuilder
{

    public function __construct()
    {
        parent::__construct(new Event);
    }

    public function getBuilder(...$args): Builder
    {
        $user = auth()->user();
        return Event::with('creator')
            ->whereHas('attendees', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
    }

    public function getAllowedFilters(): array
    {
        return [
            AllowedFilter::custom('type', new FiltersEvent(), 'start_time'),
        ];
    }

    public function getAllowedSorts(): array
    {
        return [
            AllowedSort::field('created_at'),
        ];
    }

    public function getDefaultSort(): AllowedSort
    {
        return AllowedSort::field('created_at');
    }
}
