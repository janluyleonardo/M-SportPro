
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Correo Electrónico de la Armada Nacional de Colombia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #bdbaba52;
        }
        .container {
            background-color: #ffffff;
            padding: 1%;
            width: 95%;
            max-width: 95%;
            margin: auto;
            box-shadow: 0px 0px 10px 1px;
            border-radius: 10px;
            margin-top: 10px;
            margin-bottom: 10px;
            display: grid;
            place-items: center;
        }

        .table {
            width: 100%;
            border: 1px solid #000000;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
            table-layout: fixed;
        }

        .table caption {
            font-size: 28px;
            text-transform: uppercase;
            font-weight: bold;
            margin: 8px 0px;
        }

        .table tr {
            background-color: #f8f8f8;
            border: 1px solid #272525;
        }

        .table th, .table td {
            font-size: 16px;
            padding: 8px;
            text-align: center;
        }

        .table thead th {
            text-transform: uppercase;
            background-color: #ddd;
        }

        .table tbody tr:hover {
            background-color: rgba(139, 139, 139, 0.2);
        }

        .table tbody td:hover {
            background-color: rgba(0, 0, 0, 0.3);
        }

        @media screen and (max-width: 600px) {
            .table {
                border: 0px;
            }

            .table caption {
                font-size: 22px;
            }

            .table thead {
                display: none;
            }

            .table tr {
                margin-bottom: 8px;
                border-bottom: 4px solid #7c7777;
                display: block;
            }

            .table th, .table td {
                font-size: 12px;
            }

            .table td {
                display: block;
                border-bottom: 1px solid #3b3a3a;
                text-align: right;
            }

            .table td:last-child {
                border-bottom: 0px;
            }

            .table td::before {
                content: attr(data-label);
                font-weight: bold;
                text-transform: uppercase;
                float: left;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://www.armada.mil.co/sites/default/files/styles/full_post_image/public/images/news/arc_azul.png" alt="Logo de la Armada Nacional de Colombia">
        </div>
        <div class="content">
            <h1>Notificación Solicitud de Traslado Institucional - Creación</h1>

            Reciba un cordial saludo
            <p>
                Por medio de este correo se le informa que un tripulante de su dependencia y/o Unidad ha solicitado
                la <strong>Solicitud de Traslado institucional en línea del Plan Traslado</strong> del período en curso en la <strong>{{env('APP_NAME')}}</strong>
                No. de Ticket/Código de solicitud: <strong></strong>.
                La Dirección de Gestión Humana de la Armada Nacional, con toda atención y el debido respeto
                solicita que de tramite y gestion de la solicitud en la <strong>{{env('APP_NAME')}}:</strong>
                <br>
            </p>

            <h2>Detalles de la Solicitud de traslado institucional</h2>

            <h2>{{__('General Information')}}</h2>
            <table  class="table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Jefatura</th>
                        <th>Correo</th>
                        <th>Radicado</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        {{-- <td data-label="Id">{{ $personnelRequest->head_personnel_id }}</td>
                        <td data-label="Jefatura">{{ $personnelRequest->leadership }}</td>
                        <td data-label="Correo">{{ $personnelRequest->email }}</td>
                        <td data-label="Observaciones">{{ $personnelRequest->number_file }}</td>
                        <td data-label="Radicado">{{ $personnelRequest->observations }}</td> --}}
                    </tr>
                </tbody>
            </table>
            <hr>
            {{-- <h2>{{__('Outgoing Personnel')}}</h2> --}}
            <table class="table">
                <thead>
                    <tr>
                        <th>{{__('Identification')}}</th>
                        <th>{{__('Grade')}}</th>
                        <th>{{__('Last Name')}}</th>
                        <th>{{__('First Name')}}</th>
                        <th>{{__('Current Position')}}</th>
                        <th>{{__('Unit Dependence')}}</th>
                        <th>{{__('Time Garrison')}}</th>
                        <th>{{__('Destination Unit')}}</th>
                        <th>{{__('Position to Fill')}}</th>
                        <th>{{__('Observations')}}</th>
                    </tr>
                </thead>
                {{-- @foreach($outgoingPersonnel as $outgoing) --}}
                    <tr>
                        {{-- <td>{{ $outgoing->identification }}</td>
                        <td>{{ $outgoing->grade }}</td>
                        <td>{{ $outgoing->last_name }}</td>
                        <td>{{ $outgoing->first_name }}</td>
                        <td>{{ $outgoing->current_position }}</td>
                        <td>{{ $outgoing->unit_dependence }}</td>
                        <td>{{ $outgoing->time_in_garrison }}</td>
                        <td>{{ $outgoing->destination_unit ?? '' }}</td>
                        <td>{{ $outgoing->position_to_fill ?? '' }}</td>
                        <td>{{ $outgoing->observations ?? '' }}</td> --}}
                    </tr>
                {{-- @endforeach --}}
            </table>
            <hr>
            <h2>{{__('Incoming Personnel')}}</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>{{__('Identification')}}</th>
                        <th>{{__('Grade')}}</th>
                        <th>{{__('Last Name')}}</th>
                        <th>{{__('First Name')}}</th>
                        <th>{{__('Actual Unit')}}</th>
                        <th>{{__('Destination unit')}}</th>
                        <th>{{__('Destination Dependency<')}}</th>
                        <th>{{__('Position to Fill')}}</th>
                        <th>{{__('Observations')}}</th>
                    </tr>
                </thead>
                {{-- @foreach($incomingPersonnel as $incoming) --}}
                    <tr>
                        {{-- <td>{{ $incoming->identification }}</td>
                        <td>{{ $incoming->grade }}</td>
                        <td>{{ $incoming->last_name }}</td>
                        <td>{{ $incoming->first_name }}</td>
                        <td>{{ $incoming->actual_unit }}</td>
                        <td>{{ $incoming->destination_unit }}</td>
                        <td>{{ $incoming->destination_dependency }}</td>
                        <td>{{ $incoming->position_to_fill }}</td>
                        <td>{{ $incoming->observations ?? '' }}</td> --}}
                    </tr>
                {{-- @endforeach --}}
            </table>
            <hr>
            <h2>{{__('Special Continuity Personnel')}}</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>{{__('Identification')}}</th>
                        <th>{{__('Grade')}}</th>
                        <th>{{__('Last Name')}}</th>
                        <th>{{__('First Name')}}</th>
                        <th>{{__('Current Position')}}</th>
                        <th>{{__('Time Garrison')}}</th>
                        <th>{{__('Observations')}}</th>
                    </tr>
                </thead>
                {{-- @foreach($specialPersonnel as $special) --}}
                    <tr>
                        {{-- <td>{{ $special->identification }}</td>
                        <td>{{ $special->grade }}</td>
                        <td>{{ $special->last_name }}</td>
                        <td>{{ $special->first_name }}</td>
                        <td>{{ $special->current_position }}</td>
                        <td>{{ $special->time_in_garrison }}</td>
                        <td>{{ $special->observations ?? '' }}</td> --}}
                    </tr>
                {{-- @endforeach --}}
            </table>
            <hr>
            <p>
                Respetuosamente.
            </p>
            <p>
                <strong>DIRECCIÓN DE GESTIÓN HUMANA</strong><br>
                <strong>Jefatura Desarrollo Humano</strong> <br>
                <strong>Carrera 54 N° 26 - 25 Edificio Fortaleza</strong><br>
                <strong>Tel: (1) 3692000 ext. 10014</strong>
            </p>
        </div>
        <div class="footer">
            <p>Armada Nacional de Colombia | Todos los derechos reservados | {{now()->format('Y')}}</p>
        </div>
    </div>
</body>
</html>
