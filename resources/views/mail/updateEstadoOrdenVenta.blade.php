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
    <h1>Estado de compra</h1>
    <p>{!! $datos['mensaje'] !!}</p>
    <h3>Detalle</h3>
    <table border="1">
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>P/U {{ $datos['abrev_moneda'] }}</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datos['detalleVenta'] as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->producto->nombre }}</td>
                    <td>{{ $item->producto->precio }}</td>
                    <td>{{ $item->cantidad }}</td>
                    <td>{{ $item->subtotal }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5">TOTAL {{ $datos['abrev_moneda'] }}</th>
                <th>{{ $datos['total'] }}</th>
            </tr>
        </tfoot>
    </table>
</body>

</html>
