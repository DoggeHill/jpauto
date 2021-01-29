<?php header('Content-type: text/html; charset=utf-8'); ?>
<?php

require_once 'cars_json_functions_json.php';


//content of the individual car
$content01 = new stdClass();

//Array of all titles created during the post being publish used for the deletion
$all_ids = array();

//declaration links, thumbnails, new car, brand
$is_new_car = 0;
$is_jaguar = false;
$is_new = 0;

//Link in ACF used for updates
$global_link = "";

$assoc_links_and_thumbs = [];

$all_ids_cars_published = [];



function create_all_cars_caller(){
    
    /**
     * Create cars from the links API RAFFINE
     */
    //API URLS
    $url_of_the_core = "https://new.carsinventory.com/api2/";
    $locale_url_parameter = "?locale=sk";
    $list_keys = [
        "d7306123447f4384e75fdcbe505100313a30de7b", //Jaguar new
        "12160e69e3e995bdba72cba3957fd1c196682c6c", //LR new
        "83c80d4be13bdfc01f29d8dcba7456f673102cf5", //Jaguar old
        "d44bd19410a57f0cb2789e6095630f0e3edb6c5f", //LR old
    ];

    $url_of_all_cars = $url_of_the_core . $list_keys[0] . $locale_url_parameter;
    create_cars_all($url_of_all_cars);

    $url_of_all_cars = $url_of_the_core . $list_keys[1] . $locale_url_parameter;
    create_cars_all($url_of_all_cars);

    $url_of_all_cars = $url_of_the_core . $list_keys[2] . $locale_url_parameter;
    create_cars_all($url_of_all_cars);

    $url_of_all_cars = $url_of_the_core . $list_keys[3] . $locale_url_parameter;
    create_cars_all($url_of_all_cars);

      //at the end delete the posts and show the count
      $cnt = delete_the_posts();
      echo "__post deleted: " . $cnt . "___";

}



/**
 * Basic fundamental function
 * @param string $links_of_individual
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
        echo "updating....";
        return $id_new_post;
    } else {
    }
}

/**
 * 
 */
function create_cars_all($url_of_all_cars = "")
{
    global $content01;
    global $assoc_links_and_thumbs;
    global $global_link;

    $locale_url_parameter = "?locale=sk";
    //TODO: testing
    //$url_of_all_cars = 'https://api.jsonbin.io/b/600fb184d4d77374a3f4de01/7';
    $html = get_content($url_of_all_cars);

    //initialization url + thumbs + is new?
    get_links_of_cars_and_thumbs($html);
  
    foreach (array_keys($assoc_links_and_thumbs) as $link) {
        $url = trim(strval($link));
        $url = $url . $locale_url_parameter;

       //echo $url;
       //echo '<br>';

        $content01 = get_content($url);
        $content01 = json_decode($content01);
        //print_r( $content01);
        $global_link = $url;
        create_new_car_posts($content01, $url);
    }

  
}

/**
 * Create a new posts from the json
 * @param $url_of_all_cars
 */
function create_new_car_posts($content01, $link, $single = "")
{
    require_once(ABSPATH . 'wp-admin/includes/post.php');
    global $all_ids;
    global $assoc_links_and_thumbs;

    $url = $link;
    $url = trim($url);
    $url = str_replace('\n', '', $url);

    $post_title = $content01->make . " " . $content01->version . " " . $content01->model . " " . $content01->id;
    array_push($all_ids, $post_title);

    if (post_exists($post_title)) {
        
        // send to browser
        echo " already exists: ";
        echo $post_title;
        echo "<br>";
        echo "updated..  ";
        echo "<br>";
        $post_with_id = get_page_by_title($post_title, OBJECT, 'vozidla');
        $post_id = $post_with_id->ID;
        echo "id " . $post_id;
        echo "<br>";
        echo "<br>";
        update_post_acf($post_id);
        update_vozidla_post_meta($post_id);
        
        return;
    } else {
        $post_information = array(
            'post_title' => $post_title,
            'post_type' => 'vozidla',
            'post_status' => 'publish',
            'post_author' => 'json_robot',
        );

        echo "__creating post__" . $url . "<br>";


        $postID = wp_insert_post($post_information); //here's the catch
        print_r("<br>" . $postID . "<br>");
        ob_end_flush();

        $url_thumb = str_replace('?locale=sk', '', $url);
        upload_and_asign_thumbnail($postID, $assoc_links_and_thumbs[$url_thumb]);


        echo '<pre>';
        print_r($url_thumb);
        print_r($assoc_links_and_thumbs);
        echo '</pre>';
        if ($single) {
            echo 'je single';
            upload_and_asign_thumbnail($postID, $link);
            return $postID;
        }
    }
}


/**
 * GET ALL THE LINKS FROM THE MOTHER URL
 * @param $html_content
 */
function get_links_of_cars_and_thumbs($html_content)
{

    global $is_new_car;
    global $assoc_links_and_thumbs;

    $html = $html_content;
    $array_links = array();
    $array_thumbs = array();
    $jsonIterator = new RecursiveIteratorIterator(
        new RecursiveArrayIterator(json_decode($html, TRUE)),
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($jsonIterator as $key => $val) {
        if ($key === "url") {
            array_push($array_links, "$val");
        } else if ($key === "cover_photo") {
            array_push($array_thumbs, $val["large"]);
        } else if ($key === "name") {
            if (strpos($val, 'NEW')) {
                $is_new_car = true;
            } else {
                $is_new_car = false;
            }
        }
    }
    $links = $array_links;
    $thumbs = $array_thumbs;

    //if thumbs is not equal to links we throw an error
    if (count($links) != count($thumbs)) {
        die('Wrong thumbs upload!');
    } else {
        $assoc_links_and_thumbs = array_combine($links, $thumbs);
    }
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

    global $is_new_car;
    if ($content01_param) {
        $content_explode = $content01_param;
        $is_new_car = 0;
    }

    /*
     * TAXONOMY FIELDS VARIABLES
     *
    Značky vozidla => make
    Modely => model
    Palivá => fuel_type
    Prevodovky => gearbox_type
    Karosérie =>        *THIS IS NOT IN JSON
    Motory =>           *THIS IS NOT IN JSON
    Farby => color
    Farby interiéru =>  *THIS IS NOT IN JSON
    Pohony =>           *THIS IS NOT IN JSON
    Sklad => title
    Ročníky => production_year
    Certifikáty =>      *THIS IS NOT IN JSON
    Predajcovia => description //TODO: this is somehow rip
    Ilustračný obrázok => urls Array normal
     */

    $details = array(
        "Značky vozidla" => $content_explode->make,
        "Verzia" => $content_explode->version,
        "Modely" => $content_explode->model,
        "Palivá" => $content_explode->fuel_type,
        "Prevodovky" => $content_explode->gearbox_type,
        "Farby" => $content_explode->color,
        "Sklad" => $content_explode->title,
        "Ročníky" => $content_explode->production_year,
        "Predajcovia" => "Všeobecný predajcovia",
        "Ilustračný obrázok" => $content_explode->car_photos[0]->urls->normal,
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

    //Update the post meta
    wp_set_object_terms($post_id, $details["Modely"], 'model');
    wp_set_object_terms($post_id, $details["Palivá"], 'palivo');
    wp_set_object_terms($post_id, $details["Prevodovky"], 'prevodovka');
    wp_set_object_terms($post_id, $details["Farby"], 'farba');
    wp_set_object_terms($post_id, $details["Ročníky"], 'rocnik');
    wp_set_object_terms($post_id, $details["Predajcovia"], 'predajcovia');


    if (strpos(strtolower($details["Sklad"]), 'vyrob') || strpos(strtolower($details["Sklad"]), 'výrob')) {
        wp_set_object_terms($post_id, "Vo výrobe", 'sklad');
    } else {
        wp_set_object_terms($post_id, "Na sklade", 'sklad');
    }

    if ($is_new_car) {
        // echo 'setting the object';
        wp_set_object_terms($post_id, "vseobecne", 'predajcovia');
        wp_set_object_terms($post_id, "Nové vozidlá", 'znacka', true);
    } else {
        // echo 'setting the object';
        wp_set_object_terms($post_id, "jazdene", 'predajcovia');
        wp_set_object_terms($post_id, "Jazdené vozidlá", 'znacka', true);
    }
}


/**
 * Update post ACF
 * @param $post_id
 * @param string $content01_param
 */
function update_post_acf($post_id, $content01_param = "", $link_param = "")
{
    //$post_id = global $postID;
    /*
     * ACF VARIABLES
     * FOTKY
     * kilometre
     * certifikované vozidlo(check box)     --toto netreba
     * certifikáty radio button             --toto netreba
     * link na viac info                    -- special element nebudem riešiť so far
     * kontakty na predajcov radio button   -nieje
     * vykon
     * vykon jednotka
     * cenniková cena
     * akutálna cena s DPH
     * mesačny prenájom                     -toto nieje
     * počet dverí                          -toto nieje
     * počet sedadiel                       -toto nieje
     * počet airbagov                       -toto nieje
     * id ponuky
     * vin
     * objem
     * spotreba                             -toto nieje
     * základná výbava multi field
     * doplnková výbava nulti field
     * prehiedla- gallery
     */

    /*
    * 1. get all fields besides the gallery field
    * 2. put this to my wp and try to update the meta
    * 3. try tu update acf and resolve the issues
    * 4. finalize the gallery
    */
    global $content01;
    global $global_link;

    $content_explode = $content01;
    $global_link_domestic = $global_link;

    if ($content01_param) {
        $content_explode = $content01_param;
        $global_link_domestic = $link_param;
        $content_explode = json_encode($content_explode);
    }

    $details = array(
        "Popis" => $content_explode->title,
        "Fotky" => $content_explode->car_photos,
        "Kilometre" => $content_explode->mileage,
        "Certifikované" => "",  //TODO: check this by default
        "Certifikáty" => "",    //TODO: radio button
        "Kontakt" => "",        //TODO: this requires even logic
        "Vykon" => $content_explode->power,
        "Vykon jednotka" => $content_explode->power_unit,
        "Cennikova cena" => $content_explode->price,
        "Aktualna cena" => $content_explode->sale_price,
        "Id" => $content_explode->id,
        "Vin" => $content_explode->vin,
        "Objem" => $content_explode->capacity,
        "Základná výbava" => $content_explode->standard_features,
        "Príplatková výbava" => $content_explode->optional_features,
    );



    //update Kilometre field
    //If mileage is set to none, set value to 0
    if ($details["Kilometre"]) {
        update_field("kilometre", $details["Kilometre"], $post_id);
    } else {
        $details["kilometre"] = 0;
        update_field("kilometre", 0, $post_id);
    }

    //Update popis field
    update_field("popis", $details["Popis"], $post_id);

    //Update Výkon field
    update_field("vykon", $details["Vykon"], $post_id);

    //Update Výkon jednotak field
    update_field("vykon_jednotka", $details["Vykon jednotka"], $post_id);

    //Update Cenníková Cena
    update_field("cennikova_cena", $details["Cennikova cena"], $post_id);

    //Aktuálna cena s DPH
    update_field("aktualna_cena_s_dph", $details["Aktualna cena"], $post_id);

    //Update ID ponuky
    update_field("id_ponuky", $details["Id"], $post_id);

    //Update link ponuky
    update_field("link_json", $global_link_domestic, $post_id);

    //Update VIN field
    update_field("vin", $details["Vin"], $post_id);

    //Update Objem field
    update_field("objem", $details["Objem"], $post_id);

    //Update základná výbava
    //Get the stripped text

    $vybava = $details["Základná výbava"];
    $vybava = trim($vybava);
    $vybava = strip_tags($vybava);
    $vybava = str_replace('•', ',', $vybava);
    $vybava = ltrim($vybava, " ,");
    $vybava_nadpis = "Základná výbava";
    //$addField = array("nadpis" => "nadpis", "basic_vybava_list" => "Spoiler tag");
    $addField = array("nadpis" => $vybava_nadpis);
    $addSubField = array(
        'vybava' => $vybava,
    );
    insert_field_subfield('basic_vybava', 'basic-vybava-list', $addField, $addSubField, 'field_5f9381c2ceac0', $post_id);


    //Update extra výbava
    //Get the stripped text
    $vybava = $details["Príplatková výbava"];
    $vybava = trim(strip_tags($vybava));
    $vybava = explode("\n", $vybava);
    $vybava = implode(", ", $vybava);
    $vybava_nadpis = "Príplatková výbava";
    $addField = array("nadpis" => $vybava_nadpis);
    $addSubField = array(
        'vybava' => $vybava,
    );
    insert_field_subfield('extra_vybava', 'extra-vybava-list', $addField, $addSubField, 'field_5f938248ceac7', $post_id);

}


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

        echo 'already uploaded: ';
        print_r($already_uploaded);
        echo '<br>';

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
    print_r($attachements_urls_acf);
    update_field('obrazky_linky', $attachements_urls_acf, $post_id);
    update_field('dalsie_fotky', $attachments_urls, $post_id);
}

/**
 * Asign a thumbnail for the post
 * @param $post_id
 * @param $photo
 */
function upload_and_asign_thumbnail($post_id, $photo)
{
    $url = strval($photo);
    $desc = " ";
    $att_id = media_sideload_image($url, $post_id, $desc, 'id');
    set_post_thumbnail($post_id, $att_id);
}



/**
 * Delete all the posts which titles are not in the JSON
 * @param $all_post_titles
 * @return int
 */
function delete_the_posts()
{
    global $all_ids;
    $cnt = 0;

    $args = [
        'post_type' => 'vozidla',
        'posts_per_page' => -1,
    ];

    $loop = new WP_Query($args);
    while ($loop->have_posts()) {
        $loop->the_post();
        //echo the_title() . 'deleteing...';
        if (!in_array(get_the_title(), $all_ids)) {
            echo '<br>';
            echo 'deleting...';
            the_title();
            echo '<br>';
            wp_delete_post(get_the_id(), true);
            $cnt += 1;
            echo '<br>';
            echo 'deleted...';
        }
    }
    wp_reset_query();

    //return the number of deleted posts
    return $cnt;
}
