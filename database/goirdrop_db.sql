-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 09-02-2025 a las 16:29:04
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `goirdrop_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paterno` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `materno` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracions`
--

CREATE TABLE `configuracions` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre_sistema` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conf_correos` json NOT NULL,
  `conf_moneda` json NOT NULL,
  `conf_captcha` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracions`
--

INSERT INTO `configuracions` (`id`, `nombre_sistema`, `alias`, `logo`, `fono`, `dir`, `conf_correos`, `conf_moneda`, `conf_captcha`, `created_at`, `updated_at`) VALUES
(1, 'GOIRDROP S.A.', 'GOIRDROP.', 'lg1738947677.png', '7777777', 'ZONA LOS OLIVOS C. 4 #333', '{\"host\": \"smtp.hostinger.com\", \"correo\": \"mensaje@emsytsrl.com\", \"driver\": \"smtp\", \"nombre\": \"unibienes\", \"puerto\": \"587\", \"password\": \"8Z@d>&kj^y\", \"encriptado\": \"tls\"}', '{\"abrev\": \"Bs\", \"moneda\": \"Bolivianos\"}', '{\"key\": \"Clave 1\", \"key2\": \"bbb\"}', '2025-02-07 15:22:56', '2025-02-07 17:08:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion_pagos`
--

CREATE TABLE `configuracion_pagos` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre_banco` varchar(400) COLLATE utf8mb4_unicode_ci NOT NULL,
  `titular_cuenta` varchar(400) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nro_cuenta` varchar(400) COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagen_qr` varchar(400) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ventas`
--

CREATE TABLE `detalle_ventas` (
  `id` bigint UNSIGNED NOT NULL,
  `orden_venta_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `cantidad` int NOT NULL,
  `precio` decimal(24,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_accions`
--

CREATE TABLE `historial_accions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `accion` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `datos_original` json DEFAULT NULL,
  `datos_nuevo` json DEFAULT NULL,
  `modulo` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `historial_accions`
--

INSERT INTO `historial_accions` (`id`, `user_id`, `accion`, `descripcion`, `datos_original`, `datos_nuevo`, `modulo`, `fecha`, `hora`, `created_at`, `updated_at`) VALUES
(1, 1, 'CREACIÓN', 'EL USUARIO admin@admin.com REGISTRO UN ROLE', '{\"id\": 13, \"nombre\": \"ADMINISTRADOR\", \"created_at\": \"2025-02-08T17:41:05.000000Z\", \"updated_at\": \"2025-02-08T17:41:05.000000Z\"}', NULL, 'ROLES', '2025-02-08', '13:41:05', '2025-02-08 17:41:05', '2025-02-08 17:41:05'),
(2, 1, 'CREACIÓN', 'EL USUARIO admin@admin.com REGISTRO UN ROLE', '{\"id\": 14, \"nombre\": \"AUXILIAR\", \"created_at\": \"2025-02-08T17:41:11.000000Z\", \"updated_at\": \"2025-02-08T17:41:11.000000Z\"}', NULL, 'ROLES', '2025-02-08', '13:41:11', '2025-02-08 17:41:11', '2025-02-08 17:41:11'),
(3, 1, 'ELIMINACIÓN', 'EL USUARIO admin@admin.com ELIMINÓ UN ROLE', '{\"id\": 14, \"nombre\": \"AUXILIAR\", \"permisos\": 0, \"usuarios\": 1, \"created_at\": \"2025-02-08T17:41:11.000000Z\", \"updated_at\": \"2025-02-08T17:41:11.000000Z\"}', NULL, 'ROLES', '2025-02-08', '14:04:34', '2025-02-08 18:04:34', '2025-02-08 18:04:34'),
(4, 1, 'ELIMINACIÓN', 'EL USUARIO admin@admin.com ELIMINÓ UN ROLE', '{\"id\": 13, \"nombre\": \"ADMINISTRADOR\", \"permisos\": 0, \"usuarios\": 1, \"created_at\": \"2025-02-08T17:41:05.000000Z\", \"updated_at\": \"2025-02-08T17:41:05.000000Z\"}', NULL, 'ROLES', '2025-02-08', '14:04:59', '2025-02-08 18:04:59', '2025-02-08 18:04:59'),
(5, 1, 'CREACIÓN', 'EL USUARIO admin@admin.com REGISTRO UN ROLE', '{\"id\": 3, \"nombre\": \"ADMINITRADOR\", \"created_at\": \"2025-02-09T15:32:31.000000Z\", \"updated_at\": \"2025-02-09T15:32:31.000000Z\"}', NULL, 'ROLES', '2025-02-09', '11:32:31', '2025-02-09 15:32:31', '2025-02-09 15:32:31'),
(6, 1, 'CREACIÓN', 'EL USUARIO admin@admin.com REGISTRO UN USUARIO', '{\"ci\": \"222222\", \"id\": 2, \"foto\": \"21739118494.jpg\", \"acceso\": \"1\", \"ci_exp\": \"LP\", \"correo\": \"juan@gmail.com\", \"nombres\": \"JUAN\", \"role_id\": \"3\", \"usuario\": \"juan@gmail.com\", \"apellidos\": \"PERES\", \"created_at\": \"2025-02-09T16:28:14.000000Z\", \"sedes_todo\": 0, \"updated_at\": \"2025-02-09T16:28:14.000000Z\", \"fecha_registro\": \"2025-02-09\"}', NULL, 'USUARIOS', '2025-02-09', '12:28:14', '2025-02-09 16:28:14', '2025-02-09 16:28:14'),
(7, 1, 'MODIFICACIÓN', 'admin@admin.com ACTUALIZO LAS SEDES DEL USUARIO juan@gmail.com', '{\"sedes\": [1, 2, 3]}', '{\"updated\": [], \"attached\": [8], \"detached\": []}', 'USUARIOS', '2025-02-09', '12:28:27', '2025-02-09 16:28:27', '2025-02-09 16:28:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2024_01_31_165641_create_configuracions_table', 1),
(2, '2024_11_02_153309_create_roles_table', 1),
(3, '2024_11_02_153315_create_modulos_table', 1),
(4, '2024_11_02_153316_create_permisos_table', 1),
(5, '2024_11_02_153316_create_sedes_table', 1),
(6, '2024_11_02_153317_create_users_table', 1),
(7, '2024_11_02_153318_create_historial_accions_table', 1),
(8, '2024_11_02_153834_create_clientes_table', 1),
(9, '2024_11_15_215421_create_notificacions_table', 1),
(10, '2024_11_15_215426_create_notificacion_users_table', 1),
(11, '2025_02_07_101213_create_sede_users_table', 1),
(12, '2025_02_07_101500_create_categorias_table', 1),
(13, '2025_02_07_101512_create_productos_table', 1),
(14, '2025_02_07_101529_create_configuracion_pagos_table', 1),
(15, '2025_02_07_101539_create_orden_ventas_table', 1),
(16, '2025_02_07_101545_create_detalle_ventas_table', 1),
(17, '2025_02_07_101625_create_solicitud_productos_table', 1),
(18, '2025_02_07_102411_create_producto_imagens_table', 1),
(19, '2025_02_07_103944_create_solicitud_detalles_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id` bigint UNSIGNED NOT NULL,
  `modulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `accion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id`, `modulo`, `nombre`, `accion`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Gestión de usuarios', 'usuarios.index', 'VER', 'VER LA LISTA DE USUARIOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(2, 'Gestión de usuarios', 'usuarios.create', 'CREAR', 'CREAR USUARIOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(3, 'Gestión de usuarios', 'usuarios.edit', 'EDITAR', 'EDITAR USUARIOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(4, 'Gestión de usuarios', 'usuarios.destroy', 'ELIMINAR', 'ELIMINAR USUARIOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(5, 'Roles y Permisos', 'roles.index', 'VER', 'VER LA LISTA DE ROLES Y PERMISOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(6, 'Roles y Permisos', 'roles.create', 'CREAR', 'CREAR ROLES Y PERMISOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(7, 'Roles y Permisos', 'roles.edit', 'EDITAR', 'EDITAR ROLES Y PERMISOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(8, 'Roles y Permisos', 'roles.destroy', 'ELIMINAR', 'ELIMINAR ROLES Y PERMISOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(9, 'Configuración', 'configuracions.index', 'VER', 'VER INFORMACIÓN DE LA CONFIGURACIÓN DEL SISTEMA', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(10, 'Configuración', 'configuracions.edit', 'EDITAR', 'EDITAR LA CONFIGURACIÓN DEL SISTEMA', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(11, 'Configuración de pagos', 'configuracion_pagos.index', 'VER', 'VER LA LISTA DE CONFIGURACIÓN DE PAGOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(12, 'Configuración de pagos', 'configuracion_pagos.create', 'CREAR', 'CREAR CONFIGURACIÓN DE PAGOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(13, 'Configuración de pagos', 'configuracion_pagos.edit', 'EDITAR', 'EDITAR CONFIGURACIÓN DE PAGOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(14, 'Configuración de pagos', 'configuracion_pagos.destroy', 'ELIMINAR', 'ELIMINAR CONFIGURACIÓN DE PAGOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(15, 'Categorías', 'categorias.index', 'VER', 'VER LA LISTA DE CATEGORIAS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(16, 'Categorías', 'categorias.create', 'CREAR', 'CREAR CATEGORIAS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(17, 'Categorías', 'categorias.edit', 'EDITAR', 'EDITAR CATEGORIAS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(18, 'Categorías', 'categorias.destroy', 'ELIMINAR', 'ELIMINAR CATEGORIAS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(19, 'Productos', 'productos.index', 'VER', 'VER LA LISTA DE PRODUCTOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(20, 'Productos', 'productos.create', 'CREAR', 'CREAR PRODUCTOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(21, 'Productos', 'productos.edit', 'EDITAR', 'EDITAR PRODUCTOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(22, 'Productos', 'productos.destroy', 'ELIMINAR', 'ELIMINAR PRODUCTOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(23, 'Orden de venta', 'orden_ventas.index', 'VER', 'VER LA LISTA DE ORDENES DE VENTA', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(24, 'Orden de venta', 'orden_ventas.todos', 'TODAS LAS ORDENES DE VENTA', 'VER TODAS LAS ORDENES DE VENTA', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(25, 'Orden de venta', 'orden_ventas.confirmar', 'CONFIRMAR', 'CONFIRMAR ORDENES DE VENTA', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(26, 'Orden de venta', 'orden_ventas.create', 'CREAR', 'CREAR ORDEN DE VENTA', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(27, 'Orden de venta', 'orden_ventas.edit', 'EDITAR', 'EDITAR ORDEN DE VENTA', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(28, 'Orden de venta', 'orden_ventas.destroy', 'ELIMINAR', 'ELIMINAR ORDEN DE VENTA', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(29, 'Solicitud de productos', 'solicitud_productos.index', 'VER', 'VER LA LISTA DE SOLICITUDES DE PRODUCTOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(30, 'Solicitud de productos', 'solicitud_productos.todos', 'TODAS LAS SOLICITUDES DE PRODUCTOS', 'VER TODAS LAS SOLICITUDES DE PRODUCTOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(31, 'Solicitud de productos', 'solicitud_productos.confirmar', 'VERIFICAR', 'VERIFICAR SOLICITUDES DE PRODUCTOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(32, 'Solicitud de productos', 'solicitud_productos.seguimiento', 'SEGUIMIENTO', 'SEGUIMIENTO DE SOLICITUDES DE PRODUCTOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(33, 'Solicitud de productos', 'solicitud_productos.create', 'CREAR', 'CREAR SOLICITUD DE PRODUCTO', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(34, 'Solicitud de productos', 'solicitud_productos.edit', 'EDITAR', 'EDITAR SOLICITUD DE PRODUCTO', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(35, 'Solicitud de productos', 'solicitud_productos.destroy', 'ELIMINAR', 'ELIMINAR SOLICITUD DE PRODUCTO', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(36, 'Reportes', 'reportes.usuarios', 'REPORTE LISTA DE USUARIOS', 'GENERAR REPORTES DE LISTA DE USUARIOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(37, 'Reportes', 'reportes.productos', 'REPORTE PRODUCTOS', 'GENERAR REPORTES DE PRODUCTOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(38, 'Reportes', 'reportes.orden_ventas', 'REPORTE ORDENES DE VENTAS', 'GENERAR REPORTES DE ORDENES DE VENTAS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(39, 'Reportes', 'reportes.solicitud_productos', 'REPORTE DE SOLICITUD DE PRODUCTOS', 'GENERAR REPORTES DE SOLICITUD DE PRODUCTOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(40, 'Reportes', 'reportes.seguimiento_solicituds', 'REPORTE DE SEGUIMIENTO DE SOLICITUD DE PRODUCTOS', 'GENERAR REPORTES DE SEGUIMIENTO DE SOLICITUD DE PRODUCTOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(41, 'Reportes', 'reportes.g_orden_ventas', 'REPORTE GRÁFICO DE ORDENES DE VENTAS', 'GENERAR REPORTE GRÁFICO DE ORDENES DE VENTAS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(42, 'Reportes', 'reportes.g_solicitud_productos', 'REPORTE GRÁFICO DE SOLICITUD DE PRODUCTOS', 'GENERAR REPORTE GRÁFICO DE SOLICITUD DE PRODUCTOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16'),
(43, 'Reportes', 'reportes.g_seguimiento_productos', 'REPORTE GRÁFICO DE SEGUIMIENTO DE SOLICITUD DE PRODUCTOS', 'GENERAR REPORTE GRÁFICO DE SEGUIMIENTO DE SOLICITUD DE PRODUCTOS', '2025-02-08 17:55:16', '2025-02-08 17:55:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacions`
--

CREATE TABLE `notificacions` (
  `id` bigint UNSIGNED NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(900) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacion_users`
--

CREATE TABLE `notificacion_users` (
  `notificacion_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `visto` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_ventas`
--

CREATE TABLE `orden_ventas` (
  `id` bigint UNSIGNED NOT NULL,
  `codigo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cliente_id` bigint UNSIGNED NOT NULL,
  `nombre_cliente` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidos_cliente` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado_orden` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDIENTE',
  `estado_pago` int NOT NULL,
  `configuracion_pago_id` bigint UNSIGNED NOT NULL,
  `comprobante` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `observacion` text COLLATE utf8mb4_unicode_ci,
  `status` int NOT NULL,
  `fecha_orden` date NOT NULL,
  `fecha_confirmacion` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `modulo_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` bigint UNSIGNED NOT NULL,
  `categoria_id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(600) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock_actual` int NOT NULL,
  `precio_compra` decimal(24,2) NOT NULL,
  `precio_venta` decimal(24,2) NOT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `publico` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_imagens`
--

CREATE TABLE `producto_imagens` (
  `id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permisos` int NOT NULL DEFAULT '0',
  `usuarios` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `permisos`, `usuarios`, `created_at`, `updated_at`) VALUES
(1, 'SUPER USUARIO', 1, 0, '2025-02-07 15:17:41', '2025-02-07 15:17:41'),
(2, 'CLIENTE', 0, 0, NULL, NULL),
(3, 'ADMINITRADOR', 0, 1, '2025-02-09 15:32:31', '2025-02-09 15:32:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sedes`
--

CREATE TABLE `sedes` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sedes`
--

INSERT INTO `sedes` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'LA PAZ', '2025-02-09 15:11:10', '2025-02-09 15:11:10'),
(2, 'COCHABAMBA', '2025-02-09 15:11:10', '2025-02-09 15:11:10'),
(3, 'SANTA CRUZ', '2025-02-09 15:11:10', '2025-02-09 15:11:10'),
(4, 'CHUQUISACA', '2025-02-09 15:11:10', '2025-02-09 15:11:10'),
(5, 'ORURO', '2025-02-09 15:11:10', '2025-02-09 15:11:10'),
(6, 'POTOSÍ', '2025-02-09 15:11:10', '2025-02-09 15:11:10'),
(7, 'TARIJA', '2025-02-09 15:11:10', '2025-02-09 15:11:10'),
(8, 'BENI', '2025-02-09 15:11:10', '2025-02-09 15:11:10'),
(9, 'PANDO', '2025-02-09 15:11:10', '2025-02-09 15:11:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sede_users`
--

CREATE TABLE `sede_users` (
  `sede_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sede_users`
--

INSERT INTO `sede_users` (`sede_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 2, '2025-02-09 16:28:14', '2025-02-09 16:28:14'),
(2, 2, '2025-02-09 16:28:14', '2025-02-09 16:28:14'),
(3, 2, '2025-02-09 16:28:14', '2025-02-09 16:28:14'),
(8, 2, '2025-02-09 16:28:27', '2025-02-09 16:28:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_detalles`
--

CREATE TABLE `solicitud_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `solicitud_producto_id` bigint UNSIGNED NOT NULL,
  `nombre_producto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detalle_producto` varchar(800) COLLATE utf8mb4_unicode_ci NOT NULL,
  `links_referencia` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_productos`
--

CREATE TABLE `solicitud_productos` (
  `id` bigint UNSIGNED NOT NULL,
  `codigo_solicitud` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sede_id` bigint UNSIGNED NOT NULL,
  `cliente_id` bigint UNSIGNED NOT NULL,
  `nombre_cliente` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidos_cliente` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado_solicitud` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDIENTE',
  `estado_seguimiento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `observacion` text COLLATE utf8mb4_unicode_ci,
  `status` int NOT NULL,
  `fecha_solicitud` date NOT NULL,
  `fecha_verificacion` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `usuario` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombres` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidos` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci_exp` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `sedes_todo` int NOT NULL DEFAULT '0',
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `acceso` int NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `usuario`, `nombres`, `apellidos`, `ci`, `ci_exp`, `correo`, `password`, `role_id`, `sedes_todo`, `foto`, `fecha_registro`, `acceso`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin@admin.com', 'admin', 'admin', '0', '', 'admin@admin.com', '$2y$12$65d4fgZsvBV5Lc/AxNKh4eoUdbGyaczQ4sSco20feSQANshNLuxSC', 1, 1, NULL, '2025-02-07', 1, 1, '2025-02-07 15:17:42', '2025-02-07 15:17:42'),
(2, 'juan@gmail.com', 'JUAN', 'PERES', '222222', 'LP', 'juan@gmail.com', '$2y$12$qIdvl7RF7Xc91UMfZn8rn.tO6Jgh9mhL1B1.eN/tfklHOHun6ldHe', 3, 0, '21739118494.jpg', '2025-02-09', 1, 1, '2025-02-09 16:28:14', '2025-02-09 16:28:14');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clientes_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configuracion_pagos`
--
ALTER TABLE `configuracion_pagos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_ventas_orden_venta_id_foreign` (`orden_venta_id`),
  ADD KEY `detalle_ventas_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `historial_accions_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notificacions`
--
ALTER TABLE `notificacions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notificacion_users`
--
ALTER TABLE `notificacion_users`
  ADD KEY `notificacion_users_notificacion_id_foreign` (`notificacion_id`),
  ADD KEY `notificacion_users_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `orden_ventas`
--
ALTER TABLE `orden_ventas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orden_ventas_codigo_unique` (`codigo`),
  ADD KEY `orden_ventas_cliente_id_foreign` (`cliente_id`),
  ADD KEY `orden_ventas_configuracion_pago_id_foreign` (`configuracion_pago_id`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permisos_role_id_foreign` (`role_id`),
  ADD KEY `permisos_modulo_id_foreign` (`modulo_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productos_categoria_id_foreign` (`categoria_id`);

--
-- Indices de la tabla `producto_imagens`
--
ALTER TABLE `producto_imagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_imagens_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sedes`
--
ALTER TABLE `sedes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sede_users`
--
ALTER TABLE `sede_users`
  ADD KEY `sede_users_sede_id_foreign` (`sede_id`),
  ADD KEY `fk_sede_users_user` (`user_id`);

--
-- Indices de la tabla `solicitud_detalles`
--
ALTER TABLE `solicitud_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `solicitud_detalles_solicitud_producto_id_foreign` (`solicitud_producto_id`);

--
-- Indices de la tabla `solicitud_productos`
--
ALTER TABLE `solicitud_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `solicitud_productos_cliente_id_foreign` (`cliente_id`),
  ADD KEY `solicitud_productos_sede_id_foreign` (`sede_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_usuario_unique` (`usuario`),
  ADD UNIQUE KEY `users_correo_unique` (`correo`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `configuracion_pagos`
--
ALTER TABLE `configuracion_pagos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `notificacions`
--
ALTER TABLE `notificacions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `orden_ventas`
--
ALTER TABLE `orden_ventas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto_imagens`
--
ALTER TABLE `producto_imagens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `sedes`
--
ALTER TABLE `sedes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `solicitud_detalles`
--
ALTER TABLE `solicitud_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solicitud_productos`
--
ALTER TABLE `solicitud_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `clientes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD CONSTRAINT `detalle_ventas_orden_venta_id_foreign` FOREIGN KEY (`orden_venta_id`) REFERENCES `orden_ventas` (`id`),
  ADD CONSTRAINT `detalle_ventas_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  ADD CONSTRAINT `historial_accions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `notificacion_users`
--
ALTER TABLE `notificacion_users`
  ADD CONSTRAINT `notificacion_users_notificacion_id_foreign` FOREIGN KEY (`notificacion_id`) REFERENCES `notificacions` (`id`),
  ADD CONSTRAINT `notificacion_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `orden_ventas`
--
ALTER TABLE `orden_ventas`
  ADD CONSTRAINT `orden_ventas_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `orden_ventas_configuracion_pago_id_foreign` FOREIGN KEY (`configuracion_pago_id`) REFERENCES `configuracion_pagos` (`id`);

--
-- Filtros para la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `permisos_modulo_id_foreign` FOREIGN KEY (`modulo_id`) REFERENCES `modulos` (`id`),
  ADD CONSTRAINT `permisos_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `producto_imagens`
--
ALTER TABLE `producto_imagens`
  ADD CONSTRAINT `producto_imagens_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `sede_users`
--
ALTER TABLE `sede_users`
  ADD CONSTRAINT `fk_sede_users_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sede_users_sede_id_foreign` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`),
  ADD CONSTRAINT `sede_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `solicitud_detalles`
--
ALTER TABLE `solicitud_detalles`
  ADD CONSTRAINT `solicitud_detalles_solicitud_producto_id_foreign` FOREIGN KEY (`solicitud_producto_id`) REFERENCES `solicitud_productos` (`id`);

--
-- Filtros para la tabla `solicitud_productos`
--
ALTER TABLE `solicitud_productos`
  ADD CONSTRAINT `solicitud_productos_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `solicitud_productos_sede_id_foreign` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`);

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
