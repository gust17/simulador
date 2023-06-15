<?php

namespace Database\Seeders;

use App\Models\Consignante;
use App\Models\Consignataria;
use App\Models\Regra;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class TaxasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $consignatarias = Consignataria::pluck('cd_consignataria')->toArray();
       // $consignantes = Consignante::pluck('cd_consignante')->toArray();
        $consignantes = Consignante::where('cd_consignante',40)->pluck('cd_consignante')->toArray();
        $regras = Regra::pluck('id')->toArray();

        $taxas = [];

        for ($i = 0; $i < 1000; $i++) {
            $consignataria_id = $consignatarias[array_rand($consignatarias)];
            $consignante_id = $consignantes[array_rand($consignantes)];
            $regra_id = $regras[array_rand($regras)];
            $prazo = rand(1, 40);
            $taxa = rand(1, 1000) / 100;

            $taxas[] = [
                'consignataria_cd_consignataria' => $consignataria_id,
                'consignante_cd_consignante' => $consignante_id,
                'regra_id' => $regra_id,
                'prazo' => $prazo,
                'taxa' => $taxa,
                'usuario' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('taxas')->insert($taxas);

    }
}
