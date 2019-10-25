<?php
/*
Template Name: About Page
*/
?>

<?php
	get_header();
	$url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
?>

<div class="content-wrapper">
	<div class="content">
		<?php if ( is_single('why-aa') ) { ?>
		<h2 class="page-title">About AA Blueprint</h2>
		<?php } else { ?>
		<h2 class="page-title"><?php the_title(); ?></h2>
		<?php } ?>
		<ul class="list-items subnav">
		<?php
			// Get the 'About Us' post type
			$args = array('post_type' => 'about_us');
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

<?php if( get_field('top_section') ): ?>
		<div class="section">
			<div class="article">
				<?php the_field('top_section'); ?>
			</div>
			<img src="<?php the_field('top_section_image'); ?>" class="post-image" />
		</div>
<?php endif; ?>
<?php if( get_field('bottom_section') ): ?>
		<div class="section">
			<img src="<?php the_field('bottom_section_image'); ?>" class="post-image" />
			<div class="article">
				<?php the_field('bottom_section'); ?>
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