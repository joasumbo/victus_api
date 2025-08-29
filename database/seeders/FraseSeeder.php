<?php

namespace Database\Seeders;

use App\Models\Frase;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FraseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        $user = User::first();

        if (!$user) {
            return;
        }

        $frases = [
            "A persistência realiza o impossível.",
            "Cada dia é uma nova chance de mudar sua vida.",
            "Pequenos passos levam a grandes resultados.",
            "Acredite no processo, confie na jornada."
        ];

        foreach ($frases as $frase) {
            Frase::create([
                'user_id' => $user->id,
                'frase'   => $frase,
            ]);
        }
    }
}
