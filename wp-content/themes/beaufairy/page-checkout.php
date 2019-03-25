<?php

get_header();

if ( is_checkout() && ! is_wc_endpoint_url() ) {
    // use wide layout for main checkout page
    get_template_part( 'content', 'wide');
} else {
    get_template_part( 'content', 'main' ); 
}

get_footer();
