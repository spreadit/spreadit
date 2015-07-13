<?php

class VotesTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $voter = new Vote;
        Auth::login(User::find(1));

        for($i=1; $i<9; $i++) {
            $user = User::find($i);

            for($j=0; $j<30; $j++) {
                $type = rand(0, 2);
                $type_id = 0;
                switch($type) {
                    case 0: $type_id = rand(1, 9); break;
                    case 1: $type_id = rand(1, 9); break;
                    case 2: $type_id = rand(1, 9); break;
                }
                $updown = rand(0, 1) ? Constant::VOTE_UP : Constant::VOTE_DOWN;
                $voter->applyVote($user, $type, $type_id, $updown);
            }
        }
    }
}
