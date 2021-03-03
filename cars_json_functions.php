<?php
/*
    echo '<br>';
    echo $details["Objem"];
    echo '<br>';
    echo '<br>';
    echo '<br>';
*/


header('Content-type: text/html; charset=utf-8');
require_once 'cars_json_functions_json.php';

//*variables
//content of the individual car
$content01 = new stdClass();
//Array of all titles created during the post being publish used for the deletion
$all_ids_array = array();
//All ids count
$all_ids_count = 0;

//*constants
const UUID = [
    'ALL' => 'ae7ba610-46c0-4c58-a306-323c632313a2',
];
const API_URL_HOME = 'https://api.carsinventory.com/public_api/listing/';
const API_URL_PICS = 'https://api.carsinventory.com/';
const API_URL_NO_PAGINATION = '?items=50';


/**
 * Create all cars caller
 */
function create_all_cars_caller()
{
    global $all_ids_count;
    
    //JR cars
    create_cars_all(API_URL_HOME . '' . UUID['ALL'] . '' . API_URL_NO_PAGINATION, UUID['ALL']);
    
    //LR cars
    //create_cars_all(API_URL_HOME . '' . UUID['LR'] . '' . API_URL_NO_PAGINATION, UUID['LR']);
    
    //at the end delete the posts and show the count
    //check if all ids is empty if so we have an errror and do not want to 
    //delete anything
    global $all_ids_array;
   
    echo '   Vozidiel na stránke: ' . count($all_ids_array) . '.     Vozidiel v API: ' . $all_ids_count . '.   ';
    //var_dump($all_ids_array);
   
    if ($all_ids_array) {
        $cnt = delete_the_posts();
    }
    echo "    Vymazaných vozidiel: " . $cnt  . " ";
}

/**
 * Create all cars
 * @param string $link of all cars
 */
function create_cars_all($url_of_all_cars, $uuid)
{
    global $content01;
    $html     = get_content($url_of_all_cars);
    $html     = json_decode($html);
    
    $pages     = $html->meta->pagination->pages;
    $all_items = $html->meta->pagination->count;
    //echo '    Stránok v API: ' . $pages . '.    Vozidiel v API(meta): ' . $all_items . '.  ';

    for ($i=1; $i <= $pages; $i++) { 
        //skip the first one since it is fetched already
        if($i > 1){
            $html     = get_content($url_of_all_cars . "&page=" . $i);
            $html     = json_decode($html);
        }
        $cars_ids = get_ids_of_cars($html);
        foreach ($cars_ids as $id) {
            $url = API_URL_HOME . '' . $uuid . '/vehicle/' . '' . $id;
            $content01 = get_content($url);
            $content01 = json_decode($content01);
            create_new_car_posts($content01, $url, "");
        }
    }
}

/**
 * Create a new posts from the json
 * @param $url_of_all_cars
 */
function create_new_car_posts($content01, $link, $single = "")
{
    global  $all_ids_array;
    require_once(ABSPATH . 'wp-admin/includes/post.php');
    //create post and save it to array so we know what to delete
    
    $url = $link;
    //! nadpis 
    $attr = $content01->data->attributes;
    $post_title = $attr->nameplate_brand_name . " " . $attr->nameplate_name .
        " " . $attr->version . " " . $attr->drivetrain . " " . $content01->data->id;
    
    $all_ids_array[] = $post_title;
    
    $cover_photo_url = API_URL_PICS . '' . $attr->cover_photo;
    
    if (post_exists($post_title)) {
        
        $post_with_id = get_page_by_title($post_title, OBJECT, 'vozidla');
        $post_id = $post_with_id->ID;
        //echo 'exists';
        //is updated?
        if(get_field('updated', $post_id) != $attr->updated_at ){
        
            $post_title = $attr->nameplate_brand_name . " " . $attr->nameplate_name .
            " " . $attr->version . " " . $attr->drivetrain . " " . $content01->data->id;
            $my_post = array(
                'ID' => $post_id,
                'post_title' => $post_title,
                'post_author' => 3,
            );
            wp_update_post($my_post);
            if (get_field('thumb', $post_id) != $attr->cover_photo) {
                upload_and_asign_thumbnail($post_id,  $cover_photo_url);
            }
            update_post_acf($post_id);
            update_vozidla_post_meta($post_id);
        }
    } else {
        $post_information = array(
            'post_title' => $post_title,
            'post_type' => 'vozidla',
            'post_status' => 'publish',
            'post_author' => 3,
        );
        echo " Nové vozidlo nájdené: " . $attr->nameplate_name . " ";

        $postID = wp_insert_post($post_information); //here's the catch
        //print_r("<br>" . $postID . "<br>");
        //*thumb
        upload_and_asign_thumbnail($postID, $cover_photo_url);
    }
}

/**
 * Function to get array ids
 */
function get_ids_of_cars($html_content)
{
    global $all_ids_count;
    $cars_ids = [];
    foreach ($html_content->data as $data) {
        array_push($cars_ids, $data->id);
        $all_ids_count++;
    }

    return $cars_ids;
}

/**
 * Set the CPT taxonomies after the post is created
 * @param $post_id
 * @param string $content01_param
 */
function update_vozidla_post_meta($post_id, $content01_param = "")
{
    global $content01;
    $content_explode = $content01;
    $attr = $content_explode->data->attributes;

    $fuel_type_string = implode(',', $attr->engine_type);

    $details = array(
        "Značky vozidla" =>     $attr->nameplate_brand_name,
        "Modely" =>             $attr->nameplate_name,
        "Verzia" =>             $attr->drivetrain,
        "Palivá" =>             $fuel_type_string,
        "Prevodovky" =>         $attr->transmission,
        "Farby" =>              $attr->color_name,
        "State" =>              $attr->state,
        "Sklad" =>              $attr->status,
        "Ročníky" =>            $attr->production_year,
    );

    //Remove all non printale characters
    $details = array_map(function ($value) {
        $value = strip_tags($value);
        str_replace('\n', '', $value);
        return trim($value);
    }, $details);


    //Update the name need logic
    $brand = $details["Značky vozidla"];
    $brand = strtolower($brand);
    $brand == "jaguar" ?
        wp_set_object_terms($post_id, "Jaguar", 'znacka', true) :
        wp_set_object_terms($post_id, "Land Rover", 'znacka', true);


    //4WD/FWD
    if (strpos($details["Verzia"], "FWD")) {

        wp_set_object_terms($post_id, "2wd", 'pohon');
    } else {
        wp_set_object_terms($post_id, "4wd", 'pohon');
    }

    //Motor
    if (strpos($details["Verzia"], "V8")) {
        wp_set_object_terms($post_id, "V8", 'motor');
    } elseif (strpos($details["Verzia"], "V7")) {
        wp_set_object_terms($post_id, "V7", 'motor');
    } elseif (strpos($details["Verzia"], "V6")) {
        wp_set_object_terms($post_id, "V6", 'motor');
    }

    //Sklad 
    if ($details["Sklad"] == 'delivery') {
        wp_set_object_terms($post_id, "Vo výrobe", 'sklad');
    } else {
        wp_set_object_terms($post_id, "Na sklade", 'sklad');
    }

    //Nové/ jazdené
    if ($details["State"] == "new") {
        wp_set_object_terms($post_id, "vseobecne", 'predajcovia');
        wp_set_object_terms($post_id, "Nové vozidlá", 'znacka', true);
    } else if ($details["State"] == "used") {
        wp_set_object_terms($post_id, "jazdene", 'predajcovia');
        wp_set_object_terms($post_id, "Jazdené vozidlá", 'znacka', true);
    }

    //Update the post meta
    wp_set_object_terms($post_id, $details["Modely"], 'model');
    wp_set_object_terms($post_id, $details["Palivá"], 'palivo');
    wp_set_object_terms($post_id, $details["Prevodovky"], 'prevodovka');
    wp_set_object_terms($post_id, $details["Farby"], 'farba');
    wp_set_object_terms($post_id, $details["Ročníky"], 'rocnik');
}


/**
 * Update post ACF
 * @param $post_id
 * @param string $content01_param
 */
function update_post_acf($post_id, $content01_param = "", $link_param = "")
{
    global $content01;
    $content_explode = $content01;
    $attr = $content_explode->data->attributes;

    //photos links
    $photos_array = array();
    $attachment_wrapper_array = $content_explode->included;
    foreach ($attachment_wrapper_array as $item) {
        if ($item->type === "photo") {
            array_push($photos_array, API_URL_PICS . '' . $item->attributes->image_path);
        }
    }
    $photos_links = implode(",", $photos_array);


    $details = array(
        "Popis"          =>         $attr->short_description,
        "Fotky"          =>         $photos_links,
        "Kilometre" =>              $attr->mileage,
        "Kontakt" =>                "",
        "Vykon" =>                  $attr->power,
        "Vykon jednotka" =>         $attr->power_unit,
        "Cennikova cena" =>         $attr->msrp_price,
        "Aktualna cena"  =>         $attr->sale_price,
        "Id" =>                     $content_explode->data->id,
        "Vin" =>                    $attr->vin,
        "Objem" =>                  $attr->engine_capacity_normalized . "" . $attr->engine_capacity_unit,
        "Základná výbava" =>        $attr->features_standard,
        "Príplatková výbava" =>     $attr->features_optional,
        "Thumbnail link" =>         $attr->cover_photo,
        "Link" =>                   $content_explode->data->links->self,
        "Updated" =>                $attr->updated_at
    );

    update_field("popis", $details["Popis"], $post_id);
    update_field("obrazky_linky", $details["Fotky"], $post_id);
    update_field("vykon", $details["Vykon"], $post_id);
    update_field("vykon_jednotka", $details["Vykon jednotka"], $post_id);
    update_field("cennikova_cena", $details["Cennikova cena"], $post_id);
    update_field("aktualna_cena_s_dph", $details["Aktualna cena"], $post_id);
    update_field("id_ponuky", $details["Id"], $post_id);
    update_field("vin", $details["Vin"], $post_id);
    update_field("objem", $details["Objem"], $post_id);
    update_field("thumb", $details["Thumbnail link"], $post_id);
    update_field("link_json", $details["Link"], $post_id);
    update_field("updated", $details["Updated"], $post_id);


    //update Kilometre field
    //If mileage is set to none, set value to 0
    if ($details["Kilometre"]) {
        update_field("kilometre", $details["Kilometre"], $post_id);
    } else {
        $details["kilometre"] = 0;
        update_field("kilometre", 0, $post_id);
    }

    //Update základná výbava
    $basic_features = [];
    foreach ($details["Základná výbava"] as $single_array) {
        $vybava = trim($single_array->label);
        $vybava = strip_tags($vybava);
        $vybava = str_replace('•', '', $vybava);
        array_push($basic_features, $vybava);
    }
    $vybava_nadpis = "Základná výbava";
    $addField = array("nadpis" => $vybava_nadpis);
    $addSubField = array(
        'vybava' => implode(", ", $basic_features),
    );
    insert_field_subfield('basic_vybava', 'basic-vybava-list', $addField, $addSubField, 'field_5f9381c2ceac0', $post_id);

    //Update rozšírená výbava
    $basic_features = [];
    foreach ($details["Príplatková výbava"] as $single_array) {
        $vybava = trim($single_array->label);
        $vybava = strip_tags($vybava);
        $vybava = str_replace('•', '', $vybava);
        array_push($basic_features, $vybava);
    }
    $vybava_nadpis = "Príplatková výbava";
    $addField = array("nadpis" => $vybava_nadpis);
    $addSubField = array(
        'vybava' => implode(", ", $basic_features),
    );
    insert_field_subfield('extra_vybava', 'extra-vybava-list', $addField, $addSubField, 'field_5f938248ceac7', $post_id);
}



/**
 *? Asign a thumbnail for the post
 ** @param $post_id
 ** @param $photo
 */
function upload_and_asign_thumbnail($post_id, $photo)
{
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $url = strval($photo);
    //echo '<br>';
    //echo $url;
    $desc = " ";
    $att_id = media_sideload_image($url, $post_id, $desc, 'id');
    set_post_thumbnail($post_id, $att_id);
    //echo 'finished';
}



/**
 *? Delete all the posts which titles are not in the JSON
 ** @param $all_post_titles
 ** @return int
 */
function delete_the_posts()
{
    global $all_ids_array;
    $cnt = 0;
    $args = [
        'post_type' => 'vozidla',
        'posts_per_page' => -1,
    ];

    $loop = new WP_Query($args);
    while ($loop->have_posts()) {
        $loop->the_post();
        //echo the_title() . 'deleteing...';
        if (!in_array(get_the_title(), $all_ids_array)) {
            echo '   vymazané ' . the_title() . '.   ';
            wp_delete_post(get_the_id(), true);
            $cnt += 1;
        }
    }
    wp_reset_query();

    //return the number of deleted posts
    return $cnt;
}



//
//
//
//
//
//OLD FUNCTIONS
//
//
//
//
//
//


/**
 * Upload media and add it to the gallery
 * @param $post_id
 */
function upload_and_asign_images($post_id, $content01_param = "")
{
    global $content01;
    $content_explode = $content01;

    $already_uploaded = [];

    if ($content01_param) {

        $content_explode = $content01_param;
        $already_uploaded = explode('.',  get_field('obrazky_linky', $post_id));
    }

    $photos = $content_explode->car_photos;
    $individual_photos = array();
    foreach ($photos as $photo) {
        foreach ($photo as $individual_photo) {
            array_push($individual_photos, $individual_photo);
        }
    }
    $individual_photos = json_decode(json_encode($individual_photos), true);

    $final_array_photos = array();

    foreach ($individual_photos as $photo) {

        if (is_array($photo)) {
            array_push($final_array_photos, $photo["large"]);
        }
    }

    //we are outsite of the wp admin area we need to add
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    //TODO: testing
    // array_pop($final_array_photos);
    // array_pop($final_array_photos);
    // array_pop($final_array_photos);
    // array_pop($final_array_photos);
    // array_pop($final_array_photos);

    $attachments_urls = array();
    $attachements_urls_acf = array();
    foreach ($final_array_photos as $photo) {

        //echo 'already uploaded: ';
        //print_r($already_uploaded);
        //echo '<br>';

        $url = strval($photo);
        if (in_array($url, $already_uploaded)) {
            //TODO:
            //continue;
        }

        $desc = " ";

        array_push($attachements_urls_acf, $url);

        $att_id = media_sideload_image($url, $post_id, $desc, 'id');
        array_push($attachments_urls, $att_id);
    }
    $attachements_urls_acf = implode(" ", $attachements_urls_acf);
    //print_r($attachements_urls_acf);
    update_field('obrazky_linky', $attachements_urls_acf, $post_id);
    update_field('dalsie_fotky', $attachments_urls, $post_id);
}




/**
 *? Basic fundamental function, create only one car used for updating
 ** @param string $links_of_individual
 */
function create_cars($links_of_individual = "", $link_of_thumb = "")
{
    global $content01;
    global $assoc_links_and_thumbs;


    //update only given link
    //used from the GUI app
    if ($links_of_individual) {
        $url = $links_of_individual;
        $content01 = get_content($url);
        $content01 = json_decode($content01);
        $id_new_post = create_new_car_posts($content01, $link_of_thumb, true);
        return $id_new_post;
    } else {
    }
}
