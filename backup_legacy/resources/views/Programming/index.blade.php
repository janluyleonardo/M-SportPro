<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{__('Programming')}}
    </h2>
  </x-slot>

  <div class="py-3">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="container">
          <div class="card bg-light mt-2 mb-2 mx-2">
            <div class="card-header">
              <div class="row text-center">
                <div class="col-md-4 my-auto">
                  {{-- <strong>index de {{__('Programming')}}</strong> --}}
                </div>
                <div class="col-md-4 my-auto">
                  <form action="" method="get">
                    <div class="row flex-row-reverse">
                      <div class="col-md-4 my-1">
                        <input type="submit" class="sombra btn btn-info" value="Buscar">
                      </div>
                      <div class="col-md-8 my-1">
                        <input type="text" class="form-control" name="texto" id="texto" value="{{$texto}}">
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="card-body">
              <form action="{{ route('programming.store') }}" method="post" class="requires-validation" novalidate>
                @csrf
                <div class="row">
                  <div class="col-md-4 col-sm-4 col-lg-4 mx-auto">
                    <input class="form-control form-control-sm" type="text" name="torneo" placeholder="{{ __('Tournament name') }}" value="{{ old('torneo') }}" required>
                    <div class="valid-feedback">Torneo field is valid!</div>
                    <div class="invalid-feedback">Torneo field cannot be blank!</div>
                  </div>
                  <div class="col-md-4 col-sm-4 col-lg-34mx-auto">
                    <input class="form-control form-control-sm" type="text" name="cancha" placeholder="{{ __('Cancha') }}" value="{{ old('cancha') }}" required>
                    <div class="valid-feedback">Cancha field is valid!</div>
                    <div class="invalid-feedback">Cancha field cannot be blank!</div>
                  </div>
                  <div class="col-md-2 col-sm-2 col-lg-2 mx-auto">
                    <input class="form-control form-control-sm" type="text" name="categoriaUno" placeholder="{{ __('Categoría') }} uno" value="{{ old('categoriaUno') }}" required>
                    <div class="valid-feedback">Categoria field is valid!</div>
                    <div class="invalid-feedback">Categoria field cannot be blank!</div>
                  </div>
                  <div class="col-md-2 col-sm-2 col-lg-2 mx-auto">
                    <input class="form-control form-control-sm" type="text" name="categoriaDos" placeholder="{{ __('Categoría') }} Dos" value="{{ old('categoriaDos') }}" required>
                    <div class="valid-feedback">Categoria field is valid!</div>
                    <div class="invalid-feedback">Categoria field cannot be blank!</div>
                  </div>
                  <div class="col-md-3 col-sm-3 col-lg-3 mx-auto mt-2">
                    <input class="form-control form-control-sm" type="time" name="hora" min="{{now()->format('H:i')}}" placeholder="{{ __('Match time') }}" value="{{ old('hora') }}" required>
                    <div class="valid-feedback">Hora field is valid!</div>
                    <div class="invalid-feedback">Hora field cannot be blank!</div>
                  </div>
                  <div class="col-md-3 col-sm-3 col-lg-3 mx-auto mt-2">
                    <input class="form-control form-control-sm" type="text" name="eLocal" value="Jackeline FS" readonly required>
                    <div class="valid-feedback">Equipo local field is valid!</div>
                    <div class="invalid-feedback">Equipo local field cannot be blank!</div>
                  </div>
                  <div class="col-md-3 col-sm-3 col-lg-3 mx-auto mt-2">
                    <input class="form-control form-control-sm" type="text" name="eVisitante" placeholder="{{ __('Visiting Team') }}" value="{{ old('eVisitante') }}" required>
                    <div class="valid-feedback">Equipo visitante field is valid!</div>
                    <div class="invalid-feedback">Equipo visitante field cannot be blank!</div>
                  </div>
                  <div class="col-md-3 col-sm-3 col-lg-3 mx-auto mt-2">
                    <input class="form-control form-control-sm" type="date" min="{{now()->format('Y-m-d')}}" name="fecha" placeholder="{{ __('Match date') }}" value="{{ old('fecha') }}" required>
                    <div class="valid-feedback">Fecha field is valid!</div>
                    <div class="invalid-feedback">Fecha field cannot be blank!</div>
                  </div>
                  <!-- Campo para la lista de jugadores convocados -->
                  <div class="col-md-6 col-sm-6 col-lg-6 mx-auto mt-2 text-center">
                    <label for="jugadores_convocados">{{ __('Select players to call in this match') }}</label>
                    <select class="form-control select-buscar form-control-sm text-center" name="jugadores_convocados[]" multiple required>
                      <option value="" hidden selected>Selecciona tipo de documento</option>
                        @foreach($studentList as $student)
                          <option value="{{ $student->nomDeportista }}">{{ $student->nomDeportista }}</option>
                        @endforeach
                      </select>
                    <div class="valid-feedback">Jugadores convocados seleccionados correctamente.</div>
                    <div class="invalid-feedback">Por favor, selecciona al menos un jugador convocado.</div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3 col-sm-3 col-lg-3 mx-auto">
                    <div class="form-button mt-3 mx-auto">
                      <button style="width:100% !important;" id="submit" type="submit" class="sombra btn btn-secondary ">{{__('Add Programming')}}</button>
                    </div>
                  </div>
                </div>
              </form>

              <div class="row mt-1">
                <div class="col-md-12">
                  @if ($programming->isEmpty())
                  @else
                    <table class="table table-striped table-hover">
                      <thead>
                        <th>Torneo</th>
                        <th>Cancha</th>
                        <th>Categoria</th>
                        <th>Rival</th>
                        <th>Hora</th>
                        <th>Fecha</th>
                      </thead>
                      <tbody class="table-group-divider">
                      @forelse ($programming as $item)
                      <tr>
                        <td>{{$item->torneo}}</td>
                        <td>{{$item->cancha}}</td>
                        <td>{{$item->categoria}}</td>
                        <td>{{$item->eVisitante}}</td>
                        <td>{{$item->hora}}</td>
                        <td>{{$item->fecha}}</td>
                        <td>
                          <div class="btn-group" role="group" aria-label="Basic example">
                            <a title="Editar" href="#updateModal{{$item->id}}" class="btn btn-sm btn-info sombra" data-bs-toggle="modal">
                              <i class="bi bi-pencil-square"></i>
                            </a>
                            <a title="Eliminar" href="#deleteModal{{$item->id}}" class="sombra btn btn-sm btn-danger" data-bs-toggle="modal">
                              <i class="bi bi-trash"></i>
                            </a>
                          </div>
                        </td>
                      </tr>
                      <!-- Modal update-->
                        <div class="modal fade" id="updateModal{{$item->id}}" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content sombra bg-white">
                              <div class="modal-header sombra bn-100">
                                <h1 class="modal-title fs-5 mx-auto" id="exampleModalLabel">Actualizar programación:</h1>
                                <button type="button" class="btn-close sombra" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body sombra">
                                <div class="container">
                                  <div class="row">
                                    <form action="{{ route('programming.update', $item->id) }}" method="post" class="requires-validation" novalidate>
                                      @method('put')
                                      @csrf
                                      <div class="col-md-12 mb-1">
                                        <label for="torneo">Nombre del torneo</label>
                                        <input class="form-control" type="text" name="torneo" value="{{$item->torneo}}" required>
                                      </div>
                                      <div class="col-md-6  mb-1">
                                        <label for="categoria">Categoria</label>
                                        <input class="form-control" type="text" name="categoria" value="{{$item->categoria}}" required>
                                      </div>
                                      <div class="col-md-6  mb-1">
                                        <label for="cancha">Cancha</label>
                                        <input class="form-control" type="text" name="cancha" value="{{$item->cancha}}" required>
                                      </div>
                                      <div class="col-md-6  mb-1">
                                        <label for="hora">hora</label>
                                        <input class="form-control" type="text" name="hora" value="{{$item->hora}}" required>
                                      </div>
                                      <div class="col-md-6  mb-1">
                                        <label for="eVisitante">eVisitante</label>
                                        <input class="form-control" type="text" name="eVisitante" value="{{$item->eVisitante}}" required>
                                      </div>
                                      <div class="col-md-6  mb-1">
                                        <label for="fecha">fecha</label>
                                        <input class="form-control" type="date" name="fecha" value="{{$item->fecha}}" required>
                                      </div>
                                    </form>
                                  </div>
                                </div>
                              </div>
                              <div class="modal-footer bn-100">
                                <button type="button" class=" sombra btn btn-warning" data-bs-dismiss="modal">Close</button>
                                <button id="submit" type="submit" class="sombra btn btn-secondary">{{__('Edit health check')}}</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      <!-- Modal delete-->
                      <div class="modal fade" id="deleteModal{{$item->id}}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content sombra bg-white">
                            <div class="modal-header sombra bn-100">
                              <h1 class="modal-title fs-5 mx-auto" id="exampleModalLabel">Eliminar programación</h1>
                              <button type="button" class="btn-close sombra" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body sombra">
                              esta seguro(a) de eliminar el registro de: <strong>{{ Str::upper($item->torneo)}}</strong>
                            </div>
                            <div class="modal-footer bn-100">
                              <button type="button" class=" sombra btn btn-warning" data-bs-dismiss="modal">Close</button>
                              <form action="{{ route('programming.destroy', $item) }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" class=" sombra btn btn-danger">
                                  Eliminar registro
                                </button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                      @empty
                      Sin registros para mostrar en la tabla
                      @endforelse
                      </tbody>
                    </table>
                  @endif
                </div>
              </div>
            </div>
            <div class="card-footer">
              {{$programming->links()}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
                <div class="form-holder">

                </div>
