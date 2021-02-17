<?php

//new api 
require_once 'cars_json_functions_json.php';

$url = 'https://api.carsinventory.com/public_api/listing/13b297ed-eb96-4d1a-b0af-8539d7ff00ae';

//1. z tohto url dostať len IDs

$html_content = get_content($url);

$array_ids = [];

$html_content = json_decode($html_content);

get_links_of_cars_and_thumbs($html_content);



$content01 = get_content('https://api.carsinventory.com/public_api/listing/13b297ed-eb96-4d1a-b0af-8539d7ff00ae/vehicle/' . $array_ids[0]);
$content01 = json_decode($content01);
$content_explode = $content01;

//! nadpis 
$attr = $content01->data->attributes;
$post_title = $attr->nameplate_brand_name . " " . $attr->nameplate_name . 
              " " . $attr->version . " " . $attr->drivetrain . " " . $content01->data->id;
echo $post_title;


//*
//*
//*      META
//*
//*

//TODO: ak sa zmení cover photo tak sa len nahradí, no big deal



$attr = $content_explode->data->attributes;

$details = array(
    "Značky vozidla" =>     $attr->nameplate_brand_name,
    "Modely" =>             $attr->nameplate_name,
    "Verzia" =>             $attr->drivetrain,
    "Palivá" =>             $attr->engine_type,
    "Prevodovky" =>         $attr->transmission,
    "Motory" =>             $attr->engine_capacity_normalized . '' . $attr->engine_capacity_unit,
    "Farby" =>              $attr->color_name,
    "State" =>              $attr->state,
    "Sklad" =>              $attr->status,
    "Ročníky" =>            $attr->production_year,
);


if ($details["Sklad"] == 'delivery') {
    //wp_set_object_terms($post_id, "Vo výrobe", 'sklad');
    echo 'vyroba';
} else {
    //wp_set_object_terms($post_id, "Na sklade", 'sklad');
    echo 'skald';
}

if ($details["State"] == "new") {
    // echo 'setting the object';
    //wp_set_object_terms($post_id, "vseobecne", 'predajcovia');
    //wp_set_object_terms($post_id, "Nové vozidlá", 'znacka', true);
    echo 'nove';
} else {
    // echo 'setting the object';
    //wp_set_object_terms($post_id, "jazdene", 'predajcovia');
    //wp_set_object_terms($post_id, "Jazdené vozidlá", 'znacka', true);
    echo 'jazdene';
}
/*
echo '<br>';
echo '<pre>';
print_r($details);
echo '</pre>';
*/


//*
//*
//*      ACF
//*
//*





$attr = $content_explode->data->attributes;
$details = array(
    "Popis" =>                  $attr->short_description,
    "Fotky" =>                  $attr->car_photos, //TODO: ups, treba získať
    "Kilometre" =>              $attr->mileage,
    "Kontakt" =>                "",
    "Vykon" =>                  $attr->power,
    "Vykon jednotka" =>         $attr->power_unit,
    "Cennikova cena" =>         $attr->msrp_price,
    "Aktualna cena" =>          $attr->sale_price,
    "Id" =>                     $content_explode->data->id,
    "Vin" =>                    $attr->vin,
    "Objem" =>                  $attr->engine_capacity_normalized . "" . $attr->engine_capacity_unit,
    "Základná výbava" =>        $attr->features_standard,
    "Príplatková výbava" =>     $attr->features_optional,
);

$basic_features = [];
foreach ($details["Príplatková výbava"] as $single_array) {

    $vybava = trim($single_array->label);
    $vybava = strip_tags($vybava);
    $vybava = str_replace('•', '', $vybava);
    array_push($basic_features, $vybava);
}

//print_r(implode(", ", $basic_features));
/*
echo '<br>';
echo '<pre>';
print_r($details);
echo '</pre>';
*/




/**
 * Function to get array ids
 */
function get_links_of_cars_and_thumbs($html_content)
{
    global $array_ids;
    foreach ($html_content->data as $data) {
        array_push($array_ids, $data->id);
    }
    //print_r($array_ids);
}
