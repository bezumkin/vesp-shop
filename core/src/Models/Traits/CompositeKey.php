<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use RuntimeException;

/**
 * @method array getKeyName
 * @method Builder newQuery
 */
trait CompositeKey
{
    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * @param $key
     * @return mixed|void
     */
    public function getAttribute($key)
    {
        if (is_array($key)) {
            return;
        }

        return parent::getAttribute($key);
    }

    public function getKey(): array
    {
        $key = [];
        foreach ($this->getKeyName() as $item) {
            $key[$item] = $this->getAttribute($item);
        }

        return $key;
    }

    protected function setKeysForSaveQuery($query): Builder
    {
        foreach ($this->getKeyName() as $key) {
            if (isset($this->$key)) {
                $query->where($key, '=', $this->$key);
            } else {
                throw new RuntimeException(__METHOD__ . 'Missing part of the primary key: ' . $key);
            }
        }

        return $query;
    }

    /**
     * Execute a query for a single record by ID.
     *
     * @param array $ids Array of keys, like [column => value].
     * @param array $columns
     *
     * @return mixed|static
     */
    public static function find($ids, $columns = ['*'])
    {
        $me = new self();
        $query = $me->newQuery();
        foreach ($me->getKeyName() as $key) {
            $query->where($key, '=', $ids[$key]);
        }

        return $query->first($columns);
    }
}
