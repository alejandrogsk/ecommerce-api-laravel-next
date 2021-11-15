<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'alejandro',
            'last_name' => 'suarez',
            'email' => 'alejandrogsk9900@gmail.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'role_id'=> 1,
            'phone'=> '3434807355',
            'country'=> 'Argentina',
            'state'=> 'Mendoza',
            'city'=> 'Crespo',
            'zip_code'=> 'e-3116'
        ]);

        User::factory()->count(9)->create();
    }
}
