<?php
// Extract countries data
$content = file_get_contents('database/seeders/CountryTableSeeder.php');

preg_match('/DB::table\(\'countries\'\)->insert\(\[/', $content, $matches);

if (!isset($matches[0])) {
    echo "Could not find countries insert statement\n";
    exit(1);
}

// For countries, it's a bit different - let's try to extract it
preg_match('/DB::table\(\'countries\'\)->insert\(\[(.*)\]\);/s', $content, $matches);

if (!isset($matches[1])) {
    echo "Could not extract countries array - trying alternative pattern\n";
    // Try alternative pattern
    preg_match('/DB::table\(\'countries\'\)->insert\(array\((.*)\)\);/s', $content, $matches);
}

if (!isset($matches[1])) {
    echo "Could not extract countries data\n";
    exit(1);
}

$php_code = 'return [' . $matches[1] . '];';
$countries = eval($php_code);

echo "Total countries: " . count($countries) . "\n";

// Save to a JSON file
file_put_contents('database/countries_data.json', json_encode($countries));
echo "Countries saved to database/countries_data.json\n";
