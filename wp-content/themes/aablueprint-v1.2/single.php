<?php get_header(); ?>

<div class="content-wrapper blog-single">
  <div class="content">    
    <div class="column">
      <h2 class="page-title"><?php the_title(); ?></h2>
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
      <div class="entry">
        <div class="entry-meta">
          <p class="entry-author"><?php echo get_avatar( 'email@example.com', 50 ); ?> By <span class="name"><?php the_author(); ?></span></p>
          <p class="entry-date"><?php the_time('F jS, Y') ?></p>
        </div>
        <?php if ( get_post_meta($post->ID, 'cover_photo', true) ) { ?>
        <img src="<?php the_field('cover_photo'); ?>" class="cover-photo" />
        <?php } ?>
        <?php the_content(); ?>
        <div class="blog-sharing">
          <span class="title">Share This:</span>
          <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink(); ?>&t=<?php the_title(); ?>" class="icon-sharing facebook">Facebook</a>
          <a target="_blank" href="http://twitter.com/home?status=<?php the_title(); ?>%20<?php echo get_permalink(); ?>%20via%20@decision_desk" class="icon-sharing twitter">Twitter</a>
          <a target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo get_permalink(); ?>&title=<?php the_title(); ?>&source=<?php echo get_permalink(); ?>" class="icon-sharing linkedin">LinkedIn</a>
          <a target="_blank" href="https://plus.google.com/share?url=<?php echo get_permalink(); ?>" class="icon-sharing google-plus is-last">Google Plus</a>
       </div>
       <div class="navigation"><p><?php posts_nav_link(); ?></p></div>
        <?php echo do_shortcode('[fbcomments title="" width="700" count="off" num="3" countmsg="wonderful comments!"]'); ?>
      </div>
      <?php endwhile; else: ?>
      <p>Sorry, no posts matched your criteria.</p>
      <?php endif; ?>
    </div>
    
    <?php get_sidebar('news'); ?>
    
  </div>
</div>

<?php get_footer(); ?>