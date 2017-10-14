<?php

use Faker\Generator as Faker;

$factory->define(App\Photo::class, function (Faker $faker) {

    /*$filename = $faker->numberBetween($min = 100000, $max = 999999);

    return [
        'src' => 'img/uploads/big/' . $filename . '_big.jpg',
        'src_midi' => 'img/gallery/midi/' . $filename . '_midi.jpg',
        'src_mini' => 'img/gallery/mini/' . $filename . '_mini.jpg',
        'src_mini_thumb' => 'img/gallery/' . $filename . '_thumb.jpg',
        'active' => 1,
    ];*/

    return [
        'src' => $faker->imageUrl($width = 640, $height = 480),
        'src_midi' => $faker->imageUrl($width = 400, $height = 200),
        'src_mini' => $faker->imageUrl($width = 200, $height = 100),
        'src_mini_thumb' => $faker->imageUrl($width = 100, $height = 50),
        'active' => true,
    ];
});
