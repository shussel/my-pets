<?php

namespace App\Faker;

use Faker\Provider\Base;
use App\Enums\SpeciesEnum;
use App\Enums\SexEnum;

class PetProvider extends Base
{
    protected static array $names = [
        "Abby",
        "Ace",
        "Allie",
        "Angel",
        "Annie",
        "Apollo",
        "Archie",
        "Athena",
        "Baby",
        "Bailey",
        "Bandit",
        "Baxter",
        "Bear",
        "Beau",
        "Bella",
        "Benji",
        "Benny",
        "Bentley",
        "Blue",
        "Bo",
        "Bob",
        "Bonnie",
        "Boo",
        "Boomer",
        "Boots",
        "Brady",
        "Brandy",
        "Brody",
        "Bruno",
        "Brutus",
        "Bubba",
        "Buddy",
        "Buster",
        "Cali",
        "Callie",
        "Casey",
        "Cash",
        "Casper",
        "Champ",
        "Chance",
        "Charlie",
        "Chase",
        "Chester",
        "Chico",
        "Chloe",
        "Cleo",
        "Coco",
        "Cocoa",
        "Cody",
        "Cookie",
        "Copper",
        "Cuddles",
        "Daisy",
        "Dakota",
        "Dexter",
        "Diesel",
        "Dixie",
        "Duke",
        "Dusty",
        "Ella",
        "Ellie",
        "Elvis",
        "Emma",
        "Felix",
        "Finn",
        "Fluffy",
        "Frankie",
        "Garfield",
        "George",
        "Gigi",
        "Ginger",
        "Gizmo",
        "Grace",
        "Gracie",
        "Gunner",
        "Gus",
        "Hank",
        "Hannah",
        "Harley",
        "Hazel",
        "Heidi",
        "Henry",
        "Holly",
        "Honey",
        "Hunter",
        "Izzy",
        "Jack",
        "Jackson",
        "Jake",
        "Jasmine",
        "Jax",
        "Joey",
        "Josie",
        "Katie",
        "Kiki",
        "Kitty",
        "Kobe",
        "Kona",
        "Lacey",
        "Lady",
        "Layla",
        "Leo",
        "Leo",
        "Lexi",
        "Lexie",
        "Lilly",
        "Lily",
        "Loki",
        "Lola",
        "Louie",
        "Lucky",
        "Lucy",
        "Luke",
        "Lulu",
        "Luna",
        "Mac",
        "Macy",
        "Maddie",
        "Madison",
        "Maggie",
        "Marley",
        "Max",
        "Maya",
        "Mia",
        "Mickey",
        "Midnight",
        "Millie",
        "Milo",
        "Mimi",
        "Minnie",
        "Miss kitty",
        "Missy",
        "Misty",
        "Mittens",
        "Mocha",
        "Molly",
        "Moose",
        "Muffin",
        "Murphy",
        "Nala",
        "Nikki",
        "Olive",
        "Oliver",
        "Ollie",
        "Oreo",
        "Oscar",
        "Otis",
        "Patches",
        "Peanut",
        "Peanut",
        "Pebbles",
        "Penny",
        "Pepper",
        "Phoebe",
        "Piper",
        "Precious",
        "Prince",
        "Princess",
        "Pumpkin",
        "Rascal",
        "Rex",
        "Riley",
        "Rocco",
        "Rocky",
        "Romeo",
        "Roscoe",
        "Rosie",
        "Roxie",
        "Roxy",
        "Ruby",
        "Rudy",
        "Rufus",
        "Rusty",
        "Sadie",
        "Salem",
        "Sally",
        "Sam",
        "Samantha",
        "Sammy",
        "Samson",
        "Sandy",
        "Sasha",
        "Sassy",
        "Sassy",
        "Scooter",
        "Scout",
        "Scout",
        "Shadow",
        "Sheba",
        "Shelby",
        "Sierra",
        "Simba",
        "Simba",
        "Simon",
        "Smokey",
        "Snickers",
        "Snowball",
        "Snuggles",
        "Socks",
        "Sophie",
        "Sophie",
        "Sparky",
        "Spike",
        "Spooky",
        "Stella",
        "Sugar",
        "Sugar",
        "Sydney",
        "Tank",
        "Teddy",
        "Thor",
        "Tiger",
        "Tigger",
        "Tinkerbell",
        "Toby",
        "Toby",
        "Trixie",
        "Trouble",
        "Tucker",
        "Tyson",
        "Walter",
        "Whiskers",
        "Willow",
        "Winnie",
        "Winston",
        "Zeus",
        "Ziggy",
        "Zoe",
        "Zoe",
        "Zoey",
        'Bart',
        'Brownie',
        'Fluffy',
        'Rover',
        'Sausage',
        'Spot',
    ];

    public static function pet($species = ''): array
    {
        $species = $species ?: static::petSpecies();

        return [
            'species' => $species,
            'name' => static::petName(),
            'sex' => static::petSex(),
            'weight' => static::petWeight($species),
            'birth_date' => static::petBirthdate($species),
            'avatar' => null,
        ];
    }

    public static function petName(): string
    {
        return static::randomElement(static::$names);
    }

    public static function petSpecies(): string
    {
        return static::randomElement(SpeciesEnum::values());
    }

    public static function petSex(): string
    {
        return static::randomElement(SexEnum::values());
    }

    public static function petWeight($species = ''): int
    {
        if (!$species || !($specie = SpeciesEnum::tryFrom($species)) || !($max_weight = $specie->maxWeight())) {
            $max_weight = 10;
        }
        return rand(1, $max_weight);
    }

    public static function petBirthdate($species = ''): string
    {
        if (!$species || !($specie = SpeciesEnum::tryFrom($species)) || !($max_age = $specie->maxAge())) {
            $max_age = 5;
        }
        return date('c', mt_rand(strtotime('-' . $max_age . ' years'), time()));
    }

    public static function petImage($species = ''): string
    {
        if (!$species || !($specie = SpeciesEnum::tryFrom($species)) || !($image = $specie->image())) {
            $image = '';
        }
        return $image;
    }
}
