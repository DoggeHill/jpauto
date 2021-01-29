<?php

/**
 * Template Name: Cars JSON graphic interface
 */
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
</head>
</head>

<body>


    <?php //get_header();
    //
    //
    //

    require_once 'cars_json_functions.php';
    require_once 'cars_json_functions_json.php';
    ?>


    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-6">
                <form action="" method="post">

                    <select id="select-state" name="cars" class="form-select mt-5" aria-label="Default select example" placeholder="Vyberte auto">

                        <option value="">Napíšte meno...</option>
                        <?php

                        $args = array(
                            'post_type' => 'vozidla',
                            'posts_per_page' => -1,
                        );

                        $loop = new WP_Query($args);
                        while ($loop->have_posts()) {
                            $loop->the_post();


                        ?>

                            <option value="<?php the_ID(); ?>"><?php the_title(); ?></option>

                        <?php

                        }
                        wp_reset_query();
                        ?>


                    </select>
                   
                    <div class="form-check mt-2">
                        <input name="checkbox" class="form-check-input" type="checkbox" value="Yes" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            Zmenili sa obrázky?
                        </label>
                    </div>
     -->

                    <input class="btn btn-info mt-2" type="submit" name="submit" value="Aktualizovať vozidlo">


                </form>

            </div>

            <div class="col-lg-3 mt-5">
                <?php
                if (isset($_POST['submit'])) {
                    if (!empty($_POST['cars'])) {

                        $selected = $_POST['cars'];

                        if (
                            isset($_POST['checkbox']) &&
                            $_POST['checkbox'] == 'Yes'
                        ) {
                            echo "Zmenili sa obrázky";
                            echo 'You have chosen: ' . $selected;

                            $pocet_dveri = get_field('pocet_dveri', $selected);
                            $pocet_sedadiel = get_field('pocet_sedadiel', $selected);
                            $pocet_airbagov = get_field('pocet_airbagov', $selected);

                            $link = get_field('link_json', $selected);
                            $thumbnail_url = get_the_post_thumbnail_url($selected);
                            wp_delete_post($selected, true);
                            $id = create_cars($link, $thumbnail_url);

                            update_field('pocet_dveri', $pocet_dveri, $id);
                            update_field('pocet_sedadiel', $pocet_sedadiel, $id);
                            update_field('pocet_airbagov', $pocet_airbagov, $id);

                ?>
                            <a href="<?php the_permalink($id); ?>">Link na auto</a>
                        <?php

                            echo '<br>';
                            echo 'Aktualizované zmena gelérie' . $selected;
                            echo '<br>';
                        } else {
                            $link = get_field('link_json', $selected);
                            $content01 = get_content($link);
                            $content01 = json_decode($content01);
                            $post_title = $content01->make . " " . $content01->version . " " . $content01->model . " " . $content01->id;
                            $my_post = array(
                                'ID' => $selected,
                                'post_title' => $post_title,
                            );
                            //Update the post into the database - title
                            wp_update_post($my_post);
                            update_post_acf($selected, $content01, $link);
                            update_vozidla_post_meta($selected, $content01);

                            echo '<br>';
                            echo 'Vybrali ste' . $selected;
                            echo '<br>';


                        ?>
                            <a href="<?php the_permalink($selected); ?>">Link na auto</a>
                <?php
                            echo '<br>';
                            echo 'Aktualizované bez zmeny galérie' . $selected;
                            echo '<br>';
                        }
                    } else {
                        echo 'Prosím vyberte vozidlo....';
                    }
                }
                ?>

            </div>


        </div>


        <?php //et_footer(); 
        ?>

</body>

<script>
    $(document).ready(function() {
        $('select').selectize({
            sortField: 'text'
        });
    });
</script>

</html>


<?php












?>