<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'guard_name' => 'web',
                'description' => 'Administrator sistem dengan akses penuh ke semua fitur termasuk manajemen user, data siswa, dan machine learning.',
            ],
            [
                'name' => 'guru',
                'guard_name' => 'web',
                'description' => 'Guru dapat menginput data aktivitas belajar siswa dan melihat hasil prediksi prestasi akademik.',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
