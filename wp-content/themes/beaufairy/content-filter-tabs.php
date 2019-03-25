<?php
    // Get subcategories of the current category
    $subcategories = get_categories( array(
        'child_of'   => $current,
        'hide_empty' => 0
    ) );

    if ( ! empty( $subcategories ) ) :
?>
    
    <section class="filter-tabs">
        <div href="#" class="filter-tabs__toggle">
            <?php echo __( 'Select Category', 'beaufairy' ) ?> <span class="fa fa-plus-circle"></span>
        </div>
        <div class="filter-tabs__items">
            <a <?php if ( $cat_id == $current ) echo "class=\"active\""; ?> 
                href="<?php echo get_category_link( $current ) ?>"
                >
                <?php echo __( 'All', 'beaufairy' ) ?>
            </a>

            <?php /* Loop through each subcategories */ ?>
            <?php foreach ( $subcategories as $subcat ) : ?>
            <a <?php if ( $cat_id == $subcat->term_id ) echo "class=\"active\""; ?> 
                href="<?php echo add_query_arg( array( 'subcat' => $subcat->term_id ), get_category_link( $current ) ) ?>"
                >
                <?php echo __( $subcat->name , 'beaufairy' ) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </section> <!-- .filter-tabs -->

<?php
    endif;
?>
