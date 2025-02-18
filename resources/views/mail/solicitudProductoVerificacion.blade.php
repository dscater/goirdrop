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

        table tfoot tr th,
        table thead tr th {
            background-color: black;
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
        }
    </style>
</head>

<body>
    <h1>Verificación de solicitud de producto</h1>
    <p>{!! $datos['mensaje'] !!}</p>
    <h3>Producto solicitado</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Detalle</th>
                <th>Links de Referencia</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datos['solicitudDetalles'] as $key => $item)
                <tr>
                    <td>{{ $item->nombre_producto }}</td>
                    <td>{{ $item->detalle_producto }}</td>
                    <td>{!! $item->links_referencia !!}</td>
                </tr>
            @endforeach
        </tbody>
        @if ($datos['total_precio'])
            <tfoot>
                <tr>
                    <th colspan="2">PRECIO DE COMPRA {{ $datos['abrev_moneda'] }}</th>
                    <th>{{ $datos['total_precio'] }}</th>
                </tr>
            </tfoot>
        @endif
    </table>
</body>

</html>
