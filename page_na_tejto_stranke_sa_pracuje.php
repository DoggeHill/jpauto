<?php

/**
 * Template Name: Na tejto stranke sa pracuje
 */

get_header(); ?>

<section class="service-page">

    <div class="service-form">
        <div id="objednajte_si_servis" class="a-link-anchor"></div>
        <div class="form-container" id="onlineServis">
            <div class="form-title">
                <h2>Na našej stránke sa pracuje</h2>
            </div>
            <div class="form-wrapper">
                <p class="text-center">Ospravedlňujeme sa, onedlho sa k Vám vrátime!</p>
            </div>
        </div>
    </div>

    <?php if (have_rows('elementy_stranky_servis')) :
        while (have_rows('elementy_stranky_servis')) : the_row();

            echo get_template_part('parts/image-text-element');

            break;
        endwhile;
    endif; ?>

    <?php if (have_rows('element_3_obrazky')) :
        while (have_rows('element_3_obrazky')) : the_row();

            echo get_template_part('parts/image-text-element-black');

        endwhile;
    endif; ?>

    <?php $i = 2;
    if (have_rows('elementy_stranky_servis')) :
        while (have_rows('elementy_stranky_servis')) : the_row();

            if ($i == 2 or $i == 4) : ?>
                <div class="change-order">
                    <?php echo get_template_part('parts/image-text-element'); ?>
                </div>
            <?php endif;

            if ($i == 3) :
                echo get_template_part('parts/image-text-container-element');
            endif;

            if ($i == 5) : ?>
                <div class="change-order-black">
                    <?php echo get_template_part('parts/image-text-element-black'); ?>
                </div>
    <?php endif;

            $i++;
        endwhile;
    endif; ?>

</section>

<?php echo get_template_part('parts/footer-map'); ?>


<?php get_footer(); ?>