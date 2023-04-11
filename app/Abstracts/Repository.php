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

    /**
     * Get all resources with pagination.
     *
     * @param  array  $options
     *
     * @return array
     */
    public function getWithPagination(array $options = [])
    {
        $query = $this->createBaseBuilder($options);

        $totalData = $this->countRows($query);
        $allRows = $query->get();

        $this->appendAttributes($allRows, $options);

        $data = ['rows' => $allRows, 'total_data' => $totalData];

        if (isset($options['page']) && $options['page'] && isset($options['limit']) && $options['limit']) {
            $page = intval($options['page']);
            $limit = intval($options['limit']);

            $data['page'] = $page;
            $data['limit'] = $limit;
            $data['from'] = ($page - 1) * $limit + 1;
            $data['to'] = $totalData % $limit == 0 ? $page * $limit : ($page - 1) * $limit + $totalData % $limit;
            $data['last_page'] = ceil($totalData / $limit);
        }

        return $data;
    }
}
