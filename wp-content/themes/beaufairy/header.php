<?php //if(date('m-d-Y H:i:s') < '09-31-2015 12:00:00') {
     //wp_redirect( 'http://beaufairy.com/countdown-timer/', 301 ); exit;
     //} ?>
<!DOCTYPE html>
<html <?php language_attributes() ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="JRrdfj_7EBpccWTiYqBnIK_YzrU_liONZTn3s7x4K_Q" />
    <title><?php bloginfo( 'name' ) ?></title>
    <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri() ?>/favicon.ico" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php wp_head() ?>
</head>
<body <?php body_class() ?>>

    <div class="top">
        <header class="site-header">
            <div class="container-fluid">
                <div class="site-header__inner">
                    <div class="site-header__left hidden-xs">     
                        <?php if ( dynamic_sidebar( 'header-left-column' ) ) : else : endif; ?>
                    </div>
                    <div class="site-header__logo text-center">
                        <a href="<?php echo esc_url( home_url( '/' ) ) ?>">
                            <img src="<?php echo get_template_directory_uri() ?>/img/beaufairy-logo-bold-old.png" alt="<?php echo bloginfo( 'name' ) ?>">
                        </a>
                    </div>
                    <div class="site-header__right">
                        <!-- Right header dynamic content -->
                        <?php if ( dynamic_sidebar( 'header-right-column' ) ) : else : endif; ?>
                    </div>
                </div> <!-- .site-header__inner -->
            </div> <!-- .container-fluid -->
        </header> <!-- .site-header --> 

        <!-- Primary Menu -->
        <?php wp_nav_menu( array( 
            'theme_location' => 'main-menu',
            'container' => 'ul',
            'menu_class' => 'nav navbar-nav',
        ) ) ?>
        
        <?php 
            if ( is_user_logged_in() ) :
                wc_get_template( 'myaccount/profile-navigation.php' );
            endif;
        ?>
    </div> <!-- .top -->

    <main>
    <?php echo do_shortcode("[mpm_mailchimp]"); ?>

