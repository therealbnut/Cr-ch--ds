<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$foo  = file_get_contents('centroid_poly_tas.geojson');

$sections = explode("\n", $foo);
$count = count($sections);

for ($i=4; $i<$count; $i+=2)
{
	$json = json_decode($sections[$i]);
	// echo "data: $sections[4]";
	print_r($json);
}

// print_r($json);
echo "done!";

?>