<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WasteTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('waste_types')->upsert([
            ['name' => 'Rác hữu cơ'],
            ['name' => 'Rác vô cơ'],
            ['name' => 'Rác tái chế'],
            ['name' => 'Rác nguy hại'],
        ], ['name']);
    }
}
