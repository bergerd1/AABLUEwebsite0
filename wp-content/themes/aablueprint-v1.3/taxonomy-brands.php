<?php
/*
Template Name: Equipment Sales Brands
*/
get_header();
$slug_id = get_queried_object()->term_id;
?>

<div class="content-wrapper">
	<div class="content">
		<h2 class="page-title">State of the Art Equipment Sales</h2>
		
		<ul class="list-items subnav">
		<?php 
		
			$terms = get_terms('brands');
			$count = count($terms);
			if ( $count > 0 ){
				foreach ( $terms as $term ) {
				$term_id = $term->term_id;
		?>
			<li class="item"><a href="/brands/<?php echo $term->slug ?>"<?php if ( $slug_id == $term_id ) { ?> class="active"<?php } ?>><?php echo $term->name ?></a></li>
		<?php
				}
			}
			
		?>
		</ul>
	</div>
</div>

<div class="content-wrapper l-default">
	<div class="content">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<div class="section">
			<div class="article">
				<h3 class="field-title"><?php the_title(); ?></h3>
				<h4 class="field-subtitle"><?php the_field('subtitle'); ?></h4>
				<?php the_field('content'); ?>
				<?php if ( get_post_meta($post->ID, 'brochure_url', true) ) { ?>
				<a href="<?php the_field('brochure_url'); ?>" class="field-brochure" target="_blank">View/Download Brochure</a>
				<?php } ?>
				<div class="button-wrapper">
					<a href="/contact-us" class="button button-blue">Request Quote<span class="arrow"></span></a>
					<a href="tel:+18008213700" class="button button-mobile">Click to Call</a>
					<span class="button button-desktop">Or Call <a href="tel:800.821.3700">800.821.3700</a></span>					
				</div>
			</div>
			<img src="<?php the_field('image'); ?>" class="post-image" />
		</div>
		<?php endwhile; else: ?>
		    <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
		<?php endif; ?>
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
		<div class="button-wrapper">
			<a href="/contact-us" class="button button-blue">Contact Us</a>
			<a href="tel:+18008213700" class="button">Click to Call</a>
		</div>
	</div>
</div>


<?php get_footer(); ?>