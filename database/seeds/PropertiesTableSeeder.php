<?php

use Illuminate\Database\Seeder;

class PropertiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Property::class, 50)->create([
            'address_id' => \App\Address::orderByRaw('RAND()')->first()->id
        ]);
    }
}
