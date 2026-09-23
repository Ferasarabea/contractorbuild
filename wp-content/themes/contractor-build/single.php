<?php get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
<header class="page-hero"><div class="container narrow"><?php cb_breadcrumbs(); ?><p class="eyebrow">Contractor growth resource</p><h1><?php the_title(); ?></h1><p class="post-meta"><?php echo esc_html( get_the_date() ); ?> · <?php echo esc_html( get_the_author() ); ?></p></div></header>
<section class="section"><article class="container narrow entry-content"><?php the_content(); ?></article></section>
<?php endwhile; get_footer(); ?>
