<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Student data') }}
    </h2>
  </x-slot>
  <div class="py-3">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg m-auto" style="width:650px;">
        <div class="container">
          <div class="row py-0 m-auto">
            <div class="card py-1 px-1 ">
              <div class="card-header">
                <div class="row">
                  <div class="col-md-8 mx-auto my-auto text-center">
                    <b>{{ Str::upper($student->nomDeportista)}}</b>
                  </div>
                  <div class="col-md-2">
                    <a title="Regresar" href="{{ route('imprimir', $student) }}" class="sombra btn btn-info mx-auto">imprimir</a>
                  </div>
                  <div class="col-md-2">
                    <a title="Regresar" href="{{ route('students.index', $student) }}" class="sombra btn btn-danger mx-auto">volver</a>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-5"><b>Categoria:</b></div><div class="col-md-7">{{ $student->Categoria}}</div>
                  <div class="col-md-5"><b>Fecha de inscripcion:</b></div><div class="col-md-7">{{ $student->fechaInscripcion}}</div>
                  <div class="col-md-5"><b>Numero documento:</b></div><div class="col-md-7">{{ $student->numDocumento}}</div>
                  <div class="col-md-5"><b>Genero:</b></div><div class="col-md-7">{{ $student->genero}}</div>
                  <div class="col-md-5"><b>Peso:</b></div><div class="col-md-7">{{ $student->PesoDeportista}}</div>
                  <div class="col-md-5"><b>Estatura:</b></div><div class="col-md-7">{{ $student->EstaturaDeportista}}</div>
                  <div class="col-md-5"><b>RH:</b></div><div class="col-md-7">{{ $student->RHDeportista}}</div>
                  <div class="col-md-5"><b>Fecha de nacimiento:</b></div><div class="col-md-7">{{ $student->fechaNacimiento}}</div>
                  <div class="col-md-5"><b>Ciudad:</b></div><div class="col-md-7">{{ $student->Ciudad}}</div>
                  <div class="col-md-5"><b>Departamento:</b></div><div class="col-md-7">{{ $student->Departamento}}</div>
                  <div class="col-md-5"><b>EPS:</b></div><div class="col-md-7">{{ $student->EPS}}</div>
                  <div class="col-md-5"><b>Colegio:</b></div><div class="col-md-7">{{ $student->Colegio}}</div>
                  <div class="col-md-5"><b>Curso:</b></div><div class="col-md-7">{{ $student->Curso}}</div>
                  <div class="col-md-5"><b>Telefono:</b></div><div class="col-md-7">{{ $student->numTelefonico}}</div>
                  <div class="col-md-5"><b>Telefono dos:</b></div><div class="col-md-7">{{ $student->numTelefonicoUno}}</div>
                  <div class="col-md-5"><b>Telefono tres:</b></div><div class="col-md-7">{{ $student->numTelefonicoDos}}</div>
                  <div class="col-md-5"><b>Direccion:</b></div><div class="col-md-7">{{ $student->direccionDeportista}}</div>
                  <div class="col-md-5"><b>Barrio:</b></div><div class="col-md-7">{{ $student->barrio}}</div>
                  <div class="col-md-5"><b>Localidad:</b></div><div class="col-md-7">{{ $student->localidad}}</div>
                  <div class="col-md-5"><b>Nombre mama:</b></div><div class="col-md-7">{{ $student->nombreMama}}</div>
                  <div class="col-md-5"><b>Documento mama:</b></div><div class="col-md-7">{{ $student->documentoMama}}</div>
                  <div class="col-md-5"><b>Telefono mama:</b></div><div class="col-md-7">{{ $student->telefonoMama}}</div>
                  <div class="col-md-5"><b>Direccion mama:</b></div><div class="col-md-7">{{ $student->direccionMama}}</div>
                  <div class="col-md-5"><b>Correo mama:</b></div><div class="col-md-7">{{ $student->correoMama}}</div>
                  <div class="col-md-5"><b>Nombre papa:</b></div><div class="col-md-7">{{ $student->nombrePapa}}</div>
                  <div class="col-md-5"><b>Documento papa:</b></div><div class="col-md-7">{{ $student->documentoPapa}}</div>
                  <div class="col-md-5"><b>Telefono papa:</b></div><div class="col-md-7">{{ $student->telefonoPapa}}</div>
                  <div class="col-md-5"><b>Direccion papa:</b></div><div class="col-md-7">{{ $student->direccionPapa}}</div>
                  <div class="col-md-5"><b>Correo papa:</b></div><div class="col-md-7">{{ $student->correoPapa}}</div>
              </div>
              </div>
              <div class="card-footer"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
