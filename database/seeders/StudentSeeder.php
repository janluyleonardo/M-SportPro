<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            // ── Categoría 2008 (8 deportistas) ─────────────────────────────
            ['nomDeportista' => 'Carlos Andrés Pérez Gómez',       'numDocumento' => 1001230001, 'fechaNacimiento' => '2008-03-15', 'fechaInscripcion' => '2026-01-10', 'Categoria' => '2008', 'genero' => 'Masculino'],
            ['nomDeportista' => 'Sebastián López Martínez',         'numDocumento' => 1001230002, 'fechaNacimiento' => '2008-07-22', 'fechaInscripcion' => '2026-01-10', 'Categoria' => '2008', 'genero' => 'Masculino'],
            ['nomDeportista' => 'Felipe Cardona Herrera',           'numDocumento' => 1001230003, 'fechaNacimiento' => '2008-04-11', 'fechaInscripcion' => '2026-01-12', 'Categoria' => '2008', 'genero' => 'Masculino'],
            ['nomDeportista' => 'Brayan Estrada Quintero',          'numDocumento' => 1001230004, 'fechaNacimiento' => '2008-09-28', 'fechaInscripcion' => '2026-01-14', 'Categoria' => '2008', 'genero' => 'Masculino'],
            ['nomDeportista' => 'Valentina Torres Rodríguez',       'numDocumento' => 1001230005, 'fechaNacimiento' => '2008-11-05', 'fechaInscripcion' => '2026-01-12', 'Categoria' => '2008', 'genero' => 'Femenino'],
            ['nomDeportista' => 'Daniela Ramírez Cruz',             'numDocumento' => 1001230006, 'fechaNacimiento' => '2008-02-18', 'fechaInscripcion' => '2026-01-15', 'Categoria' => '2008', 'genero' => 'Femenino'],
            ['nomDeportista' => 'Natalia Ospina Vargas',            'numDocumento' => 1001230007, 'fechaNacimiento' => '2008-06-30', 'fechaInscripcion' => '2026-01-16', 'Categoria' => '2008', 'genero' => 'Femenino'],
            ['nomDeportista' => 'Laura Sofía Ríos Salcedo',         'numDocumento' => 1001230008, 'fechaNacimiento' => '2008-12-14', 'fechaInscripcion' => '2026-01-18', 'Categoria' => '2008', 'genero' => 'Femenino'],

            // ── Categoría 2010 (8 deportistas) ─────────────────────────────
            ['nomDeportista' => 'Miguel Ángel Hernández Díaz',     'numDocumento' => 1001230009, 'fechaNacimiento' => '2010-05-30', 'fechaInscripcion' => '2026-02-01', 'Categoria' => '2010', 'genero' => 'Masculino'],
            ['nomDeportista' => 'Juan Pablo Moreno Vargas',         'numDocumento' => 1001230010, 'fechaNacimiento' => '2010-09-14', 'fechaInscripcion' => '2026-02-01', 'Categoria' => '2010', 'genero' => 'Masculino'],
            ['nomDeportista' => 'Samuel Ortiz Castillo',            'numDocumento' => 1001230011, 'fechaNacimiento' => '2010-12-03', 'fechaInscripcion' => '2026-02-05', 'Categoria' => '2010', 'genero' => 'Masculino'],
            ['nomDeportista' => 'David Santiago Rueda Fuentes',     'numDocumento' => 1001230012, 'fechaNacimiento' => '2010-03-19', 'fechaInscripcion' => '2026-02-07', 'Categoria' => '2010', 'genero' => 'Masculino'],
            ['nomDeportista' => 'Diego Fernando Lozano Patiño',     'numDocumento' => 1001230013, 'fechaNacimiento' => '2010-07-08', 'fechaInscripcion' => '2026-02-08', 'Categoria' => '2010', 'genero' => 'Masculino'],
            ['nomDeportista' => 'Isabella Sánchez Reyes',           'numDocumento' => 1001230014, 'fechaNacimiento' => '2010-04-25', 'fechaInscripcion' => '2026-02-05', 'Categoria' => '2010', 'genero' => 'Femenino'],
            ['nomDeportista' => 'Mariana Aguilar Mendoza',          'numDocumento' => 1001230015, 'fechaNacimiento' => '2010-08-17', 'fechaInscripcion' => '2026-02-10', 'Categoria' => '2010', 'genero' => 'Femenino'],
            ['nomDeportista' => 'Camila Andrea Nieto Bernal',       'numDocumento' => 1001230016, 'fechaNacimiento' => '2010-11-22', 'fechaInscripcion' => '2026-02-12', 'Categoria' => '2010', 'genero' => 'Femenino'],

            // ── Categoría 2012 (8 deportistas) ─────────────────────────────
            ['nomDeportista' => 'Andrés Felipe Castro Ruiz',        'numDocumento' => 1001230017, 'fechaNacimiento' => '2012-01-20', 'fechaInscripcion' => '2026-01-20', 'Categoria' => '2012', 'genero' => 'Masculino'],
            ['nomDeportista' => 'Nicolás Bedoya Ospina',            'numDocumento' => 1001230018, 'fechaNacimiento' => '2012-06-11', 'fechaInscripcion' => '2026-01-20', 'Categoria' => '2012', 'genero' => 'Masculino'],
            ['nomDeportista' => 'Tomás Jiménez Ríos',               'numDocumento' => 1001230019, 'fechaNacimiento' => '2012-10-28', 'fechaInscripcion' => '2026-01-22', 'Categoria' => '2012', 'genero' => 'Masculino'],
            ['nomDeportista' => 'Esteban Guzmán Correa',            'numDocumento' => 1001230020, 'fechaNacimiento' => '2012-04-05', 'fechaInscripcion' => '2026-01-23', 'Categoria' => '2012', 'genero' => 'Masculino'],
            ['nomDeportista' => 'Santiago Restrepo Medina',         'numDocumento' => 1001230021, 'fechaNacimiento' => '2012-08-15', 'fechaInscripcion' => '2026-01-24', 'Categoria' => '2012', 'genero' => 'Masculino'],
            ['nomDeportista' => 'Lucía Fernández Gutiérrez',        'numDocumento' => 1001230022, 'fechaNacimiento' => '2012-03-07', 'fechaInscripcion' => '2026-01-25', 'Categoria' => '2012', 'genero' => 'Femenino'],
            ['nomDeportista' => 'Sofía Vélez Palomino',             'numDocumento' => 1001230023, 'fechaNacimiento' => '2012-07-19', 'fechaInscripcion' => '2026-01-25', 'Categoria' => '2012', 'genero' => 'Femenino'],
            ['nomDeportista' => 'María Paula Acevedo Soto',         'numDocumento' => 1001230024, 'fechaNacimiento' => '2012-12-01', 'fechaInscripcion' => '2026-01-27', 'Categoria' => '2012', 'genero' => 'Femenino'],

            // ── Categoría 2014 (8 deportistas) ─────────────────────────────
            ['nomDeportista' => 'Emanuel Suárez Muñoz',             'numDocumento' => 1001230025, 'fechaNacimiento' => '2014-02-14', 'fechaInscripcion' => '2026-03-01', 'Categoria' => '2014', 'genero' => 'Masculino'],
            ['nomDeportista' => 'Alejandro Parra Niño',             'numDocumento' => 1001230026, 'fechaNacimiento' => '2014-09-09', 'fechaInscripcion' => '2026-03-01', 'Categoria' => '2014', 'genero' => 'Masculino'],
            ['nomDeportista' => 'Jerónimo Cano Villarreal',         'numDocumento' => 1001230027, 'fechaNacimiento' => '2014-05-25', 'fechaInscripcion' => '2026-03-03', 'Categoria' => '2014', 'genero' => 'Masculino'],
            ['nomDeportista' => 'Simón Arbeláez Montoya',           'numDocumento' => 1001230028, 'fechaNacimiento' => '2014-10-17', 'fechaInscripcion' => '2026-03-03', 'Categoria' => '2014', 'genero' => 'Masculino'],
            ['nomDeportista' => 'Juan Esteban Melo Trujillo',       'numDocumento' => 1001230029, 'fechaNacimiento' => '2014-01-30', 'fechaInscripcion' => '2026-03-04', 'Categoria' => '2014', 'genero' => 'Masculino'],
            ['nomDeportista' => 'Valeria Cárdenas Espinosa',        'numDocumento' => 1001230030, 'fechaNacimiento' => '2014-04-23', 'fechaInscripcion' => '2026-03-05', 'Categoria' => '2014', 'genero' => 'Femenino'],
            ['nomDeportista' => 'Sara Milena Acosta Bermúdez',      'numDocumento' => 1001230031, 'fechaNacimiento' => '2014-11-30', 'fechaInscripcion' => '2026-03-05', 'Categoria' => '2014', 'genero' => 'Femenino'],
            ['nomDeportista' => 'Gabriela Ximena Rojas Alvarado',   'numDocumento' => 1001230032, 'fechaNacimiento' => '2014-07-12', 'fechaInscripcion' => '2026-03-07', 'Categoria' => '2014', 'genero' => 'Femenino'],

            // ── Categoría 2016 (8 deportistas) ─────────────────────────────
            ['nomDeportista' => 'Mateo Giraldo Quintero',           'numDocumento' => 1001230033, 'fechaNacimiento' => '2016-05-16', 'fechaInscripcion' => '2026-03-10', 'Categoria' => '2016', 'genero' => 'Masculino'],
            ['nomDeportista' => 'Salomón Pedraza Ávila',            'numDocumento' => 1001230034, 'fechaNacimiento' => '2016-02-28', 'fechaInscripcion' => '2026-03-10', 'Categoria' => '2016', 'genero' => 'Masculino'],
            ['nomDeportista' => 'Nicolás Fuentes Chávez',           'numDocumento' => 1001230035, 'fechaNacimiento' => '2016-08-19', 'fechaInscripcion' => '2026-03-11', 'Categoria' => '2016', 'genero' => 'Masculino'],
            ['nomDeportista' => 'Martín Aldana Serrano',            'numDocumento' => 1001230036, 'fechaNacimiento' => '2016-11-07', 'fechaInscripcion' => '2026-03-11', 'Categoria' => '2016', 'genero' => 'Masculino'],
            ['nomDeportista' => 'Joaquín Posada Reina',             'numDocumento' => 1001230037, 'fechaNacimiento' => '2016-03-23', 'fechaInscripcion' => '2026-03-12', 'Categoria' => '2016', 'genero' => 'Masculino'],
            ['nomDeportista' => 'Ana Camila Londoño Zapata',        'numDocumento' => 1001230038, 'fechaNacimiento' => '2016-08-04', 'fechaInscripcion' => '2026-03-10', 'Categoria' => '2016', 'genero' => 'Femenino'],
            ['nomDeportista' => 'Emilia Guerrero Salamanca',        'numDocumento' => 1001230039, 'fechaNacimiento' => '2016-06-13', 'fechaInscripcion' => '2026-03-13', 'Categoria' => '2016', 'genero' => 'Femenino'],
            ['nomDeportista' => 'Antonella Vargas Pinzón',          'numDocumento' => 1001230040, 'fechaNacimiento' => '2016-12-29', 'fechaInscripcion' => '2026-03-14', 'Categoria' => '2016', 'genero' => 'Femenino'],
        ];

        foreach ($students as $data) {
            Student::firstOrCreate(
                ['numDocumento' => $data['numDocumento']],
                array_merge($data, [
                    'Photo'              => '',
                    'PesoDeportista'     => rand(30, 65),
                    'EstaturaDeportista' => rand(120, 175) . ' cm',
                    'RHDeportista'       => collect(['O+', 'A+', 'B+', 'AB+', 'O-'])->random(),
                    'direccionDeportista'=> 'Calle ' . rand(1, 100) . ' # ' . rand(1, 50) . '-' . rand(1, 99),
                    'barrio'             => collect(['La Esperanza', 'El Prado', 'San José', 'Villa Nueva', 'Centro'])->random(),
                    'localidad'          => 'Kennedy',
                    'Ciudad'             => 'Bogotá',
                    'numTelefonico'      => '31' . rand(10000000, 99999999),
                    'numTelefonicoUno'   => null,
                    'numTelefonicoDos'   => null,
                    'Colegio'            => collect(['Colegio Nacional', 'Instituto Técnico', 'Liceo Moderno', 'IED'])->random(),
                    'Curso'              => (string) rand(1, 11),
                    'Departamento'       => 'Cundinamarca',
                    'EPS'                => collect(['Sura', 'Nueva EPS', 'Sanitas', 'Compensar', 'Cafam'])->random(),
                    'nombreMama'         => 'María ' . collect(['García', 'López', 'Martínez', 'Rodríguez'])->random(),
                    'documentoMama'      => rand(40000000, 55000000),
                    'telefonoMama'       => '31' . rand(10000000, 99999999),
                    'correoMama'         => 'mama' . rand(100, 999) . '@gmail.com',
                    'direccionMama'      => 'Carrera ' . rand(1, 80) . ' # ' . rand(1, 40) . '-' . rand(1, 99),
                    'nombrePapa'         => collect(['Juan', 'Carlos', 'Luis', 'Pedro'])->random() . ' ' . collect(['García', 'López', 'Martínez'])->random(),
                    'documentoPapa'      => rand(70000000, 85000000),
                    'telefonoPapa'       => '31' . rand(10000000, 99999999),
                    'correoPapa'         => 'papa' . rand(100, 999) . '@gmail.com',
                    'direccionPapa'      => 'Avenida ' . rand(1, 60) . ' # ' . rand(1, 30) . '-' . rand(1, 99),
                    'enfermedades'       => 'Ninguna',
                    'medicamento'        => 'Ninguno',
                    'lesion'             => 'No',
                    'Cirugia'            => 'No',
                    'impedimento'        => 'Ninguno',
                    'lesionOM'           => 'Ninguna',
                ])
            );
        }

        $this->command->info('✅ 40 estudiantes de prueba creados (8 por categoría).');
    }
}
