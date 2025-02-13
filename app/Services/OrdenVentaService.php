<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Services\HistorialAccionService;
use App\Models\OrdenVenta;
use App\Models\OrdenVentaImagen;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class OrdenVentaService
{
    private $modulo = "ORDENES DE VENTA";

    public function __construct(private HistorialAccionService $historialAccionService) {}

    /**
     * Lista de todos los ordenVentas
     *
     * @return Collection
     */
    public function listado(): Collection
    {
        $ordenVentas = OrdenVenta::select("ordenVentas.*")->where("status", 1)->get();
        return $ordenVentas;
    }

    /**
     * Lista de ordenVentas paginado con filtros
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
        $ordenVentas = OrdenVenta::with(["imagens", "categoria"])
            ->select("ordenVentas.*")
            ->leftJoin("detalle_ventas", "ordenVentas.id", "=", "detalle_ventas.ordenVenta_id")
            ->selectRaw("SUM(detalle_ventas.cantidad) as total_vendido")
            ->groupBy("ordenVentas.id");

        if (!empty($columnsFilter)) {
            foreach ($columnsFilter as $key => $value) {
                if ($value) {
                    $ordenVentas->where($key, $value);
                }
            }
        }

        if (!empty($columnsBetweenFilter)) {
            foreach ($columnsBetweenFilter as $key => $value) {
                if ($value[0] && $value[1]) {
                    $ordenVentas->whereBetween($key, $value);
                }
            }
        }

        if ($search && trim($search) != '') {
            if (!empty($columnsSerachLike)) {
                foreach ($columnsSerachLike as $col) {
                    $ordenVentas->orWhere($col, "LIKE", "%$search%");
                }
            }
        }


        $ordenVentas->where("status", 1);

        if (!empty($orderBy)) {
            foreach ($orderBy as $value) {
                $ordenVentas->orderBy($value[0], $value[1]);
            }
        }


        $ordenVentas = $ordenVentas->paginate($length, ['*'], 'page', $page);
        return $ordenVentas;
    }

    /**
     * Crear ordenVenta
     *
     * @param array $datos
     * @return OrdenVenta
     */
    public function crear(array $datos): OrdenVenta
    {
        $codigo = $this->generarCodigoOrden();
        $cliente = Cliente::findOrFail($datos["cliente_id"]);
        $ordenVenta = OrdenVenta::create([
            "codigo" => $codigo[0],
            "nro" => $codigo[1],
            "cliente_id" => $cliente->id,
            "nombre_cliente" => $cliente->nombres,
            "apellidos_cliente" => $cliente->apellidos,
            "cel" => $cliente->cel,
            "estado_orden" => "PENDIENTE",
            "estado_pago" => "estado_pago",
            "configuracion_pago_id" => "configuracion_pago_id",
            "comprobante" => "comprobante",
            "observacion" => "observacion",
            "status" => "status",
            "fecha_orden" => "fecha_orden",
            "fecha_confirmacion" => "fecha_confirmacion",
            "fecha_registro" => date("Y-m-d"),
        ]);


        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UNA ORDEN DE VENTA", $ordenVenta->load(["imagens"]));

        // registrar comprobante

        return $ordenVenta;
    }

    /**
     * Actualizar ordenVenta
     *
     * @param array $datos
     * @param OrdenVenta $ordenVenta
     * @return OrdenVenta
     */
    public function actualizar(array $datos, OrdenVenta $ordenVenta): OrdenVenta
    {
        $old_ordenVenta = OrdenVenta::find($ordenVenta->id);
        $ordenVenta->update([
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
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UNA ORDEN DE VENTA", $old_ordenVenta, $ordenVenta);

        // actualizar imagenes
        $existe_cambios = false;
        if (!empty($datos["imagens"])) {
            foreach ($datos["imagens"] as $key => $imagen) {
                if ($imagen["id"] == 0) {
                    $this->ordenVentaImagenService->guardarImagenOrdenVenta($ordenVenta, $imagen["file"], $key);
                    $existe_cambios = true;
                }
            }
        }

        // imagenes eliminadas
        if (!empty($datos["eliminados_imagens"])) {
            foreach ($datos["eliminados_imagens"] as $key => $eliminado) {
                $ordenVentaImagen = OrdenVentaImagen::find($eliminado);
                if ($ordenVentaImagen) {
                    $this->ordenVentaImagenService->eliminarImagenOrdenVenta($ordenVentaImagen);
                    $existe_cambios = true;
                }
            }
        }

        if ($existe_cambios) {
            // registrar imagens asignadas
            $datos_original = $ordenVenta->imagens->map(function ($imagen) {
                return $imagen->makeHidden($imagen->getAppends())->toArray();
            });

            $datos_nuevo = $ordenVenta->imagens->map(function ($imagen) {
                return $imagen->makeHidden($imagen->getAppends())->toArray();
            });
            $this->historialAccionService->registrarAccionRelaciones($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ LAS IMAGENES DEL PRODUCTO " . $ordenVenta->nombre, $datos_original, $datos_nuevo);
        }
        return $ordenVenta;
    }

    /**
     * Eliminar ordenVenta
     *
     * @param OrdenVenta $ordenVenta
     * @return boolean
     */
    public function eliminar(OrdenVenta $ordenVenta): bool
    {
        // verificar usos
        $usos = DetalleVenta::where("ordenVenta_id", $ordenVenta->id)->get();
        if (count($usos) > 0) {
            throw ValidationException::withMessages([
                'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
            ]);
        }

        // no eliminar ordenVentas predeterminados para el funcionamiento del sistema
        $old_ordenVenta = OrdenVenta::find($ordenVenta->id);
        $ordenVenta->status = 1;
        $ordenVenta->save();

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UNA ORDEN DE VENTA", $old_ordenVenta);

        return true;
    }

    /**
     * Generar nuevo código de orden
     *
     * @return array<string,int>
     */
    private function generarCodigoOrden(): array
    {
        $codigo = "ORD.";
        $nro = 1;
        $ultimaOrdenVenta = OrdenVenta::orderBy("id", "desc")->get()->first();
        if ($ultimaOrdenVenta) {
            $nro = (int)$ultimaOrdenVenta->nro + 1;
        }

        return [$codigo . $nro, $nro];
    }
}
