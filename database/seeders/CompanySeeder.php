<?php

namespace Database\Seeders;

use App\Models\Gapco;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $query = Gapco::where("company_name","Gapco")->first();
      if(!$query){
        Gapco::create([
            "company_name" => "Gapco",
            "type" => "Fuel Campony"
        ]);

      }
    }
}
