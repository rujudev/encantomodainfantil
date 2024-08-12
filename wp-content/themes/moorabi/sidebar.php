<?php
$blog_used_sidebar = 'widget-area';
if (is_single()) {
    $blog_used_sidebar = 'widget-area';
}
?>
<?php if (is_active_sidebar($blog_used_sidebar)) : ?>
    <div id="widget-area" class="widget-area sidebar-blog">
        <?php dynamic_sidebar($blog_used_sidebar); ?>
    </div><!-- .widget-area -->
<?php endif; ?>