<?php

namespace App\Models;

use App\Database\Eloquent\Builder;
use App\Database\Eloquent\Collections\Collection;
use App\Utils\Arr;
use App\Utils\Lang;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EloquentModel.
 * Base model
 *
 * @package namespace App\Models;
 *
 * @method bool hasJoin(string $table)
 */
abstract class EloquentModel extends Model
{
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    /**
     * Convert the model instance to JSON.
     *
     * @param int $options
     * @param bool $camerize = false
     *
     * @return string
     *
     * @throws \Illuminate\Database\Eloquent\JsonEncodingException
     */
    public function toJson($options = JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, bool $camerize = false)
    {
        $array = $this->jsonSerialize($camerize);

        $json = json_encode($array, $options);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw JsonEncodingException::forModel($this, json_last_error_msg());
        }

        return $json;
    }

    public function toArray(bool $camerize = false)
    {
        $array = parent::toArray();

        if ($camerize) {
            $array = Arr::camerizeKeys($array);
        }

        return $array;
    }

    /**
     * @return array
     */
    public function toFrontArray()
    {
        return $this->toArray(true);
    }

    /**
     * @param bool $camerize
     *
     * @return array
     */
    public function jsonSerialize(bool $camerize = false): mixed
    {
        return $this->toArray($camerize);
    }

    /**
     * toJsonフロントエンドにJSONを渡すとき用のtoJson
     *
     * @param int $options
     * @param bool $camerize
     *
     * @return string
     */
    public function toFrontJson($options = JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, bool $camerize = true)
    {
        return $this->toJson($options, $camerize);
    }

    /**
     * @param array $models
     *
     * @return \App\Database\Eloquent\Collections\Collection<int, static>
     */
    public function newCollection(array $models = [])
    {
        return new Collection($models);
    }

    /**
     * @return string
     */
    public static function getDescription(): string
    {
        return Lang::get('models.' . static::class);
    }
}
