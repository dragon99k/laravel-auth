<?php

namespace App\Repositories;

use App\Exceptions\InvalidMethodCallException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class RepositoryEloquent.
 *
 * Base RepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
abstract class RepositoryEloquent extends BaseRepository implements Repository
{
    /**
     * @return static
     */
    public function factory()
    {
        return resolve(static::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function createCollection(array $models = []): \Illuminate\Database\Eloquent\Collection
    {
        return $this->createModel()->newCollection($models);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function createModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $model;
    }

    /**
     * @return string
     */
    protected function getTableName(): string
    {
        $model = $this->model();

        return (new $model)->getTable();
    }

    /**
     * Push Criteria for filter the query
     *
     * @param $criteria
     *
     * @return $this
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function pushCriteria($criteria)
    {
        if (is_string($criteria)) {
            $criteria = resolve($criteria);
        }
        if (!$criteria instanceof CriteriaInterface) {
            throw new RepositoryException('Class ' . get_class($criteria) . ' must be an instance of Prettus\\Repository\\Contracts\\CriteriaInterface');
        }
        $this->criteria->push($criteria);

        return $this;
    }

    /**
     * @param int|null $limit
     * @param int|null $page
     * @param array|string $columns
     * @param string $pageName
     * @param string $method
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate($limit = null, $page = null, $columns = ['*'], $pageName = 'page', $method = 'paginate')
    {
        $this->applyCriteria();
        $this->applyScope();
        $limit = is_null($limit) ? config('repository.pagination.limit', 15) : $limit;
        $results = $this->model->{$method}($limit, $columns, $pageName, $page);
        $results->appends(app('request')->query());
        $this->resetModel();

        return $this->parserResult($results);
    }

    /**
     * @param int|null $limit
     * @param int|null $page
     * @param array|string $columns
     * @param string $pageName
     * @param string $method
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function cursorPaginate($limit = null, $cursor = null, $columns = ['*'], $cursorName = 'cursor', $method = 'cursorPaginate')
    {
        $this->applyCriteria();
        $this->applyScope();
        $limit = is_null($limit) ? config('repository.pagination.limit', 15) : $limit;
        $results = $this->model->{$method}($limit, $columns, $cursorName, $cursor);
        $results->appends(app('request')->query());
        $this->resetModel();

        return $this->parserResult($results);
    }

    /**
     * updates models with where conditions
     *
     * @param array $where
     * @param array $values
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function updateWhere(array $where, array $values)
    {
        $this->applyCriteria();
        $this->applyScope();

        $this->model->where($where)->update($values);

        $this->resetModel();

        $this->applyCriteria();
        $this->applyScope();

        $model = $this->model;

        $models = ($model instanceof \Illuminate\Database\Eloquent\Builder
            ? $model->useWritePdo()
            : $model->onWriteConnection())->where($where)->get();

        $this->resetModel();
        $this->resetScope();

        return $models;
    }

    /**
     * @param int $count
     * @param callable $callback
     *
     * @return void
     */
    public function chunk(int $count, callable $callback)
    {
        $this->applyCriteria();
        $this->applyScope();

        $this->model->chunk($count, $callback);

        $this->resetModel();
        $this->resetScope();
    }

    /**
     * @return void
     */
    public function truncate()
    {
        $this->createModel()->truncate();
    }

    /**
     * @return void
     */
    public function deleteAll()
    {
        DB::table($this->getTableName())->delete();
    }

    /**
     * @param array $where
     *
     * @return bool
     */
    public function exists(array $where)
    {
        $this->applyConditions($where);

        $model = $this->model->first();

        $this->resetModel();

        return !empty($model);
    }

    /**
     * @param array $data
     *
     * @return void
     */
    public function bulkInsert(array $data)
    {
        $table = $this->getTableName();

        DB::table($table)->insert($data);
    }

    /**
     * @return int[]
     */
    public function getRange()
    {
        $table = $this->getTableName();
        $this->applyCriteria();
        $this->applyScope();

        $model = $this->model;

        $range = $model->select([
            DB::raw("MIN({$table}.id) as from_id"),
            DB::raw("MAX({$table}.id) as to_id"),
        ])
        ->get()->first();

        $from = $range->from_id ?? 0;
        $to = $range->to_id ?? 0;

        $this->resetModel();
        $this->resetScope();

        return [$from, $to];
    }

    /**
     * @param array $where
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrFail(array $where = [])
    {
        $this->applyCriteria();
        $this->applyScope();

        $model = $this->model->where($where)->firstOrFail();

        $this->resetModel();

        return $model;
    }

    /**
     * @param array $where
     * @param array $params
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function recreate(array $where, array $params): \Illuminate\Database\Eloquent\Collection
    {
        $this->deleteWhere($where);

        $collections = $this->createCollection();

        foreach ($params as $attributes) {
            $model = $this->create($attributes);

            $collections->add($model);
        }

        return $collections;
    }

    /**
     * Trigger static method calls to the model
     *
     * @param $method
     * @param $arguments
     *
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        throw new InvalidMethodCallException();
    }

    /**
     * Trigger method calls to the model
     *
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        throw new InvalidMethodCallException();
    }
}
