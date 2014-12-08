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
        Section::create(['title' => 'news']);
    }
}
