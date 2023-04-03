<?php

namespace App\Database\Eloquent;

use App\Utils\Pagination\LengthAwarePaginator;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Builder as BaseBuilder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\Paginator;

class Builder extends BaseBuilder
{
    /**
     * Create a new length-aware paginator instance.
     *
     * @param \Illuminate\Support\Collection $items
     * @param int $total
     * @param int $perPage
     * @param int $currentPage
     * @param array $options
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected function paginator($items, $total, $perPage, $currentPage, $options)
    {
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
            'items',
            'total',
            'perPage',
            'currentPage',
            'options'
        ));
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->getModel()->getTable();
    }

    /**
     * @param string $table
     * @param string|null $type
     *
     * @return bool
     */
    public function hasJoin(string $table, ?string $type = null): bool
    {
        /** @var array<int, JoinClause> */
        $joins = $this->getQuery()->joins ?? [];

        foreach ($joins as $join) {
            if ($join->table === $table) {
                if (is_null($type) || $join->type === $type) {
                    return true;
                }
            }
        }

        return false;
    }
}
