<?php
// Extract states data from the large seeder file
$content = file_get_contents('database/seeders/StatesTableSeeder.php');

// Extract the array from the file
preg_match('/DB::table\(\'states\'\)->insert\((array.*?)\);/s', $content, $matches);

if (!isset($matches[1])) {
    echo "Could not extract states array\n";
    exit(1);
}

$php_code = 'return ' . $matches[1] . ';';
$states = eval($php_code);

echo "Total states: " . count($states) . "\n";

// Save to a JSON file for use in optimized seeder
file_put_contents('database/states_data.json', json_encode($states));
echo "States saved to database/states_data.json\n";
