<?php
// Extract city data from the large seeder file and save it
$content = file_get_contents('database/seeders/CitySeeder.php');

// Extract the array from the file
preg_match('/DB::table\(\'cities\'\)->insert\((array.*?)\);/s', $content, $matches);

if (!isset($matches[1])) {
    echo "Could not extract cities array\n";
    exit(1);
}

$php_code = 'return ' . $matches[1] . ';';
$cities = eval($php_code);

echo "Total cities: " . count($cities) . "\n";
echo "First city: " . json_encode($cities[0]) . "\n";
echo "Last city: " . json_encode($cities[count($cities)-1]) . "\n";

// Save to a JSON file for use in optimized seeder
file_put_contents('database/cities_data.json', json_encode($cities));
echo "Cities saved to database/cities_data.json\n";
