    </main> <!-- main -->

    <footer class="site-footer">
        <div class="container">
            <div class="piece">
                <div class="row">
                    <div class="col-md-4 col-sm-4">
                        <?php if ( dynamic_sidebar( 'footer-first-column' ) ) : else : endif; ?>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <?php if ( dynamic_sidebar( 'footer-second-column' ) ) : else : endif; ?>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <?php if ( dynamic_sidebar( 'footer-third-column' ) ) : else : endif; ?>
                    </div>
                </div> <!-- .row -->
            </div> <!-- .piece -->
        </div> <!-- .container -->
        <div class="site-footer__note">
            <div class="container-fluid">
                <p class="copyright left-lg left-md left-sm">
                    &copy;
                    <?php echo date( 'Y' ) ?> 
                    <?php echo bloginfo( 'name' ) ?>. 
                    <?php echo __( 'All rights reserved.', 'beaufairy' ) ?>
                </p>
                <p class="powered-by right-lg right-md right-sm">
                    <?php echo __( 'Powered by', 'beaufairy' ) ?>
                    <strong><a href="http://leetdigital.com.au">Leet Digital</a></strong>
                </p>
            </div>
        </div><!-- .site-footer__note -->
    </footer> <!-- .site-footer -->

    <!-- Extra markups goes here -->
    <?php get_search_form() ?>
    <?php get_template_part( 'content', 'spinner' ) ?>
    &nbsp;<a href="https://www.cheapjerseysonlinefreeshipping.us.com">Nike NFL Jerseys Supply</a>

    <?php wp_footer() ?>
    
</body>
</html>