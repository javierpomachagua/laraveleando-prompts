<?php

namespace App\Console\Commands;

use App\Models\Country;
use Illuminate\Console\Command;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\password;
use function Laravel\Prompts\search;
use function Laravel\Prompts\select;
use function Laravel\Prompts\suggest;
use function Laravel\Prompts\text;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = text(
            label: 'Ingrese su nombre',
            placeholder: 'Gokú',
            required: true
        );

        $email = text(
            label: 'Ingrese su email',
            placeholder: 'goku@dbz.com',
            required: true,
            validate: fn (string $value) => match (true) {
                !preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $value) => 'EL email es inválido',
                default => null
            }
        );

        $password = password(
            label: 'Ingrese su password',
            required: true,
        );

        $isAdmin = confirm(
            label: '¿Es Administrador?',
            yes: 'Sí',
            no: 'No'
        );

        $maritalStatus = select(
            label: '¿Estado Civil?',
            options: [
                'Soltero', 'Casado', 'Divorciado', 'Viudo'
            ]
        );

        $interests = multiselect(
            label: '¿Cuáles son sus intereses?',
            options: [
                'Deportes',
                'Farándula',
                'Comida',
                'Viajes',
                'Política'
            ]
        );

        $hobbies = suggest(
            label: '¿Cuáles son sus hobbies?',
            options: [
                'Jugar videojuegos',
                'Ir al cine',
                'Jugar fútbol'
            ]
        );

        $country = search(
            label: '¿Cuál es su país?',
            options: fn(string $value) =>
                Country::where('name', 'like', "%{$value}%")
                    ->pluck('name', 'id')
                    ->all()
        );


    }
}
