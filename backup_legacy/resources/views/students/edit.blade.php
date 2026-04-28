<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Manage Incomes') }}
    </h2>
  </x-slot>
  <div class="container">
    <div class="row py-0">
      <div class="col-md-6 mx-auto py-1">
        <div class="card shadow">
          <div class="form-body">
            <div class="row">
              <div class="form-holder">
                <div class="form-content">
                  <div class="form-items">
                      <form action="{{ route('students.update', $student) }}" method="post" class="requires-validation" enctype="multipart/form-data" novalidate>
                        @method('put')
                        @csrf
                        <div class="input-group">
                            <div class="col-md-7">
                              @php
                                $cat2018 = $student->Categoria == "2018" ? "selected" : "";
                                $cat2017 = $student->Categoria == "2017" ? "selected" : "";
                                $cat2016 = $student->Categoria == "2016" ? "selected" : "";
                                $cat2015 = $student->Categoria == "2015" ? "selected" : "";
                                $cat2014 = $student->Categoria == "2014" ? "selected" : "";
                                $cat2013 = $student->Categoria == "2013" ? "selected" : "";
                                $cat2012 = $student->Categoria == "2012" ? "selected" : "";
                                $cat2011 = $student->Categoria == "2011" ? "selected" : "";
                                $cat2010 = $student->Categoria == "2010" ? "selected" : "";
                                $cat2009 = $student->Categoria == "2009" ? "selected" : "";
                                $cat2008 = $student->Categoria == "2008" ? "selected" : "";
                                $cat2007 = $student->Categoria == "2007" ? "selected" : "";
                                $cat2006 = $student->Categoria == "2006" ? "selected" : "";
                                $cat2005 = $student->Categoria == "2005" ? "selected" : "";
                              @endphp
                                <select class="form-control" name="Categoria" id="Categoria" required>
                                    <option value="">Seleccionar un categoria</option>
                                    <option value="2018" {{$cat2018}}>2018</option>
                                    <option value="2017" {{$cat2017}}>2017</option>
                                    <option value="2016" {{$cat2016}}>2016</option>
                                    <option value="2015" {{$cat2015}}>2015</option>
                                    <option value="2014" {{$cat2014}}>2014</option>
                                    <option value="2013" {{$cat2013}}>2013</option>
                                    <option value="2012" {{$cat2012}}>2012</option>
                                    <option value="2011" {{$cat2011}}>2011</option>
                                    <option value="2010" {{$cat2010}}>2010</option>
                                    <option value="2009" {{$cat2009}}>2009</option>
                                    <option value="2008" {{$cat2008}}>2008</option>
                                    <option value="2007" {{$cat2007}}>2007</option>
                                    <option value="2006" {{$cat2006}}>2006</option>
                                    <option value="2005" {{$cat2006}}>2005</option>
                                </select>
                                <div class="valid-feedback mv-up">You selected a Categoria!</div>
                                <div class="invalid-feedback mv-up">Please select a Categoria!</div>
                                Categoria de inscripcion
                            </div>
                            <div class="col-md-1 "></div>
                            <div class="col-md-4 pt-3">
                                <input class="form-control" type="date" name="fechaInscripcion" value="{{ old('fechaInscripcion', $student->fechaInscripcion) }}" required>
                                <div class="valid-feedback mv-up">You selected a fecha de matricula!</div>
                                <div class="invalid-feedback mv-up">Please select a fecha de matricula!</div>
                                Fecha de {{ __('Registration') }}
                            </div>
                        </div>
                        <div class="row ">
                          <div class="col-md-12 encabezado">
                            {{ __('Athlete Information') }}
                          </div>
                        </div>
                        <div class="container">
                          <div class="input-group">
                            <div class="col-md-12">
                              <input class="form-control mt-3" type="file" name="Photo" accept="image/png, image/jpeg" value="{{ old('Photo', $student->Photo) }}" required>
                              <div class="valid-feedback">UserPhoto field is valid!</div>
                              <div class="invalid-feedback">UserPhoto field cannot be blank!</div>
                            </div>
                            <div class="col-md-12">
                                <input class="form-control" type="text" name="nomDeportista" value="{{ old('EstaturaDeportista', $student->nomDeportista) }}" required>
                                <div class="valid-feedback">Username field is valid!</div>
                                <div class="invalid-feedback">Username field cannot be blank!</div>
                            </div>
                            <div class="col-md-4 my-auto mt-4">
                              <input class="form-control" type="number" name="numDocumento" value="{{ old('numDocumento', $student->numDocumento) }}" required>
                              <div class="valid-feedback">Numero de documento field is valid!</div>
                              <div class="invalid-feedback">Numero de documento field cannot be blank!</div>
                            </div>
                            <div class="col-md-4 my-auto mt-3">
                              @php
                                  $femCheck = $student->genero=="Masculino" ? 'checked' : '';
                                  $masCheck = $student->genero=="Femenino" ? 'checked' : '';
                              @endphp
                              <center>
                                  <input type="radio" class="btn-check"  name="genero" id="masc" value="Masculino" {{$masCheck}} required>
                                  <label class="btn btn-sm btn-outline-secondary" for="masc"><i><img src="{{asset('images/icono-niño.png')}}" alt="icono-niño" width="45px"></i></label>
                                  <input type="radio" class="btn-check" name="genero" id="fem" value="Femenino" {{$femCheck}}>
                                  <label class="btn btn-sm btn-outline-secondary" for="fem"><i><img src="{{asset('images/icono-niña.png')}}" alt="icono-niña" width="45px"></i></label>
                                  <div class="valid-feedback">Gender field is valid!</div>
                                  <div class="invalid-feedback">Gender field cannot be blank!</div>
                              </center>
                            </div>
                            <div class="col-md-4 my-auto ">
                              <input class="form-control" type="text" name="PesoDeportista" id="PesoDeportista" value="{{ old('PesoDeportista', $student->PesoDeportista) }}" required>
                              <div class="valid-feedback">Peso field is valid!</div>
                              <div class="invalid-feedback">Peso field cannot be blank!</div>
                            </div>
                          </div>
                          <div class="input-group">
                            <div class="col-md-4 my-auto ">
                              <input class="form-control" type="text" name="EstaturaDeportista" id="EstaturaDeportista" value="{{ old('EstaturaDeportista', $student->EstaturaDeportista) }}" required>
                              <div class="valid-feedback">Estatura field is valid!</div>
                              <div class="invalid-feedback">Estatura field cannot be blank!</div>
                            </div>
                            <div class="col-md-1 my-auto "></div>
                            <div class="col-md-2 my-auto ">
                              <input class="form-control" type="text" name="RHDeportista" id="RHDeportista" value="{{ old('RHDeportista', $student->RHDeportista) }}" required>
                              <div class="valid-feedback">RH field is valid!</div>
                              <div class="invalid-feedback">RH field cannot be blank!</div>
                            </div>
                            <div class="col-md-1 my-auto "></div>
                            <div class="col-md-4 my-auto mt-4">
                              <input class="form-control" type="date" name="fechaNacimiento" id="fechaNacimiento" value="{{ old('fechaNacimiento', $student->fechaNacimiento) }}" required>
                              <div class="valid-feedback mv-up">You selected a fecha de nacimiento!</div>
                              <div class="invalid-feedback mv-up">Please select a fecha de nacimiento!</div>
                              Fecha de nacimiento
                            </div>
                          </div>
                          <div class="input-group">
                            <div class="col-md-5  mx-auto">
                              <input class="form-control" type="text" name="Ciudad" value="{{ old('Ciudad', $student->Ciudad) }}" required>
                              <div class="valid-feedback">Ciudad field is valid!</div>
                              <div class="invalid-feedback">Ciudad field cannot be blank!</div>
                            </div>
                            <div class="col-md-2 mx-auto"></div>
                            <div class="col-md-5 mx-auto">
                                <input class="form-control" type="text" name="Departamento" value="{{ old('Departamento', $student->Departamento) }}" required>
                                <div class="valid-feedback">Departamento field is valid!</div>
                                <div class="invalid-feedback">Departamento field cannot be blank!</div>
                            </div>
                            <div class="col-md-4  mx-auto">
                              Lugar de nacimiento
                            </div>
                          </div>
                          <div class="input-group">
                            <div class="col-md-3  mx-auto">
                              <input class="form-control" type="text" name="EPS" value="{{ old('EPS', $student->EPS) }}" required>
                              <div class="valid-feedback">Eps field is valid!</div>
                              <div class="invalid-feedback">Eps field cannot be blank!</div>
                            </div>
                            <div class="col-md-1 mx-auto"></div>
                            <div class="col-md-4 mx-auto">
                              <input class="form-control" type="text" name="Colegio" value="{{ old('Colegio', $student->Colegio) }}" required>
                              <div class="valid-feedback">Colegio field is valid!</div>
                              <div class="invalid-feedback">Colegio field cannot be blank!</div>
                            </div>
                            <div class="col-md-1 mx-auto"></div>
                            <div class="col-md-3 mx-auto">
                              <input class="form-control mt-3" type="number" name="Curso" value="{{ old('Curso', $student->Curso) }}" required>
                              <div class="valid-feedback">Curso field is valid!</div>
                              <div class="invalid-feedback">Curso field cannot be blank!</div>
                            </div>
                            <div class="col-md-1 mx-auto"></div>
                          </div>
                          <div class="input-group">
                            <div class="col-md-3 mt-2">
                                <input class="form-control mr-1" type="number" name="numTelefonico" value="{{ old('numTelefonico', $student->numTelefonico) }}"  required>
                                <div class="valid-feedback">Telefono field is valid!</div>
                                <div class="invalid-feedback">Telefono field cannot be blank!</div>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-3 mt-2 mr-1">
                                <input class="form-control" type="number" name="numTelefonicoUno" value="{{ old('numTelefonicoUno', $student->numTelefonicoUno) }}" >
                                <div class="valid-feedback">Telefono field is valid!</div>
                                <div class="invalid-feedback">Telefono field cannot be blank!</div>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-3 mt-2 ">
                                <input class="form-control" type="number" name="numTelefonicoDos" value="{{ old('numTelefonicoDos', $student->numTelefonicoDos) }}" >
                                <div class="valid-feedback">Telefono field is valid!</div>
                                <div class="invalid-feedback">Telefono field cannot be blank!</div>
                            </div>
                          </div>
                          <div class="input-group">
                            <div class="col-md-12 ">
                                <input class="form-control" type="text" name="direccionDeportista" value="{{ old('direccionDeportista', $student->direccionDeportista) }}" required>
                                <div class="valid-feedback">Direccion field is valid!</div>
                                <div class="invalid-feedback">Direccion field cannot be blank!</div>
                            </div>
                            <div class="col-md-5">
                                <input class="form-control" type="text" name="barrio" value="{{ old('barrio', $student->barrio) }}" required>
                                <div class="valid-feedback">Barrio field is valid!</div>
                                <div class="invalid-feedback">Barrio field cannot be blank!</div>
                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-5">
                                <input class="form-control" type="text" name="localidad" value="{{ old('localidad', $student->localidad) }}" required>
                                <div class="valid-feedback">Localidad field is valid!</div>
                                <div class="invalid-feedback">Localidad field cannot be blank!</div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="encabezado col-md-12 mt-1">
                            {{ __('Informacion de la Madre') }}
                          </div>
                        </div>
                        <div class="container cp_oculta" id="Madre">
                            <div class="input-group">
                              <div class="col-md-6">
                                  <input class="form-control" type="text" name="nombreMama" value="{{ old('nombreMama', $student->nombreMama) }}" required>
                                  <div class="valid-feedback">Nombre field is valid!</div>
                                  <div class="invalid-feedback">Nombre field cannot be blank!</div>
                              </div>
                              <div class="col-md-1"></div>
                              <div class="col-md-5 my-auto mt-3">
                                  <input class="form-control" type="number" name="documentoMama" value="{{ old('documentoMama', $student->documentoMama) }}" required>
                                  <div class="valid-feedback">Nº documento mama field is valid!</div>
                                  <div class="invalid-feedback">Nº documento mama field cannot be blank!</div>
                              </div>
                              <div class="col-md-4 my-auto mt-3">
                                  <input class="form-control" type="number" name="telefonoMama" value="{{ old('telefonoMama', $student->telefonoMama) }}" required>
                                  <div class="valid-feedback">Telefono field is valid!</div>
                                  <div class="invalid-feedback">Telefono field cannot be blank!</div>
                              </div>
                              <div class="col-md-1"></div>
                              <div class="col-md-7">
                                  <input class="form-control" type="text" name="direccionMama" value="{{ old('direccionMama', $student->direccionMama) }}" required>
                                  <div class="valid-feedback">Direccion field is valid!</div>
                                  <div class="invalid-feedback">Direccion field cannot be blank!</div>
                              </div>
                              <div class="col-md-12">
                                  <input class="form-control" type="email" name="correoMama" value="{{ old('correoMama', $student->correoMama) }}" required>
                                  <div class="valid-feedback">Correo field is valid!</div>
                                  <div class="invalid-feedback">Correo field cannot be blank!</div>
                              </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12 encabezado mt-1">
                            {{ __('Informacion del Padre') }}
                          </div>
                        </div>
                        <div class="container cp_oculta" id="padre">
                          <div class="input-group">
                            <div class="col-md-6">
                                <input class="form-control" type="text" name="nombrePapa" value="{{ old('nombrePapa', $student->nombrePapa) }}" required>
                                <div class="valid-feedback">Nombre field is valid!</div>
                                <div class="invalid-feedback">Nombre field cannot be blank!</div>
                              </div>
                              <div class="col-md-1"></div>
                              <div class="col-md-5">
                                <input class="form-control my-auto mt-3" type="number" name="documentoPapa" value="{{ old('documentoPapa', $student->documentoPapa) }}" required>
                                <div class="valid-feedback">Documento field is valid!</div>
                                <div class="invalid-feedback">Documento field cannot be blank!</div>
                              </div>
                              <div class="col-md-4">
                                <input class="form-control my-auto mt-3" type="number" name="telefonoPapa" value="{{ old('telefonoPapa', $student->telefonoPapa) }}" required>
                                <div class="valid-feedback">Telefono field is valid!</div>
                                <div class="invalid-feedback">Telefono field cannot be blank!</div>
                              </div>
                              <div class="col-md-1"></div>
                              <div class="col-md-7">
                                <input class="form-control" type="text" name="direccionPapa" value="{{ old('direccionPapa', $student->direccionPapa) }}" required>
                                <div class="valid-feedback">Direccion field is valid!</div>
                                <div class="invalid-feedback">Direccion field cannot be blank! </div>
                            </div>
                            <div class="col-md-12">
                              <input class="form-control" type="email" name="correoPapa" value="{{ old('correoPapa', $student->correoPapa) }}" required>
                              <div class="valid-feedback">Correo field is valid!</div>
                              <div class="invalid-feedback">Correo field cannot be blank!</div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12 encabezado mt-1">
                            {{ __('Medical history') }}
                          </div>
                        </div>
                        <div class="container cp_oculta" id="responsable1">
                          <div class="input-group">
                            <div class="col-md-12">
                                <input class="form-control" type="text" name="enfermedades" placeholder="Enfermedades que padece" value="{{ old('enfermedades', $student->enfermedades) }}" required>
                                <div class="valid-feedback">Enfermedades 1er responsable field is valid!</div>
                                <div class="invalid-feedback">Enfermedades 1er responsable field cannot be blank!</div>
                            </div>
                            <div class="col-md-12">
                                <input class="form-control" type="text" name="medicamento" placeholder="Usa algun medicamento" value="{{ old('medicamento', $student->medicamento) }}" required>
                                <div class="valid-feedback">Medicamento 1er responsable field is valid!</div>
                                <div class="invalid-feedback">Medicamento 1er responsable field cannot be blank!</div>
                            </div>
                            <div class="col-md-12">
                                <input class="form-control" type="text" name="lesion" placeholder="Tiene alguna lesion congenita" value="{{ old('lesion', $student->lesion) }}" required>
                                <div class="valid-feedback">Lesion 1er responsable field is valid!</div>
                                <div class="invalid-feedback">Lesion 1er responsable field cannot be blank!</div>
                            </div>
                            <div class="col-md-12">
                                <input class="form-control" type="text" name="Cirugia" placeholder="Tiene alguna cirugia" value="{{ old('Cirugia', $student->Cirugia) }}" required>
                                <div class="valid-feedback">Cirugia 1er responsable field is valid!</div>
                                <div class="invalid-feedback">Cirugia 1er responsable field cannot be blank!</div>
                            </div>
                            <div class="col-md-12">
                                <input class="form-control" type="text" name="impedimento" placeholder="Algo que le impida pracvticar algun deporte" value="{{ old('impedimento', $student->impedimento) }}" required>
                                <div class="valid-feedback">Impedimento 1er responsable field is valid!</div>
                                <div class="invalid-feedback">Impedimento 1er responsable field cannot be blank!</div>
                            </div>
                            <div class="col-md-12">
                                <input class="form-control" type="text" name="lesionOM" placeholder="Tiene alguna lesion oseo muscular" value="{{ old('lesionOM', $student->lesionOM) }}" required>
                                <div class="valid-feedback">Lesion oseo muscular 1er responsable field is valid!</div>
                                <div class="invalid-feedback">Lesion oseo muscular 1er responsable field cannot be blank!</div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4 mx-auto">
                          <div class="form-button mt-3 ">
                              <button id="submit" type="submit" class="sombra btn btn-secondary">{{__('Add Athlete')}}</button>
                          </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
