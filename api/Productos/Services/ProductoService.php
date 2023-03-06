<?php

namespace Api\Productos\Services;

use Api\Productos\Exceptions\ProductoNoEncontradoException;
use Api\Productos\Repositories\ProductoRepository;

class ProductoService
{
    private ProductoRepository $productoRepository;

    public function __construct(ProductoRepository $productoRepository)
    {
        $this->productoRepository = $productoRepository;
    }

    public function obtenerTodos($options = [])
    {
        return $this->productoRepository->getWithCount($options);
    }

    public function obtenerPorId($productoId, array $options = [])
    {
        $producto = $this->obtenerProductoSolicitado($productoId, $options);

        return $producto;
    }

    public function crear($data)
    {
        $producto = $this->productoRepository->create($data);

        return $producto;
    }

    public function actualizar($productoId, array $data)
    {
        $producto = $this->obtenerProductoSolicitado($productoId);

        $producto = $this->productoRepository->update($producto, $data);

        return $producto;
    }

    public function eliminar($productoId)
    {
        $producto = $this->obtenerProductoSolicitado($productoId, ['select' => ['id']]);

        $this->productoRepository->delete($productoId);

        return $producto;
    }

    private function obtenerProductoSolicitado($productoId, array $options = [])
    {
        $producto = $this->productoRepository->getById($productoId, $options);

        if (is_null($producto)) {
            throw new ProductoNoEncontradoException;
        }

        return $producto;
    }
}
