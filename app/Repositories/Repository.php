<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface Repository.
 * Base interface.
 *
 * @package namespace App\Repositories;
 */
interface Repository extends RepositoryInterface
{
    /**
     * @return static
     */
    public function factory();

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function createCollection(): \Illuminate\Database\Eloquent\Collection;

    /**
     * Push Criteria for filter the query
     *
     * @param $criteria
     *
     * @return $this
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function pushCriteria($criteria);

    /**
     * Reset all Criterias
     *
     * @return $this
     */
    public function resetCriteria();

    /**
     * @param int|null $limit
     * @param int|null $page
     * @param array|string $columns
     * @param string $pageName
     * @param string $method
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate($limit = null, $page = null, $columns = ['*'], $pageName = 'page', $method = 'paginate');

    /**
     * updates models with where conditions
     *
     * @param array $where
     * @param array $values
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function updateWhere(array $where, array $values);

    /**
     * Returns the current Model instance
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel();

    /**
     * @param int $count
     * @param callable $callback
     *
     * @return void
     */
    public function chunk(int $count, callable $callback);

    /**
     * @return void
     */
    public function truncate();

    /**
     * @return void
     */
    public function deleteAll();

    /**
     * @param array $where
     *
     * @return bool
     */
    public function exists(array $where);

    /**
     * @param array $data
     *
     * @return void
     */
    public function bulkInsert(array $data);

    /**
     * @return int[]
     */
    public function getRange();

    /**
     * Count results of repository
     *
     * @param array $where
     * @param string $columns
     *
     * @return int
     */
    public function count(array $where = [], $columns = '*');

    /**
     * Delete multiple entities by given criteria.
     *
     * @param array $where
     *
     * @return int
     */
    public function deleteWhere(array $where);

    /**
     * @param array $where
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrFail(array $where = []);

    /**
     * @param array $where
     * @param array $params
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function recreate(array $where, array $params): \Illuminate\Database\Eloquent\Collection;

    /**
     * Retrieve first data of repository
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function first($columns = ['*']);
}
