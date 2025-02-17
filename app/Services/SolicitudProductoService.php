<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Services\HistorialAccionService;
use App\Services\EnviarCorreoService;
use App\Models\SolicitudProducto;
use App\Models\SolicitudProductoImagen;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class SolicitudProductoService
{
    private $modulo = "SOLICITUD DE PRODUCTO";

    public function __construct(private HistorialAccionService $historialAccionService, private CargarArchivoService $cargarArchivoService, private EnviarCorreoService $enviarCorreoService) {}

    /**
     * Lista de todos los solicitudProductos
     *
     * @return Collection
     */
    public function listado(): Collection
    {
        $solicitudProductos = SolicitudProducto::select("solicitudProductos.*")->where("status", 1)->get();
        return $solicitudProductos;
    }

    /**
     * Lista de solicitudProductos paginado con filtros
     *
     * @param integer $length
     * @param integer $page
     * @param string $search
     * @param array $columnsSerachLike
     * @param array $columnsFilter
     * @return LengthAwarePaginator
     */
    public function listadoPaginado(int $length, int $page, string $search, array $columnsSerachLike = [], array $columnsFilter = [], array $columnsBetweenFilter = [], array $orderBy = []): LengthAwarePaginator
    {
        $solicitudProductos = SolicitudProducto::with(["imagens", "categoria"])
            ->select("solicitudProductos.*")
            ->leftJoin("detalle_ventas", "solicitudProductos.id", "=", "detalle_ventas.solicitudProducto_id")
            ->selectRaw("SUM(detalle_ventas.cantidad) as total_vendido")
            ->groupBy("solicitudProductos.id");

        if (!empty($columnsFilter)) {
            foreach ($columnsFilter as $key => $value) {
                if ($value) {
                    $solicitudProductos->where($key, $value);
                }
            }
        }

        if (!empty($columnsBetweenFilter)) {
            foreach ($columnsBetweenFilter as $key => $value) {
                if ($value[0] && $value[1]) {
                    $solicitudProductos->whereBetween($key, $value);
                }
            }
        }

        if ($search && trim($search) != '') {
            if (!empty($columnsSerachLike)) {
                foreach ($columnsSerachLike as $col) {
                    $solicitudProductos->orWhere($col, "LIKE", "%$search%");
                }
            }
        }


        $solicitudProductos->where("status", 1);

        if (!empty($orderBy)) {
            foreach ($orderBy as $value) {
                $solicitudProductos->orderBy($value[0], $value[1]);
            }
        }


        $solicitudProductos = $solicitudProductos->paginate($length, ['*'], 'page', $page);
        return $solicitudProductos;
    }

    /**
     * Lista de solicitudProductos paginado con filtros
     *
     * @param integer $length
     * @param integer $page
     * @param string $search
     * @param array $columnsSerachLike
     * @param array $columnsFilter
     * @return LengthAwarePaginator
     */
    public function listadoPaginadoCliente(int $cliente_id, int $length, int $page, string $search, array $columnsSerachLike = [], array $columnsFilter = [], array $columnsBetweenFilter = [], array $orderBy = []): LengthAwarePaginator
    {
        $solicitudProductos = SolicitudProducto::with(["detalleVenta.producto.imagens", "cliente"])
            ->select("solicitud_productos.*");


        $solicitudProductos->where("cliente_id", $cliente_id);
        if (!empty($columnsFilter)) {
            foreach ($columnsFilter as $key => $value) {
                if ($value) {
                    $solicitudProductos->where($key, $value);
                }
            }
        }

        if (!empty($columnsBetweenFilter)) {
            foreach ($columnsBetweenFilter as $key => $value) {
                if ($value[0] && $value[1]) {
                    $solicitudProductos->whereBetween($key, $value);
                }
            }
        }

        if ($search && trim($search) != '') {
            if (!empty($columnsSerachLike)) {
                foreach ($columnsSerachLike as $col) {
                    $solicitudProductos->orWhere($col, "LIKE", "%$search%");
                }
            }
        }


        $solicitudProductos->where("status", 1);

        if (!empty($orderBy)) {
            foreach ($orderBy as $value) {
                $solicitudProductos->orderBy($value[0], $value[1]);
            }
        }


        $solicitudProductos = $solicitudProductos->paginate($length, ['*'], 'page', $page);
        return $solicitudProductos;
    }


    /**
     * Crear solicitudProducto
     *
     * @param array $datos
     * @return SolicitudProducto
     */
    public function crear(array $datos): SolicitudProducto
    {
        $codigo = $this->generarCodigoSolicitud();
        $cliente = Cliente::findOrFail($datos["cliente_id"]);
        $solicitudProducto = SolicitudProducto::create([
            "codigo_solicitud" => $codigo[0],
            "nro" => $codigo[1],
            "sede_id" => $datos["sede_id"],
            "cliente_id" => $cliente->id,
            "nombre_cliente" => $cliente->nombres,
            "apellidos_cliente" => $cliente->apellidos,
            "cel" => $cliente->cel,
            "fecha_solicitud" => date("Y-m-d"),
        ]);

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UNA SOLICITUD DE PRODUCTO", $solicitudProducto);

        // registrar Detalle(solicitudes)
        $this->registrarSolicitudes($solicitudProducto, $datos["solicitudes"]);

        // enviar correo
        $this->enviarCorreoService->nuevaSolicitudProducto($solicitudProducto);

        return $solicitudProducto;
    }

    /**
     * Actualizar solicitudProducto
     *
     * @param array $datos
     * @param SolicitudProducto $solicitudProducto
     * @return SolicitudProducto
     */
    public function actualizar(array $datos, SolicitudProducto $solicitudProducto): SolicitudProducto
    {
        $old_solicitudProducto = SolicitudProducto::find($solicitudProducto->id);
        $solicitudProducto->update([
            "categoria_id" => mb_strtoupper($datos["categoria_id"]),
            "nombre" => mb_strtoupper($datos["nombre"]),
            "descripcion" => mb_strtoupper($datos["descripcion"]),
            "stock_actual" => $datos["stock_actual"],
            "precio_compra" => $datos["precio_compra"],
            "precio_venta" => $datos["precio_venta"],
            "observaciones" => mb_strtoupper($datos["observaciones"]),
            "publico" => mb_strtoupper($datos["publico"]),
        ]);

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UNA SOLICITUD DE PRODUCTO", $old_solicitudProducto, $solicitudProducto);

        // actualizar imagenes
        $existe_cambios = false;
        if (!empty($datos["imagens"])) {
            foreach ($datos["imagens"] as $key => $imagen) {
                if ($imagen["id"] == 0) {
                    $this->solicitudProductoImagenService->guardarImagenSolicitudProducto($solicitudProducto, $imagen["file"], $key);
                    $existe_cambios = true;
                }
            }
        }

        // imagenes eliminadas
        if (!empty($datos["eliminados_imagens"])) {
            foreach ($datos["eliminados_imagens"] as $key => $eliminado) {
                $solicitudProductoImagen = SolicitudProductoImagen::find($eliminado);
                if ($solicitudProductoImagen) {
                    $this->solicitudProductoImagenService->eliminarImagenSolicitudProducto($solicitudProductoImagen);
                    $existe_cambios = true;
                }
            }
        }

        if ($existe_cambios) {
            // registrar imagens asignadas
            $datos_original = $solicitudProducto->imagens->map(function ($imagen) {
                return $imagen->makeHidden($imagen->getAppends())->toArray();
            });

            $datos_nuevo = $solicitudProducto->imagens->map(function ($imagen) {
                return $imagen->makeHidden($imagen->getAppends())->toArray();
            });
            $this->historialAccionService->registrarAccionRelaciones($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ LAS IMAGENES DEL PRODUCTO " . $solicitudProducto->nombre, $datos_original, $datos_nuevo);
        }
        return $solicitudProducto;
    }

    /**
     * Eliminar solicitudProducto
     *
     * @param SolicitudProducto $solicitudProducto
     * @return boolean
     */
    public function eliminar(SolicitudProducto $solicitudProducto): bool
    {
        // verificar usos
        $usos = DetalleVenta::where("solicitudProducto_id", $solicitudProducto->id)->get();
        if (count($usos) > 0) {
            throw ValidationException::withMessages([
                'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
            ]);
        }

        // no eliminar solicitudProductos predeterminados para el funcionamiento del sistema
        $old_solicitudProducto = SolicitudProducto::find($solicitudProducto->id);
        $solicitudProducto->status = 1;
        $solicitudProducto->save();

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UNA SOLICITUD DE PRODUCTO", $old_solicitudProducto);

        return true;
    }

    /**
     * Generar nuevo código de orden
     *
     * @return array<string,int>
     */
    private function generarCodigoSolicitud(): array
    {
        $codigo = "SOL.";
        $nro = 1;
        $ultimaSolicitudProducto = SolicitudProducto::orderBy("id", "desc")->get()->first();
        if ($ultimaSolicitudProducto) {
            $nro = (int)$ultimaSolicitudProducto->nro + 1;
        }

        return [$codigo . $nro, $nro];
    }

    /**
     * Guardar detalle de venta (solicitudes)
     *
     * @param SolicitudProducto $solicitudProducto
     * @param array $solicitudes
     * @return void
     */
    private function registrarSolicitudes(SolicitudProducto $solicitudProducto, array $solicitudes): void
    {
        foreach ($solicitudes as $item) {
            $solicitudProducto->solicitudDetalles()->create([
                "nombre_producto" => $item["nombre_producto"],
                "detalle_producto" => $item["detalle_producto"],
                "links_referencia" => $item["links_referencia"],
            ]);
        }
    }
}
