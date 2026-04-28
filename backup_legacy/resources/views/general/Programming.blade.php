<x-general-layout>
  <x-slot name="header">
    <div class="container">
      <div class="row">
        <div class="col-md-1">
          <x-jet-application-mark/>
        </div>
        <div class="col-md-11">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <x-navbarMain/>
          </h2>
        </div>
      </div>
    </div>
  </x-slot>
  <div class="py-3">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="mt-3 text-gray-500">
                <table class="table table-striped table-hover">
                  <thead>
                    <tr>
                      <th>Torneo</th>
                      <th>Cancha</th>
                      <th>Fecha</th>
                      <th>Hora</th>
                      <th>Categoria</th>
                      <th>Local</th>
                      <th>Visitante</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($programming as $item)
                    <tr>
                      <td>{{Str::title($item->torneo)}}</td>
                      <td>{{$item->cancha}}</td>
                      <td>{{$item->fecha}}</td>
                      <td>{{$item->hora}}</td>
                      <td>{{$item->categoria}}</td>
                      <td>{{$item->eLocal}}</td>
                      <td>{{$item->eVisitante}}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                {{-- @foreach ($programming as $item)
                  <div class="row mt-1">
                    <div class="col-sm-3 bg-danger">
                      {{ Str::title($item->torneo) }}
                    </div>
                    <div class="col-md-3 borde-top borde-bottom">
                      <div class="col-md-12">{{$item->categoria}}</div>
                      <div class="col-md-12">{{$item->eLocal}}</div>
                    </div>
                    <div class="col-md-3 borde-top borde-bottom">
                      <div class="col-md-12">{{$item->hora}}</div>
                      <div class="col-md-12">{{$item->eVisitante}}</div>
                    </div>
                    <div class="col-md-3 borde-top borde-bottom borde-right b-t-r-r b-b-r-r">
                      <div class="col-md-12">{{$item->fecha}}</div>
                    </div>
                  </div>
                  @endforeach --}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-general-layout>
