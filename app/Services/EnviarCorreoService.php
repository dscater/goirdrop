<?php

namespace App\Services;

use App\Mail\NuevaOrdenVentaMail;
use App\Models\Configuracion;
use App\Models\OrdenVenta;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class EnviarCorreoService
{

    /**
     * Enviar correo de nueva orden de venta
     *
     * @param OrdenVenta $ordenVenta
     * @return void
     */
    public function nuevaOrdenVenta(OrdenVenta $ordenVenta): void
    {
        $configuracion = Configuracion::first();
        if ($configuracion) {
            $servidor_correo = $configuracion->conf_correos;
            Config::set(
                [
                    'mail.mailers.default' => $servidor_correo["driver"] ?? 'smtp',
                    'mail.mailers.smtp.host' => $servidor_correo["host"] ?? 'smtp.hostinger.com',
                    'mail.mailers.smtp.port' => $servidor_correo["puerto"] ?? '587',
                    'mail.mailers.smtp.encryption' => $servidor_correo["encriptado"] ?? 'tls',
                    'mail.mailers.smtp.username' => $servidor_correo["correo"] ?? 'mensaje@emsytsrl.com',
                    'mail.mailers.smtp.password' => $servidor_correo["password"] ?? '8Z@d>&kj^y',
                    'mail.from.address' => $servidor_correo["correo"] ?? 'mensaje@emsytsrl.com',
                    'mail.from.name' => $servidor_correo["nombre"] ?? 'GOIRDROP',
                ]
            );

            $mensaje = "Hola " . $ordenVenta->cliente->full_name . '<br/>';
            $mensaje .= 'Tu orden de venta fue registrada correctamente, nosotros nos comunicaremos contigo para finalizar la compra';

            $datos = [
                "mensaje" => $mensaje
            ];

            Mail::to($ordenVenta->cliente->correo)
                ->send(new NuevaOrdenVentaMail($datos));
        }
    }
}
