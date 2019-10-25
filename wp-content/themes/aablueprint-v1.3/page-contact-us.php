<?php
/*
Template Name: Contact Us
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

<div class="container">

	<div class="content-wrapper l-default">
		<div class="content">
			<?php echo do_shortcode( '[contact-form-7 id="47" title="Contact Us Form"]' ); ?>
		</div>
	</div>
	
	<div class="content-wrapper contact-boxes">
		<div class="content">
			<div class="contact-box contact-one">
				<h3>By Phone</h3>
				<img src="<?php bloginfo( 'template_url' ); ?>/img/icon-phone.jpg" class="icon" />
				<p>
					Toll Free: <a href="tel:<?php the_field('toll_free_number'); ?>"><?php the_field('toll_free_number'); ?></a><br />
					Local: <a href="tel:<?php the_field('local_number'); ?>"><?php the_field('local_number'); ?></a><br />
					Fax: <?php the_field('fax_number'); ?>
				</p>
			</div>
			<div class="contact-box contact-two">
				<h3>By Visiting</h3>
				<img src="<?php bloginfo( 'template_url' ); ?>/img/icon-house.jpg" class="icon" />
				<p>
					<?php the_field('address'); ?>
				</p>
				<p class="p-map"><a href="<?php the_field('map_url'); ?>" target="_blank" >Map it</a></p>
			</div>
		</div>
	</div>

</div>

<?php get_footer(); ?>