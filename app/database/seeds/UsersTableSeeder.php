<?php

class UsersTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('users')->insert([
            'username'                  => "user",
            'password'                  => "password",
            'points'                    => 10,
            'created_at'                => time(),
            'updated_at'                => time(),
            'remember_token'            => '',
            'upvotes'                   => 0,
            'downvotes'                 => 0,
            'frontpage_show_sections'   => '',
            'frontpage_ignore_sections' => '',
            'profile_data'              => '',
            'profile_css'               => '',
            'profile_markdown'          => '',
        ]);

        $faker = Faker\Factory::create();
        for($i=0; $i<200; $i++) {
            DB::table('users')->insert([
                'username'                  => $faker->firstName,
                'password'                  => "password",
                'points'                    => 10,
                'created_at'                => time(),
                'updated_at'                => time(),
                'remember_token'            => '',
                'upvotes'                   => 0,
                'downvotes'                 => 0,
                'frontpage_show_sections'   => '',
                'frontpage_ignore_sections' => '',
                'profile_data'              => '',
                'profile_css'               => '',
                'profile_markdown'          => '',
            ]);
        }
    }
}
