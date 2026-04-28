<table class="table table-striped">
  <thead>
      <tr>
          <th scope="col"><b>{{__('id')}}</b></th>
          <th scope="col"><b>{{__('nivel')}}</b></th>
          <th scope="col"><b>{{__('fechaMatricula')}}</b></th>
          <th scope="col"><b>{{__('nomAlumno')}}</b></th>
          <th scope="col"><b>{{__('fechaNacimiento')}}</b></th>
          <th scope="col"><b>{{__('genero')}}</b></th>
          <th scope="col"><b>{{__('EdadAlumno')}}</b></th>
          <th scope="col"><b>{{__('documentType')}}</b></th>
          <th scope="col"><b>{{__('numDocumento')}}</b></th>
          <th scope="col"><b>{{__('Esalud')}}</b></th>
          <th scope="col"><b>{{__('EPS')}}</b></th>
          <th scope="col"><b>{{__('numTelefonico')}}</b></th>
          <th scope="col"><b>{{__('numTelefonicoUno')}}</b></th>
          <th scope="col"><b>{{__('numTelefonicoDos')}}</b></th>
          <th scope="col"><b>{{__('direccionAlumno')}}</b></th>
          <th scope="col"><b>{{__('barrio')}}</b></th>
          <th scope="col"><b>{{__('localidad')}}</b></th>
          <th scope="col"><b>{{__('nombreMama')}}</b></th>
          <th scope="col"><b>{{__('documentoMama')}}</b></th>
          <th scope="col"><b>{{__('telefonoMama')}}</b></th>
          <th scope="col"><b>{{__('direccionMama')}}</b></th>
          <th scope="col"><b>{{__('correoMama')}}</b></th>
          <th scope="col"><b>{{__('nombrePapa')}}</b></th>
          <th scope="col"><b>{{__('documentoPapa')}}</b></th>
          <th scope="col"><b>{{__('telefonoPapa')}}</b></th>
          <th scope="col"><b>{{__('direccionPapa')}}</b></th>
          <th scope="col"><b>{{__('correoPapa')}}</b></th>
          <th scope="col"><b>{{__('nomPriRes')}}</b></th>
          <th scope="col"><b>{{__('docPriRes')}}</b></th>
          <th scope="col"><b>{{__('dirPriRes')}}</b></th>
          <th scope="col"><b>{{__('telPriRes')}}</b></th>
          <th scope="col"><b>{{__('nomSegRes')}}</b></th>
          <th scope="col"><b>{{__('docSegRes')}}</b></th>
          <th scope="col"><b>{{__('dirSegRes')}}</b></th>
          <th scope="col"><b>{{__('telSegRes')}}</b></th>
          <th scope="col"><b>{{__('nomTerRes')}}</b></th>
          <th scope="col"><b>{{__('docTerRes')}}</b></th>
          <th scope="col"><b>{{__('dirTerRes')}}</b></th>
          <th scope="col"><b>{{__('telTerRes')}}</b></th>
      </tr>
  </thead>
  <tbody>
      @foreach($Students as $Student)
      <tr>
        <td>{{$Student->id}}</td>
        <td>{{$Student->nivel}}</td>
        <td>{{$Student->fechaMatricula}}</td>
        <td>{{$Student->nomAlumno}}</td>
        <td>{{$Student->fechaNacimiento}}</td>
        <td>{{$Student->genero}}</td>
        <td>{{$Student->EdadAlumno}}</td>
        <td>{{$Student->documentType}}</td>
        <td>{{$Student->numDocumento}}</td>
        <td>{{$Student->Esalud}}</td>
        <td>{{$Student->EPS}}</td>
        <td>{{$Student->numTelefonico}}</td>
        <td>{{$Student->numTelefonicoUno}}</td>
        <td>{{$Student->numTelefonicoDos}}</td>
        <td>{{$Student->direccionAlumno}}</td>
        <td>{{$Student->barrio}}</td>
        <td>{{$Student->localidad}}</td>
        <td>{{$Student->nombreMama}}</td>
        <td>{{$Student->documentoMama}}</td>
        <td>{{$Student->telefonoMama}}</td>
        <td>{{$Student->direccionMama}}</td>
        <td>{{$Student->correoMama}}</td>
        <td>{{$Student->nombrePapa}}</td>
        <td>{{$Student->documentoPapa}}</td>
        <td>{{$Student->telefonoPapa}}</td>
        <td>{{$Student->direccionPapa}}</td>
        <td>{{$Student->correoPapa}}</td>
        <td>{{$Student->nomPriRes}}</td>
        <td>{{$Student->docPriRes}}</td>
        <td>{{$Student->dirPriRes}}</td>
        <td>{{$Student->telPriRes}}</td>
        <td>{{$Student->nomSegRes}}</td>
        <td>{{$Student->docSegRes}}</td>
        <td>{{$Student->dirSegRes}}</td>
        <td>{{$Student->telSegRes}}</td>
        <td>{{$Student->nomTerRes}}</td>
        <td>{{$Student->docTerRes}}</td>
        <td>{{$Student->dirTerRes}}</td>
        <td>{{$Student->telTerRes}}</td>
      </tr>
      @endforeach
  </tbody>
</table>
