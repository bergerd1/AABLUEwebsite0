<?php
/*
Template Name: Services & Repairs
*/
?>

<?php
	get_header();
	$url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
?>

<div class="content-wrapper">
	<div class="content">
		<h2 class="page-title"><?php the_title(); ?></h2>
	</div>
</div>

<div class="content-wrapper l-default">
	<div class="content">

		<div class="section">
			<div class="article">
				<h3><?php the_field('top_section_heading'); ?></h3>
				<?php the_field('top_section_content'); ?>
			</div>
			<img src="<?php the_field('top_section_image'); ?>" class="post-image" />
		</div>
<?php if( get_field('bottom_section_content') ): ?>		
		<div class="section">
			<img src="<?php the_field('bottom_section_image'); ?>" class="post-image" />
			<div class="article">
				<h3><?php the_field('bottom_section_heading'); ?></h3>
				<?php the_field('bottom_section_content'); ?>
			</div>
		</div>
<?php endif; ?>
		<div class="section">
			<div class="button-wrapper">
				<a href="/contact-us" class="button button-blue">Request Quote<span class="arrow"></span></a>
				<a href="tel:+18008213700" class="button button-mobile">Click to Call</a>
				<span class="button button-desktop">Or Call 800.821.3700</span>
			</div>
		</div>

	</div>
</div>

<?php get_footer(); ?>