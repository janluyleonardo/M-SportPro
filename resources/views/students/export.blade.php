<table class="table table-striped">
  <thead>
      <tr>
          <th scope="col"><b>ID</b></th>
          <th scope="col"><b>Categoría</b></th>
          <th scope="col"><b>Fecha Inscripción</b></th>
          <th scope="col"><b>Nombre Deportista</b></th>
          <th scope="col"><b>Documento</b></th>
          <th scope="col"><b>Género</b></th>
          <th scope="col"><b>Peso</b></th>
          <th scope="col"><b>Estatura</b></th>
          <th scope="col"><b>RH</b></th>
          <th scope="col"><b>Fecha Nacimiento</b></th>
          <th scope="col"><b>Ciudad</b></th>
          <th scope="col"><b>Departamento</b></th>
          <th scope="col"><b>EPS</b></th>
          <th scope="col"><b>Colegio</b></th>
          <th scope="col"><b>Curso</b></th>
          <th scope="col"><b>Teléfono</b></th>
          <th scope="col"><b>Teléfono 1</b></th>
          <th scope="col"><b>Teléfono 2</b></th>
          <th scope="col"><b>Dirección</b></th>
          <th scope="col"><b>Barrio</b></th>
          <th scope="col"><b>Localidad</b></th>
          <th scope="col"><b>Nombre Mamá</b></th>
          <th scope="col"><b>Documento Mamá</b></th>
          <th scope="col"><b>Teléfono Mamá</b></th>
          <th scope="col"><b>Dirección Mamá</b></th>
          <th scope="col"><b>Correo Mamá</b></th>
          <th scope="col"><b>Nombre Papá</b></th>
          <th scope="col"><b>Documento Papá</b></th>
          <th scope="col"><b>Teléfono Papá</b></th>
          <th scope="col"><b>Dirección Papá</b></th>
          <th scope="col"><b>Correo Papá</b></th>
          <th scope="col"><b>Enfermedades</b></th>
          <th scope="col"><b>Medicamento</b></th>
          <th scope="col"><b>Lesión</b></th>
          <th scope="col"><b>Cirugía</b></th>
          <th scope="col"><b>Impedimento</b></th>
          <th scope="col"><b>Lesión O.M.</b></th>
          <th scope="col"><b>Saldo</b></th>
      </tr>
  </thead>
  <tbody>
      @foreach($Students as $Student)
      <tr>
          <td>{{ $Student->id }}</td>
          <td>{{ $Student->Categoria }}</td>
          <td>{{ $Student->fechaInscripcion }}</td>
          <td>{{ $Student->nomDeportista }}</td>
          <td>{{ $Student->numDocumento }}</td>
          <td>{{ $Student->genero }}</td>
          <td>{{ $Student->PesoDeportista }}</td>
          <td>{{ $Student->EstaturaDeportista }}</td>
          <td>{{ $Student->RHDeportista }}</td>
          <td>{{ $Student->fechaNacimiento }}</td>
          <td>{{ $Student->Ciudad }}</td>
          <td>{{ $Student->Departamento }}</td>
          <td>{{ $Student->EPS }}</td>
          <td>{{ $Student->Colegio }}</td>
          <td>{{ $Student->Curso }}</td>
          <td>{{ $Student->numTelefonico }}</td>
          <td>{{ $Student->numTelefonicoUno }}</td>
          <td>{{ $Student->numTelefonicoDos }}</td>
          <td>{{ $Student->direccionDeportista }}</td>
          <td>{{ $Student->barrio }}</td>
          <td>{{ $Student->localidad }}</td>
          <td>{{ $Student->nombreMama }}</td>
          <td>{{ $Student->documentoMama }}</td>
          <td>{{ $Student->telefonoMama }}</td>
          <td>{{ $Student->direccionMama }}</td>
          <td>{{ $Student->correoMama }}</td>
          <td>{{ $Student->nombrePapa }}</td>
          <td>{{ $Student->documentoPapa }}</td>
          <td>{{ $Student->telefonoPapa }}</td>
          <td>{{ $Student->direccionPapa }}</td>
          <td>{{ $Student->correoPapa }}</td>
          <td>{{ $Student->enfermedades }}</td>
          <td>{{ $Student->medicamento }}</td>
          <td>{{ $Student->lesion }}</td>
          <td>{{ $Student->Cirugia }}</td>
          <td>{{ $Student->impedimento }}</td>
          <td>{{ $Student->lesionOM }}</td>
          <td>{{ $Student->balance }}</td>
      </tr>
      @endforeach
  </tbody>
</table>
