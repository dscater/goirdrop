<?php

namespace App\Http\Controllers;

use App\Http\Requests\SolicitudProductoStoreRequest;
use App\Http\Requests\SolicitudProductoUpdateRequest;
use App\Models\SolicitudProducto;
use App\Services\SolicitudProductoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class SolicitudProductoController extends Controller
{
    public function __construct(private SolicitudProductoService $solicitudProductoService) {}

    /**
     * Página index
     *
     * @return Response
     */
    public function index(): InertiaResponse
    {
        return Inertia::render("Admin/SolicitudProductos/Index");
    }

    /**
     * Listado de solicitudProductos
     *
     * @return JsonResponse
     */
    public function listado(): JsonResponse
    {
        return response()->JSON([
            "solicitudProductos" => $this->solicitudProductoService->listado()
        ]);
    }

    /**
     * Listado de solicitudProductos para portal
     *
     * @return JsonResponse
     */
    public function listadoPortal(): JsonResponse
    {
        return response()->JSON([
            "solicitudProductos" => $this->solicitudProductoService->listado()
        ]);
    }


    public function ordenesCliente(Request $request): JsonResponse
    {
        $perPage = $request->perPage;
        $page = (int)($request->input("page", 1));
        $search = (string)$request->input("search", "");
        $orderByCol = $request->orderByCol;
        $desc = $request->desc;

        $columnsSerachLike = ["nombre", "descripcion"];
        $columnsFilter = [];
        $arrayOrderBy = [];
        if ($orderByCol && $desc) {
            $arrayOrderBy = [
                [$orderByCol, $desc]
            ];
        }

        $cliente_id = (int)(Auth::user()->cliente ? Auth::user()->cliente->id : 0);

        $solicitudProductos =  $this->solicitudProductoService->listadoPaginadoCliente($cliente_id, $perPage, $page, $search, $columnsSerachLike, $columnsFilter, [], $arrayOrderBy);

        return response()->JSON([
            "total" => $solicitudProductos->total(),
            "solicitudProductos" => $solicitudProductos->items(),
            "lastPage" => $solicitudProductos->lastPage()
        ]);
    }

    /**
     * Endpoint para obtener la lista de solicitudProductos paginado para datatable
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function api(Request $request): JsonResponse
    {

        $length = (int)$request->input('length', 10); // Valor de `length` enviado por DataTable
        $start = (int)$request->input('start', 0); // Índice de inicio enviado por DataTable
        $page = (int)(($start / $length) + 1); // Cálculo de la página actual
        $search = (string)$request->input('search', '');

        $usuarios = $this->solicitudProductoService->listadoDataTable($length, $start, $page, $search);

        return response()->JSON([
            'data' => $usuarios->items(),
            'recordsTotal' => $usuarios->total(),
            'recordsFiltered' => $usuarios->total(),
            'draw' => intval($request->input('draw')),
        ]);
    }

    /**
     * Registrar un nuevo solicitudProducto
     *
     * @param SolicitudProductoStoreRequest $request
     * @return RedirectResponse|Response
     */
    public function store(SolicitudProductoStoreRequest $request): RedirectResponse|Response|JsonResponse
    {
        DB::beginTransaction();
        try {
            // crear el SolicitudProducto
            $this->solicitudProductoService->crear($request->validated());
            DB::commit();

            $mensaje = 'Tu compra fue registrada correctamente.<br/> Puedes realizar su seguimiento en la sección de "Mis solicitudes"';
            session()->flash("bien", $mensaje);
            if ($request->ajax()) {
                return response()->JSON([
                    "sw" => true,
                    "message" => $mensaje
                ]);
            }

            return redirect()->route("solicitudProductos.index");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    /**
     * Mostrar un solicitudProducto
     *
     * @param SolicitudProducto $solicitudProducto
     * @return JsonResponse
     */
    public function show(SolicitudProducto $solicitudProducto): JsonResponse
    {
        return response()->JSON($solicitudProducto);
    }

    public function update(SolicitudProducto $solicitudProducto, SolicitudProductoUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            // actualizar solicitudProducto
            $this->solicitudProductoService->actualizar($request->validated(), $solicitudProducto);
            DB::commit();
            return redirect()->route("solicitudProductos.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    /**
     * Eliminar solicitudProducto
     *
     * @param SolicitudProducto $solicitudProducto
     * @return JsonResponse|Response
     */
    public function destroy(SolicitudProducto $solicitudProducto): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->solicitudProductoService->eliminar($solicitudProducto);
            DB::commit();
            return response()->JSON([
                'sw' => true,
                'message' => 'El registro se eliminó correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
}
