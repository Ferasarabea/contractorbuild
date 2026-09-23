<?php get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
<header class="page-hero"><div class="container"><?php cb_breadcrumbs(); ?><p class="eyebrow">Verified client story</p><h1><?php the_title(); ?></h1><p class="lede"><?php echo esc_html( cb_page_intro() ); ?></p></div></header>
<section class="section"><div class="container narrow entry-content"><?php the_content(); ?></div></section>
<?php endwhile; get_footer(); ?>
