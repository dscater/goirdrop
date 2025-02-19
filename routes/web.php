<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\ConfiguracionPagoController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\OrdenVentaController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\SolicitudProductoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [PortalController::class, 'index'])->name("portal.index");

Route::get('/clear-cache', function () {
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('optimize');
    return 'Cache eliminado <a href="/">Ir al inicio</a>';
})->name('clear.cache');

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('inicio');
    }
    return Inertia::render('Auth/Login');
})->name("login");

Route::get('/registro', function () {
    if (Auth::check()) {
        return redirect()->route('inicio');
    }
    return Inertia::render('Auth/Register');
})->name("registro");

Route::get("configuracions/getConfiguracion", [ConfiguracionController::class, 'getConfiguracion'])->name("configuracions.getConfiguracion");

// PORTAL
Route::get("productos", [PortalController::class, 'productos'])->name("portal.productos");

Route::get("productos/verProducto/{producto}", [PortalController::class, 'producto'])->name("portal.producto");

Route::get("productos/miCarrito", [PortalController::class, 'miCarrito'])->name("portal.miCarrito");

Route::get("productos/misSolicitudes", [PortalController::class, 'misSolicitudes'])->name("portal.misSolicitudes");

// configuracion pagos
Route::get("configuracion_pagos/listadoPortal", [ConfiguracionPagoController::class, 'listadoPortal'])->name("portal.configuracionPagosLista");

// productos
Route::get("productos/inicioPortal", [ProductoController::class, 'productosInicioPortal'])->name("productos.productosInicioPortal");
Route::get("productos/dataProductos", [ProductoController::class, 'productosPaginadoPortal'])->name("productos.dataProductos");

// categorias
Route::get("categorias/listadoPortal", [CategoriaController::class, 'listadoPortal'])->name("categorias.listadoPortal");

// perfil cliente
Route::get('profile_cliente', [ProfileController::class, 'profile_cliente'])->name('profile.profile_cliente');
Route::get('getInfoCliente', [ProfileController::class, 'getInfoCliente'])->name('profile.getInfoCliente');
Route::post('updateInfoCliente', [ProfileController::class, 'updateInfoCliente'])->name('profile.updateInfoCliente');

// ADMINISTRACION
Route::middleware(['auth', 'permisoUsuario'])->prefix("admin")->group(function () {
    // INICIO
    Route::get('/inicio', [InicioController::class, 'inicio'])->name('inicio');

    // CONFIGURACION
    Route::put("configuracions/update", [ConfiguracionController::class, 'update'])->name("configuracions.update");
    Route::resource("configuracions", ConfiguracionController::class)->only(
        ["index", "show"]
    );

    // USUARIO
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('profile/update_foto', [ProfileController::class, 'update_foto'])->name('profile.update_foto');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get("getUser", [UserController::class, 'getUser'])->name('users.getUser');
    Route::get("permisosUsuario", [UserController::class, 'permisosUsuario']);

    // USUARIOS
    Route::get("usuarios/clientes", [UsuarioController::class, 'clientes'])->name("usuarios.clientes");
    Route::put("usuarios/password/{user}", [UsuarioController::class, 'actualizaPassword'])->name("usuarios.password");
    Route::get("usuarios/api_clientes", [UsuarioController::class, 'api_clientes'])->name("usuarios.api_clientes");
    Route::get("usuarios/api", [UsuarioController::class, 'api'])->name("usuarios.api");
    Route::get("usuarios/paginado", [UsuarioController::class, 'paginado'])->name("usuarios.paginado");
    Route::get("usuarios/listado", [UsuarioController::class, 'listado'])->name("usuarios.listado");
    Route::get("usuarios/listado/byTipo", [UsuarioController::class, 'byTipo'])->name("usuarios.byTipo");
    Route::get("usuarios/show/{user}", [UsuarioController::class, 'show'])->name("usuarios.show");
    Route::put("usuarios/update/{user}", [UsuarioController::class, 'update'])->name("usuarios.update");
    Route::delete("usuarios/{user}", [UsuarioController::class, 'destroy'])->name("usuarios.destroy");
    Route::resource("usuarios", UsuarioController::class)->only(
        ["index", "store"]
    );

    // CLIENTES
    Route::get("clientes/listado", [ClienteController::class, 'listado'])->name("clientes.listado");
    Route::put("clientes/update/{cliente}", [ClienteController::class, 'update'])->name("clientes.update");

    // ROLES
    Route::get("roles/api", [RoleController::class, 'api'])->name("roles.api");
    Route::get("roles/paginado", [RoleController::class, 'paginado'])->name("roles.paginado");
    Route::get("roles/listado", [RoleController::class, 'listado'])->name("roles.listado");
    Route::post("roles/actualizaPermiso/{role}", [RoleController::class, 'actualizaPermiso'])->name("roles.actualizaPermiso");
    Route::resource("roles", RoleController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // SEDES
    Route::get("sedes/listado", [SedeController::class, 'listado'])->name("sedes.listado");

    // CATEGORIAS
    Route::get("categorias/api", [CategoriaController::class, 'api'])->name("categorias.api");
    Route::get("categorias/paginado", [CategoriaController::class, 'paginado'])->name("categorias.paginado");
    Route::get("categorias/listado", [CategoriaController::class, 'listado'])->name("categorias.listado");
    Route::resource("categorias", CategoriaController::class)->only(
        ["index", "store", "show", "update", "destroy"]
    );

    // PRODUCTOS
    Route::get("productos/api", [ProductoController::class, 'api'])->name("productos.api");
    Route::get("productos/paginado", [ProductoController::class, 'paginado'])->name("productos.paginado");
    Route::get("productos/listado", [ProductoController::class, 'listado'])->name("productos.listado");
    Route::resource("productos", ProductoController::class)->only(
        ["index", "store", "show", "update", "destroy"]
    );

    // CONFIGURACIÓN DE PAGOS
    Route::get("configuracion_pagos/api", [ConfiguracionPagoController::class, 'api'])->name("configuracion_pagos.api");
    Route::get("configuracion_pagos/paginado", [ConfiguracionPagoController::class, 'paginado'])->name("configuracion_pagos.paginado");
    Route::get("configuracion_pagos/listado", [ConfiguracionPagoController::class, 'listado'])->name("configuracion_pagos.listado");
    Route::post("configuracion_pagos/actualizaPermiso/{role}", [ConfiguracionPagoController::class, 'actualizaPermiso'])->name("configuracion_pagos.actualizaPermiso");
    Route::resource("configuracion_pagos", ConfiguracionPagoController::class)->only(
        ["index", "store", "show", "update", "destroy"]
    );

    // ORDEN VENTAS
    Route::get("orden_ventas/misSolicitudes", [OrdenVentaController::class, 'ordenesCliente'])->name("orden_ventas.ordenesCliente");
    Route::get("orden_ventas/api", [OrdenVentaController::class, 'api'])->name("orden_ventas.api");
    Route::get("orden_ventas/paginado", [OrdenVentaController::class, 'paginado'])->name("orden_ventas.paginado");
    Route::get("orden_ventas/listado", [OrdenVentaController::class, 'listado'])->name("orden_ventas.listado");
    Route::patch("orden_ventas/update_estado/{ordenVenta}", [OrdenVentaController::class, 'update_estado'])->name("orden_ventas.update_estado");
    Route::resource("orden_ventas", OrdenVentaController::class)->only(
        ["index", "store", "show", "update", "destroy"]
    );

    // SOLICITUD PRODUCTOS
    Route::get("solicitud_productos/misSolicitudes", [SolicitudProductoController::class, 'solicitudProductosCliente'])->name("solicitud_productos.solicitudProductosCliente");
    Route::get("solicitud_productos/api", [SolicitudProductoController::class, 'api'])->name("solicitud_productos.api");
    Route::get("solicitud_productos/paginado", [SolicitudProductoController::class, 'paginado'])->name("solicitud_productos.paginado");
    Route::get("solicitud_productos/listado", [SolicitudProductoController::class, 'listado'])->name("solicitud_productos.listado");
    Route::patch("solicitud_productos/update_estado_verificacion/{solicitudProducto}", [SolicitudProductoController::class, 'update_estado_verificacion'])->name("solicitud_productos.update_estado_verificacion");
    Route::patch("solicitud_productos/update_estado_seguimiento/{solicitudProducto}", [SolicitudProductoController::class, 'update_estado_seguimiento'])->name("solicitud_productos.update_estado_seguimiento");
    Route::resource("solicitud_productos", SolicitudProductoController::class)->only(
        ["index", "store", "show", "update", "destroy"]
    );

    // NOTIFICACIONS
    Route::get("notificacions/listadoPorUsuario", [NotificacionController::class, "listadoPorUsuario"])->name("notificacions.listadoPorUsuario");

    // REPORTES
    Route::get('reportes/usuarios', [ReporteController::class, 'usuarios'])->name("reportes.usuarios");
    Route::get('reportes/r_usuarios', [ReporteController::class, 'r_usuarios'])->name("reportes.r_usuarios");

    Route::get('reportes/productos', [ReporteController::class, 'productos'])->name("reportes.productos");
    Route::get('reportes/r_productos', [ReporteController::class, 'r_productos'])->name("reportes.r_productos");

    Route::get('reportes/orden_ventas', [ReporteController::class, 'orden_ventas'])->name("reportes.orden_ventas");
    Route::get('reportes/r_orden_ventas', [ReporteController::class, 'r_orden_ventas'])->name("reportes.r_orden_ventas");

    Route::get('reportes/solicitud_productos', [ReporteController::class, 'solicitud_productos'])->name("reportes.solicitud_productos");
    Route::get('reportes/r_solicitud_productos', [ReporteController::class, 'r_solicitud_productos'])->name("reportes.r_solicitud_productos");

    Route::get('reportes/seguimiento_solicituds', [ReporteController::class, 'seguimiento_solicituds'])->name("reportes.seguimiento_solicituds");
    Route::get('reportes/r_seguimiento_solicituds', [ReporteController::class, 'r_seguimiento_solicituds'])->name("reportes.r_seguimiento_solicituds");

    Route::get('reportes/g_orden_ventas', [ReporteController::class, 'g_orden_ventas'])->name("reportes.g_orden_ventas");
    Route::get('reportes/r_g_orden_ventas', [ReporteController::class, 'r_g_orden_ventas'])->name("reportes.r_g_orden_ventas");

    Route::get('reportes/g_solicitud_productos', [ReporteController::class, 'g_solicitud_productos'])->name("reportes.g_solicitud_productos");
    Route::get('reportes/r_g_solicitud_productos', [ReporteController::class, 'r_g_solicitud_productos'])->name("reportes.r_g_solicitud_productos");

    Route::get('reportes/g_seguimiento_productos', [ReporteController::class, 'g_seguimiento_productos'])->name("reportes.g_seguimiento_productos");
    Route::get('reportes/r_g_seguimiento_productos', [ReporteController::class, 'r_g_seguimiento_productos'])->name("reportes.r_g_seguimiento_productos");
});
require __DIR__ . '/auth.php';
