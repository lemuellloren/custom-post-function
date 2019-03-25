<div class="search-overlay overlay">
    <div class="container">
        <form action="/" class="search">
            <div class="input-group">
                <input type="text" class="form-control search-overlay__input" name="s" id="search" value="<?php the_search_query(); ?>">
                <div class="search-overlay__submit input-group-addon">
                   <span class="fa fa-search"></span>
                </div>
                <div class="search-overlay__close input-group-addon">
                    <span class="fa fa-times"></span>
                </div>
            </div>
        </form>
    </div> <!-- .container -->
</div> <!-- .search-overlay -->