<?php

namespace App\Database\Eloquent\Collections;

use App\Utils\Arr;
use Illuminate\Database\Eloquent\Collection as BaseCollection;
use Illuminate\Database\Eloquent\JsonEncodingException;

class Collection extends BaseCollection
{
    /**
     * Get the collection of items as JSON.
     *
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS)
    {
        return parent::toJson($options);
    }

    /**
     * @return array
     */
    public function toArray(bool $camerize = false)
    {
        $array = parent::toArray();

        if ($camerize) {
            $array = array_map(fn ($value) => is_array($value) ? Arr::camerizeKeys($value) : $value, $array);
        }

        return $array;
    }

    /**
     * @return array
     */
    public function toFrontArray()
    {
        return $this->toArray(camerize: true);
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
        $array = $this->toArray($camerize);

        $json = json_encode($array, $options);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw JsonEncodingException::forModel($this, json_last_error_msg());
        }

        return $json;
    }
}
