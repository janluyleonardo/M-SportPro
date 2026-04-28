<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Manage Incomes') }}
    </h2>
  </x-slot>
  <div class="container my-auto">
    <div class="row py-0">
      <div class="col-md-6 mx-auto py-1">
        <div class="card shadow">
          <div class="form-body">
            <form action="{{ route('students.store') }}" method="post" class="requires-validation" enctype="multipart/form-data" novalidate>
              @csrf
              <div class="container mt-2 mb-2">
                <div class="row">
                  <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="accordion" id="accordionExample">
                      <div class="accordion-item">
                        <h2 class="accordion-header">
                          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            {{ __('Athlete Information') }}
                          </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                          <div class="accordion-body">
                            <div class="row">
                              <div class="col-md-4 mx-auto mt-2 text-center">
                                <input class="form-control form-control-sm" type="date" name="fechaNacimiento" id="fechaNacimiento" required>
                                <span style="font-size: 0.75em;">Fecha de nacimiento</span>
                                <div class="valid-feedback mv-up">You selected a fecha de nacimiento!</div>
                                <div class="invalid-feedback mv-up">Please select a fecha de nacimiento!</div>
                              </div>
                              <div class="col-md-4 mb-1 mt-2 text-center">
                                <input class="form-control form-control-sm" type="date" name="fechaInscripcion" required>
                                <span style="font-size: 0.75em;">Fecha de {{ __('Registration') }}</span>
                                <div class="valid-feedback mv-up">You selected a fecha de matricula!</div>
                                <div class="invalid-feedback mv-up">Please select a fecha de matricula!</div>
                              </div>
                              <div class="col-md-4 mx-auto mt-2 text-center" >
                                <input type="radio" class="btn-check mx-auto" name="genero" id="masc" value="Masculino" required>
                                <label class="btn btn-sm btn-outline-secondary" for="masc">
                                  <i>
                                    <img src="{{asset('images/icono-niño.png')}}" alt="icono-niño" width="24px">
                                  </i>
                                </label>
                                <input type="radio" class="btn-check mx-auto" name="genero" id="fem" value="Femenino" required>
                                <label class="btn btn-sm btn-outline-secondary" for="fem">
                                  <i>
                                    <img src="{{asset('images/icono-niña.png')}}" alt="icono-niña" width="24px">
                                  </i>
                                </label><br>
                                <span style="font-size: 0.75em;">Genero</span>
                                <div class="valid-feedback">Gender field is valid!</div>
                                <div class="invalid-feedback">Gender field cannot be blank!</div>
                              </div>
                              <div class="col-md-12">
                                  <input class="form-control form-control-sm mt-2" type="file" name="Photo" accept="image/png, image/jpeg" required>
                                  <div class="valid-feedback">UserPhoto field is valid!</div>
                                  <div class="invalid-feedback">UserPhoto field cannot be blank!</div>
                              </div>
                              <div class="col-md-12">
                                  <input class="form-control form-control-sm mt-2" type="text" name="nomDeportista" placeholder="{{ __('Full Name') }}" required>
                                  <div class="valid-feedback">Username field is valid!</div>
                                  <div class="invalid-feedback">Username field cannot be blank!</div>
                              </div>
                              <div class="col-md-3 mx-auto mt-2">
                                <input class="form-control form-control-sm" type="number" name="numDocumento" placeholder="N° documento" required>
                                <div class="valid-feedback">Numero de documento field is valid!</div>
                                <div class="invalid-feedback">Numero de documento field cannot be blank!</div>
                              </div>
                              <div class="col-md-2 mx-auto mt-2">
                                <input class="form-control form-control-sm" type="text" name="PesoDeportista" id="PesoDeportista" placeholder="Peso" required>
                                <div class="valid-feedback">Peso field is valid!</div>
                                <div class="invalid-feedback">Peso field cannot be blank!</div>
                              </div>
                              <div class="col-md-2 mx-auto mt-2 ">
                                <input class="form-control form-control-sm" type="text" name="EstaturaDeportista" id="EstaturaDeportista" placeholder="Estatura" required>
                                <div class="valid-feedback">Estatura field is valid!</div>
                                <div class="invalid-feedback">Estatura field cannot be blank!</div>
                              </div>
                              <div class="col-md-2 mx-auto mt-2">
                                <input class="form-control form-control-sm" type="text" name="RHDeportista" id="RHDeportista" placeholder="RH" required>
                                <div class="valid-feedback">RH field is valid!</div>
                                <div class="invalid-feedback">RH field cannot be blank!</div>
                              </div>
                              <div class="col-md-3 mx-auto mt-2">
                                <input class="form-control form-control-sm" type="text" name="EPS" placeholder="EPS" required>
                                <div class="valid-feedback">Eps field is valid!</div>
                                <div class="invalid-feedback">Eps field cannot be blank!</div>
                              </div>
                              <div class="col-md-4 mx-auto mt-2">
                                <input class="form-control form-control-sm" type="text" name="Ciudad" placeholder="Ciudad" required>
                                <div class="valid-feedback">Ciudad field is valid!</div>
                                <div class="invalid-feedback">Ciudad field cannot be blank!</div>
                              </div>
                              <div class="col-md-4 mx-auto mt-2">
                                <input class="form-control form-control-sm" type="text" name="Departamento" placeholder="Departamento" required>
                                <div class="valid-feedback">Departamento field is valid!</div>
                                <div class="invalid-feedback">Departamento field cannot be blank!</div>
                              </div>
                              <div class="col-md-4 mb-1 mt-2 text-center">
                                <select class="form-control form-control-sm" name="Categoria" id="Categoria" required>
                                  <option value="">Año nacimiento</option>
                                  <option value="2018">2018</option>
                                  <option value="2017">2017</option>
                                  <option value="2016">2016</option>
                                  <option value="2014">2014</option>
                                  <option value="2013">2013</option>
                                  <option value="2012">2012</option>
                                  <option value="2011">2011</option>
                                  <option value="2010">2010</option>
                                  <option value="2009">2009</option>
                                  <option value="2008">2008</option>
                                  <option value="2007">2007</option>
                                  <option value="2006">2006</option>
                                  <option value="2005">2005</option>
                                </select>
                                <div class="valid-feedback mv-up">You selected a Categoria!</div>
                                <div class="invalid-feedback mv-up">Please select a Categoria!</div>
                              </div>
                              <div class="col-md-6 mx-auto mt-2">
                                <input class="form-control form-control-sm" type="text" name="Colegio" placeholder="Colegio" required>
                                <div class="valid-feedback">Colegio field is valid!</div>
                                <div class="invalid-feedback">Colegio field cannot be blank!</div>
                              </div>
                              <div class="col-md-6 mx-auto mt-2">
                                <input class="form-control form-control-sm" type="number" name="Curso" placeholder="Curso" required>
                                <div class="valid-feedback">Curso field is valid!</div>
                                <div class="invalid-feedback">Curso field cannot be blank!</div>
                              </div>
                              <div class="col-md-4 mx-auto mt-2">
                                  <input class="form-control form-control-sm" type="number" name="numTelefonico" placeholder="N° telefono" required>
                                  <div class="valid-feedback">Telefono field is valid!</div>
                                  <div class="invalid-feedback">Telefono field cannot be blank!</div>
                              </div>
                              <div class="col-md-4 mx-auto mt-2 mr-1">
                                  <input class="form-control form-control-sm" type="number" name="numTelefonicoUno" placeholder="N° telefono">
                                  <div class="valid-feedback">Telefono field is valid!</div>
                                  <div class="invalid-feedback">Telefono field cannot be blank!</div>
                              </div>
                              <div class="col-md-4 mx-auto mt-2 ">
                                  <input class="form-control form-control-sm" type="number" name="numTelefonicoDos" placeholder="N° telefono">
                                  <div class="valid-feedback">Telefono field is valid!</div>
                                  <div class="invalid-feedback">Telefono field cannot be blank!</div>
                              </div>
                              <div class="col-md-12 mt-2">
                                  <input class="form-control" type="text" name="direccionDeportista"placeholder="Direccion" required>
                                  <div class="valid-feedback">Direccion field is valid!</div>
                                  <div class="invalid-feedback">Direccion field cannot be blank!</div>
                              </div>
                              <div class="col-md-6 mx-auto mt-2">
                                  <input class="form-control" type="text" name="barrio"placeholder="Barrio" required>
                                  <div class="valid-feedback">Barrio field is valid!</div>
                                  <div class="invalid-feedback">Barrio field cannot be blank!</div>
                              </div>
                              <div class="col-md-6 mx-auto mt-2">
                                  <input class="form-control" type="text" name="localidad"placeholder="Localidad" required>
                                  <div class="valid-feedback">Localidad field is valid!</div>
                                  <div class="invalid-feedback">Localidad field cannot be blank!</div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <h2 class="accordion-header">
                          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            {{ __('Mother Information') }}
                          </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                          <div class="accordion-body">
                            <div class="row">
                              <div class="col-md-8 mx-auto">
                                <input class="form-control form-control-sm mt-2" type="text" name="nombreMama" placeholder="Nombre completo" required>
                                <div class="valid-feedback">Nombre field is valid!</div>
                                <div class="invalid-feedback">Nombre field cannot be blank!</div>
                              </div>
                              <div class="col-md-4 mx-auto">
                                <input class="form-control form-control-sm mt-2" type="number" name="documentoMama"placeholder="Nº documento" required>
                                <div class="valid-feedback">Nº documento mama field is valid!</div>
                                <div class="invalid-feedback">Nº documento mama field cannot be blank!</div>
                              </div>
                              <div class="col-md-8 mx-auto">
                                <input class="form-control form-control-sm mt-2" type="email" name="correoMama"placeholder="Correo electronico" required>
                                <div class="valid-feedback">Correo field is valid!</div>
                                <div class="invalid-feedback">Correo field cannot be blank!</div>
                              </div>
                              <div class="col-md-4 mx-auto">
                                <input class="form-control form-control-sm mt-2" type="number" name="telefonoMama" placeholder="Nº telefonico" required>
                                <div class="valid-feedback">Telefono field is valid!</div>
                                <div class="invalid-feedback">Telefono field cannot be blank!</div>
                              </div>
                              <div class="col-md-12 mx-auto">
                                <input class="form-control form-control-sm mt-2" type="text" name="direccionMama"placeholder="Direccion" required>
                                <div class="valid-feedback">Direccion field is valid!</div>
                                <div class="invalid-feedback">Direccion field cannot be blank!</div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <h2 class="accordion-header">
                          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            {{ __('Father Information') }}
                          </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                          <div class="accordion-body">
                            <div class="row">
                              <div class="col-md-8 mx-auto">
                                <input class="form-control form-control-sm mt-2" type="text" name="nombrePapa" placeholder="Nombre completo" required>
                                <div class="valid-feedback">Nombre field is valid!</div>
                                <div class="invalid-feedback">Nombre field cannot be blank!</div>
                              </div>
                              <div class="col-md-4 mx-auto">
                                <input class="form-control form-control-sm mt-2" type="number" name="documentoPapa"placeholder="Nº documento" required>
                                <div class="valid-feedback">Nº documento mama field is valid!</div>
                                <div class="invalid-feedback">Nº documento mama field cannot be blank!</div>
                              </div>
                              <div class="col-md-8 mx-auto">
                                <input class="form-control form-control-sm mt-2" type="email" name="correoPapa"placeholder="Correo electronico" required>
                                <div class="valid-feedback">Correo field is valid!</div>
                                <div class="invalid-feedback">Correo field cannot be blank!</div>
                              </div>
                              <div class="col-md-4 mx-auto">
                                <input class="form-control form-control-sm mt-2" type="number" name="telefonoPapa" placeholder="Nº telefonico" required>
                                <div class="valid-feedback">Telefono field is valid!</div>
                                <div class="invalid-feedback">Telefono field cannot be blank!</div>
                              </div>
                              <div class="col-md-12 mx-auto">
                                <input class="form-control form-control-sm mt-2" type="text" name="direccionPapa"placeholder="Direccion" required>
                                <div class="valid-feedback">Direccion field is valid!</div>
                                <div class="invalid-feedback">Direccion field cannot be blank!</div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <h2 class="accordion-header">
                          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            {{ __('Medical history') }}
                          </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                          <div class="accordion-body">
                            <div class="col-md-12 col-sm-12 col-lg-12">
                              <input class="form-control form-control-sm mt-2" type="text" name="enfermedades" placeholder="Enfermedades que padece" required>
                              <div class="valid-feedback">Enfermedades 1er responsable field is valid!</div>
                              <div class="invalid-feedback">Enfermedades 1er responsable field cannot be blank!</div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-lg-12">
                              <input class="form-control form-control-sm mt-2" type="text" name="medicamento" placeholder="Usa algun medicamento" required>
                              <div class="valid-feedback">Medicamento 1er responsable field is valid!</div>
                              <div class="invalid-feedback">Medicamento 1er responsable field cannot be blank!</div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-lg-12">
                              <input class="form-control form-control-sm mt-2" type="text" name="lesion" placeholder="Tiene alguna lesion congenita" required>
                              <div class="valid-feedback">Lesion 1er responsable field is valid!</div>
                              <div class="invalid-feedback">Lesion 1er responsable field cannot be blank!</div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-lg-12">
                              <input class="form-control form-control-sm mt-2" type="text" name="Cirugia" placeholder="Tiene alguna cirugia" required>
                              <div class="valid-feedback">Cirugia 1er responsable field is valid!</div>
                              <div class="invalid-feedback">Cirugia 1er responsable field cannot be blank!</div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-lg-12">
                              <input class="form-control form-control-sm mt-2" type="text" name="impedimento" placeholder="Algo que le impida pracvticar algun deporte" required>
                              <div class="valid-feedback">Impedimento 1er responsable field is valid!</div>
                              <div class="invalid-feedback">Impedimento 1er responsable field cannot be blank!</div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-lg-12">
                              <input class="form-control form-control-sm mt-2" type="text" name="lesionOM" placeholder="Tiene alguna lesion oseo muscular" required>
                              <div class="valid-feedback">Lesion oseo muscular 1er responsable field is valid!</div>
                              <div class="invalid-feedback">Lesion oseo muscular 1er responsable field cannot be blank!</div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-lg-4 mx-auto">
                              <div class="form-button mt-2 ">
                                <button id="submit" type="submit" class="sombra btn btn-secondary">{{__('Add Athlete')}}</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
