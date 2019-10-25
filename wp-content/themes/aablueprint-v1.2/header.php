<!DOCTYPE html>
<!--[if lt IE 7 ]><html lang="en-US" class="no-js ie ie6 lte7 lte8 lte9"><![endif]-->
<!--[if IE 7 ]><html lang="en-US" class="no-js ie ie7 lte7 lte8 lte9"><![endif]-->
<!--[if IE 8 ]><html lang="en-US" class="no-js ie ie8 lte8 lte9"><![endif]-->
<!--[if IE 9 ]><html lang="en-US" class="no-js ie ie9 lte9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en-US" class="no-js"><!--<![endif]-->
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <title><?php bloginfo( 'name' ); ?> <?php wp_title(); ?></title>
  
  <link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/favicon.ico" />
  <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'template_url' ); ?>/style.css" />  
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div class="content-wrapper header">
  <div class="content">
    <h1 class="title"><a href="/" class="logo">AA Blueprint</a></h1>
    <span class="address">2757 Gilchrist Road, Akron, Ohio 44305</span>
    <span class="phone">800.821.3700</span>
    <span class="icon-menu js-menu">Menu</span>
    <ul id="menu-main" class="menu">
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
  </div>
</div>