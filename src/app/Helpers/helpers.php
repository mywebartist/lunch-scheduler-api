<?php

/**
 * Write code on Methode
 *
 * @return response()
 */
if (! function_exists('generateRandomPin')) {
    // generate random login pin
    function generateRandomPin(): string
    {
        $pin_numbers = [2,3,4,5];
        $pin_letters = ['C', 'K', 'J', 'E', 'Z'];

        $pin_characters = array_merge($pin_numbers, $pin_letters);
        shuffle($pin_characters);
        $pin = array_slice($pin_characters, 0,4);

        return implode('',  $pin);
    }
}




