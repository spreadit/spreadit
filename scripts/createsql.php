<?php
function get_random_string($valid_chars, $length)
{
    // start with an empty random string
    $random_string = "";

    // count the number of chars in the valid chars string so we know how many choices we have
    $num_valid_chars = strlen($valid_chars);

    // repeat the steps until we've created a string of the right length
    for ($i = 0; $i < $length; $i++)
    {
        // pick a random number from 1 up to the number of valid chars
        $random_pick = mt_rand(1, $num_valid_chars);

        // take the random character out of the string of valid chars
        // subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
        $random_char = $valid_chars[$random_pick-1];

        // add the randomly-chosen char onto the end of our string so far
        $random_string .= $random_char;
    }

    // return our finished random string
    return $random_string;
}
$valid_chars = 'abcdefghijklmnopqurstuvwxvz09865135462!@#$$%^&*(()_{}:LABCDEFGHJHIKLMNOPEQREURSTYUWXYZ';


for($i=0; $i<1000; $i++)
echo "INSERT INTO `spreadit`.`posts` (`id`, `user_id`, `created_at`, `type`, `data`, `updated_at`, `section_id`, `title`, `upvotes`, `downvotes`, `url`, `comment_count`, `markdown`) VALUES (NULL, ".rand(0, 40).", ".time().", 1, '".get_random_string($valid_chars, rand(0, 2000))."', ".time().", ".rand(0, 10).", '".get_random_string($valid_chars, rand(6, 128))."', ".rand(0, 100).", ".rand(0, 100).", '".get_random_string($valid_chars, rand(10, 128))."', ".rand(0, 100).", '".get_random_string($valid_chars, rand(0, 4000))."');";
