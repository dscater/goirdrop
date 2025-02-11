<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class PortalController extends Controller
{
    /**
     * Pagina inicio-portal
     *
     * @return InertiaResponse
     */
    public function index(): InertiaResponse
    {
        return Inertia::render("Portal/Inicio");
    }

    /**
     * Pagina productos-portal
     *
     * @return InertiaResponse
     */
    public function productos(): InertiaResponse
    {
        return Inertia::render("Portal/Productos");
    }

    /**
     * Ver producto portal
     *
     * @param Producto $producto
     * @return InertiaResponse
     */
    public function producto(Producto $producto): InertiaResponse
    {
        $producto = $producto->load(["imagens"]);
        return Inertia::render("Portal/Producto", compact("producto"));
    }
}
