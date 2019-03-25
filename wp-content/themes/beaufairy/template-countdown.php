<?php
/*
 * Template Name: countdown
*/?>

<!DOCTYPE html>
<html <?php language_attributes() ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php bloginfo( 'name' ) ?></title>
    <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri() ?>/favicon.ico" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php wp_head() ?>
</head>
<body <?php body_class(); ?> style="text-align:center;">

			<br><br>
		<img src="http://beaufairy.com/wp-content/uploads/2015/08/bf_logo.png" style="width:500px;"> <br><br><br>
		<h2>Be the fairest of them all..</h2><br>
    	<?php echo do_shortcode('[ujicountdown id="Website Launching" expire="2015/08/31 12:00" hide="false" url="beaufairy.com" subscr="Website Launching" recurring="" rectype="second" repeats=""]');?>

<?php wp_footer(); ?>
 </body>
	
</html>