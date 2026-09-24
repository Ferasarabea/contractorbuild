<?php get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
<header class="page-hero"><div class="container"><?php cb_breadcrumbs(); ?><p class="eyebrow">Contractor Build</p><h1><?php the_title(); ?></h1><?php if ( cb_page_intro() ) : ?><p class="lede"><?php echo esc_html( cb_page_intro() ); ?></p><?php endif; ?></div></header>
<section class="section"><div class="container content-layout"><article class="entry-content"><?php the_content(); ?></article><aside><div class="sidebar-cta"><p class="eyebrow">Free strategy review</p><h3>Find your next growth opportunity.</h3><p>Get a practical review of your website, visibility, campaigns, and follow-up.</p><a class="btn btn--dark" href="<?php echo esc_url( home_url( '/free-marketing-audit/' ) ); ?>">Request an audit</a></div></aside></div></section>
<?php endwhile; ?>
<?php get_footer(); ?>
