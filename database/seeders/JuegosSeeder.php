<?php

namespace Database\Seeders;

use App\Models\Juego;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class JuegosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $csvFile = fopen(base_path("database/wiitdb.csv"), "r");
  
        $firstline = true;

        $arrayCodigos[] = null;
        $arrayNombres[] = null;

        $arrayCSV = array();

        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {

                $tempArray = [
                    "codigo" => $data['0'],
                    "nombre" => $data['1']
                ];

                //array_push($arrayCSV, $data['0'], $data['1']);
                array_push($arrayCSV, $tempArray);
            }
            $firstline = false;
        }
   
        fclose($csvFile);

        /*$test = [
            ["codigo" => "01", "nombre" => "hoa"],
            ["codigo" => "01", "nombre" => "hoa"],
            ["codigo" => "01", "nombre" => "hoa"],
            ["codigo" => "01", "nombre" => "hoa"],
            ["codigo" => "01", "nombre" => "hoa"],
            ["codigo" => "01", "nombre" => "hoa"],
            ["codigo" => "01", "nombre" => "hoa"]
        ];*/
        
        //var_dump($arrayCSV);
        //var_dump($test);
        Juego::insert($arrayCSV);
        /*Juego::create([
            "codigo" => $arrayCodigos,
            "nombre" => $arrayNombres
        ]);*/

    }
}
