<?php
namespace App\DataFixtures\Faker;

class DataForFaker extends \Faker\Provider\Base
{

    protected static $addressType = [
        'Siège',
        'Contact',
        'Facturation',
        'Livraison'
    ];

    public static function setAddressType(){
        return static::randomElement(static::$addressType);
    }

}