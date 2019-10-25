
<div class="content-wrapper footer">
  <div class="content">
  	<ul class="menu">
    	<li class="menu-item drop-down js-submenu">
    		<a href="javascript:void(0);">Equipment Sales</a>
    		<ul class="sub-menu">
			<?php 
				$terms = get_terms('brands');
				$count = count($terms);
				if ( $count > 0 ){
					foreach ( $terms as $term ) {			
			?>
				<li class="menu-item"><a href="/brands/<?php echo $term->slug ?>"><?php echo $term->name ?></a></li>
			<?php
					}
				}
				
			?>
    		</ul>
    	</li>
    	<li class="menu-item drop-down js-submenu">
    		<a href="javascript:void(0);">Print Services</a>
    		<ul class="sub-menu">
			<?php
				// Get the 'Print Services' post type
				$args = array('post_type' => 'print_services');
				$loop = new WP_Query($args);
			
				while($loop->have_posts()): $loop->the_post();
				$pageid = $post->ID;
			?>
				<li class="menu-item"><a href="<?php echo get_permalink($pageid); ?>"><?php the_title(); ?></a></li>
			<?php
				endwhile;
				wp_reset_query();
			?>
    		</ul>
    	</li>
	    <?php wp_nav_menu( array('menu' => 'main', 'container' => '', 'items_wrap' => '%3$s') ) ?>
  	</ul>
  	<ul class="list-items list-social">
	  	<li class="item"><img src="<?php bloginfo('template_url'); ?>/img/logo_repromax.jpg" /></li>
	  	<li class="item"><img src="<?php bloginfo('template_url'); ?>/img/logo_epson.jpg" /></li>
	  	<li class="item"><img src="<?php bloginfo('template_url'); ?>/img/logo_canon.jpg" /></li>
	  	<li class="item"><img src="<?php bloginfo('template_url'); ?>/img/logo_hp.jpg" /></li>
	  	<li class="item"><img src="<?php bloginfo('template_url'); ?>/img/logo_edge.jpg" /></li>
	  	<li class="item"><a href="https://www.facebook.com/pages/AA-Blueprint/220203271339571" target="_blank"><img src="<?php bloginfo('template_url'); ?>/img/icon_facebook.jpg" /></a></li>
  	</ul>
    <p class="muted">
      <span class="line line-1">AA Blueprint Company&reg;,</span>
      <span class="line line-2">2757 Gilchrist Road,</span>
      <span class="line line-3">Akron, Ohio 44305.</span>
      <span class="line line-4"><a href="tel:330-794-8803">(330) 794-8803</a>.</span>
      <span class="line line-5">All Rights Reserved 2014</span>
    </p>
  </div>
</div>

<?php wp_footer(); ?>

<script type="text/javascript">

  //////////////////////////////////////////////////////////////////////////////////
  // Mobile Menu
  //////////////////////////////////////////////////////////////////////////////////
  
  jQuery('.js-menu').on('click', function() {
    jQuery('#menu-main').toggleClass('is-active');
  });


  //////////////////////////////////////////////////////////////////////////////////
  // Mobile Menu
  //////////////////////////////////////////////////////////////////////////////////

 jQuery('.js-submenu').on('click', function() {
 	jQuery(this).toggleClass('is-active');
 });

 jQuery('.menu a[href="#"]').on('click', function() {
	javascript:void(0);
 	jQuery(this).parent('li').toggleClass('is-active');
 });


</script>

</body>
</html>