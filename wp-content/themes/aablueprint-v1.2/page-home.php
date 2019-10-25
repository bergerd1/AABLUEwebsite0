<?php
/*
Template Name: Home
*/
?>

<?php get_header(); ?>

<div class="content-wrapper slider">
	<div class="content">
		<?php nivo_slider('home'); ?>
	</div>
</div>

<div class="content-wrapper feature-boxes">
	<div class="content">
		<div class="feature-box feature-one">
			<span class="photo"></span>
			<h2 class="title">State of the Art Equipment Sales</h2>
<?php
$term = get_field('equipment_sales_link');
if( $term ): ?>
	<a class="button button-blue" href="/brands/<?php echo $term->slug; ?>">Show Me<span class="arrow"></span></a> 
<?php endif; ?>
		</div>
		<div class="feature-box feature-two">
			<span class="photo"></span>
			<h2 class="title">Industry Leading Print Services</h2>
			<a class="button button-blue" href="<?php the_field('print_services_link'); ?>">Show Me<span class="arrow"></span></a>
		</div>
	</div>
</div>

<div class="content-wrapper way-finding">
	<div class="content">
		<a href="/about_us/why-aa/" class="way-box way-one">
			<h3 class="title">What is the AA Blueprint Guarantee?</h3>
			<span class="arrow"></span>
		</a>
		<a href="/supplies-repair" class="way-box way-two">
			<h3 class="title">Need Service or Have a Repair Call?</h3>
			<span class="arrow"></span>
		</a>
		<a href="http://www.aaplanroom.com/" class="way-box way-three">
			<h3 class="title">Visit Our Custom Plan Room &amp; Upload Center!</h3>
			<span class="arrow"></span>
		</a>
	</div>
</div>

<?php get_footer(); ?>