<?php

header('Content-type: application/json; charset=UTF-8');


/*
* Title:   CSV to GeoJSON
* Notes:   Convert a comma separated CSV file of points with x & y fields to 
GeoJSON format, suitable for use in OpenLayers, Leaflet, etc. Only point 
features are supported.
* Author:  Bryan R. McBride, GISP
* Contact: bryanmcbride.com
* GitHub:  https://github.com/bmcbride/PHP-Database-GeoJSON
*https://docs.google.com/spreadsheets/d/1TvQIEgqRPhdrG3S6MOiVoTVfw5QKqMWar_PyCn6_CYk/export?format=csv&gid=467614608
https://docs.google.com/spreadsheets/d/1TvQIEgqRPhdrG3S6MOiVoTVfw5QKqMWar_PyCn6_CYk/edit#gid=467614608
*/
# Read the CSV file
$feed = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vTycKLqWVLOayAJwkGcVb7FHK1J7jtmV5cP3giTVsBkVZSutbi5RN-OgtUJct3UeolrwVe1ETGBBoqN/pub?output=csv';
$handle = fopen($feed, 'r');
# Build GeoJSON feature collection array
$geojson = array(
  'type' => 'FeatureCollection',
  'features' => array()
);
# Loop through rows to build feature arrays
$header = NULL;
while (($row = fgetcsv($handle, 1000000, ',')) !== FALSE) {
  if (!$header) {
      $header = $row;
  } else {
      $data = array_combine($header, $row);
     // print_r($data) ;
      $properties = $data;
      var_dump($data);
      
      # Remove x and y fields from properties (optional)
//        unset($properties['x']);
//        unset($properties['y']);
      $feature = array(
          'type' => 'Feature',
          'properties'=>array(
          'icon'=>$data['icon'],
           'size'=> $data['size'],
           'time'=> $data['time'],
           'info'=> $data['info'],
           'title'=> $data['tag'],
           'color'=> $data['color'],
           'adress'=> $data['adress'],
          ),
          'geometry' => array(
              'type' => 'Point',
              'coordinates' => array(
                  $data['longitude'],
                  $data['latitude']
              )
          ),
//            'properties' => $properties
      );
      # Add feature arrays to feature collection array
      array_push($geojson['features'], $feature);
  }
}
//fclose($handle);
echo json_encode($geojson, JSON_NUMERIC_CHECK);



//output as JSON string


//write to JSON file
$fp = fopen('geo.json', 'w');
fwrite($fp, json_encode($geojson, JSON_PRETTY_PRINT));
fclose($fp);