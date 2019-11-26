<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('usuario')->insert([
            'primeironome'      => 'Bruno',
            'sobrenome'         => 'Roberto Gomes',
            'sexo'              => 'M',
            'timezone'          => 'America/Araguaina',
            'pais_id'           => 33,
            'email'             => 'bruno.gpi@gmail.com',
            'password'          => bcrypt('1'),
            'datasenha'         => $now,
            'ativo'			    => true,
            'admin'             => true,
            'email_verified_at' => $now,
        ]);
    }
}
