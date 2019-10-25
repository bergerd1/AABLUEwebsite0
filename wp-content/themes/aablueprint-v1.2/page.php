<?php
	get_header();
	$url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
?>

<h2 class="page-title" style="background: url('<?php echo $url; ?>') 0 0 no-repeat; -moz-background-size: cover; -webkit-background-size: cover; background-size: cover; "><?php the_title(); ?></h2>

<div class="content-wrapper l-default">
	<div class="content">		
		<?php if (have_posts()) : while (have_posts()) : the_post();?>
		<?php the_content(); ?>
		<?php endwhile; endif; ?>
	</div>
</div>

<?php get_footer(); ?>