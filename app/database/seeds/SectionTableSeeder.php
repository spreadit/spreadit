<?php

class SectionTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Section::create(['title' => 'all']);
        $statement = "UPDATE  `spreadit`.`sections` SET  `id` =  '0' WHERE  `sections`.`id` =1;";
        DB::unprepared($statement);
        Section::create(['title' => 'news']);
        Section::create(['title' => 'science']);
        Section::create(['title' => 'tech']);
        Section::create(['title' => 'politics']);
        Section::create(['title' => 'frogs']);
        Section::create(['title' => 'econ']);
        Section::create(['title' => 'math']);
        Section::create(['title' => 'prog']);
        Section::create(['title' => 'worms']);
    }
}
