<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class QueryExceptionUtil
{
    public const CODE_DUPLICATE_ENTRY_ERROR = 'SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry';
    public const CODE_FOREIGN_KEY_CONSTRAINT_FAILS = 'SQLSTATE[23000]: Integrity constraint violation: 1451 Cannot delete or update a parent row: a foreign key constraint fails';

    /**
     * @param \Throwable $exception
     *
     * @return bool
     */
    public static function isDuplicateEntryError(\Throwable $exception): bool
    {
        if (!static::isQueryException($exception)) {
            return false;
        }

        return strpos($exception->getMessage(), static::CODE_DUPLICATE_ENTRY_ERROR) === 0;
    }

    /**
     * @param \Throwable $exception
     *
     * @return bool
     */
    public static function isForeignKeyConstraintFailsError(\Throwable $exception): bool
    {
        if (!static::isQueryException($exception)) {
            return false;
        }

        return strpos($exception->getMessage(), static::CODE_FOREIGN_KEY_CONSTRAINT_FAILS) === 0;
    }

    /**
     * @param \Throwable $exception
     *
     * @return bool
     */
    public static function isQueryException(\Throwable $exception): bool
    {
        return $exception instanceof \Illuminate\Database\QueryException;
    }

    /**
     * @param \Throwable $exception
     *
     * @return void
     */
    public static function translateHttpException(\Throwable $exception)
    {
        if (static::isForeignKeyConstraintFailsError($exception)) {
            throw new HttpClientException(Response::HTTP_UNPROCESSABLE_ENTITY, __('error.foreign_key_constraint_fails'), $exception);
        }

        throw $exception;
    }
}
