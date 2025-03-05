<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Comprobante</title>
    <style>
        html,
        body {
            color: black;
        }

        .bg-black {
            background: black;
            color: white;
        }

        .text-md {
            font-size: 1.3em;
        }

        b {
            font-size: 1.15em;
        }

        table {
            border-collapse: collapse;
        }

        table tbody tr td {
            padding: 5px;
        }
    </style>
</head>

<body>
    <h1>Nueva solicitud de producto</h1>
    <p>{!! $datos['mensaje'] !!}</p>
    <table border="1">
        <tbody>
            <tr>
                <td><b>Código solicitud: </b> {{ $datos["solicitudProducto"]->codigo_solicitud }}</td>
            </tr>
            <tr>
                <td><b>Nombre Producto: </b> {{ $datos["solicitudProducto"]->solicitudDetalles[0]->nombre_producto }}</td>
            </tr>
            <tr>
                <td><b>Detalle Producto: </b> {{ $datos["solicitudProducto"]->solicitudDetalles[0]->detalle_producto }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
