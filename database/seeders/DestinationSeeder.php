<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Destination;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $destinations = [
            ['municipality' => 'Antequera', 'kilometers' => 15.00],
            ['municipality' => 'Baclayon', 'kilometers' => 25.00],
            ['municipality' => 'Balilihan', 'kilometers' => 20.00],
            ['municipality' => 'Batuan', 'kilometers' => 35.00],
            ['municipality' => 'Bilar', 'kilometers' => 30.00],
            ['municipality' => 'Buenavista', 'kilometers' => 22.00],
            ['municipality' => 'Calape', 'kilometers' => 12.00],
            ['municipality' => 'Candijay', 'kilometers' => 45.00],
            ['municipality' => 'Carmen', 'kilometers' => 38.00],
            ['municipality' => 'Catigbian', 'kilometers' => 18.00],
            ['municipality' => 'Clarin', 'kilometers' => 28.00],
            ['municipality' => 'Danao', 'kilometers' => 33.00],
            ['municipality' => 'Dauis', 'kilometers' => 30.00],
            ['municipality' => 'Dimiao', 'kilometers' => 22.00],
            ['municipality' => 'Duero', 'kilometers' => 37.00],
            ['municipality' => 'Garcia Hernandez', 'kilometers' => 34.00],
            ['municipality' => 'Jagna', 'kilometers' => 26.00],
            ['municipality' => 'Lila', 'kilometers' => 15.00],
            ['municipality' => 'Loboc', 'kilometers' => 20.00],
            ['municipality' => 'Loon', 'kilometers' => 28.00],
            ['municipality' => 'Mabini', 'kilometers' => 18.00],
            ['municipality' => 'Maribojoc', 'kilometers' => 14.00],
            ['municipality' => 'Panglao', 'kilometers' => 38.00],
            ['municipality' => 'Pilar', 'kilometers' => 40.00],
            ['municipality' => 'P. Carlos Garcia', 'kilometers' => 42.00],
            ['municipality' => 'Sagbayan', 'kilometers' => 43.00],
            ['municipality' => 'San Isidro', 'kilometers' => 41.00],
            ['municipality' => 'San Miguel', 'kilometers' => 39.00],
            ['municipality' => 'Sevilla', 'kilometers' => 32.00],
            ['municipality' => 'Sierra Bullones', 'kilometers' => 50.00],
            ['municipality' => 'Talibon', 'kilometers' => 45.00],
            ['municipality' => 'Trinidad', 'kilometers' => 49.00],
            ['municipality' => 'Tubugon', 'kilometers' => 0.00],
            ['municipality' => 'Tagbilaran', 'kilometers' => 40.00],
            ['municipality' => 'Ubay', 'kilometers' => 55.00],
            ['municipality' => 'Valencia', 'kilometers' => 60.00],
        ];

        foreach ($destinations as $destination) {
            Destination::create($destination);
        }
    }
}
