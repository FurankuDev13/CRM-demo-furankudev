<?php
namespace App\DataFixtures\Faker;

trait CategoryData
{
    protected $categoryList = [
        'Ale' => [
            'name' => 'Les Ales',
            'description' => 'Une variété de bières également très riche. De fermentation haute, elles sont originaires de Grande-Bretagne et se présentent sous de nombreuses formes aromatiques et de couleurs.',
            'picture' => 'https://i.pinimg.com/564x/8c/af/44/8caf4465932585e1f8a2f05ac7fa327a.jpg'
        ],
        'Domestic Specialty' => [
            'name' => 'Les Spéciales',
            'description' => 'Fruits de techniques de brassage particulières, d’ajonction d’ingrédients, de techniques de fermentation, ces bières présentent des qualités gustatives vraiment différent',
            'picture' => 'https://i.pinimg.com/564x/46/ad/64/46ad64d4781530347bc1e8d60fd72489.jpg'
        ],
        'Flavoured Malt' => [
            'name' => "Les bière d'Abbaye",
            'description' => "Les bières d'abbaye sont de fermentation haute, fortes, maltées, fruitées et bien arrondies.
            L'abbaye reste propriétaire de la recette et donne son nom à la bière qui est brassée sous licence par une autre brasserie. ",
            'picture' => 'https://i.pinimg.com/564x/d6/cf/6a/d6cf6a0da4063c0a07f9e4c5a682ac83.jpg'
        ],
        'Import' => [
            'name' => 'Les exports',
            'description' => "Ce type désigne une bière de fermentation basse de type pils, mais plus forte (plus de 6% de vol. d'alcool), brassée pour être destinée à l'exportation.",
            'picture' => 'https://i.pinimg.com/564x/24/87/2e/24872e3213b8078cb937b174d7d49c96.jpg'
        ],
        'Lager' => [
            'name' => 'Les Lagers',
            'description' => 'Ce sont certainement les bières les plus répandues sur l’ensemble des continents. Obtenues à partir de fermentation basse, ce sont des bières généralement blondes, pas trop alcoolisées.',
            'picture' => 'https://i.pinimg.com/564x/29/5f/21/295f21c2441946394f8998e1d67e47ef.jpg'
        ],
        'Malt' => [
            'name' => 'La fermentation spontanée',
            'description' => "La fermentation spontanée est un type de fermentation utilisé dans le brassage de la bière. Contrairement aux fermentations haute et basse, elle ne nécessite pas d'ajout de levure dans le moût  car étant exposé à l'air libre, il est ensemencé par des levures sauvages.",
            'picture' => 'https://i.pinimg.com/564x/94/f5/b0/94f5b09fc63fdf2c2fe97ea72c45d470.jpg'
        ],
        'Non-Alcoholic Beer' => [
            'name' => 'La fermentation sans alcool',
            'description' => "Les bières sans alcool sont majoritairement obtenues par fermentation rapide. Un procédé à base de filtration de la bière existe : une membrane retient les molécules d'éthanol",
            'picture' => 'https://i.pinimg.com/564x/78/85/ab/7885abe3ab9b821da6490106241136cd.jpg'
        ],
        'Ontario Craft' => [
            'name' => 'Les artisanales',
            'description' => 'Les bières artisanales sont logiquement issues de transformations opérées par des brasseries dites artisanales.',
            'picture' => 'https://i.pinimg.com/564x/bc/6d/45/bc6d45cc32ead4d204f4d1b7a56e3c24.jpg'
        ],
        'Premium' => [
            'name' => 'La fermentation haute',
            'description' => "La fermentation haute est un type de fermentation utilisé dans le brassage de la bière. Il nécessite l'adjonction dans le moût de levure « haute », qui transforme le glucose (entre autres) en alcool et en gaz carbonique.",
            'picture' => 'https://i.pinimg.com/236x/86/7b/a7/867ba7cf123d9330160670e57b653753.jpg'
        ],
        'Stout' => [
            'name' => 'Les Stouts
            ',
            'description' => 'A l’origine ces bières sont une variante des Porter de Londres. Elles sont obtenues par fermentation haute à partir de malt d’orge torréfié, ce qui leur confère cette couleur sombre, proche parfois du noir goudron.',
            'picture' => 'https://i.pinimg.com/564x/ab/d3/fa/abd3fa2236bddb8a0ce644423cb4a008.jpg'
        ],
        'Value' => [
            'name' => 'La fermentation basse',
            'description' => "Appelées aussi lager. La fermentation se déroule à une température d'environ 10-15°C.",
            'picture' => 'https://i.pinimg.com/564x/74/16/dc/7416dcfaf5b5203269183e6377a8dace.jpg'
        ],
    ];

}