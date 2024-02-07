<?php

namespace Database\Seeders;

use App\Models\Ecole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
        public function run()
    {
        $userData = [
            'nom' => 'Diatta',
            'prenom' => 'Noel',
            'email' => 'noel@gmail.com',
            'photo' => 'photo',
            'telephone' => '771111100',
            'password' => bcrypt('passer@123'),
            'role_id' => 2,
            'adresse' => 'New Orléan',
            'civilite' => 'monsieur',
            'ecole_id' => 1
        ];
        $user = new User($userData);
        $ecole = Ecole::find($userData['ecole_id']);
        if ($ecole) {
            $user->ecole()->associate($ecole);
        }
        $user->save();
    }
    }

