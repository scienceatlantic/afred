<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(LanguageCodesTableSeeder::class);
        $this->call(FieldTypesTableSeeder::class);
        $this->call(FormEntryStatusesTableSeeder::class);
        $this->call(FormEntryTokenStatusesTableSeeder::class);
        $this->call(SearchFacetOperatorTableSeeder::class);

        $this->call(LabelledValueDataSeeder::class);
        $this->call(OrganizationDataSeeder::class);
        $this->call(ProvinceDataSeeder::class);
        $this->call(ResearchDisciplinesDataSeeder::class);
        $this->call(SectorsOfApplicationDataSeeder::class);
        $this->call(ExcessCapacityDataSeeder::class);
        $this->call(SearchVisibilityDataSeeder::class);
        $this->call(IlosTableSeeder::class);
        
        $this->call(DirectoriesTableSeeder::class);
        $this->call(AfredFormDataSeeder::class);
        $this->call(UCalgaryFormDataSeeder::class);

        $this->call(FormReportTableSeeder::class);

        $this->call(DummyUsersTableSeeder::class);
        //$this->call(Afred2DataSeeder::class);
        //$this->call(DummyAfredFormDataSeeder::class);
    }
}
