<?php get_header(); ?>

<section class="car-single-page">
  <div class="car-single-top">
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-5 col-lg-5">
          <div class="row">
            <div class="car-price-info">
              <h1><?php the_title() ?></h1>
              <?php if (get_field('cennikova_cena')) : ?>
                <p class="catalog-price">Cenníková cena: <?php echo number_format(get_field('cennikova_cena'), 0, '.', ' ') ?> €</p>
              <?php endif; ?>
              <p class="actual-price-with-DPH"><?php echo number_format(get_field('aktualna_cena_s_dph'), 0, '.', ' ') ?> € s DPH</p>
              <p class="actual-price-without-DPH">
                <?php
                $bezDPH = get_field('aktualna_cena_s_dph') * 0.83333;
                echo number_format($bezDPH, 0, '.', ' ') ?>
                € možný odpočet DPH
              </p>
              <div class="links-wrapper">
                <a class="link-item" href="#rezervaciaVozidla" data-target="#rezervaciaVozidla" data-toggle="modal">> rezervácia vozidla</a>
                <a class="link-item" href="#" id="test-drive-go">> testovacia jazda</a>
                <a class="link-item" href="#" id="calc-drive-go">> kalkulácia financovania</a>
                <a class="link-item" href="#kontaktovatPredajcu" data-target="#kontaktovatPredajcu" data-toggle="modal">> kontaktovať predajcu</a>
              </div>
              <div class="kalkulacia-wrapper">
                <p class="kalkulacia-text">Mesačný prenájom: od <b><?php echo number_format(get_field('mesacny_prenajom'), 0, '.', ' ') ?> €</b> s DPH</p>
                <a class="btn kalkulacia-button" href="">Kalkulácia</a>
              </div>




              <!--   <?php //if( have_rows('info_kontakt', 'option') ): 
                      ?>
      <div class="single-car-contacts">
      <?php //while( have_rows('info_kontakt', 'option') ): the_row();
      ?>
          <div class="single-contact">
            <h6><?php //the_sub_field('meno', 'option'); 
                ?></h6>
            <p><?php //the_sub_field('pozicia', 'option'); 
                ?></p>
            <hr>
            <a href="tel:<?php //the_sub_field('telefonne_cislo', 'option'); 
                          ?>" target="_blank"><?php //the_sub_field('telefonne_cislo', 'option'); 
                                              ?></a>
            <a href="mailto:<?php //the_sub_field('e-mail', 'option'); 
                            ?>" target="_blank"><?php //the_sub_field('e-mail', 'option'); 
                                                ?></a>
          </div>
      <?php //endwhile; 
      ?>
      </div>
  <?php //endif; 
  ?> -->



              <?php $predajcovia = get_the_terms(get_the_id(), 'predajcovia'); ?>

              <?php if (!empty($predajcovia)) : ?>
                <?php foreach ($predajcovia  as $key => $predajca) { ?>


                  <?php if (have_rows('info_kontakt_tax',  $predajca)) : ?>
                    <div class="single-car-contacts cd">
                      <?php while (have_rows('info_kontakt_tax',  $predajca)) : the_row(); ?>
                        <div class="single-contact">
                          <h6><?php the_sub_field('meno'); ?></h6>
                          <p><?php the_sub_field('pozicia'); ?></p>
                          <hr>
                          <a href="tel:<?php the_sub_field('telefonne_cislo'); ?>" target="_blank"><?php the_sub_field('telefonne_cislo'); ?></a>
                          <a href="mailto:<?php the_sub_field('e-mail'); ?>" target="_blank"><?php the_sub_field('e-mail'); ?></a>
                        </div>
                      <?php endwhile; ?>
                    </div>
                  <?php endif; ?>



                <?php  } ?>
              <?php
              endif;
              ?>






            </div>
            <div class="scroll">
              <img src="<?php echo get_template_directory_uri() ?>/seduco-core/pictures/svg/scroll.svg">
            </div>
          </div>
        </div>
        <div class="col-xl-7 col-lg-7">
          <div class="row">
            <div class="image-wrapper">
              <div class="model_swiper">
                <div class="swiper-wrapper">
                  <div class="swiper-slide">
                    <?php
                    $image = get_post_thumbnail_id();
                    $size = 'large';
                    if ($image) {
                      echo wp_get_attachment_image($image, $size, "", array("class" => ""));
                    }
                    ?>
                  </div>
                  <?php $images = get_field('dalsie_fotky');
                  $size = 'large';
                  if ($images) :
                    foreach ($images as $image_id) :
                  ?>
                      <div class="swiper-slide">
                        <?php echo wp_get_attachment_image($image_id, $size); ?>
                      </div>
                  <?php endforeach;
                  endif; ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <!--              <div class="model_swiper_box">-->
                <!--                <div class="image">-->
                <!--                  <img src="--><?php //echo get_template_directory_uri() 
                                                    ?>
                <!--/seduco-core/pictures/svg/gearshifticon.svg">-->
                <!--                </div>-->
                <!--                <div class="prev_button"><img src="--><?php //echo get_template_directory_uri() 
                                                                          ?>
                <!--/seduco-core/pictures/svg/up_white.svg"></div>-->
                <!--                <div class="swiper-pagination"></div>-->
                <!--                <div class="next_button"><img src="--><?php //echo get_template_directory_uri() 
                                                                          ?>
                <!--/seduco-core/pictures/svg/down_white.svg"></div>-->
                <!--              </div>-->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="car-tech-info car-technical">
    <div class="container-fluid">
      <div class="tech-wrapper row">
        <div class="tech-item">
          <div class="tech-key">
            <h6>Modely:</h6>
          </div>
          <div class="tech-value">
            <p><?php $term = get_the_terms(get_the_ID(), 'model');
                echo $term[0]->name; ?></p>
          </div>
        </div>
        <div class="tech-item">
          <div class="tech-key">
            <h6>Modelový ročník:</h6>
          </div>
          <div class="tech-value">
            <p><?php $term = get_the_terms(get_the_ID(), 'rocnik');
                echo $term[0]->name; ?></p>
          </div>
        </div>
        <div class="tech-item">
          <div class="tech-key">
            <h6>VIN:</h6>
          </div>
          <div class="tech-value">
            <p><?php the_field('vin'); ?></p>
          </div>
        </div>
        <div class="tech-item">
          <div class="tech-key">
            <h6>Exteriér:</h6>
          </div>
          <div class="tech-value">
            <p><?php $term = get_the_terms(get_the_ID(), 'farba');
                echo $term[0]->name; ?></p>
          </div>
        </div>
        <div class="tech-item">
          <div class="tech-key">
            <h6>Druh paliva:</h6>
          </div>
          <div class="tech-value">
            <p><?php $term = get_the_terms(get_the_ID(), 'palivo');
                echo $term[0]->name; ?></p>
          </div>
        </div>
        <div class="tech-item">
          <div class="tech-key">
            <h6>Počet najazdených kilometrov:</h6>
          </div>
          <div class="tech-value">
            <p><?php the_field('kilometre'); ?> km</p>
          </div>
        </div>
        <div class="tech-item">
          <div class="tech-key">
            <h6>Objem motora:</h6>
          </div>
          <div class="tech-value">
            <p><?php the_field('objem'); ?></p>
          </div>
        </div>
        <div class="tech-item">
          <div class="tech-key">
            <h6>Výkon motora:</h6>
          </div>
          <div class="tech-value">
            <p><?php the_field('vykon');
                the_field('vykon_jednotka'); ?> </p>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="car-equipment">
    <div class="container-fluid">
      <?php if (have_rows('basic_vybava')) : ?>
        <div class="row">
          <?php while (have_rows('basic_vybava')) : the_row(); ?>

            <div class="col">

              <?php if (get_sub_field('nadpis')) : ?>
                <h4><?php the_sub_field('nadpis'); ?></h4>
              <?php endif; ?>

              <?php if (have_rows('basic-vybava-list')) : ?>
                <ul class="basic-options-car">
                  <?php while (have_rows('basic-vybava-list')) : the_row(); ?>
                    <li>
                      <?php the_sub_field('vybava'); ?>
                    </li>
                  <?php endwhile; ?>
                </ul>
              <?php endif; ?>

            </div>
          <?php endwhile; ?>
        </div>

      <?php endif; ?>

      <?php if (have_rows('extra_vybava')) : ?>


        <div class="row">
          <?php while (have_rows('extra_vybava')) : the_row(); ?>
            <div class="col">

              <?php if (get_sub_field('nadpis')) : ?>
                <h4><?php the_sub_field('nadpis'); ?></h4>
              <?php endif; ?>


              <?php if (have_rows('extra-vybava-list')) : ?>
                <ul class="extra-options-car">
                  <?php while (have_rows('extra-vybava-list')) : the_row(); ?>
                    <li>
                      <?php the_sub_field('vybava'); ?>
                    </li>
                  <?php endwhile; ?>
                </ul>
              <?php endif; ?>

            </div>
          <?php endwhile; ?>
        </div>

      <?php endif; ?>

    </div>
  </div>



  <?php
  $image = get_field('prehliadka');
  $size = 'full'; // (thumbnail, medium, large, full or custom size)
  if ($image) { ?>


    <section class="prehliadka-section">
      <div class="container-fluid">
        <div class="row">
          <div class="col pl-0 pr-0">
            <div class="img-holder-cover">
              <?php
              echo wp_get_attachment_image($image, $size);
              ?>
            </div>
          </div>
        </div>
      </div>
    </section>
  <?php
  } ?>



  <section class="certificate">
    <div class="container-fluid">


      <?php $certificates = get_the_terms(get_the_id(), 'certificates'); ?>

      <?php if (!empty($certificates)) : ?>
        <?php foreach ($certificates  as $key => $certificate) { ?>




          <div class="row">
            <div class="col">









              <?php if (have_rows('certifikat',  $certificate)) : ?>
                <?php while (have_rows('certifikat',  $certificate)) : the_row(); ?>

                  <h4><?php the_sub_field('nadpis'); ?></h4>

                  <?php if (have_rows('extra_sluzby')) : ?>
                    <ul class="extra_sluzby">
                      <?php while (have_rows('extra_sluzby')) : the_row();
                        $image = get_sub_field('image');
                      ?>
                        <li>
                          <?php
                          $image = get_sub_field('ikona');
                          $size = 'full'; // (thumbnail, medium, large, full or custom size)
                          if ($image) {
                            echo wp_get_attachment_image($image, $size);
                          }
                          ?>
                          <p><?php the_sub_field('popis'); ?></p>
                        </li>
                      <?php endwhile; ?>
                    </ul>
                  <?php endif; ?>


                  <div class="dalsie_sluzby">
                    <?php the_sub_field('dalsie_sluzby'); ?>
                  </div>

                <?php endwhile; ?>
              <?php endif; ?>




            </div>
          </div>

        <?php  } ?>
      <?php
      endif;
      ?>


      <div class="row justify-content-center row-button">
        <div class="col-auto">


          <?php
          $link = get_field('link_viac_info');
          if ($link) :
            $link_url = $link['url'];
            $link_title = $link['title'];
            $link_target = $link['target'] ? $link['target'] : '_self';
          ?>
            <a class="more-info-link" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
          <?php endif; ?>


        </div>
      </div>

    </div>
  </section>

  <div class="new-offers">
    <div class="container-fluid">
      <h4>Najnovšie ponuky</h4>
      <div class="new-offers-swiper">
        <div class="swiper-wrapper">
          <?php
          $modelTerm = get_the_terms(get_the_ID(), 'model');
          $modelslug = $modelTerm[0]->slug;
          if (in_array($modelslug, array('defender', 'range-rover'))) {
            $modelArray = array('defender', 'range-rover');
          } elseif (in_array($modelslug, array('e-pace', 'xe', 'range-rover-evoque', 'discovery-sport'))) {
            $modelArray = array('e-pace', 'xe', 'range-rover-evoque', 'discovery-sport');
          } elseif (in_array($modelslug, array('f-pace', 'xf', 'range-rover-velar', 'i-pace'))) {
            $modelArray = array('f-pace', 'xf', 'range-rover-velar', 'i-pace');
          } elseif (in_array($modelslug, array('discovery', 'range-rover-sport'))) {
            $modelArray = array('discovery', 'range-rover-sport');
          }
          $args = array(
            'post_type' => 'vozidla',
            'orderby'   => 'date',
            'order'     => 'DESC',
            'post__not_in' => array(get_the_ID()),
            'tax_query' => array(
              array(
                'taxonomy' => 'model',
                'field' => 'slug',
                'terms' => $modelArray
              ),
            ),
          );
          // the query
          $the_query = new WP_Query($args); ?>
          <?php if ($the_query->have_posts()) : ?>
            <!-- pagination here -->
            <!-- the loop -->
            <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
              <div class="swiper-slide">
                <div class="image-wrapper">
                  <a href="<?php the_permalink(); ?>">
                    <?php
                    $image = get_post_thumbnail_id();
                    $size = 'medium';
                    if ($image) {
                      echo wp_get_attachment_image($image, $size, "", array("class" => ""));
                    }
                    ?>
                  </a>
                </div>
                <div class="info-wrapper">
                  <p class="title"><a href="<?php the_permalink(); ?>"><?php
                                                                        $title = get_the_title();
                                                                        $title = substr($title, 0, -5);
                                                                        echo $title;
                                                                        ?></a></p>
                  <div class="bottom-info">
                    <span><?php $rocnik = get_the_terms(get_the_ID(), 'rocnik');
                          echo $rocnik[0]->name; ?></span>
                    <span class="line"></span>
                    <span><?php $term = get_the_terms(get_the_ID(), 'palivo');
                          echo $term[0]->name; ?></span>
                    <span class="line"></span>
                    <span><?php the_field('vykon'); ?> PS</span>
                    <span class="line"></span>
                    <span><?php the_field('kilometre'); ?> km</span>
                  </div>
                  <p class="price">
                    <?php

                    if (get_field('aktualna_cena_s_dph') == 0) {
                      echo number_format(get_field('cennikova_cena'), 0, '.', ' ')  ?> € s DPH</p>

                <?php
                    } else {
                      echo number_format(get_field('aktualna_cena_s_dph'), 0, '.', ' ')  ?> € s DPH</p>

                <?php
                    }
                ?>
                </div>
              </div>
            <?php endwhile; ?>
            <!-- end of the loop -->
            <!-- pagination here -->
            <?php wp_reset_postdata(); ?>
          <?php else : ?>
            <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
          <?php endif; ?>
        </div>
      </div>
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-bullets"></div>
    </div>
  </div>

  <section class="modal-section">
    <div class="modal fade" id="rezervaciaVozidla" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <div class="form-container">
              <div class="form-title">
                <img src="<?php echo get_template_directory_uri(); ?>/seduco-core/pictures/support.png" alt="Wheel icon" class="form-icon">
                <span class="line"></span>
                <h2>Mám záujem o nezáväznú rezerváciu vozidla na 24 hodín. </h2>
              </div>
              <div class="form-wrapper">
                <?php echo do_shortcode('[contact-form-7 id="420" title="Rezervácia vozidla"]'); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="post-url"><?php echo get_permalink(); ?></div>

  <section class="modal-section kalkulaciaModal">
    <div class="modal fade" id="kalkulaciaFinancovania" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <div class="form-container">
              <div class="form-title">
                <img src="<?php echo get_template_directory_uri(); ?>/seduco-core/pictures/support.png" alt="Wheel icon" class="form-icon">
                <span class="line"></span>
                <h2>Kalkulácia financovania</h2>
              </div>
              <div class="form-wrapper">
                <?php echo do_shortcode('[contact-form-7 id="668" title="Kalkulácia financovania"]'); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="modal-section kontaktovatModal">
    <div class="modal fade" id="kontaktovatPredajcu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <div class="form-container">
              <div class="form-title">
                <img src="<?php echo get_template_directory_uri(); ?>/seduco-core/pictures/support.png" alt="Wheel icon" class="form-icon">
                <span class="line"></span>
                <h2>Kontaktovať predajcu</h2>
              </div>
              <div class="form-wrapper">
                <?php echo do_shortcode('[contact-form-7 id="669" title="Kontaktovať predajcu"]'); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


</section>

<?php echo get_template_part('parts/test-drive-form'); ?>

<?php echo get_template_part('parts/footer-map'); ?>


<?php get_footer(); ?>