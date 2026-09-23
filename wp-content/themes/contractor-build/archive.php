<?php get_header(); ?>
<header class="page-hero"><div class="container"><?php cb_breadcrumbs(); ?><p class="eyebrow">Explore</p><h1><?php the_archive_title(); ?></h1><?php the_archive_description( '<div class="lede">', '</div>' ); ?></div></header>
<section class="section"><div class="container archive-grid">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?><a class="archive-card" href="<?php the_permalink(); ?>"><span class="card-number"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></span><h3><?php the_title(); ?></h3><p><?php echo esc_html( get_the_excerpt() ); ?></p><span class="card-link">Learn more →</span></a><?php endwhile; else : ?><p>No content has been published yet.</p><?php endif; ?>
</div></section>
<?php get_footer(); ?>
