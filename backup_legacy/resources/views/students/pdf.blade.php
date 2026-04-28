<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Ficha de inscripcion de: {{ Str::title($student->nomDeportista)}}</title>
  <link href="{{ env('APP_URL') }}css/pdf-imprimir.css" rel="stylesheet">
  <style>
    body {
      background-image: url("{{ env('APP_URL') }}images/logo/LOGO.png");
      background-repeat: no-repeat;
      background-size:100%;
      background-position: 50% 50%;
      /* background-color:red; */
      margin: 3rem 2rem 2rem;
    }
  </style>
</head>
<body>
  <header>
    <div class="h-pdf-left">
      <img src="{{ env('APP_URL') }}images/logo/LOGO.png" alt="LOGO-jackeline" width="100">
    </div>
    <div class="h-pdf-center">
      <h2 style="text-shadow: 2px 2px #FF0000 !important;">{{__('CLUB DEPORTIVO JACKELINE FS A.F.A')}}</h2>
        <h5 class="resolucion" style="background-color: rgba(169, 45, 169,0);margin-top:-5%;">
          Resolución 175 del 13 de marzo de 2017, otorgada por el Instituto de Recreación y Deporte (IDRD).<br>
        </h5>
        <h5 style="background-color: rgba(215, 21, 215,0);margin-top:-2%;">
          <strong>FORMATO UNICO DE REGISTRO</strong>
        </h5>
    </div>
    <div class="h-pdf-right">
      <div class="div-img">
        <img class="photo" src="{{env('APP_URL').$student->Photo}}" alt="foto-deportista">
      </div>
    </div>
  </header>

  <table width="100%" class="tabla-datos" border="0">
    <tbody>
      <tr>
        <td style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Fecha inscripcion:</b></td>
        <td style="background-color:rgba(1, 142, 203,0.7);text-align:center;" colspan="2"><b>Nombre completo deportista</b></td>
        <td style="background-color:rgba(1, 142, 203,0.7);text-align:center;" colspan="2"><b>Categoria:</b></td>
      </tr>
      <tr>
        <td align="center">{{ \Carbon\Carbon::parse(strtotime($student->fechaInscripcion))->formatLocalized('%d-%m-%Y') }}</td>
        <td colspan="2" align="center"><b>{{$student->nomDeportista}}</b></td>
        <td colspan="2" align="center">{{ $student->Categoria}}</td>
      </tr>
      <tr>
        <td style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Numero documento:</b></td>
        <td style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Genero:</b></td>
        <td style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Peso:</b></td>
        <td style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Estatura:</b></td>
        <td style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>RH:</b></td>
      </tr>
      <tr>
        <td align="center">{{ $student->numDocumento}}</td>
        <td align="center">{{ $student->genero}}</td>
        <td align="center">{{ $student->PesoDeportista}}</td>
        <td align="center">{{ $student->EstaturaDeportista}}</td>
        <td align="center">{{ $student->RHDeportista}}</td>
      </tr>
      <tr>
        <td rowspan="2" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Fecha de nacimiento:</b> </td>
        <td colspan="2" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Lugar de nacimiento:</b> </td>
        <td colspan="2" rowspan="2" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>EPS:</b> </td>
      </tr>
      <tr>
        <td style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Ciudad:</b> </td>
        <td style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Departamento:</b> </td>
      </tr>
      <tr>
        <td align="center">{{ \Carbon\Carbon::parse(strtotime($student->fechaNacimiento))->formatLocalized('%d-%m-%Y') }}</td>
        <td align="center">{{ $student->Ciudad}}</td>
        <td align="center">{{ $student->Departamento}}</td>
        <td align="center" colspan="2">{{ $student->EPS}}</td>
      </tr>
      <tr>
        <td colspan="2" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Direccion:</b></td>
        <td style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Barrio:</b></td>
        <td colspan="2" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Localidad:</b></td>
      </tr>
      <tr>
        <td align="center" colspan="2">{{ $student->direccionDeportista}}</td>
        <td align="center">{{ $student->barrio}}</td>
        <td align="center" colspan="2">{{ $student->localidad}}</td>
      </tr>
      <tr>
        <td colspan="3" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Telefonos de contacto:</b></td>
        <td style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Colegio:</b></td>
        <td style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Curso:</b></td>
      </tr>
      <tr>
        <td align="center">{{ $student->numTelefonico}}</td>
        <td align="center">{{ $student->numTelefonicoUno}}</td>
        <td align="center">{{ $student->numTelefonicoDos}}</td>
        <td align="center">{{ $student->Colegio}}</td>
        <td align="center">{{ $student->Curso}}</td>
      </tr>
      <tr>
        <td colspan="5"style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>DATOS MADRE</b></td>
      </tr>
      <tr>
        <td colspan="2" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Nombre</b></td>
        <td colspan="3" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Direccion</b></td>
      </tr>
      <tr>
        <td align="center" colspan="2">{{ $student->nombreMama}}</td>
        <td align="center" colspan="3">{{ $student->direccionMama}}</td>
      </tr>
      <tr>
        <td colspan="1" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Documento</b></td>
        <td colspan="1" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Telefono</b></td>
        <td colspan="4" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Correo</b></td>
      </tr>
      <tr>
        <td align="center" colspan="1">{{ $student->documentoMama}}</td>
        <td align="center" colspan="1">{{ $student->telefonoMama}}</td>
        <td align="center" colspan="4">{{ $student->correoMama}}</td>
      </tr>

      <tr>
        <td colspan="5"style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>DATOS PADRE</b></td>
      </tr>
      <tr>
        <td colspan="2" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Nombre</b></td>
        <td colspan="3" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Direccion</b></td>
      </tr>
      <tr>
        <td align="center" colspan="2">{{ $student->nombrePapa}}</td>
        <td align="center" colspan="3">{{ $student->direccionPapa}}</td>
      </tr>
      <tr>
        <td colspan="1" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Documento</b></td>
        <td colspan="1" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Telefono</b></td>
        <td colspan="4" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Correo</b></td>
      </tr>
      <tr>
        <td align="center" colspan="1">{{ $student->documentoPapa}}</td>
        <td align="center" colspan="1">{{ $student->telefonoPapa}}</td>
        <td align="center" colspan="4">{{ $student->correoPapa}}</td>
      </tr>
      <tr>
        <td colspan="5" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>HISTORIA CLINICA</b></td>
      </tr>
        <td colspan="2" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Enfermedades que padece:</b> </td>
        <td colspan="3" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Usa algun medicamento:</b> </td>
      <tr>
      </tr>
      <tr>
        <td align="center" colspan="2">{{ $student->enfermedades}}</td>
        <td align="center" colspan="3">{{ $student->medicamento}}</td>
      </tr>
      <tr>
        <td colspan="2" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Tiene alguna lesion congenita:</b> </td>
        <td colspan="3" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Tiene cirugias:</b> </td>
      </tr>
      <tr>
        <td align="center" colspan="2">{{ $student->lesion}}</td>
        <td align="center" colspan="3">{{ $student->Cirugia}}</td>
      </tr>
      <tr>
        <td colspan="2" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Algo que le impida practicar algun deporte:</b> </td>
        <td colspan="3" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>Tiene alguna lesion oseo muscular:</b> </td>
      </tr>
      <tr>
        <td align="center" colspan="2">{{ $student->impedimento}}</td>
        <td align="center" colspan="3">{{ $student->lesionOM}}</td>
      </tr>
      <tr>
        <td colspan="5" style="background-color:rgba(1, 142, 203,0.7);text-align:center;"><b>AUTORIZACION PARA MENORES DE 18 AÑOS</b></td>
      </tr>
      <tr>
        <td align="justify" style="font-size:12px" colspan="5">{{ __('Authorization to a minor') }}</td>
      </tr>
    </tbody>
  </table>
  <table width="100%" border="0">
  {{-- <table width="100%" class="tabla-datos" border="1"> --}}
    <tbody>
      <tr>
        <td style="width:33.333333%" rowspan="8" align="center">
          <div class="firmas"></div>
          Firma del jugador
        </td>
        <td style="width:33.333333%" rowspan="8" align="center">
          <div class="firmas"></div>
          Firma del padre
        </td>
        <td style="width:33.333333%" rowspan="8" align="center">
          <div class="firmas"></div>
          Firma de la madre
        </td>
      </tr>
    </tbody>
  </table>


<footer>
    <h1>{{ env('APP_NAME') }}</h1>
</footer>
</body>
</html>
