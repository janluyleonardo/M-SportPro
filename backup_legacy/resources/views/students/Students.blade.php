<x-app-layout></x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Students') }}
    </h2>
  </x-slot>

  <div class="py-3">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="container">
          <div class="row py-0">
            <div class="col-md-6 mx-auto py-4">
              @if ($mensaje != "")
              <div class="col-md-12">
                  <div class="alert alert-success mt-auto" role="alert">
                      <h1>{{ $mensaje }}</h1>
                  </div>
              </div>
              @endif
              <div class="card bg-light ">
                <div class="card-header">
                  <div class="row">
                    <div class="col-md-6">{{__('Records in database')}}</div>
                    <div class="col-md-6" >
                      <form action="{{route('Students')}}" method="get">
                        <div class="row form-row">
                          <div class="col-md-8 my-1">
                              <input type="text" class="form-control" name="texto" id="texto" value="{{$student}}">
                          </div>
                          <div class="col-md-4 my-1">
                              <input type="submit" class="sombra btn btn-primary" value="Buscar">
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle table-striped table-hover table-sm">
                        @if (count($estudiantes) <= 0)
                          <thead>
                            <tr>
                              <th colspan="6" align="center">
                                <center>El estudiante "{{ $texto }}" que esta buscano no existe en la base de datos</center>
                              </th>
                            </tr>
                          </thead>
                        @else
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>{{ __('Name') }}</th>
                              <th>{{ __('Actions') }}</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php $i = 1; ?>
                            @foreach ($estudiantes as $estudiante)
                            <tr>
                              <td>{{ $i }}</td>
                              <td>{{ Str::title($estudiante->nomAlumno) }}</td>
                              <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                  <a class="sombra btn btn-info" href="#" data-bs-toggle="modal" data-bs-target="#showModal{{$estudiante->id}}"><i class="bi bi-eye"></i></a>
                                  <a class="sombra btn btn-warning" href="#" data-bs-toggle="modal" data-bs-target="#editModal{{$estudiante->id}}"><i class="bi bi-pencil"></i></a>
                                  <a class="sombra btn btn-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteModal{{$estudiante->id}}"><i class="bi bi-trash"></i></a>
                                </div>
                              </td>
                            </tr>
                            <?php $i += +1; ?>
                            <!-- Modal show-->
                            <div class="modal fade" id="showModal{{$estudiante->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content sombra bg-white">
                                  <div class="modal-header sombra bn-100">
                                    <h1 class="modal-title fs-5 mx-auto" id="exampleModalLabel">{{ Str::upper($estudiante->nomAlumno)}}</h1>
                                    <button type="button" class="btn-close sombra" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body sombra">
                                    <div class="row">
                                      <div class="col-md-5">Nivel:</div><div class="col-md-7">{{ $estudiante->nivel}}</div>
                                      <div class="col-md-5">Fecha matricula:</div><div class="col-md-7">{{ $estudiante->fechaMatricula}}</div>
                                      <div class="col-md-5">Edad:</div><div class="col-md-7">{{ $estudiante->EdadAlumno}}</div>
                                      <div class="col-md-5">Genero:</div><div class="col-md-7">{{ $estudiante->genero}}</div>
                                      <div class="col-md-5">EPS:</div><div class="col-md-7">{{ $estudiante->EPS}}</div>
                                      <div class="col-md-5">Fecha de nacimiento:</div><div class="col-md-7">{{ $estudiante->fechaNacimiento}}</div>
                                      <div class="col-md-5">Tipo documento:</div><div class="col-md-7">{{ $estudiante->documentType}}</div>
                                      <div class="col-md-5">Numero documento:</div><div class="col-md-7">{{ $estudiante->numDocumento}}</div>
                                      <div class="col-md-5">Regimen de salud:</div><div class="col-md-7">{{ $estudiante->Esalud}}</div>
                                      <div class="col-md-5">Numero telefonico:</div><div class="col-md-7">{{ $estudiante->numTelefonico}}</div>
                                      <div class="col-md-5">Direccion:</div><div class="col-md-7">{{ $estudiante->direccionAlumno}}</div>
                                      <div class="col-md-5">Barrio:</div><div class="col-md-7">{{ $estudiante->barrio}}</div>
                                      <div class="col-md-5">Localidad:</div><div class="col-md-7">{{ $estudiante->localidad}}</div>
                                      <div class="col-md-5">Nombre mama:</div><div class="col-md-7">{{ $estudiante->nombreMama}}</div>
                                      <div class="col-md-5">Documento mama:</div><div class="col-md-7">{{ $estudiante->documentoMama}}</div>
                                      <div class="col-md-5">Telefono mama:</div><div class="col-md-7">{{ $estudiante->telefonoMama}}</div>
                                      <div class="col-md-5">Direccion mama:</div><div class="col-md-7">{{ $estudiante->direccionMama}}</div>
                                      <div class="col-md-5">Nombre papa:</div><div class="col-md-7">{{ $estudiante->nombrePapa}}</div>
                                      <div class="col-md-5">Documento papa:</div><div class="col-md-7">{{ $estudiante->documentoPapa}}</div>
                                      <div class="col-md-5">Telefono papa:</div><div class="col-md-7">{{ $estudiante->telefonoPapa}}</div>
                                      <div class="col-md-5">Direccion papa:</div><div class="col-md-7">{{ $estudiante->direccionPapa}}</div>
                                      <div class="col-md-5">Primer responsable:</div><div class="col-md-7">{{ $estudiante->nomPriRes}}</div>
                                      <div class="col-md-5">Documento:</div><div class="col-md-7">{{ $estudiante->docPriRes}}</div>
                                      <div class="col-md-5">Direccion:</div><div class="col-md-7">{{ $estudiante->dirPriRes}}</div>
                                      <div class="col-md-5">Telefono:</div><div class="col-md-7">{{ $estudiante->telPriRes}}</div>
                                      <div class="col-md-5">Segundo responsable:</div><div class="col-md-7">{{ $estudiante->nomSegRes}}</div>
                                      <div class="col-md-5">Documento:</div><div class="col-md-7">{{ $estudiante->docSegRes}}</div>
                                      <div class="col-md-5">Direccion:</div><div class="col-md-7">{{ $estudiante->dirSegRes}}</div>
                                      <div class="col-md-5">Telefono:</div><div class="col-md-7">{{ $estudiante->telSegRes}}</div>
                                    </div>
                                  </div>
                                  <div class="modal-footer bn-100">
                                    <button type="button" class=" sombra btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <!-- Modal edit-->
                                <div class="modal fade" id="editModal{{$estudiante->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="false">
                                    <div class="modal-dialog" style=" min-width:750px;">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title editar a:{{ $estudiante->nomAlumno }}</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        @php
                                        $hoy = now()->format('Y-m-d');
                                        @endphp
                                        <div class="modal-body">
                                                <div class="row">
                                                    <div class="form-holder">
                                                        <div class="form-content">
                                                            <div class="form-items">
                                                                <form action="{{ route('editar/') }}" method="post"class="requires-validation" novalidate>
                                                                    @csrf
                                                                    <div class="input-group">
                                                                        <div class="col-md-8 pt-4">
                                                                            <input type="radio" class="btn-check" name="nivel" id="SALA-CUNA" value="SALA-CUNA" selected required>
                                                                            <label class="btn btn-sm btn-outline-secondary" for="SALA-CUNA">SALA-CUNA</label>
                                                                            <input type="radio" class="btn-check" name="nivel" id="PARVULOS" value="PARVULOS" autocomplete="off" required>
                                                                            <label class="btn btn-sm btn-outline-secondary" for="PARVULOS">PARVULOS</label>
                                                                            <input type="radio" class="btn-check" name="nivel" id="PRE-KINDER" value="PRE-KINDER" autocomplete="off" required>
                                                                            <label class="btn btn-sm btn-outline-secondary" for="PRE-KINDER">PRE-KINDER</label>
                                                                            <input type="radio" class="btn-check" name="nivel" id="KINDER" value="KINDER" autocomplete="off" required>
                                                                            <label class="btn btn-sm btn-outline-secondary" for="KINDER">KINDER</label>
                                                                            <div class="valid-feedback mv-up">You selected a nivel!</div>
                                                                            <div class="invalid-feedback mv-up">Please select a nivel!</div>
                                                                            <br>Nivel de matricula
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <input class="form-control" type="text" name="fechaMatricula" id="" value="{{ $hoy }}" readonly required>
                                                                            <div class="valid-feedback mv-up">You selected a fecha de matricula!</div>
                                                                            <div class="invalid-feedback mv-up">Please select a fecha de matricula!</div>
                                                                            Fecha de{{ __('Registration') }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12" style="background-color: rgba(0, 0, 0, 0.068); border-bottom: 1px solid rgba(0, 0, 0, 0.5)">
                                                                        {{ __('Informacion del(a) niño(a)') }}</div>
                                                                    <div class="col-md-12">
                                                                        <input class="form-control" type="text" name="nomAlumno" placeholder="{{ __('Full Name') }}" required>
                                                                        <div class="valid-feedback">Username field is valid!</div>
                                                                        <div class="invalid-feedback">Username field cannot be blank!</div>
                                                                    </div>

                                                                    <div class="input-group">
                                                                        <div class="col-md-4 my-auto mt-3">
                                                                            <input class="form-control" type="date" name="fechaNacimiento" id="fechaNacimiento" onblur="edad()" required>
                                                                            <div class="valid-feedback mv-up">You selected a fecha de nacimiento!</div>
                                                                            <div class="invalid-feedback mv-up">Please select a fecha de nacimiento!</div>
                                                                            Fecha de nacimiento
                                                                        </div>
                                                                        <div class="col-md-4 my-auto mt-3">
                                                                            <center>
                                                                                <label for="masc"><i><img src="{{asset('images/icono-niño.png')}}" alt="icono-niño" width="35px"></i></label>
                                                                                <input type="radio" name="genero" id="masc" value="Maculino" required>
                                                                                <label for="fem"><i><img src="{{asset('images/icono-niña.png')}}" alt="icono-niña" width="35px"></i></label>
                                                                                <input type="radio" name="genero" id="fem" value="Femenino">
                                                                                <div class="valid-feedback">Gender field is valid!</div>
                                                                                <div class="invalid-feedback">Gender field cannot be blank!</div>
                                                                                <br>genero:
                                                                            </center>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <input class="form-control" type="text" name="EPS" placeholder="EPS" required>
                                                                            <div class="valid-feedback">EPS field is valid!</div>
                                                                            <div class="invalid-feedback">EPS field cannot be blank!</div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="input-group">
                                                                        <div class="col-md-4 my-auto ">
                                                                            <input class="form-control" type="text" name="EdadAlumno" id="EdadAlumno" placeholder="Edad" readonly required>
                                                                            <div class="valid-feedback">Edad field is valid!</div>
                                                                            <div class="invalid-feedback">Edad field cannot be blank!</div>
                                                                        </div>
                                                                        <div class="col-md-4 my-auto mt-4">
                                                                            <center>
                                                                                <label for="documentType">NUIP</label>
                                                                                <input type="radio" name="documentType" id="documentType" value="NUIP" required>
                                                                                <label for="documentType">RC</label>
                                                                                <input type="radio" name="documentType" id="documentType" value="RC">
                                                                                <div class="valid-feedback">Tipo documento field is valid!</div>
                                                                                <div class="invalid-feedback">Tipo documento field cannot be blank!
                                                                                </div><br>
                                                                                Tipo documento
                                                                            </center>
                                                                        </div>
                                                                        <div class="col-md-4 my-auto mt-4">
                                                                            <input class="form-control" type="number" name="numDocumento"placeholder="N° documento" required>
                                                                            <div class="valid-feedback">Numero de documento field is valid!
                                                                            </div>
                                                                            <div class="invalid-feedback">Numero de documento field cannot be blank!</div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="input-group">
                                                                        <div class="col-md-8 mt-1 pt-3">
                                                                            Reg de salud:
                                                                            <input type="radio" class="btn-check" name="Esalud" id="EPS" value="EPS" autocomplete="off" required>
                                                                            <label class="btn btn-sm btn-outline-secondary" for="EPS">EPS</label>

                                                                            <input type="radio" class="btn-check" name="Esalud" id="ARS" value="ARS" autocomplete="off" required>
                                                                            <label class="btn btn-sm btn-outline-secondary" for="ARS">ARS</label>

                                                                            <input type="radio" class="btn-check" name="Esalud" id="VINCULADO" value="VINCULADO" autocomplete="off" required>
                                                                            <label class="btn btn-sm btn-outline-secondary" for="VINCULADO">VINCULADO</label>

                                                                            <div class="valid-feedback mv-up">You selected a regimen de salud de salud!</div>
                                                                            <div class="invalid-feedback mv-up">Please select a regimen de salud de salud!</div>
                                                                        </div>
                                                                        <div class="col-md-4 mt-3">
                                                                            <input class="form-control" type="number" name="numTelefonico"placeholder="N° telefono" required>
                                                                            <div class="valid-feedback">Telefono field is valid!</div>
                                                                            <div class="invalid-feedback">Telefono field cannot be blank!</div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="input-group">
                                                                        <div class="col-md-12 ">
                                                                            <input class="form-control" type="text" name="direccionAlumno"placeholder="Direccion" required>
                                                                            <div class="valid-feedback">Direccion field is valid!</div>
                                                                            <div class="invalid-feedback">Direccion field cannot be blank!</div>
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <input class="form-control" type="text" name="barrio"placeholder="Barrio" required>
                                                                            <div class="valid-feedback">Barrio field is valid!</div>
                                                                            <div class="invalid-feedback">Barrio field cannot be blank!</div>
                                                                        </div>
                                                                        <div class="col-md-2"></div>
                                                                        <div class="col-md-5">
                                                                            <input class="form-control" type="text" name="localidad"placeholder="Localidad" required>
                                                                            <div class="valid-feedback">Localidad field is valid!</div>
                                                                            <div class="invalid-feedback">Localidad field cannot be blank!</div>
                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                                    <div class="col-md-12" style="background-color: rgba(0, 0, 0, 0.068); border-bottom: 1px solid rgba(0, 0, 0, 0.5)"> {{ __('Informacion de la Madre') }}</div>
                                                                    <div class="input-group">
                                                                        <div class="col-md-6">
                                                                            <input class="form-control" type="text" name="nombreMama" placeholder="Nombre completo" required>
                                                                            <div class="valid-feedback">Nombre field is valid!</div>
                                                                            <div class="invalid-feedback">Nombre field cannot be blank!</div>
                                                                        </div>
                                                                        <div class="col-md-1"></div>
                                                                        <div class="col-md-5 my-auto mt-3">
                                                                            <input class="form-control" type="number" name="documentoMama"placeholder="Nº documento" required>
                                                                            <div class="valid-feedback">Nº documento mama field is valid!</div>
                                                                            <div class="invalid-feedback">Nº documento mama field cannot be blank!</div>
                                                                        </div>
                                                                        <div class="col-md-4 my-auto mt-3">
                                                                            <input class="form-control" type="number" name="telefonoMama" placeholder="Nº telefonico" required>
                                                                            <div class="valid-feedback">Telefono field is valid!</div>
                                                                            <div class="invalid-feedback">Telefono field cannot be blank!</div>
                                                                        </div>
                                                                        <div class="col-md-1"></div>
                                                                        <div class="col-md-7">
                                                                            <input class="form-control" type="text" name="direccionMama"placeholder="Direccion" required>
                                                                            <div class="valid-feedback">Direccion field is valid!</div>
                                                                            <div class="invalid-feedback">Direccion field cannot be blank!</div>
                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                                    <div class="col-md-12" style="background-color: rgba(0, 0, 0, 0.068); border-bottom: 1px solid rgba(0, 0, 0, 0.5)"> {{ __('Informacion del Padre') }}</div>
                                                                    <div class="input-group">
                                                                        <div class="col-md-6">
                                                                            <input class="form-control" type="text" name="nombrePapa"placeholder="Nombre completo" required>
                                                                            <div class="valid-feedback">Nombre field is valid!</div>
                                                                            <div class="invalid-feedback">Nombre field cannot be blank!</div>
                                                                        </div>
                                                                        <div class="col-md-1"></div>
                                                                        <div class="col-md-5">
                                                                            <input class="form-control my-auto mt-3" type="number" name="documentoPapa"placeholder="Nº documento" required>
                                                                            <div class="valid-feedback">Documento field is valid!</div>
                                                                            <div class="invalid-feedback">Documento field cannot be blank!</div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <input class="form-control my-auto mt-3" type="number" name="telefonoPapa" placeholder="Nº telefonico" required>
                                                                            <div class="valid-feedback">Telefono field is valid!</div>
                                                                            <div class="invalid-feedback">Telefono field cannot be blank!</div>
                                                                        </div>
                                                                        <div class="col-md-1"></div>
                                                                        <div class="col-md-7">
                                                                            <input class="form-control" type="text" name="direccionPapa" placeholder="Direccion" required>
                                                                            <div class="valid-feedback">Direccion field is valid!</div>
                                                                            <div class="invalid-feedback">Direccion field cannot be blank! </div>
                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                                    <div class="col-md-12" style="background-color: rgba(0, 0, 0, 0.068); border-bottom: 1px solid rgba(0, 0, 0, 0.5)"> {{ __('Informacion de los responsables') }}</div>
                                                                    <div class="input-group">
                                                                        <div class="col-md-5">
                                                                            <br>
                                                                            <div class="col-md-12" style="background-color: rgba(0, 0, 0, 0.068); border-bottom: 1px solid rgba(0, 0, 0, 0.5)"> {{ __('Primer responsable') }}</div>
                                                                            <input class="form-control" type="text" name="nomPriRes"placeholder="Apellido(s) y nombre(s)" required>
                                                                            <div class="valid-feedback">Nombre 1er responsable field is valid!</div>
                                                                            <div class="invalid-feedback">Nombre 1er responsable field cannot be blank!</div>
                                                                            <input class="form-control my-auto mt-3" type="number" name="docPriRes"placeholder="N° documento" required>
                                                                            <div class="valid-feedback">Documento 1er responsable field is valid!</div>
                                                                            <div class="invalid-feedback">Documento 1er responsable field cannot be blank!</div>
                                                                            <input class="form-control" type="text" name="dirPriRes"placeholder="Direccion" required>
                                                                            <div class="valid-feedback">Direccion 1er responsable field is valid!</div>
                                                                            <div class="invalid-feedback">Direccion 1er responsable field cannot be blank!</div>
                                                                            <input class="form-control my-auto mt-3" type="number" name="telPriRes" placeholder="N° telefono" required>
                                                                            <div class="valid-feedback">Telefono 1er responsable field is valid!</div>
                                                                            <div class="invalid-feedback">Telefono 1er responsable field cannot be blank!</div>
                                                                        </div>
                                                                        <div class="col-md-2"></div>
                                                                        <div class="col-md-5">
                                                                            <br>
                                                                            <div class="col-md-12" style="background-color: rgba(0, 0, 0, 0.068); border-bottom: 1px solid rgba(0, 0, 0, 0.5)"> {{ __('Segundo responsable') }}</div>
                                                                            <input class="form-control" type="text" name="nomSegRes"placeholder="Apellido(s) y nombre(s)" required>
                                                                            <div class="valid-feedback">Nombre 2do responsable is valid!</div>
                                                                            <div class="invalid-feedback">Nombre 2do responsable cannot be blank!</div>
                                                                            <input class="form-control my-auto mt-3" type="number" name="docSegRes"placeholder="N° documento" required>
                                                                            <div class="valid-feedback">Documento 2do responsable field is valid!</div>
                                                                            <div class="invalid-feedback">Documento 2do responsable field cannot be blank!</div>
                                                                            <input class="form-control" type="text" name="dirSegRes"placeholder="Direccion" required>
                                                                            <div class="valid-feedback">Direccion 2do responsable field is valid!</div>
                                                                            <div class="invalid-feedback">Direccion 2do responsable field cannot be blank!</div>
                                                                            <input class="form-control my-auto mt-3" type="number" name="telSegRes"placeholder="N° telefono" required>
                                                                            <div class="valid-feedback">Telefono 2do responsable field is valid!</div>
                                                                            <div class="invalid-feedback">Telefono 2do responsable field cannot be blank!</div>
                                                                        </div>
                                                                    </div>
                                                                    {{-- <div class="col-md-4 mx-auto">
                                                                        <div class="form-button mt-3 ">
                                                                            <button id="submit" type="submit" class="btn btn-secondary">Register alumno</button>
                                                                        </div>
                                                                    </div> --}}
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <!-- Modal delete-->
                                <div class="modal fade" id="deleteModal{{$estudiante->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title eliminar a:{{ $estudiante->nomAlumno }}</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                        ...
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            @endforeach
                          </tbody>
                        @endif
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                  {{$estudiantes->links()}}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
