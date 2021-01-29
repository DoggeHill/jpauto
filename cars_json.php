<?php

/**
 * Template Name: Cars JSON
 */

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php

    /*
 * TODO:
 * 1. cron job set up and run
 *
 * JSON TESTING
 * https://jsonbin.io/5fe8a790c8f3567b4a1f06df/2
 *
 */

    /**
     * Create cars from the links API RAFFINE
     */

    require_once 'cars_json_functions.php';
    //API URLS
    create_all_cars_caller();



    ?>

</body>

</html>