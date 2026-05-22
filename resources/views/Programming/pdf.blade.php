<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Programación de Partidos - {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</title>
  <link href="{{ public_path('css/pdf-imprimir.css') }}" rel="stylesheet">
  <style>
    @page {
      margin: 1cm;
    }
    body {
      @if(isset($base64Logo) && $base64Logo)
      background-image: url("{{ $base64Logo }}");
      @endif
      background-repeat: no-repeat;
      background-size:100%;
      background-position: 50% 50%;
    }
    .pdf-header-container {
      margin-bottom: 30px;
      width: 100%;
      border-bottom: 2px solid #f3f4f6;
      padding-bottom: 15px;
    }
    .match-card {
        border: 2px solid rgba(1, 142, 203, 0.5);
        border-radius: 15px;
        margin-bottom: 20px;
        background-color: rgba(255, 255, 255, 0.9);
        overflow: hidden;
    }
    .match-header {
        background-color: rgba(1, 142, 203, 0.7);
        color: white;
        padding: 8px 15px;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 14px;
        display: flex;
        justify-content: space-between;
    }
    .match-body {
        padding: 15px;
    }
    .team-container {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        margin-bottom: 10px;
    }
    .vs-badge {
        background-color: #f3f4f6;
        padding: 2px 10px;
        border-radius: 5px;
        font-size: 12px;
        margin: 0 15px;
        color: #6b7280;
    }
    .convocados-list {
        margin-top: 10px;
        font-size: 11px;
        color: #374151;
        border-top: 1px solid #e5e7eb;
        padding-top: 10px;
    }
    .convocados-title {
        font-weight: bold;
        color: #1f2937;
        margin-bottom: 5px;
        display: block;
    }
    .footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        text-align: center;
        font-size: 10px;
        color: #9ca3af;
    }
  </style>
</head>
<body>
  <div class="pdf-header-container">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="15%" align="left" valign="middle">
          @if(isset($base64Logo) && $base64Logo)
          <img src="{{ $base64Logo }}" alt="LOGO-club" width="90">
          @endif
        </td>
        <td width="85%" align="center" valign="middle">
          @php
            $displayClubName = isset($clubName) ? $clubName : 'Club Deportivo Jackeline FS';
            $showResolution = (Str::contains(Str::lower($displayClubName), 'jackeline') || Str::contains(Str::lower($displayClubName), 'jacqueline'));
          @endphp
          <h2 style="text-shadow: 2px 2px #FF0000 !important; margin: 0; padding: 0; font-size: 20px;">{{ Str::upper($displayClubName) }}</h2>
          @if($showResolution)
          <h5 class="resolucion" style="margin: 5px 0 0 0; padding: 0; font-weight: normal; font-size: 10px;">
            Resolución 175 del 13 de marzo de 2017, otorgada por el Instituto de Recreación y Deporte (IDRD).
          </h5>
          @endif
          <h5 style="margin: 10px 0 0 0; padding: 0; font-size: 14px;">
            <strong>PROGRAMACIÓN DE ENCUENTROS - {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d \d\e F \d\e Y') }}</strong>
          </h5>
        </td>
      </tr>
    </table>
  </div>
  <div style="height: 20px;"></div>

  @foreach($programming as $match)
    <div class="match-card">
        <div class="match-header" style="background-color: rgba(1, 142, 203, 0.7); color: white; padding: 10px 15px;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="color: white; font-weight: bold; text-transform: uppercase; font-size: 14px;">
                <tr>
                    <td align="left">{{ $match->torneo }}</td>
                    <td align="right" style="font-size: 12px;">{{ $match->hora }} | {{ $match->cancha }}</td>
                </tr>
            </table>
        </div>
        <div class="match-body">
            <div style="text-align: center; margin-bottom: 10px;">
                <span style="font-weight: bold; color: #1e40af; font-size: 16px;">{{ $match->eLocal }}</span>
                <span class="vs-badge">VS</span>
                <span style="font-weight: bold; color: #dc2626; font-size: 16px;">{{ $match->eVisitante }}</span>
            </div>
            
            <div style="font-size: 12px; margin-bottom: 5px; text-align: center;">
                <strong>Categoría:</strong> {{ $match->categoriaUno }}{{ $match->categoriaDos ? ' / ' . $match->categoriaDos : '' }}
            </div>

            <div style="font-size: 11px; margin-top: 5px; text-align: center; color: #4b5563;">
                <span style="margin-right: 15px;"><strong>Inscripción:</strong> ${{ number_format($match->costo_inscripcion, 0) }}</span>
                <span><strong>Arbitraje:</strong> ${{ number_format($match->costo_arbitraje, 0) }}</span>
            </div>

            <div class="convocados-list">
                <span class="convocados-title">Jugadores Convocados:</span>
                @php
                    $jugadores = explode(',', $match->jugadores_convocados);
                @endphp
                @foreach($jugadores as $jugadorId)
                    @php
                        $id = trim($jugadorId);
                        $nombre = $studentNames[$id] ?? $id;
                    @endphp
                    <span style="display: inline-block; background-color: #f9fafb; padding: 2px 8px; border-radius: 4px; margin: 2px; border: 1px solid #f3f4f6;">
                        {{ $nombre }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
  @endforeach

  <div class="footer">
    Documento generado automáticamente por el Sistema Administrativo Jackeline FS.
  </div>
</body>
</html>
