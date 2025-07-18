<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Diario</title>
    <style type="text/css">
        @page {
            header: page-header;
        }

        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .page-break {
            page-break-after: always;
        }

        /* Estilo del encabezado */
        .container-header {
            min-width: 100%;
            padding: 20px 0;
        }

        .page-header {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            border: 1px solid #000;
            padding: 10px 30px;
            width: 80%;
            margin: 0 auto;
            height: 80px;
        }

        /* Portada */
        .cover-page {
            text-align: center;
            width: 100%;
            height: 100vh;
        }

        .cover-page.cover-page.cover-page--with-header {
            padding: 40px 60px;
        }

        .cover-page_header--logo {
            width: 50%;
            text-align: center;
            padding: 10px;
        }

        .cover-page_header--logo img {
            width: 250px;
            height: auto;
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            object-position: center;
            margin: 0 auto;
            display: block;
        }

        .cover-page_title {
            padding: 30px 10px 10px 10px;
            text-align: center;
        }

        .cover-page_title span {
            font-size: 30px;
            font-weight: bold;
        }

        .cover-page_title p {
            font-size: 24px;
            font-weight: bold;
            margin-top: 10px;
        }

        .cover-page_title p span {
            font-size: 24px;
            font-weight: bold;
        }

        .cover-page_subtitle {
            padding: 10px 10px;
            text-align: center;
        }

        .cover-page_subtitle p {
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
        }

        .cover-page_subtitle p span {
            font-size: 20px;
            font-weight: bold;
        }

        .cover-page_footer {
            padding: 10px 10px;
            text-align: center;
        }

        .cover-page_footer img {
            width: 350px;
            height: auto;
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            object-position: center;
            margin: 0 auto;
            display: block;
        }



        .section-title {
            font-weight: bold;
            color: #FFFFFF;
            font-size: 1rem;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table td {
            padding: 4px 8px;
            vertical-align: top;
            font-size: .825rem;
        }

        .data-table.inpections td {
            width: 25%;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .data-table.inpections .inspections-title {
            font-weight: bold;
            color: #000;
            font-size: .875rem;
            text-transform: uppercase;
            font-weight: bold;
            padding-right: 10px;
            text-align: left;
            width: 40%;
        }

        .data-table.inpections .inspections-text {
            color: #555;
            font-size: .875rem;
            font-weight: normal;
            word-break: break-word;
            line-height: 1.5;
            text-align: left;
            width: 60%;
        }

        .bold {
            font-weight: bold;
        }

        .green {
            color: green;
            font-weight: bold;
        }

        .red {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <!-- Portada -->
    <table class="cover-page cover-page--with-header" style="margin-top: 40px;">
        <tr class="cover-page_header">
            <td class="cover-page_header--logo">
                <img src="{{ public_path('assets/media/resources/report/logo.png') }}" alt="Logo">
            </td>
            <td class="cover-page_header--logo">
                <img src="{{ public_path('assets/media/resources/report/logo.png') }}" alt="Logo">
            </td>
        </tr>
    </table>
    <table class="cover-page" style="margin-top: 40px;">
        <tr class="cover-page_title">
            <td colspan="2">
                <p>
                    Administración de Puntos de Control
                </p>
            </td>
        </tr>
    </table>
    <table class="cover-page" style="margin-top: 40px;">
        <tr class="cover-page_subtitle">
            <td colspan="2">
                <p>
                    Reporte Diario de Actividades
                </p>
            </td>
        </tr>
        <tr class="cover-page_subtitle">
            <td colspan="2">
                <p>
                    Control Cero
                </p>
            </td>
        </tr>
        <tr class="cover-page_subtitle">
            <td colspan="2">
                <p>
                    15 - 05 - 2025
                </p>
            </td>
        </tr>
    </table>
    <table class="cover-page" style="margin-top: 40px;">
        <tr class="cover-page_footer">
            <td colspan="2">
                <img src="{{ public_path('assets/media/resources/report/logo_sen.jpg') }}" alt="Logo">
            </td>
        </tr>
    </table>
    <br>
    <table class="page-break"></table>
    @if (count($inspections) > 0)
        @foreach ($inspections as $inspection)
            <htmlpageheader name="page-header">
                <table class="container-header">
                    <tr>
                        <table class="page-header">
                            <tr>
                                <td width="20%" style="text-align: left; border-right: 1px solid #000;">
                                    <img src="{{ public_path('assets/media/resources/report/logo.png') }}"
                                        alt="Logo" width="100">
                                </td>
                                <td width="60%" style="text-align: center;">
                                    <p
                                        style="font-size: 16px; font-weight: bold; text-align: center; text-transform: uppercase;">
                                        Reporte de inspección
                                    </p>
                                </td>
                                <td width="20%" style="text-align: right; border-left: 1px solid #000;">
                                    <img src="{{ public_path('assets/media/resources/report/mem4.jpg') }}"
                                        alt="Logo" width="100">
                                </td>
                            </tr>
                        </table>
                    </tr>
                </table>
            </htmlpageheader>
            <table style="width: 100%; padding: 5px 50px;">
                <tr>
                    <!-- Datos Generales -->
                    <td colspan="4" style="font-size: 18px; font-weight: bold; text-transform: uppercase; text-align: center; background-color: #354C9C; color: #FFFFFF; padding: 5px; vertical-align: center;">
                        <p class="section-title">
                            1. DATOS GENERALES
                        </p>
                    </td>
                </tr>
            </table>
            <table style="width: 100%; padding: 0px 40px; margin-top: 10px;">
                <tr>
                    <td>
                        <table class="data-table inpections" style="padding: 0px 20px; width: 100%;">
                            <tr>
                                <td class="bold inspections-title">
                                    N° Serie:
                                </td>
                                <td class="inspections-text">
                                    {{ $inspection->folio }}
                                </td>
                                <td class="bold inspections-title">Dirigido a:</td>
                                <td class="inspections-text">
                                    {{ $inspection->targeted->name }}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold inspections-title">Lugar de Inspección:</td>
                                <td class="inspections-text">
                                    {{ $inspection->checkpoint->name }}
                                </td>
                                <td class="bold inspections-title">Tipo de Vehículo:</td>
                                <td class="inspections-text">
                                    {{ $inspection->targeted->targeted->name }}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold inspections-title">Responsable de Inspección:</td>
                                <td class="inspections-text">
                                    {{ $inspection->user->fullname }}
                                </td>
                                <td class="bold inspections-title">Placa:</td>
                                <td class="inspections-text"></td>
                            </tr>
                            <tr>
                                <td class="bold inspections-title">Fecha:</td>
                                <td class="inspections-text">
                                    {{ $inspection->date }}
                                </td>
                                <td class="bold inspections-title">Conductor:</td>
                                <td class="inspections-text"></td>

                            </tr>
                            <tr>
                                <td class="bold inspections-title">Hora:</td>
                                <td class="inspections-text">
                                    {{ $inspection->hour }}
                                </td>
                                <td class="bold inspections-title">Fotocheck:</td>
                                <td class="inspections-text"></td>

                            </tr>
                            <tr>
                                <td class="bold inspections-title">Convoys:</td>
                                <td class="inspections-text">
                                    {{ $inspection->convoy->convoy }}
                                </td>
                                <td class="bold inspections-title">Proveedor:</td>
                                <td class="inspections-text">
                                    {{ $inspection->enterpriseSupplier->name }}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold inspections-title">Cantidad de Unidades:</td>
                                <td class="inspections-text">
                                    {{ $inspection->convoy->quantity_light_units }} +
                                    {{ $inspection->convoy->quantity_heavy_units }}
                                </td>
                                <td class="bold inspections-title">Transportista:</td>
                                <td class="inspections-text">
                                    {{ $inspection->enterpriseTransport->name }}
                                </td>
                            </tr>

                            <tr>
                                <td class="bold inspections-title">Supervisor:</td>
                                <td class="inspections-text">
                                    {{ $inspection->user->fullname }}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold inspections-title">Fotocheck:</td>
                                <td class="inspections-text"></td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </table>
            <table style="width: 100%; padding: 5px 50px;">
                <tr>
                    <!-- Datos de Inspección -->
                    <td style="font-size: 18px; font-weight: bold; text-transform: uppercase; text-align: center; background-color: #354C9C; color: #FFFFFF; padding: 5px; vertical-align: center;">
                        <p class="section-title">
                            2. DATOS DE INSPECCIÓN
                        </p>
                    </td>
                </tr>
            </table>
            <table style="width: 100%; padding: 0px 40px; margin-top: 10px;">
                <tr>
                    <td>
                        <table class="data-table inpections" style="padding: 0px 20px; width: 100%;">
                            <tr>
                                <td class="bold inspections-title">Tipo de Inspección:</td>
                                <td class="inspections-text">
                                    {{ $inspection->inspectionType->name }}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold inspections-title">Inspección Específica:</td>
                                <td class="inspections-text">
                                    @foreach ($inspection->groupedEvidencesByCategory() as $item)
                                        <span>
                                            - {{ $item }} <br>
                                        </span>
                                    @endforeach
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table style="width: 100%; padding: 5px 50px;">
                <tr>
                    <!-- Descripción -->
                    <td style="font-size: 18px; font-weight: bold; text-transform: uppercase; text-align: center; background-color: #354C9C; color: #FFFFFF; padding: 5px; vertical-align: center;">
                        <p class="section-title">
                            3. DESCRIPCIÓN
                        </p>
                    </td>
                </tr>
            </table>
            <table style="width: 100%; padding: 0px 40px; margin-top: 10px;">
                <tr>
                    <td>
                        <table class="data-table inpections" style="padding: 0px 20px; width: 100%;">
                            <tr>
                                <td>
                                    {{ $inspection->observation }}
                                </td>
                            </tr>
                    </td>
                </tr>
            </table>
            <table style="width: 100%; padding: 5px 50px;">
                <tr>
                    <!-- Condición -->
                    <td style="font-size: 18px; font-weight: bold; text-transform: uppercase; text-align: center; background-color: #354C9C; color: #FFFFFF; padding: 5px; vertical-align: center;">
                        <p class="section-title">
                            4. CONDICIÓN
                        </p>
                    </td>
                </tr>
            </table>
            <table style="width: 100%; padding: 0px 40px; margin-top: 10px;">
                <tr>
                    <td>
                        <table class="data-table inpections" style="padding: 0px 20px; width: 100%;">
                            <tr>
                                <td></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table class="page-break"></table>
            @foreach ($inspection->groupedEvidencesByCategory() as $item)
                <table style="width: 100%; padding: 30px 50px;">
                    <tr>
                        <td
                            style="text-align: center; font-weight: bold; font-size: 16px; text-transform: uppercase; margin-top: 20px; background-color: #354C9C; color: #FFFFFF; padding: 5px; vertical-align: center;">
                            {{ $item }}
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; padding: 0px 40px; margin-top: 10px;">
                    <tr>
                        <td
                            style="border-bottom: 1px solid #000; padding: 10px 0; margin: 0; font-weight: bold; text-align: center; background-color: #f0f0f0;">
                            Evidencia
                        </td>
                        <td
                            style="border-bottom: 1px solid #000; padding: 10px 0; margin: 0; font-weight: bold; text-align: center; background-color: #f0f0f0;">
                            Condición
                        </td>
                        <td
                            style="border-bottom: 1px solid #000; padding: 10px 0; margin: 0; font-weight: bold; text-align: center; background-color: #f0f0f0;">
                            Observaciones
                        </td>
                        <td
                            style="border-bottom: 1px solid #000; padding: 10px 0; margin: 0; font-weight: bold; text-align: center; background-color: #f0f0f0;">
                            Evidencia 1
                        </td>
                        <td
                            style="border-bottom: 1px solid #000; padding: 10px 0; margin: 0; font-weight: bold; text-align: center; background-color: #f0f0f0;">
                            Evidencia 2
                        </td>
                    </tr>
                    @php $i = 0; @endphp
                    @foreach ($inspection->evidences as $iteration => $relationship)
                        @if ($relationship->evidence->categoryName() == $item)
                            @php $i++; @endphp
                            @if ($i > 5)
                                @php $i = 0; @endphp

                                <table class="page-break "></table>
                                <table style="padding-top: 50px;"></table>
                                <tr>
                                    <td
                                        style="border-bottom: 1px solid #000; padding: 10px 0; margin: 0; font-weight: bold; text-align: center; background-color: #f0f0f0;">
                                        Evidencia
                                    </td>
                                    <td
                                        style="border-bottom: 1px solid #000; padding: 10px 0; margin: 0; font-weight: bold; text-align: center; background-color: #f0f0f0;">
                                        Condición
                                    </td>
                                    <td
                                        style="border-bottom: 1px solid #000; padding: 10px 0; margin: 0; font-weight: bold; text-align: center; background-color: #f0f0f0;">
                                        Observaciones
                                    </td>
                                    <td
                                        style="border-bottom: 1px solid #000; padding: 10px 0; margin: 0; font-weight: bold; text-align: center; background-color: #f0f0f0;">
                                        Evidencia 1
                                    </td>
                                    <td
                                        style="border-bottom: 1px solid #000; padding: 10px 0; margin: 0; font-weight: bold; text-align: center; background-color: #f0f0f0;">
                                        Evidencia 2
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td
                                    style="border-bottom: 1px solid #000; padding: 10px; width: 20%; text-align: justify;">
                                    {{ $relationship->evidence->subcategoryName() }} <br />
                                    <strong>{{ $relationship->evidence->name }}</strong>
                                </td>
                                <td
                                    style="border-bottom: 1px solid #000; padding: 10px; width: 10%; text-align: center;">

                                    @if ($relationship->state == 1)
                                        <span class="green">CONFORME</span>
                                    @elseif ($relationship->state == 2)
                                        <span class="red">NO CONFORME</span>
                                    @else
                                        <span class="bold">OPORTUNIDAD DE MEJORA</span>
                                    @endif
                                </td>
                                <td
                                    style="border-bottom: 1px solid #000; padding: 10px;  width: 25%; text-align: justify;">
                                    {{ $relationship->observations }}
                                </td>
                                <td
                                    style="border-bottom: 1px solid #000; padding: 10px; width: 22%; text-align: center;">
                                    <img src="{{ public_path($relationship->evidence_one) }}" alt="Evidence Image"
                                        style="width: 120px; height: 120px !important; max-width: 100%; max-height: 100%; object-fit: contain; object-position: center;">
                                </td>
                                <td
                                    style="border-bottom: 1px solid #000; padding: 10px; width: 23%; text-align: center;">
                                    @if ($relationship->evidence_two)
                                        <img src="{{ public_path($relationship->evidence_two) }}"
                                            alt="Evidence Image"
                                            style="width: 120px; height: 120px !important; max-width: 100%; max-height: 100%; object-fit: contain; object-position: center;">
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </table>
                <table class="page-break"></table>
            @endforeach
        @endforeach
    @endif
    @if (count($dialogs) > 0)
        <table style="width: 100%; padding: 5px 50px; margin-top: 20px;">
            <tr>
                <td colspan="4"
                    style="font-size: 18px; font-weight: bold; text-transform: uppercase; text-align: center; margin: 5px 0; background-color: #354C9C; color: #FFFFFF; padding: 5px; vertical-align: center;">
                    Dialogos de Diarios
                </td>
            </tr>
        </table>
        @php $i = 0; @endphp
        @foreach ($dialogs as $itera => $pausa)
            @if ($i > 1)
                <table class="page-break"></table>
                <table style="padding-top: 50px;"></table>
                @php $i = 0; @endphp
            @endif
            @php $i++; @endphp
            <table style="width: 100%; padding: 5px 50px; margin-top: 10px; ">
                <tr style="background-color: #eee; font-weight: bold; text-transform: uppercase; font-size: 12px;">
                    <td style="padding: 10px; text-align: center;"><strong>Empresa Transportista</strong></td>
                    <td style="padding: 10px; text-align: center;"><strong>Empresa Proveedora</strong></td>
                    <td style="padding: 10px; text-align: center;"><strong>Supervisor</strong></td>
                </tr>
                <tr>
                    <td style="padding: 10px; text-align: center;">
                        {{ $pausa->enterpriseTransport->name }}
                    </td>
                    <td style="padding: 10px; text-align: center;">
                        {{ $pausa->enterpriseSupplier->name }}
                    </td>
                    <td style="padding: 10px; text-align: center;">
                        {{ $pausa->user->fullname }}
                    </td>
                </tr>
                <tr style="background-color: #eee; font-weight: bold; text-transform: uppercase; font-size: 12px;">
                    <td style="padding: 10px; text-align: center;"><strong>FECHA</strong></td>
                    <td style="padding: 10px; text-align: center;"><strong>Punto de Control</strong>
                    </td>
                    <td style="padding: 10px; text-align: center;"><strong>Tema</strong></td>
                </tr>
                <tr>
                    <td style="padding: 10px; text-align: center;">
                        {{ $pausa->date }}
                    </td>
                    <td style="padding: 10px; text-align: center;">
                        {{ $pausa->checkpoint->name }}
                    </td>
                    <td style="padding: 10px; text-align: center;">
                        {{ $pausa->topic }}
                    </td>
                </tr>
            </table>

            <table style="width: 100%; padding: 5px 50px; margin-top: 10px; margin-bottom: 10px; ">
                <thead>
                    <tr style="background-color: #eee; font-weight: bold; text-transform: uppercase; font-size: 12px;">
                        <th style="padding: 10px; text-align: center;">Hora</th>
                        <th style="padding: 10px; text-align: center;">Cant. Participantes</th>
                        <th style="padding: 10px; text-align: center;">Foto Participación</th>
                        <th style="padding: 10px; text-align: center;">Fotografía Descriptiva</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;">{{ $pausa->hour }}</td>
                        <td style="text-align: center;">{{ $pausa->participants }}</td>
                        <td style="text-align: center;">
                            <img src="{{ public_path($pausa->photo_one) }}" alt="Evidence Image"
                                style="width: 120px; height: 120px !important; max-width: 100%; max-height: 100%; object-fit: contain; object-position: center;">
                        </td>
                        <td style="text-align: center;">
                            <img src="{{ public_path($pausa->photo_two) }}" alt="Evidence Image"
                                style="width: 120px; height: 120px !important; max-width: 100%; max-height: 100%; object-fit: contain; object-position: center;">
                        </td>
                    </tr>
                </tbody>
            </table>
        @endforeach
        <table class="page-break"></table>
    @endif
    @if (count($breaks) > 0)
        <table style="width: 100%; padding: 5px 50px; margin-top: 20px;">
            <tr>
                <td colspan="4"
                    style="font-size: 18px; font-weight: bold; text-transform: uppercase; text-align: center; margin: 5px 0; background-color: #354C9C; color: #FFFFFF; padding: 5px; vertical-align: center;">
                    Pausas Activas
                </td>
            </tr>
        </table>
        @php $i = 0; @endphp
        @foreach ($breaks as $itera => $pausa)
            @if ($i > 1)
                <table class="page-break"></table>
                <table style="padding-top: 50px;"></table>
                @php $i = 0; @endphp
            @endif
            @php $i++; @endphp
            <table style="width: 100%; padding: 5px 50px; margin-top: 10px; ">
                <tr style="background-color: #eee; font-weight: bold; text-transform: uppercase; font-size: 12px;">
                    <td style="padding: 10px; text-align: center;"><strong>Empresa Transportista</strong></td>
                    <td style="padding: 10px; text-align: center;"><strong>Empresa Proveedora</strong></td>
                    <td style="padding: 10px; text-align: center;"><strong>Supervisor</strong></td>
                </tr>
                <tr>
                    <td style="padding: 10px; text-align: center;">
                        {{ $pausa->enterpriseTransport->name }}
                    </td>
                    <td style="padding: 10px; text-align: center;">
                        {{ $pausa->enterpriseSupplier->name }}
                    </td>
                    <td style="padding: 10px; text-align: center;">
                        {{ $pausa->user->fullname }}
                    </td>
                </tr>
                <tr style="background-color: #eee; font-weight: bold; text-transform: uppercase; font-size: 12px;">
                    <td style="padding: 10px; text-align: center;"><strong>FECHA</strong></td>
                    <td style="padding: 10px; text-align: center;" colspan="2"><strong>Punto de Control</strong>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px; text-align: center;">
                        {{ $pausa->date }}
                    </td>
                    <td style="padding: 10px; text-align: center;" colspan="2">
                        {{ $pausa->checkpoint->name }}
                    </td>
                </tr>
            </table>

            <table style="width: 100%; padding: 5px 50px; margin-top: 10px; margin-bottom: 10px; ">
                <thead>
                    <tr style="background-color: #eee; font-weight: bold; text-transform: uppercase; font-size: 12px;">
                        <th style="padding: 10px; text-align: center;">Hora</th>
                        <th style="padding: 10px; text-align: center;">Cant. Participantes</th>
                        <th style="padding: 10px; text-align: center;">Foto Participación</th>
                        <th style="padding: 10px; text-align: center;">Fotografía Descriptiva</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;">{{ $pausa->hour }}</td>
                        <td style="text-align: center;">{{ $pausa->participants }}</td>
                        <td style="text-align: center;">
                            <img src="{{ public_path($pausa->photo_one) }}" alt="Evidence Image"
                                style="width: 120px; height: 120px !important; max-width: 100%; max-height: 100%; object-fit: contain; object-position: center;">
                        </td>
                        <td style="text-align: center;">
                            <img src="{{ public_path($pausa->photo_two) }}" alt="Evidence Image"
                                style="width: 120px; height: 120px !important; max-width: 100%; max-height: 100%; object-fit: contain; object-position: center;">
                        </td>
                    </tr>
                </tbody>
            </table>
        @endforeach
        <table class="page-break"></table>
    @endif
    @if (count($alcoholTests) > 0)
        <table style="width: 100%; padding: 5px 50px; margin-top: 20px;">
            <tr>
                <td colspan="4"
                    style="font-size: 18px; font-weight: bold; text-transform: uppercase; text-align: center; margin: 5px 0; background-color: #354C9C; color: #FFFFFF; padding: 5px; vertical-align: center;">
                    Pruebas de Alcohol
                </td>
            </tr>
        </table>
        @php $i = 0; @endphp
        @foreach ($alcoholTests as $itera => $pausa)
            @if ($i > 1)
                <table class="page-break"></table>
                <table style="padding-top: 50px;"></table>
                @php $i = 0; @endphp
            @endif
            @php $i++; @endphp
            <table style="width: 100%; padding: 5px 50px; margin-top: 10px; ">
                <tr style="background-color: #eee; font-weight: bold; text-transform: uppercase; font-size: 12px;">
                    <td style="padding: 10px; text-align: center;"><strong>Empresa Transportista</strong></td>
                    <td style="padding: 10px; text-align: center;"><strong>Empresa Proveedora</strong></td>
                    <td style="padding: 10px; text-align: center;"><strong>Supervisor</strong></td>
                </tr>
                <tr>
                    <td style="padding: 10px; text-align: center;">
                        {{ $pausa->enterpriseTransport->name }}
                    </td>
                    <td style="padding: 10px; text-align: center;">
                        {{ $pausa->enterpriseSupplier->name }}
                    </td>
                    <td style="padding: 10px; text-align: center;">
                        {{ $pausa->user->fullname }}
                    </td>
                </tr>
                <tr style="background-color: #eee; font-weight: bold; text-transform: uppercase; font-size: 12px;">
                    <td style="padding: 10px; text-align: center;"><strong>FECHA</strong></td>
                    <td style="padding: 10px; text-align: center;"><strong>Punto de Control</strong>
                    </td>
                    <td style="padding: 10px; text-align: center;"><strong>Colaborador</strong></td>
                </tr>
                <tr>
                    <td style="padding: 10px; text-align: center;">
                        {{ $pausa->date }}
                    </td>
                    <td style="padding: 10px; text-align: center;">
                        {{ $pausa->checkpoint->name }}
                    </td>
                    <td style="padding: 10px; text-align: center;">
                        {{ $pausa->employee->fullname }}
                    </td>
                </tr>
            </table>

            <table style="width: 100%; padding: 5px 50px; margin-top: 10px; margin-bottom: 10px; ">
                <thead>
                    <tr style="background-color: #eee; font-weight: bold; text-transform: uppercase; font-size: 12px;">
                        <th style="padding: 10px; text-align: center;">Hora</th>
                        <th style="padding: 10px; text-align: center;">Resultado</th>
                        <th style="padding: 10px; text-align: center;">Estado</th>
                        <th style="padding: 10px; text-align: center;">Foto Participación</th>
                        <th style="padding: 10px; text-align: center;">Fotografía Descriptiva</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;">{{ $pausa->hour }}</td>
                        <td style="text-align: center;">{{ $pausa->result }}</td>
                        <td style="text-align: center;">
                            @if ($pausa->state == 1)
                                <span class="red">POSITIVO</span>
                            @elseif ($pausa->state == 2)
                                <span class="green">NEGATIVO</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <img src="{{ public_path($pausa->photo_one) }}" alt="Evidence Image"
                                style="width: 120px; height: 120px !important; max-width: 100%; max-height: 100%; object-fit: contain; object-position: center;">
                        </td>
                        <td style="text-align: center;">
                            <img src="{{ public_path($pausa->photo_two) }}" alt="Evidence Image"
                                style="width: 120px; height: 120px !important; max-width: 100%; max-height: 100%; object-fit: contain; object-position: center;">
                        </td>
                    </tr>
                </tbody>
            </table>
        @endforeach
        <table class="page-break"></table>
    @endif
    @if (count($gpsControls) > 0)
        <table style="width: 100%; padding: 5px 50px; margin-top: 20px;">
            <tr>
                <td colspan="4"
                    style="font-size: 18px; font-weight: bold; text-transform: uppercase; text-align: center; margin: 5px 0; background-color: #354C9C; color: #FFFFFF; padding: 5px; vertical-align: center;">
                    Control GPS
                </td>
            </tr>
        </table>
        @php $i = 0; @endphp
        @foreach ($gpsControls as $itera => $pausa)
            @if ($i > 1)
                <table class="page-break"></table>
                <table style="padding-top: 50px;"></table>
                @php $i = 0; @endphp
            @endif
            @php $i++; @endphp
            <table style="width: 100%; padding: 5px 50px; margin-top: 10px; ">
                <tr style="background-color: #eee; font-weight: bold; text-transform: uppercase; font-size: 12px;">
                    <td style="padding: 10px; text-align: center;"><strong>Empresa Transportista</strong></td>
                    <td style="padding: 10px; text-align: center;"><strong>Empresa Proveedora</strong></td>
                    <td style="padding: 10px; text-align: center;"><strong>Supervisor</strong></td>
                </tr>
                <tr>
                    <td style="padding: 10px; text-align: center;">
                        {{ $pausa->enterpriseTransport->name }}
                    </td>
                    <td style="padding: 10px; text-align: center;">
                        {{ $pausa->enterpriseSupplier->name }}
                    </td>
                    <td style="padding: 10px; text-align: center;">
                        {{ $pausa->user->fullname }}
                    </td>
                </tr>
                <tr style="background-color: #eee; font-weight: bold; text-transform: uppercase; font-size: 12px;">
                    <td style="padding: 10px; text-align: center;"><strong>FECHA</strong></td>
                    <td style="padding: 10px; text-align: center;"><strong>Punto de Control</strong>
                    </td>
                    <td style="padding: 10px; text-align: center;"><strong>Tipo</strong></td>
                </tr>
                <tr>
                    <td style="padding: 10px; text-align: center;">
                        {{ $pausa->date }}
                    </td>
                    <td style="padding: 10px; text-align: center;">
                        {{ $pausa->checkpoint->name }}
                    </td>
                    <td style="padding: 10px; text-align: center;">
                        @if ($pausa->option == 1)
                            <span>VELOCIDAD</span>
                        @elseif ($pausa->option == 2)
                            <span class="red">UBICACION</span>
                        @endif
                    </td>
                </tr>
            </table>

            <table style="width: 100%; padding: 5px 50px; margin-top: 10px; margin-bottom: 10px; ">
                <thead>
                    <tr style="background-color: #eee; font-weight: bold; text-transform: uppercase; font-size: 12px;">
                        <th style="padding: 10px; text-align: center;">Hora</th>
                        <th style="padding: 10px; text-align: center;">ESTADO</th>
                        <th style="padding: 10px; text-align: center;">OBSERVACION</th>
                        <th style="padding: 10px; text-align: center;">Foto Participación</th>
                        <th style="padding: 10px; text-align: center;">Fotografía Descriptiva</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;">{{ $pausa->hour }}</td>
                        <td style="text-align: center;">
                            @if ($pausa->state == 1)
                                <span class="green">CONFORME</span>
                            @elseif ($pausa->state == 2)
                                <span class="red">NO CONFORME</span>
                            @else
                                <span class="bold">OPORTUNIDAD DE MEJORA</span>
                            @endif
                        </td>
                        <td style="text-align: center;">{{ $pausa->observation }}</td>
                        <td style="text-align: center;">
                            <img src="{{ public_path($pausa->photo_one) }}" alt="Evidence Image"
                                style="width: 120px; height: 120px !important; max-width: 100%; max-height: 100%; object-fit: contain; object-position: center;">
                        </td>
                        <td style="text-align: center;">
                            <img src="{{ public_path($pausa->photo_two) }}" alt="Evidence Image"
                                style="width: 120px; height: 120px !important; max-width: 100%; max-height: 100%; object-fit: contain; object-position: center;">
                        </td>
                    </tr>
                </tbody>
            </table>
        @endforeach
        <table class="page-break"></table>
    @endif

    <table style="width: 100%; padding: 5px 50px; margin-top: 20px;">
        <tr>
            <td colspan="4"
                style="font-size: 18px; font-weight: bold; text-transform: uppercase; text-align: center; margin: 5px 0; background-color: #354C9C; color: #FFFFFF; padding: 5px; vertical-align: center;">
                Consolidado de Actividades Diarias
            </td>
        </tr>
    </table>

    <table style="width: 100%; padding: 5px 50px; margin-top: 10px; ">
        <tr style="background-color: #eee; font-weight: bold; text-transform: uppercase; font-size: 12px;">
            <td style="padding: 10px; text-align: center;"><strong>Actividad</strong></td>
            <td style="padding: 10px; text-align: center;"><strong>Cantidad</strong></td>
        </tr>
        @if (count($inspections) > 0)
            <tr>
                <td style="padding: 10px; text-align: center;">Inspecciones</td>
                <td style="padding: 10px; text-align: center;">{{ count($inspections) }}</td>
            </tr>
        @endif
        @if (count($dialogs) > 0)
            <tr>
                <td style="padding: 10px; text-align: center;">Dialogos Diarios</td>
                <td style="padding: 10px; text-align: center;">{{ count($dialogs) }}</td>
            </tr>
        @endif
        @if (count($breaks) > 0)
            <tr>
                <td style="padding: 10px; text-align: center;">Pausas Activas</td>
                <td style="padding: 10px; text-align: center;">{{ count($breaks) }}</td>
            </tr>
        @endif
        @if (count($alcoholTests) > 0)
            <tr>
                <td style="padding: 10px; text-align: center;">Pruebas de Alcohol</td>
                <td style="padding: 10px; text-align: center;">{{ count($alcoholTests) }}</td>
            </tr>
        @endif
        @if (count($gpsControls) > 0)
            <tr>
                <td style="padding: 10px; text-align: center;">Control GPS</td>
                <td style="padding: 10px; text-align: center;">{{ count($gpsControls) }}</td>
            </tr>
        @endif
    </table>

</body>

</html>
