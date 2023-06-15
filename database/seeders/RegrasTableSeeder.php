<?php

namespace Database\Seeders;

use App\Models\Consignataria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;
class RegrasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $consignatarias_ids = Consignataria::pluck('cd_consignataria')->toArray();

        $regras = [];

        for ($i = 0; $i < 30; $i++) {
            $consignataria_id = $consignatarias_ids[array_rand($consignatarias_ids)];

            $regras[] = [
                'consignataria_cd_consignataria' => $consignataria_id,
                'name' => 'Regra ' . ($i + 1),
                'inicio' => Carbon::now(),
                'fim' => Carbon::now()->addDays(30),
                'usuario' => $i+1
            ];
        }

        DB::table('regras')->insert($regras);
    }
}
