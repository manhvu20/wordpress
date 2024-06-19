<?php

$text_domain               = esc_html__(' comments', 'hara');
$thumbsize       = isset($thumbnail_size_size) ? $thumbnail_size_size : 'thumbnail';
if (get_comments_number() == 1) {
    $text_domain = esc_html__(' comment', 'hara');
}

?>
<div class="post item-post single-reladted">   
    <figure class="entry-thumb <?php echo(!has_post_thumbnail() ? 'no-thumb' : ''); ?>">
        <?php 
            if (has_post_thumbnail()) {
                ?>
                <span class="entry-date"><?php echo hara_time_link(); ?></span>
                <?php
            }
        ?>
        <a href="<?php the_permalink(); ?>"  class="entry-image">
            <?php 
                if (hara_elementor_activated()) {
                    the_post_thumbnail($thumbsize);
                } else {
                    the_post_thumbnail();
                }
            ?>
        </a> 
    </figure>
    <div class="entry-header">
        <?php if (get_the_category_list()) {
            ?>
                <div class="entry-category"><?php hara_the_post_category_full() ?></div>
            <?php
        } ?>
        <?php if (get_the_title()) : ?>
            <h3 class="entry-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>
        <?php endif; ?>
        
        <ul class="entry-meta-list"> 
            <li class="entry-author">
                <?php echo get_avatar(hara_tbay_get_id_author_post(), 30); ?>
                <?php echo get_the_author_posts_link(); ?>
            </li>
 
            <?php if (comments_open()): ?>
                <li class="comments-link">
                <i class="tb-icon tb-icon-comment"></i> 
                    <?php comments_popup_link(
                    '0' .'<span>'. $text_domain .'</span>',
                    '1' .'<span>'. $text_domain .'</span>',
                    '%' .'<span>'. $text_domain .'</span>'
                ); ?>
                </li>
            <?php endif; ?>
        </ul>

    </div>
</div>
