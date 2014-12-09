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
            'username' => "user",
            'password' => "password",
            'points' => 10,
            'created_at' => time(),
            'updated_at' => time()
        ]);

        $faker = Faker\Factory::create();
        for($i=0; $i<100; $i++) {
            DB::table('users')->insert([
                'username' => $faker->firstName,
                'password' => "password",
                'points' => 10,
                'created_at' => time(),
                'updated_at' => time()
            ]);
        }
    }
}
