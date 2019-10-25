<?php
/*
Template Name: Print Services
*/
?>

<?php
	get_header();
	$postid = get_the_ID();
?>

<div class="content-wrapper">
	<div class="content">
		<h2 class="page-title">Industry Leading Print Services</h2>
		
		<ul class="list-items subnav">
		<?php
			// Get the 'Print Services' post type
			$args = array('post_type' => 'print_services');
			$loop = new WP_Query($args);
		
			while($loop->have_posts()): $loop->the_post();
			$pageid = $post->ID;
		?>
			<li class="item"><a href="<?php echo get_permalink($pageid); ?>" <?php if ( $pageid == $postid ) { ?>class="active"<?php } ?>><?php the_title(); ?></a></li>
		<?php
			endwhile;
			wp_reset_query();
		?>
		</ul>
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
		<div class="section">
			<img src="<?php the_field('bottom_section_image'); ?>" class="post-image" />
			<div class="article">
				<h3><?php the_field('bottom_section_heading'); ?></h3>
				<?php the_field('bottom_section_content'); ?>
				<div class="button-wrapper">
					<a href="/contact-us" class="button button-blue">Contact Us</a>
					<a href="tel:+18008213700" class="button button-mobile">Click to Call</a>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="content-wrapper l-default example-work">
	<div class="content">
		<?php echo do_shortcode('[supercarousel id=104]'); ?>
	</div>
</div>

<div class="content-wrapper l-default testimonies">
	<div class="content">
		<h3>Customer Testimonials</h3>
		<ul class="list-items">
		<?php
			// Get the 'Testimonial' post type
			$args = array('post_type' => 'testimonial');
			$loop = new WP_Query($args);
		
			while($loop->have_posts()): $loop->the_post();
			$pageid = $post->ID;
		?>
			<li class="item"><p><?php the_field('quote'); ?></p><?php the_title(); ?></li>
		<?php
			endwhile;
			wp_reset_query();
		?>
		</ul>
	</div>
</div>


<?php get_footer(); ?>