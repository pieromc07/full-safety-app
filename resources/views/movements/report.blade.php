<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Movimientos de Unidades</title>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        @page {
            margin: 50px 30px;
        }

        @page {
            header: page-header;
        }

        /* Estilo del encabezado */
        header {
            position: fixed;
            top: -40px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            width: 100%;
            height: 80px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 10px;
            border: 1px solid #dee2e6;
            text-align: center;
            vertical-align: middle;
        }

        .table-primary {
            background-color: #cfe2ff;
            color: #000;
            font-weight: bold;
        }

        /* Portada */
        .cover-page {
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-size: 24px;
            font-weight: bold;
        }

        .cover-page img {
            width: 500px;
            margin-bottom: 20px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

    <!-- Portada -->
    <div class="cover-page">
        <img src="{{ public_path('assets/media/resources/report/logo.png') }}" alt="Logo">
        <p>Servicio</p>
        <p>Reporte de Movimientos de Unidades</p>
        <p>{{ $title }}</p>
        <p>{{ $dates }}</p>
    </div>

    <!-- Salto de página -->
    <div class="page-break"></div>

    <!-- Encabezado -->
    <htmlpageheader name="page-header">
        <table width="100%">
            <tr width="100%">
                <td width="20%" style="text-align: left;">
                    <img src="{{ public_path('assets/media/resources/report/logo.png') }}" alt="Logo" width="100">
                </td>
                <td width="60%" style="text-align: center;">
                    <p style="font-size: 16px; font-weight: bold;"
                    >Reporte de Movimientos de Unidades</p>
                </td>
                <td width="20%" style="text-align: right;">
                    <img src="{{ public_path('assets/media/resources/report/mem4.jpg') }}" alt="Logo" width="100">
                </td>
            </tr>
        </table>
    </htmlpageheader>

    <div class="col-md-12">
        @foreach ($unitmovements as $unitmovement)
            <div style="page-break-inside: avoid;">
                <div style="margin-bottom: 20px;">
                    <table class="table">
                        <tbody>
                            <tr class="table-primary">
                                <th>Fecha</th>
                                <th>Semana</th>
                                <th>Punto de Control</th>
                                <th>Acción</th>
                                <th>Hora de llegada al PO</th>
                            </tr>
                            <tr>
                                <td>{{ date('d/m/Y', strtotime($unitmovement->date)) }}</td>
                                <td>{{ date('W', strtotime($unitmovement->date)) }}</td>
                                <td>{{ $unitmovement->checkpoint->name }}</td>
                                <td>{{ $unitmovement->direction == 1 ? 'Subida' : 'Bajada' }}</td>
                                <td>{{ $unitmovement->time_arrival ?? '-' }}</td>
                            </tr>
                            <tr class="table-primary">
                                <th>Estado de Convoy</th>
                                <th>Emp. Proveedora</th>
                                <th>Emp. Transportista</th>
                                <th>Administrador</th>
                                <th>Hora de salida del PO</th>
                            </tr>
                            <tr>
                                <td>{{ $unitmovement->convoy_state == 1 ? 'Cargado' : 'Vacío' }}</td>
                                <td>{{ $unitmovement->supplierEnterprise->name }}</td>
                                <td>{{ $unitmovement->transportEnterprise->name }}</td>
                                <td>{{ $unitmovement->user->fullname }}</td>
                                <td>{{ $unitmovement->time_departure ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>

</body>

</html>
