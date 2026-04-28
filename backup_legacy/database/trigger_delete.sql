-- backup_students
-- table students
-- after
-- delete
INSERT INTO `std_deleteds`(
  `idDeleted`,
  `Photo`,
  `Categoria`,
  `fechaInscripcion`,
  `nomDeportista`,
  `numDocumento`,
  `genero`,
  `PesoDeportista`,
  `EstaturaDeportista`,
  `RHDeportista`,
  `fechaNacimiento`,
  `Ciudad`,
  `Departamento`,
  `EPS`,
  `Colegio`,
  `Curso`,
  `numTelefonico`,
  `numTelefonicoUno`,
  `numTelefonicoDos`,
  `direccionDeportista`,
  `barrio`,
  `localidad`,
  `nombreMama`,
  `documentoMama`,
  `telefonoMama`,
  `direccionMama`,
  `correoMama`,
  `nombrePapa`,
  `documentoPapa`,
  `telefonoPapa`,
  `direccionPapa`,
  `correoPapa`,
  `enfermedades`,
  `medicamento`,
  `lesion`,
  `Cirugia`,
  `impedimento`,
  `lesionOM`
) VALUES (
  old.id,
  old.Photo,
  old.Categoria,
  old.fechaInscripcion,
  old.nomDeportista,
  old.numDocumento,
  old.genero,
  old.PesoDeportista,
  old.EstaturaDeportista,
  old.RHDeportista,
  old.fechaNacimiento,
  old.Ciudad,
  old.Departamento,
  old.EPS,
  old.Colegio,
  old.Curso,
  old.numTelefonico,
  old.numTelefonicoUno,
  old.numTelefonicoDos,
  old.direccionDeportista,
  old.barrio,
  old.localidad,
  old.nombreMama,
  old.documentoMama,
  old.telefonoMama,
  old.direccionMama,
  old.correoMama,
  old.nombrePapa,
  old.documentoPapa,
  old.telefonoPapa,
  old.direccionPapa,
  old.correoPapa,
  old.enfermedades,
  old.medicamento,
  old.lesion,
  old.Cirugia,
  old.impedimento,
  old.lesionOM
)

-- charge_healt
-- table students
-- after
-- insert
INSERT INTO `control_healths`(
  `nomAlumno`,
  `numDocumento`,
  `edadAlumno`
) VALUES (
  new.nomAlumno,
  new.numDocumento,
  new.edadAlumno
)
