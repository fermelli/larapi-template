<?php

namespace App\Abstracts;

use one2tek\larapi\Database\Repository as BaseRepository;

abstract class Repository extends BaseRepository
{
    protected $sortProperty = 'id';

    protected $sortDirection = 'DESC';

    /**
     * Get a resource by its primary key.
     *
     * @param  mixed  $id
     * @param  array  $options
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getById($id, array $options = [])
    {
        $query = $this->createBaseBuilder($options);

        $query = $query->find($id);

        $this->appendAttributes($query, $options);

        return $query;
    }

    /**
     * Force delete a resource by its primary key.
     *
     * @param  mixed  $id
     *
     * @return void
     */
    public function forceDelete($id)
    {
        $query = $this->createQueryBuilder();

        $query->where($this->getPrimaryKey($query), $id);
        $query->forceDelete();
    }

    /**
     * Restore a resource by its primary key.
     *
     * @param  mixed  $id
     *
     * @return void
     */
    public function restore($id)
    {
        $query = $this->createQueryBuilder();

        $query->where($this->getPrimaryKey($query), $id);
        $query->restore();
    }
}
