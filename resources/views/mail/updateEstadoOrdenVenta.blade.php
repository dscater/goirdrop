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

        .centreado {
            text-align: center;
        }

        .paddingy {
            padding-top: 6px;
            padding-bottom: 6px;
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
                    <td>{{ number_format($item->producto->precio_venta, 2, '.', ',') }}</td>
                    <td>{{ $item->cantidad }}</td>
                    <td>{{ number_format($item->subtotal, 2, '.', ',') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-center paddingy">{{ $datos['literal'] }}</td>
            </tr>
            <tr>
                <th colspan="4">TOTAL {{ $datos['abrev_moneda'] }}</th>
                <th>{{ number_format($datos['total'], 2, '.', ',') }}</th>
            </tr>
        </tfoot>
    </table>
</body>

</html>
