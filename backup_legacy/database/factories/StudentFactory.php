<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
          'Photo' => $this->faker->name(),
          'Categoria' => $this->faker->randomElement(['2005','2006','2007','2008','2009','2010','2011','2012','2013','2014','2015']),
          'fechaInscripcion' => $this->faker->date(),
          'nomDeportista' => $this->faker->userName(),
          'numDocumento' => $this->faker->unique()->randomNumber(),
          'genero' => $this->faker->randomElement(['Masculino','Femenino']),
          'PesoDeportista' => $this->faker->randomNumber(),
          'EstaturaDeportista' => $this->faker->randomFloat(),
          'RHDeportista' => $this->faker->sentence(),
          'fechaNacimiento' => $this->faker->date(),
          'Ciudad' => $this->faker->city(),
          'Departamento' => $this->faker->state(),
          'EPS' => $this->faker->randomElement(['Famisanar','Salud total']),
          'Colegio' => $this->faker->randomElement(['Instituto Yolkin','Liceo Reynel']),
          'Curso' => $this->faker->randomNumber(['6','7','8','9','10','11']),
          'numTelefonico' => $this->faker->phoneNumber(),
          'numTelefonicoUno' => $this->faker->phoneNumber(),
          'numTelefonicoDos' => $this->faker->phoneNumber(),
          'direccionDeportista' => $this->faker->address(),
          'barrio' => $this->faker->streetAddress(),
          'localidad' => $this->faker->streetAddress(),
          'nombreMama' => $this->faker->userName(),
          'documentoMama' => $this->faker->randomNumber(),
          'telefonoMama' => $this->faker->phoneNumber(),
          'direccionMama' => $this->faker->address(),
          'correoMama' => $this->faker->safeEmail(),
          'nombrePapa' => $this->faker->userName(),
          'documentoPapa' => $this->faker->randomNumber(),
          'telefonoPapa' => $this->faker->phoneNumber(),
          'direccionPapa' => $this->faker->address(),
          'correoPapa' => $this->faker->safeEmail(),
          'enfermedades' => $this->faker->randomElement(['no aplica','Si aplica']),
          'medicamento' => $this->faker->randomElement(['no aplica','Si aplica']),
          'lesion' => $this->faker->randomElement(['no aplica','Si aplica']),
          'Cirugia' => $this->faker->randomElement(['no aplica','Si aplica']),
          'impedimento' => $this->faker->randomElement(['no aplica','Si aplica']),
          'lesionOM' => $this->faker->randomElement(['no aplica','Si aplica']),
        ];
    }
}
