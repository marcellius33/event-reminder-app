<?php

namespace App\QueryBuilders\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\FiltersExact;

class FiltersEvent extends FiltersExact
{

    public function __invoke(Builder $query, $value, string $property)
    {
        if ($this->addRelationConstraint) {
            if ($this->isRelationProperty($query, $property)) {
                $this->withRelationConstraint($query, $value, $property);

                return;
            }
        }

        if ($value === 'Upcoming') {
            $query->where($property, '>=', Carbon::now());
        }
        if ($value === 'Completed') {
            $query->where($property, '<', Carbon::now());
        }
    }
}
