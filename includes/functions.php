<?php
/* funcion que muestra el producto anterior y el siguiente */
function AntSegProduct()
{
    //comprova que es wordpress qui ejecuta la funcio
    if (!defined('WP_UNINSTALL_PLUGIN')) {
        global $wpdb;
        global $post;
        $showProducts = true;
        $lang = $_COOKIE['pll_language'];
        $posicion_actual = null;
        $noDisponibleIMG = '<img width="180" height="180" src="' . WP_PLUGIN_URL . '/AntSegProduct/public/img/no_disponible.png" data-src="' . WP_PLUGIN_URL . '/AntSegProduct/public/img/no_disponible.png"
         class="attachment-180x180 size-180x180 wp-post-image ls-is-cached lazyloaded" alt="imagen no disponible" loading="lazy" data-srcset="' . WP_PLUGIN_URL . '/AntSegProduct/public/img/no_disponible.png" 
         srcset="' . WP_PLUGIN_URL . '/AntSegProduct/public/img/no_disponible.png" data-sizes="(max-width: 180px) 100vw, 180px" sizes="(max-width: 180px) 100vw, 180px">';


        //Categoria
        if ($_COOKIE['section'] === "product-category") {
            //categoria actual en el idioma actual
            $cat_actual = $lang == 'ca' ? $_COOKIE['cat-ca'] : $_COOKIE['cat-es'];

            //productes de la mateixa categoria del idioma selecciOnat
            $sql = "SELECT DISTINCT p.ID, p.post_name, p.post_title FROM $wpdb->posts as p, $wpdb->term_relationships as tr, $wpdb->term_taxonomy as tt, $wpdb->terms as t WHERE p.ID=tr.object_id AND tr.term_taxonomy_id=tt.term_taxonomy_id AND tt.term_id=t.term_id AND p.post_type = 'product' AND t.slug ='" . $cat_actual . "' ORDER BY p.post_title;";

            //buscar
        } elseif ($_COOKIE['section'] === "search") {
            $search = $_COOKIE['search'];
            $sql = "SELECT DISTINCT p.ID, p.post_name, p.post_title, t.slug FROM $wpdb->posts as p, $wpdb->term_relationships as tr, $wpdb->term_taxonomy as tt, $wpdb->terms as t WHERE p.ID=tr.object_id AND tr.term_taxonomy_id=tt.term_taxonomy_id AND tt.term_id=t.term_id AND p.post_type = 'product' AND p.post_title LIKE '%" . $search . "%' AND t.slug='" . $lang . "' ORDER BY p.post_title;";

            //ninguna
        } else {
            $showProducts = false;
        }

        //mostra anterior i següent si section es categoria o si es search
        if ($showProducts === true) {
            //executa la consulta
            $posts = $wpdb->get_results($sql);

            //busca la posicio que te en el array el producte mostrat per a agarrar el -1 i el +1

            foreach ($posts as $i => $p) {
                if ($post->ID == $p->ID) {
                    $posicion_actual = $i;
                    break;
                }
            }

            //si posicio actual es la primera (0), la anterior serà la última (-1 perque count no conta el 0);
            $previoPost = $posicion_actual == 0 ? $posts[count($posts) - 1] : $posts[$posicion_actual - 1];

            //si posicio actual es l'últim, el següent serà el primer (0)
            $nextPost = $posicion_actual == count($posts) - 1 ? $posts[0] : $posts[$posicion_actual + 1];


            //Producte Anterior
            if ($previoPost !== NULL) {
                $prevThumbnail = get_the_post_thumbnail($previoPost->ID, array(180, 180));
                if (!isset($prevThumbnail) || $prevThumbnail == '') {
                    $prevThumbnail = $noDisponibleIMG;
                }
                $prevTitle = get_the_title($previoPost->ID);
                $prevLink = get_permalink($previoPost->ID);


                echo '<div class="cta-bar-container position-left d-none d-md-block">
                        <div class="cta-bar" title="' . $prevTitle . '">
                            <a href="' . $prevLink . '">
                                ' . $prevThumbnail . '
                            </a>
                        </div>
                        <div class="hide-cta d-flex align-items-center">
                            <b class="pr-1">
                                ' . __('PREVIO', 'antsegproduct') . '
                            </b>
                            <div class="text-block overflow-elipsis d-block">
                                <p class="m-0">/ ' . $prevTitle . '</p>
                            </div>

                        </div>
                    </div>';
            } //fin if null



            //Producte Seguent

            if ($nextPost !== NULL) {
                $nextThumbnail = get_the_post_thumbnail($nextPost->ID, array(180, 180));
                if (!isset($nextThumbnail) || $nextThumbnail == '') {
                    $nextThumbnail = $noDisponibleIMG;
                }
                $nextTitle = get_the_title($nextPost->ID);
                $nextLink = get_permalink($nextPost->ID);

                echo '<div class="cta-bar-container position-right d-none d-md-block">
                        <div class="cta-bar" title="' . $nextTitle . '">
                            <a href="' . $nextLink . '">
                                ' . $nextThumbnail . '
                            </a>
                        </div>
                        <div class="hide-cta d-flex align-items-center">
                            <b class="pr-1">' .
                    __('SIGUIENTE', 'antsegproduct') . ' </b>
                            <div class="text-block overflow-elipsis d-block">
                                <p class="m-0">/ ' . $nextTitle . '</p>
                            </div>

                        </div>
                    </div>';
            } //fin if null
        } // fin if products true
    }
}

/* Enganxar en woocommerce per a que s'execute */
add_action('woocommerce_before_single_product', 'AntSegProduct');
add_action('woocommerce_after_single_product', 'AntSegProduct');
