<?php
class InstitutionTableSeeder extends Seeder
{
    public function run()
    {
        $institutions = array(
            array('name' => 'Acadia University'),
            array('name' => 'Cape Breton University'),
            array('name' => 'Crandall University'),
            array('name' => 'Dalhouse University'),
            array('name' => 'Dalhouse University, Faculty of Agriculture'),
            array('name' => 'Memorial University'),
            array('name' => 'Memorial University, Grenfell Campus'),
            array('name' => 'Mount Allison University'),
            array('name' => 'Mount Saint Vincent University'),
            array('name' => 'Saint Mary\'s University'),
            array('name' => 'St. Francis Xavier University'),
            array('name' => 'St. Thomas University'),
            array('name' => 'Université de Moncton'),
            array('name' => 'University of New Brunswick, Fredericton'),
            array('name' => 'University of New Brunswick, Saint John'),
            array('name' => 'University of Prince Edward Island')
        );
        
        DB::table('institutions')->delete();
        DB::table('ilo_contacts')->delete();
                
        foreach($institutions as $i) {
            $institution = new Institution();
            $institution->name = $i['name'];
            $institution->save();
        }
    }
}
