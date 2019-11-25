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
        DB::table('usuario')->insert([
            'primeironome' 	=> 'Bruno',
            'sobrenome'		=> 'Roberto Gomes',
            'sexo'			=> 'M',
            'timezone'		=> 'America/Araguaina',
            'pais_id'		=> 33,
            'email' 		=> 'bruno@brgomes.com',
            'senha' 		=> bcrypt('1'),
            'datasenha'		=> Carbon::now(),
            'ativo'			=> true,
            'admin'			=> true,
        ]);
    }
}
