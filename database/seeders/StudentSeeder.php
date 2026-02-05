<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            // Kelas XI IPA 1
            ['nis' => '2024001', 'name' => 'Andi Pratama', 'class' => 'XI IPA 1', 'gender' => 'L'],
            ['nis' => '2024002', 'name' => 'Budi Santoso', 'class' => 'XI IPA 1', 'gender' => 'L'],
            ['nis' => '2024003', 'name' => 'Citra Dewi', 'class' => 'XI IPA 1', 'gender' => 'P'],
            ['nis' => '2024004', 'name' => 'Dina Permata', 'class' => 'XI IPA 1', 'gender' => 'P'],
            ['nis' => '2024005', 'name' => 'Eko Wijaya', 'class' => 'XI IPA 1', 'gender' => 'L'],
            ['nis' => '2024006', 'name' => 'Fitri Handayani', 'class' => 'XI IPA 1', 'gender' => 'P'],
            ['nis' => '2024007', 'name' => 'Gunawan Saputra', 'class' => 'XI IPA 1', 'gender' => 'L'],
            ['nis' => '2024008', 'name' => 'Hana Puspita', 'class' => 'XI IPA 1', 'gender' => 'P'],
            ['nis' => '2024009', 'name' => 'Irfan Hakim', 'class' => 'XI IPA 1', 'gender' => 'L'],
            ['nis' => '2024010', 'name' => 'Jasmine Putri', 'class' => 'XI IPA 1', 'gender' => 'P'],

            // Kelas XI IPA 2
            ['nis' => '2024011', 'name' => 'Kurniawan Putra', 'class' => 'XI IPA 2', 'gender' => 'L'],
            ['nis' => '2024012', 'name' => 'Lisa Maulida', 'class' => 'XI IPA 2', 'gender' => 'P'],
            ['nis' => '2024013', 'name' => 'Muhammad Rizki', 'class' => 'XI IPA 2', 'gender' => 'L'],
            ['nis' => '2024014', 'name' => 'Nadira Safitri', 'class' => 'XI IPA 2', 'gender' => 'P'],
            ['nis' => '2024015', 'name' => 'Oscar Ramadhan', 'class' => 'XI IPA 2', 'gender' => 'L'],
            ['nis' => '2024016', 'name' => 'Putri Amelia', 'class' => 'XI IPA 2', 'gender' => 'P'],
            ['nis' => '2024017', 'name' => 'Qori Fadillah', 'class' => 'XI IPA 2', 'gender' => 'L'],
            ['nis' => '2024018', 'name' => 'Rani Kusuma', 'class' => 'XI IPA 2', 'gender' => 'P'],
            ['nis' => '2024019', 'name' => 'Satria Darmawan', 'class' => 'XI IPA 2', 'gender' => 'L'],
            ['nis' => '2024020', 'name' => 'Tari Nugraha', 'class' => 'XI IPA 2', 'gender' => 'P'],

            // Kelas XI IPS 1
            ['nis' => '2024021', 'name' => 'Umar Abdullah', 'class' => 'XI IPS 1', 'gender' => 'L'],
            ['nis' => '2024022', 'name' => 'Vina Oktavia', 'class' => 'XI IPS 1', 'gender' => 'P'],
            ['nis' => '2024023', 'name' => 'Wahyu Hidayat', 'class' => 'XI IPS 1', 'gender' => 'L'],
            ['nis' => '2024024', 'name' => 'Xena Maharani', 'class' => 'XI IPS 1', 'gender' => 'P'],
            ['nis' => '2024025', 'name' => 'Yoga Prasetyo', 'class' => 'XI IPS 1', 'gender' => 'L'],
            ['nis' => '2024026', 'name' => 'Zahra Aulia', 'class' => 'XI IPS 1', 'gender' => 'P'],
            ['nis' => '2024027', 'name' => 'Arif Rahman', 'class' => 'XI IPS 1', 'gender' => 'L'],
            ['nis' => '2024028', 'name' => 'Bella Safira', 'class' => 'XI IPS 1', 'gender' => 'P'],
            ['nis' => '2024029', 'name' => 'Chandra Wijaya', 'class' => 'XI IPS 1', 'gender' => 'L'],
            ['nis' => '2024030', 'name' => 'Dewi Lestari', 'class' => 'XI IPS 1', 'gender' => 'P'],

            // Kelas XI IPS 2
            ['nis' => '2024031', 'name' => 'Eka Putra', 'class' => 'XI IPS 2', 'gender' => 'L'],
            ['nis' => '2024032', 'name' => 'Fani Rahmawati', 'class' => 'XI IPS 2', 'gender' => 'P'],
            ['nis' => '2024033', 'name' => 'Gilang Ramadhan', 'class' => 'XI IPS 2', 'gender' => 'L'],
            ['nis' => '2024034', 'name' => 'Hesti Wulandari', 'class' => 'XI IPS 2', 'gender' => 'P'],
            ['nis' => '2024035', 'name' => 'Ivan Kurniawan', 'class' => 'XI IPS 2', 'gender' => 'L'],
            ['nis' => '2024036', 'name' => 'Julia Anggraini', 'class' => 'XI IPS 2', 'gender' => 'P'],
            ['nis' => '2024037', 'name' => 'Kevin Saputra', 'class' => 'XI IPS 2', 'gender' => 'L'],
            ['nis' => '2024038', 'name' => 'Linda Permatasari', 'class' => 'XI IPS 2', 'gender' => 'P'],
            ['nis' => '2024039', 'name' => 'Mario Setiawan', 'class' => 'XI IPS 2', 'gender' => 'L'],
            ['nis' => '2024040', 'name' => 'Nadia Kusumawati', 'class' => 'XI IPS 2', 'gender' => 'P'],
        ];

        foreach ($students as $student) {
            Student::updateOrCreate(
                ['nis' => $student['nis']],
                array_merge($student, [
                    'birth_date' => fake()->dateTimeBetween('-18 years', '-15 years')->format('Y-m-d'),
                    'birth_place' => fake()->randomElement(['Bukittinggi', 'Padang', 'Payakumbuh', 'Solok', 'Batusangkar']),
                    'address' => fake()->address(),
                    'parent_name' => fake()->name(),
                    'parent_phone' => fake()->phoneNumber(),
                    'is_active' => true,
                ])
            );
        }
    }
}
