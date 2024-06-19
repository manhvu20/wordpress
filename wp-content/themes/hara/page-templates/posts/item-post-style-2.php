<?php
$style           = isset($style) ? $style : 'post-style-2';
$thumbsize       = isset($thumbnail_size_size) ? $thumbnail_size_size : 'medium';
$show_title      = hara_switcher_to_boolean($show_title); ;
$show_category   = hara_switcher_to_boolean($show_category);
$show_author     =  hara_switcher_to_boolean($show_author);
$show_date       =  hara_switcher_to_boolean($show_date);
$show_comments   =  hara_switcher_to_boolean($show_comments);
$show_comments_text   =  hara_switcher_to_boolean($show_comments_text);
$post_title_tag       = isset($post_title_tag) ? $post_title_tag : 'h3';
$show_excerpt    =  hara_switcher_to_boolean($show_excerpt);
$excerpt_length  = isset($excerpt_length) ? $excerpt_length : 15;
$show_read_more  =  hara_switcher_to_boolean($show_read_more);
$read_more_text  = isset($read_more_text) ? $read_more_text : esc_html__('Read more', 'hara');


$text_domain               = esc_html__(' comments', 'hara');
if (get_comments_number() == 1) {
    $text_domain = esc_html__(' comment', 'hara');
}

?>
<article class="post item-post <?php echo esc_attr($style); ?>">   
    <?php
        if(has_post_thumbnail()) {
            ?>
            <figure class="entry-thumb <?php echo(!has_post_thumbnail() ? 'no-thumb' : ''); ?>">
                <?php if ($show_date == 1): ?>
                    <span class="entry-date"><?php echo hara_time_link(); ?></span>
                <?php endif ?>
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
            <?php
        }

    ?> 

    
    <div class="entry-header">
        <?php hara_post_meta(array(
            'date'          => $show_date,
            'author'        => $show_author,
            'author_img'    => 0,
            'comments'      => $show_comments,
            'comments_text' => $show_comments_text,
            'tags'          => 0,
            'cats'          => $show_category,
            'edit'          => 0,
        )); ?>
        
        <?php do_action('hara_blog_before_meta_list'); ?>
        
        <?php if ($show_title && get_the_title()) : ?>
            <<?php echo trim($post_title_tag); ?> class="entry-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </<?php echo trim($post_title_tag); ?>>
        <?php endif; ?>

        <?php if ($show_excerpt) : ?>
            <div class="entry-description"><?php echo hara_tbay_substring(get_the_excerpt(), $excerpt_length, '...'); ?></div>
        <?php endif; ?>

        <?php if ($show_read_more) : ?>
            <a href="<?php the_permalink(); ?>" class="readmore" title="<?php echo esc_attr($read_more_text); ?>"><?php echo trim($read_more_text); ?></a>
        <?php endif; ?>

        <?php do_action('hara_blog_after_meta_list'); ?>
    </div>
</article>
