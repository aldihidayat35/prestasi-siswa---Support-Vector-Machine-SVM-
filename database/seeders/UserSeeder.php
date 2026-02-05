<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $guruRole = Role::where('name', 'guru')->first();

        // Create Admin user
        User::updateOrCreate(
            ['email' => 'admin@sman2bukittinggi.sch.id'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'is_active' => true,
            ]
        );

        // Create Guru users
        $gurus = [
            [
                'name' => 'Dr. Ahmad Syafii, M.Pd',
                'email' => 'ahmad.syafii@sman2bukittinggi.sch.id',
                'phone' => '081234567891',
            ],
            [
                'name' => 'Dra. Siti Nurhaliza, M.Si',
                'email' => 'siti.nurhaliza@sman2bukittinggi.sch.id',
                'phone' => '081234567892',
            ],
            [
                'name' => 'Budi Santoso, S.Pd',
                'email' => 'budi.santoso@sman2bukittinggi.sch.id',
                'phone' => '081234567893',
            ],
        ];

        foreach ($gurus as $guru) {
            User::updateOrCreate(
                ['email' => $guru['email']],
                [
                    'role_id' => $guruRole->id,
                    'name' => $guru['name'],
                    'password' => Hash::make('password'),
                    'phone' => $guru['phone'],
                    'is_active' => true,
                ]
            );
        }
    }
}
