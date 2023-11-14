<?php

declare(strict_types=1);

// Fizzbuzz classique.
function fizzbuzz(int $n) {
    for ($i = 1; $i <= $n; $i++) {
        if ($i % 15 === 0) {
            print "FizzBuzz" . PHP_EOL;
        } else if ($i % 5 === 0) {
            print "Buzz" . PHP_EOL;
        } else if ($i % 3 === 0) {
            print "Fizz" . PHP_EOL;
        }
    }
}

// La version fizzbuzz qui scale.
function speed_fizzbuzz(int $n) {
    $remaining = $n % 15;
    $fixedFizzbuzz = intdiv($n, 15);
    for ($i = 0; $i < $fixedFizzbuzz; $i++) {
        print <<<FIXED
Fizz
Buzz
Fizz
Fizz
Buzz
Fizz
FizzBuzz

FIXED;
    }

    fizzbuzz($remaining);
}

if (count($argv) === 1) {
    fwrite(STDERR, "La commande nécéssite un nombre entier positif en argument" . PHP_EOL);
    exit(-1);
}

if (!is_numeric($argv[1]) || intval($argv[1]) <= 0) {
    fwrite(STDERR, "La commande nécéssite un nombre entier positif en argument" . PHP_EOL);
    exit(-1);
}

speed_fizzbuzz(intval($argv[1]));