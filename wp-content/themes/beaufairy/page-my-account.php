<?php

get_header();

if ( is_wc_endpoint_url( 'edit-address' ) || is_wc_endpoint_url( 'edit-account' ) ) {
    // use wide layout for main checkout page
    get_template_part( 'content', 'account');
} else {
    get_template_part( 'content', 'main' ); 
}

get_footer();
