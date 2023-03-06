<?php

namespace Api\Productos\Repositories;

use Api\Productos\Models\Producto;
use App\Abstracts\Repository;

class ProductoRepository extends Repository
{
    public function getModel()
    {
        return new Producto();
    }

    public function create(array $data)
    {
        $producto = $this->getModel();

        $producto->fill($data);

        $producto->save();

        return  $producto;
    }

    public function update(Producto  $producto, array $data)
    {
        $producto->fill($data);

        $producto->save();

        return  $producto;
    }
}