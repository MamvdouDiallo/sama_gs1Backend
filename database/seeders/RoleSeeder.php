<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $roles = [
            [
                'id' => 1,
                'libelle' => 'Admin',
            ],
            [
                'id' => 2,
                'libelle' => 'Super admin',
            ],
            [
                'id' => 3,
                'libelle' => 'Responsable pédagogique',
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(['id' => $roleData['id']], $roleData);
        }
    }
}
