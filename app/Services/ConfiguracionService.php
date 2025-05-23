<?php

namespace App\Services;

use App\Models\Configuracion;
use App\Services\CargarArchivoService;
use Illuminate\Http\UploadedFile;

class ConfiguracionService
{

    public function __construct(private CargarArchivoService $cargarArchivoService) {}

    /**
     * Actualizar configuracion
     *
     * @param array $datos
     * @return Configuracion
     */
    public function actualizar(array $datos): Configuracion
    {
        $configuracion = Configuracion::first();
        if (!$configuracion) {
            $configuracion = Configuracion::create([
                "nombre_sistema" => $datos["nombre_sistema"],
                "alias" => $datos["alias"],
                "fono" =>  $datos["fono"],
                "dir" => $datos["dir"],
                "conf_correos" => $datos["conf_correos"],
                "conf_moneda" => $datos["conf_moneda"],
                "conf_captcha" => $datos["conf_captcha"],
            ]);
        } else {
            $configuracion->update([
                "nombre_sistema" => $datos["nombre_sistema"],
                "alias" => $datos["alias"],
                "fono" =>  $datos["fono"],
                "dir" => $datos["dir"],
                "conf_correos" => $datos["conf_correos"],
                "conf_moneda" => $datos["conf_moneda"],
                "conf_captcha" => $datos["conf_captcha"],
            ]);
        }

        // cargar logo
        if (!is_string($datos["logo"])) {
            $this->cargarLogo($configuracion, $datos["logo"]);
        }

        return $configuracion;
    }

    /**
     * Cargar logo
     *
     * @param Configuracion $configuracion
     * @param UploadedFile $file
     * @return void
     */
    private function cargarLogo(Configuracion $configuracion, UploadedFile $file): void
    {
        $nombre = "lg" . time();
        $configuracion->logo = $this->cargarArchivoService->cargarArchivo($file, public_path("imgs"), $nombre);
        $configuracion->save();
    }
}
