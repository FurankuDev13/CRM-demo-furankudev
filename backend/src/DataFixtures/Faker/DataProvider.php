<?php
namespace App\DataFixtures\Faker;

class DataProvider extends \Faker\Provider\Base
{
    protected static $addressTypeList = [
        'Siège',
        'Contact',
        'Facturation',
        'Livraison'
    ];

    protected static $contactTypeList = [
        'Directeur',
        'Gérant',
        'Responsable Commercial',
        'Commercial',
        'Responsable des Achats'
    ];

    protected static $discountList = [
        '5%' => 5,
        '10%' => 10,
        '15%'=> 15,
        '20%' => 20,
        '25%' => 25,
        '30%' => 30
    ];

    protected static $handlingStatusList = [
        'Initiée',
        'En cours',
        'En attente',
        'Terminée'
    ];

    protected static $requestTypeList = [
        'Informations',
        'Devis simple',
        'Devis Détaillé',
        'Commande'
    ];

    public static function setAddressType(){
        return static::randomElement(static::$addressTypeList);
    }

    public static function setContactType(){
        return static::randomElement(static::$contactTypeList);
    }

    public static function setDiscount(){
        return static::randomElement(static::$discountList);
    }

    public static function setHandlingStatus(){
        return static::randomElement(static::$handlingStatusList);
    }

    public static function setRequestType(){
        return static::randomElement(static::$requestTypeList);
    }

}