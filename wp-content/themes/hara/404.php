<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage Hara
 * @since Hara 1.0
 */
/*

*Template Name: 404 Page
*/
get_header();
$image = hara_tbay_get_config('img_404');
if (isset($image['url']) && !empty($image['url'])) {
    $image = $image['url'];
} else {
    $image = HARA_IMAGES . '/img-404.jpg';
}
?>

<section id="main-container" class=" container inner page-404">
	<div id="main-content" class="main-page">

		<section class="error-404">

			<h1 class="title-404"><?php esc_html_e('OOPS!', 'hara') ?></h1>
			<h2 class="subtitle-404"><?php esc_html_e('Error 404: Page Not Found', 'hara') ?></h2>

			<div class="hara-img-404">
				<img src="<?php echo esc_attr($image); ?>" alt="<?php esc_attr_e('Img 404', 'hara'); ?>">
			</div>
			<div class="hara-content-404">
				<p class="sub-title"><?php esc_html_e( 'We’re very sorry but the page you are looking for doesn’t exist or has been moved. Please back to', 'hara') ?> <a href="<?php echo esc_url(home_url( '/' )) ?>" class="back"><?php esc_html_e('home page', 'hara'); ?></a> <?php esc_html_e( 'if It’s mistake.', 'hara') ?></p>
			</div>
		</section><!-- .error-404 -->
	</div>
</section>

<?php get_footer(); ?>