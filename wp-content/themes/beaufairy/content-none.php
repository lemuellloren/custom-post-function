<div class="well">
    <h1 class="text-muted"><?php _e( 'Sorry,', 'beaufairy' ) ?></h1>
    <?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
        <p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'beaufairy' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
    <?php elseif ( is_search() ) : ?>            
        <p><?php _e( 'Nothing matched your search terms. Please try again with some different keywords.', 'beaufairy' ); ?></p>
    <?php else : ?>
        <p><?php _e( 'Can&rsquo;t find any items matching your criteria.', 'beaufairy' ); ?></p>
    <?php endif; ?>
</div> <!-- .well -->