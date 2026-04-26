<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reporte de Inspección</title>
</head>

<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background:#e9ecef; margin:0; padding:20px;">

    <table width="650" align="center" cellspacing="0" cellpadding="0"
        style="background:white; border-radius:8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">


        <tr>
            <td style="padding:25px 30px 15px 30px;">
                <p style="margin:0; color:#333; font-size:15px; line-height:1.6;">
                    Estimado,
                </p>
                <p style="margin:12px 0 0 0; color:#555; font-size:15px; line-height:1.6;">
                    Les informo que se ha realizado una nueva inspección. A continuación encontrarán el detalle
                    completo:
                </p>
            </td>
        </tr>


        <tr>
            <td style="padding:0 30px 20px 30px;">
                <table width="100%" cellpadding="0" cellspacing="0"
                    style="background:#f8f9fa; border-radius:6px; padding:15px;">
                    <tr>
                        <td style="padding:8px;">
                            <span style="color:#666; font-size:13px;">Fecha de elaboración:</span>
                            <strong
                                style="color:#333; font-size:14px; display:block; margin-top:3px;">{{ $inspection->date }}</strong>
                        </td>
                        <td style="padding:8px;">
                            <span style="color:#666; font-size:13px;">Hora:</span>
                            <strong
                                style="color:#333; font-size:14px; display:block; margin-top:3px;">{{ $inspection->hour }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding:8px;">
                            <span style="color:#666; font-size:13px;">Tipo de Inspección:</span>
                            <strong
                                style="color:#333; font-size:14px; display:block; margin-top:3px;">{{ $inspection->inspectionType->name }}</strong>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>


        <tr>
            <td style="padding:0 30px 20px 30px;">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="padding:8px;">
                            <span style="color:#666; font-size:13px;">Punto de Control:</span>
                            <strong
                                style="color:#333; font-size:14px; display:block; margin-top:3px;">{{ $inspection->checkpoint->name }}</strong>
                        </td>
                        <td style="padding:8px;">
                            <span style="color:#666; font-size:13px;">Objetivo:</span>
                            <strong
                                style="color:#333; font-size:14px; display:block; margin-top:3px;">{{ $inspection->targeted->name }}</strong>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>


        @if (!empty($inspection->observation))
            <tr>
                <td style="padding:0 30px 20px 30px;">
                    <div style="background:#fff3cd; padding:15px; border-left:4px solid #ffc107; border-radius:6px;">
                        <strong style="color:#856404; font-size:13px;">Observación General:</strong>
                        <p style="margin:8px 0 0 0; color:#856404; font-size:14px; line-height:1.5;">
                            {{ $inspection->observation }}</p>
                    </div>
                </td>
            </tr>
        @endif


        <tr>
            <td style="padding:0 30px 20px 30px;">
                <h3
                    style="margin:0 0 12px 0; color:#333; font-size:16px; border-bottom:2px solid #667eea; padding-bottom:8px;">
                    Empresas Involucradas
                </h3>

                <table width="100%" cellpadding="12" cellspacing="0"
                    style="border:1px solid #e0e0e0; border-radius:6px;">
                    <tr style="background:#fafafa;">
                        <td width="30%" style="color:#666; font-size:13px; font-weight:600;">Proveedor:</td>
                        <td style="color:#333; font-size:14px;">
                            {{ $inspection->enterpriseSupplier->name }}
                            <br><span style="color:#888; font-size:12px;">RUC:
                                {{ $inspection->enterpriseSupplier->ruc }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="color:#666; font-size:13px; font-weight:600; border-top:1px solid #e0e0e0; padding-top:12px;">
                            Transporte:</td>
                        <td style="color:#333; font-size:14px; border-top:1px solid #e0e0e0; padding-top:12px;">
                            {{ $inspection->enterpriseTransport->name }}
                            <br><span style="color:#888; font-size:12px;">RUC:
                                {{ $inspection->enterpriseTransport->ruc }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>


        @if ($convoy)
            <tr>
                <td style="padding:0 30px 20px 30px;">
                    <h3
                        style="margin:0 0 12px 0; color:#333; font-size:16px; border-bottom:2px solid #667eea; padding-bottom:8px;">
                        Información del Convoy
                    </h3>

                    <table width="100%" cellpadding="12" cellspacing="0"
                        style="border:1px solid #e0e0e0; border-radius:6px; background:#fafafa;">
                        <tr>
                            <td width="30%" style="color:#666; font-size:13px; font-weight:600;">ID Convoy:</td>
                            <td style="color:#333; font-size:14px; font-weight:600;">{{ $convoy->convoy }}</td>
                        </tr>
                        <tr>
                            <td
                                style="color:#666; font-size:13px; font-weight:600; border-top:1px solid #e0e0e0; padding-top:12px;">
                                Estado:</td>
                            <td style="border-top:1px solid #e0e0e0; padding-top:12px;">
                                <span
                                    style="background:{{ $convoy->convoy_status == 1 ? '#d4edda' : '#f8d7da' }}; color:{{ $convoy->convoy_status == 1 ? '#28a745' : '#dc3545' }}; padding:4px 12px; border-radius:4px; font-size:13px; font-weight:600; display:inline-block;">
                                    {{ $convoy->convoy_status == 1 ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td
                                style="color:#666; font-size:13px; font-weight:600; border-top:1px solid #e0e0e0; padding-top:12px;">
                                Unidades Ligeras:</td>
                            <td style="color:#333; font-size:14px; border-top:1px solid #e0e0e0; padding-top:12px;">
                                {{ $convoy->quantity_light_units }}</td>
                        </tr>
                        <tr>
                            <td
                                style="color:#666; font-size:13px; font-weight:600; border-top:1px solid #e0e0e0; padding-top:12px;">
                                Unidades Pesadas:</td>
                            <td style="color:#333; font-size:14px; border-top:1px solid #e0e0e0; padding-top:12px;">
                                {{ $convoy->quantity_heavy_units }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        @endif


        <tr>
            <td style="padding:0 30px 20px 30px;">
                <h3
                    style="margin:0 0 12px 0; color:#333; font-size:16px; border-bottom:2px solid #667eea; padding-bottom:8px;">
                    Resultados de la Inspección
                </h3>

                @foreach ($evidences as $i => $ev)
                    <table width="100%" cellpadding="15" cellspacing="0"
                        style="border:1px solid #e0e0e0; border-radius:6px; margin-bottom:15px; background:white;">
                        <tr>
                            <td>
                                <div style="margin-bottom:10px;">
                                    <span
                                        style="background:#667eea; color:white; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600;">
                                        Item {{ $i + 1 }}
                                    </span>
                                </div>

                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td width="30%" style="color:#666; font-size:13px; padding:5px 0;">Categoría:
                                        </td>
                                        <td style="color:#333; font-size:14px; font-weight:600;">
                                            {{ $inspection->targeted->targeteds->first()->categoria ?? '---' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="color:#666; font-size:13px; padding:5px 0;">Condición:</td>
                                        <td>
                                            @php
                                                $states = [
                                                    1 => [
                                                        'text' => 'Conforme',
                                                        'color' => '#28a745',
                                                        'bg' => '#d4edda',
                                                    ],
                                                    2 => [
                                                        'text' => 'No Conforme',
                                                        'color' => '#dc3545',
                                                        'bg' => '#f8d7da',
                                                    ],
                                                    3 => [
                                                        'text' => 'Oportunidad de Mejora',
                                                        'color' => '#ffc107',
                                                        'bg' => '#fff3cd',
                                                    ],
                                                ];
                                                $state = $states[$ev->state] ?? [
                                                    'text' => '---',
                                                    'color' => '#666',
                                                    'bg' => '#e9ecef',
                                                ];
                                            @endphp
                                            <span
                                                style="background:{{ $state['bg'] }}; color:{{ $state['color'] }}; padding:5px 12px; border-radius:4px; font-size:13px; font-weight:600; display:inline-block;">
                                                {{ $state['text'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    @if ($ev->observation)
                                        <tr>
                                            <td style="color:#666; font-size:13px; padding:5px 0; vertical-align:top;">
                                                Observaciones:</td>
                                            <td style="color:#555; font-size:13px; line-height:1.5; padding:5px 0;">
                                                {{ $ev->observation }}
                                            </td>
                                        </tr>
                                    @endif
                                </table>


                                @php
                                    $imgs = [];
                                    $baseUrl = rtrim(config('app.url'), '/');
                                    if (!empty($ev->evidence_one)) {
                                        $imgs[] = $baseUrl . '/' . $ev->evidence_one;
                                    }
                                    if (!empty($ev->evidence_two)) {
                                        $imgs[] = $baseUrl . '/' . $ev->evidence_two;
                                    }
                                @endphp

                                <div style="margin-top:15px; padding-top:15px; border-top:1px solid #ddd;">
                                    <p style="margin:0 0 10px 0; color:#666; font-size:12px; font-weight:600;">📸
                                        Evidencias fotográficas:</p>

                                    @if (count($imgs) > 0)
                                        <table cellpadding="5" cellspacing="0">
                                            <tr>
                                                @foreach ($imgs as $img)
                                                    <td style="padding:5px;">
                                                        <div
                                                            style="width:140px; height:140px; border:2px solid #ddd; border-radius:6px; overflow:hidden; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                                            <img src="{{ $img }}"
                                                                style="width:100%; height:100%; object-fit:cover;">
                                                        </div>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        </table>
                                    @else
                                        <div
                                            style="padding:15px; background:#f8f9fa; border:1px dashed #ccc; border-radius:6px; text-align:center;">
                                            <span style="color:#888; font-size:13px;">Sin imágenes</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </table>
                @endforeach
            </td>
        </tr>


        <tr>
            <td style="padding:20px 30px 30px 30px;">
                <div style="border-top:2px solid #e0e0e0; padding-top:20px;">
                    <p style="margin:0; color:#555; font-size:14px; line-height:1.6;">
                        Saludos cordiales,
                    </p>
                    <p style="margin:10px 0 0 0; color:#333; font-size:15px; font-weight:600;">
                        {{ $user->fullname }}
                    </p>
                    <p style="margin:5px 0 0 0; color:#888; font-size:13px;">
                        Usuario: {{ $user->username }}
                    </p>
                    <p style="margin:5px 0 0 0; color:#888; font-size:13px;">
                        Inspections - Sistema de Control de Calidad
                    </p>
                </div>
            </td>
        </tr>
    </table>

</body>

</html>
