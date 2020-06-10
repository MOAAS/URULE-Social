<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

trait HasCompositePrimaryKey
{
    public function getIncrementing()
    {
        return false;
    }

    protected function setKeysForSaveQuery(Builder $query)
    {
        foreach ($this->getKeyName() as $key) {
            if (isset($this->$key))
                $query->where($key, '=', $this->$key);
            else
                throw new Exception(__METHOD__ . 'Missing part of the primary key: ' . $key);
        }

        return $query;
    }

    public static function find($id, $columns = ['*']) {
        $me = new self;
        $query = $me->newQuery();
        $i=0;

        foreach ($me->getKeyName() as $key) {
            $query->where($key, '=', $id[$i]);
            $i++;
        }

        return $query->first($columns);
    }

    public static function findOrFail($id, $columns = array('*'))
    {
        if (!is_null($model = static::find($id, $columns))) return $model;

        throw (new ModelNotFoundException)->setModel(get_called_class());
    }
}