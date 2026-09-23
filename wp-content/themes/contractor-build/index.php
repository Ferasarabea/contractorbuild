<?php get_header(); ?>
<header class="page-hero"><div class="container"><p class="eyebrow">Insights</p><h1><?php bloginfo( 'name' ); ?> Resources</h1><p class="lede">Straightforward guidance on websites, local visibility, lead generation, paid media, and contractor growth.</p></div></header>
<section class="section"><div class="container archive-grid"><?php while ( have_posts() ) : the_post(); ?><a class="archive-card" href="<?php the_permalink(); ?>"><span class="post-meta"><?php echo esc_html( get_the_date() ); ?></span><h3><?php the_title(); ?></h3><p><?php echo esc_html( get_the_excerpt() ); ?></p><span class="card-link">Read article →</span></a><?php endwhile; ?></div></section>
<?php get_footer(); ?>
