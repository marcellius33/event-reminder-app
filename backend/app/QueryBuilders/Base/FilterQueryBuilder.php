<?php

namespace App\QueryBuilders\Base;

use App\Http\Helpers\QueryBuilderHelpers\FilterComposer;
use App\Http\Helpers\QueryBuilderHelpers\SortComposer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

abstract class FilterQueryBuilder
{

    public function __construct(protected Model|string $model) {}

    public function getQueryBuilder(...$args): QueryBuilder
    {
        $queryBuilder =  QueryBuilder::for($this->getBuilder(...$args))
            ->allowedFilters($this->getAllowedFilters());

        if (empty($queryBuilder->getQuery()->columns)) {
            $table = $this->getTable($this->model);
            $queryBuilder->select("$table.*");
        }

        return $queryBuilder;
    }

    public function getResource(Request $request): array
    {
        return [
            'filters'   => FilterComposer::composeFilters($this->getAllowedFilters(), $request->input('filter', [])),
        ];
    }

    protected function getTable(Model|string $model): string
    {
        if (is_string($model)) {
            return $model;
        }

        return $model->getTable();
    }

    abstract public function getBuilder(...$args): Builder;

    abstract public function getAllowedFilters(): array;
}
