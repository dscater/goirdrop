<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenVenta extends Model
{
    use HasFactory;

    protected $fillable = [
        "codigo",
        "cliente_id",
        "nombre_cliente",
        "apellidos_cliente",
        "cel",
        "estado_orden",
        "estado_pago",
        "configuracion_pago_id",
        "comprobante",
        "observacion",
        "status",
        "fecha_orden",
        "fecha_confirmacion",
    ];
}
