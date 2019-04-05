<?php
namespace App\DataFixtures\Faker;

trait EmailTemplateData
{
    protected $emailTemplatesList = [
        'Inscription - Internet' => [
            'messageTitle' => 
            "Votre inscription a bien été enregistrée sur notre site internet.",

            'messageBody' => 
            "Vous faites désormais partie de la communauté Beer o'Clock, nous vous en remercions.
            Vous pouvez désormais vous connecter sur http://www.cerberus-crm.space/login et consulter notre catalogue. Nous allons rapidement vous recontacter pour vous apporter toutes les informations complémentaires dont vous auriez besoin.",

            'messageSignature' => 
            "L'équipe de Beer o'Clock - hey@oclock.io - 09.74.76.80.67"
        ],
        'Inscription - Backoffice' => [
            'messageTitle' => 
            "Votre inscription a bien été enregistrée à votre demande.",

            'messageBody' => 
            "Vous faites désormais partie de la communauté Beer o'Clock, nous vous en remercions.
            Vous pouvez désormais vous connecter sur http://www.cerberus-crm.space/login et consulter notre catalogue. Nous allons rapidement vous recontacter pour vous apporter toutes les informations complémentaires dont vous auriez besoin.",

            'messageSignature' => 
            "L'équipe de Beer o'Clock - hey@oclock.io - 09.74.76.80.67"
        ],
        'Nouvelle demande - Internet' => [
            'messageTitle' => 
            "Votre demande a bien été prise en compte !",

            'messageBody' => 
            "Nous vous remercions, nous allons rapidement vous recontacter pour vous apporter toutes les informations complémentaires dont vous auriez besoin.Voici le récapitulatif de votre demande :",

            'messageSignature' => 
            "L'équipe de Beer o'Clock - hey@oclock.io - 09.74.76.80.67"
        ],
    ];

}