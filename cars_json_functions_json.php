<?php



    /**
     * HELPER FUNCTION PRETTY PRINT LINKS OF CARS
     */
    function print_links_of_cars($array_links)
    {
        foreach ($array_links as $value) echo $value;
    }

    /**
     * Helper funciton print all data gathered
     * @param $html_content
     */
    function pretty_print_json($html_content)
    {
        if (is_array($html_content) && !is_string($html_content)) $html_content = json_encode($html_content);

        echo "<pre>";
        print_r(json_decode($html_content, true));
        echo "</pre>";

        echo "<br> <br>  new content <br>";
    }



    /**
     * GET THE JSON FROM THE URL
     * @param $url
     * @return bool|string
     */
    function get_content($url)
    {
        ///CREATE A CURL SESSSION
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //pretend to be not a bot
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        $html = new stdClass();

        //avoid any errors and generate dump content if data is not gathered
        try {
            //TRY TO GET THE CONTENT
            curl_setopt($ch, CURLOPT_URL, $url);
            $html = curl_exec($ch);
            curl_error($ch);
            curl_reset($ch);
            curl_close($ch);
            $ch = new stdClass();
            if ($html == NULL) throw new Exception('NULL after crawling');
        } catch (Exception $e) {
            echo 'An error has occured: ', $e->getMessage(), "\n";
            //add message to our html content
            $error = new stdClass();
            $error->error = "error";
            $html = json_encode($error);
        }
        return $html;
    }


       

    /**
     * Stackoverflow function to update the nested repeater in ACF on the CPT creation
     * https://stackoverflow.com/questions/38384500/add-row-to-nested-repeater-in-wordpress-advanced-custom-fields-pro#
     * @param $repeater_field
     * @param $repeater_subfield
     * @param $field_values
     * @param $subfield_values
     * @param $field_key
     * @param $postid
     * @return bool
     */
    function insert_field_subfield($repeater_field, $repeater_subfield, $field_values, $subfield_values, $field_key, $postid)
    {
        //never add new row
        /*
        if (get_field($field_key, $postid)) {
            $value = get_field($field_key, $postid);
        } else {
        }*/

        $value = array();
        $value[] = $field_values;

        if (update_field($field_key, $value, $postid)) {

            $i = 0;

            if (have_rows($repeater_field, $postid)) {

                $spoiler_item = get_field($repeater_field, $postid);
                $total_rows = count(get_field($repeater_field, $postid)) - 1;

                while (have_rows($repeater_field, $postid)) : the_row();

                    if ($i == ($total_rows)) {

                        if (!is_array($spoiler_item[$i][$repeater_subfield])) {

                            $spoiler_item[$i][$repeater_subfield] = array();
                        }

                        if (count($subfield_values) == count($subfield_values, COUNT_RECURSIVE)) { // subfield_values is not multidimensional

                            array_push($spoiler_item[$i][$repeater_subfield], $subfield_values);
                        } else { // subfield_values is multidimensional

                            foreach ($subfield_values as $subfield_value) {

                                array_push($spoiler_item[$i][$repeater_subfield], $subfield_value);
                            }
                        }
                    }
                    $i++;

                endwhile;

                if (update_field($field_key, $spoiler_item, $postid)) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }
