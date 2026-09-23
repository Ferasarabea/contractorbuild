<?php get_header(); ?>
<section class="hero">
	<div class="container hero-grid">
		<div>
			<p class="eyebrow">Everything contractors need to grow</p>
			<h1>Build your business. <span>Book more jobs.</span></h1>
			<p class="lede">We help contractors generate more calls, qualified leads, and booked opportunities through high-converting websites, Google visibility, paid media, and connected growth systems.</p>
			<div class="button-row"><a class="btn" href="<?php echo esc_url( home_url( '/free-marketing-audit/' ) ); ?>">Get my free growth plan</a><a class="btn btn--outline" href="#system">See how it works</a></div>
			<div class="hero-proof"><span>Built for contractors</span><span>Tracking from click to job</span><span>No inflated promises</span></div>
		</div>
		<div class="growth-panel" aria-label="Customer acquisition system illustration">
			<div class="metric"><span class="metric-icon">G</span><span><strong>Google visibility</strong><small>Search · Maps · Local</small></span><span class="metric-status">Attract</span></div>
			<div class="flow-arrow">↓</div>
			<div class="metric"><span class="metric-icon">W</span><span><strong>Conversion-ready website</strong><small>Calls · Forms · Estimates</small></span><span class="metric-status">Convert</span></div>
			<div class="flow-arrow">↓</div>
			<div class="metric"><span class="metric-icon">CRM</span><span><strong>Lead follow-up</strong><small>Routing · SMS · Pipeline</small></span><span class="metric-status">Book</span></div>
			<div class="flow-arrow">↓</div>
			<div class="metric"><span class="metric-icon">↗</span><span><strong>Measurable growth</strong><small>Jobs · Revenue · Reviews</small></span><span class="metric-status">Improve</span></div>
		</div>
	</div>
</section>
<div class="trust-bar"><ul class="container trust-items"><li>Built for contractors</li><li>Data-driven marketing</li><li>Transparent reporting</li><li>Conversion-focused</li><li>Full-service growth</li></ul></div>

<section class="section" id="services">
	<div class="container">
		<div class="section-head"><div><p class="eyebrow">One growth team</p><h2>Everything you need to grow.</h2></div><p>Stop piecing together disconnected vendors. We build the strategy, assets, campaigns, tracking, and follow-up around one goal: creating more qualified opportunities for your crews.</p></div>
		<div class="grid grid--4">
		<?php
		$services = array(
			array( '01', 'Contractor websites', 'Fast WordPress websites designed to turn local traffic into calls and estimate requests.', '/services/contractor-websites/' ),
			array( '02', 'Contractor SEO', 'A practical search strategy built around the services and markets that matter to your business.', '/services/contractor-seo/' ),
			array( '03', 'Local SEO', 'Improve local relevance across your website, Maps presence, citations, and service areas.', '/services/local-seo/' ),
			array( '04', 'Google Ads', 'Search campaigns, landing pages, and conversion tracking focused on lead quality—not vanity clicks.', '/services/google-ads/' ),
			array( '05', 'Local Services Ads', 'Setup, service areas, budgets, lead workflows, and ongoing performance monitoring.', '/services/local-services-ads/' ),
			array( '06', 'Social media ads', 'Create local demand with Meta campaigns, offers, retargeting, and useful creative.', '/services/social-media-advertising/' ),
			array( '07', 'Google Business Profile', 'Policy-safe profile, service, category, photo, post, and review strategy.', '/services/google-business-profile/' ),
			array( '08', 'Lead generation', 'Campaigns and landing pages engineered around qualified calls and forms.', '/services/lead-generation/' ),
			array( '09', 'Reputation management', 'Build consistent, legitimate review-request and customer-feedback workflows.', '/services/reputation-management/' ),
			array( '10', 'Marketing automation', 'Connect leads to fast SMS, email, routing, reminders, and pipeline visibility.', '/services/marketing-automation/' ),
			array( '11', 'Analytics & tracking', 'Know which channels create calls, forms, appointments, and sales opportunities.', '/services/analytics-tracking/' ),
			array( '12', 'Branding & content', 'A credible identity, useful content, and campaign creative that earn attention.', '/services/branding/' ),
		);
		foreach ( $services as $service ) : ?>
			<article class="card"><span class="card-number"><?php echo esc_html( $service[0] ); ?></span><h3><?php echo esc_html( $service[1] ); ?></h3><p><?php echo esc_html( $service[2] ); ?></p><a class="card-link" href="<?php echo esc_url( home_url( $service[3] ) ); ?>">Explore service →</a></article>
		<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section section--dark" id="system">
	<div class="container system-grid">
		<div><p class="eyebrow">One connected system</p><h2>Your entire marketing operation under one roof.</h2><p class="muted">One team owns the journey from first impression to follow-up. That means your website, SEO, ads, landing pages, tracking, and CRM stop working against each other.</p><a class="btn" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">Meet your growth team</a></div>
		<div><p>Instead of five vendors and five versions of the truth, build one measurable acquisition system.</p><div class="pipeline"><span>Traffic</span><i>→</i><span>Website</span><i>→</i><span>Lead</span><i>→</i><span>CRM</span><i>→</i><span>Booked job</span><i>→</i><span>Revenue</span></div></div>
	</div>
</section>

<section class="section">
	<div class="container split">
		<div class="visual-card"><p class="eyebrow">Conversion-ready</p><h2>Built for the way customers hire.</h2><div class="search-box">Roof repair near me <strong style="float:right">⌕</strong></div><div class="search-results"><small>contractor-build.com/service-area</small><strong style="display:block">A clear answer. A trusted contractor. An easy next step.</strong></div></div>
		<div><p class="eyebrow">Contractor websites</p><h2>A website built to generate calls.</h2><p>Your website should do more than look good. We plan the page structure, message, search foundation, mobile experience, and conversion paths around how homeowners actually choose a contractor.</p><ul class="feature-list"><li>Custom WordPress development</li><li>Mobile-first UX</li><li>Service and location pages</li><li>Technical SEO foundation</li><li>Call and form tracking</li><li>CRM integrations</li><li>Speed and image optimization</li><li>Accessible, secure code</li></ul><a class="btn btn--dark" href="<?php echo esc_url( home_url( '/services/contractor-websites/' ) ); ?>">Build my website</a></div>
	</div>
</section>

<section class="section section--mist">
	<div class="container split">
		<div><p class="eyebrow">Search visibility</p><h2>Get found when customers need you.</h2><p>We connect technical SEO, useful service content, local relevance, internal links, and a credible Google Business Profile into one long-term visibility strategy.</p><ul class="feature-list"><li>Contractor and local SEO</li><li>Keyword and competitor research</li><li>Google Maps visibility</li><li>Service-area strategy</li><li>On-page optimization</li><li>Search Console reporting</li></ul><a class="btn btn--dark" href="<?php echo esc_url( home_url( '/services/contractor-seo/' ) ); ?>">Improve my visibility</a></div>
		<div class="card"><span class="card-number">SEARCH JOURNEY</span><h3>Turn high-intent searches into real opportunities.</h3><div class="funnel"><span>Google search</span><i>→</i><span>Your service page</span><i>→</i><span>Call or form</span><i>→</i><span>Booked estimate</span></div><p>Every step is measured so strategy is based on performance—not guesswork.</p></div>
	</div>
</section>

<section class="section section--dark">
	<div class="container">
		<div class="section-head"><div><p class="eyebrow">Paid acquisition</p><h2>Ads focused on leads—not clicks.</h2></div><p>We plan search and social campaigns around geography, intent, offers, conversion paths, and lead quality. No guaranteed outcomes. Just disciplined testing, transparent measurement, and ongoing optimization.</p></div>
		<div class="grid grid--3"><article class="card"><span class="card-number">GOOGLE</span><h3>Capture existing demand</h3><p>Search campaigns, negative keywords, geo targeting, call tracking, landing pages, remarketing, and budget optimization.</p><a class="card-link" href="<?php echo esc_url( home_url( '/services/google-ads/' ) ); ?>">Launch Google Ads →</a></article><article class="card"><span class="card-number">META</span><h3>Create local demand</h3><p>Facebook and Instagram offers, local audience targeting, lead forms, useful creative, and retargeting.</p><a class="card-link" href="<?php echo esc_url( home_url( '/services/social-media-advertising/' ) ); ?>">Explore social ads →</a></article><article class="card"><span class="card-number">LOCAL</span><h3>Own your local presence</h3><p>Legitimate profile optimization, local pages, review strategy, category alignment, citations, and content.</p><a class="card-link" href="<?php echo esc_url( home_url( '/services/google-business-profile/' ) ); ?>">Improve Maps visibility →</a></article></div>
	</div>
</section>

<section class="section">
	<div class="container"><div class="section-head"><div><p class="eyebrow">From click to customer</p><h2>We build beyond the lead.</h2></div><p>Marketing does not stop when a form is submitted. Fast follow-up, clear routing, pipeline tracking, and review requests help your team make more of every opportunity.</p></div><div class="funnel"><span>Google / Meta / Maps</span><i>→</i><span>Website</span><i>→</i><span>Call / form</span><i>→</i><span>CRM</span><i>→</i><span>Follow-up</span><i>→</i><span>Booked job</span><i>→</i><span>Review / referral</span></div></div>
</section>

<section class="section section--mist">
	<div class="container"><div class="section-head"><div><p class="eyebrow">A practical process</p><h2>Clear steps. Shared numbers.</h2></div></div><div class="grid grid--5" style="grid-template-columns:repeat(5,1fr)"><?php foreach ( array( 'Audit' => 'We analyze the market, visibility, website, campaigns, and lead flow.', 'Build' => 'We create the assets, tracking, and infrastructure the strategy needs.', 'Launch' => 'Campaigns go live with deliberate targeting and conversion paths.', 'Optimize' => 'We study calls, forms, quality, cost, and conversion performance.', 'Scale' => 'Investment moves toward the channels creating measurable opportunity.' ) as $number => $copy ) : static $i = 0; $i++; ?><article class="step"><strong>0<?php echo esc_html( $i ); ?></strong><h3><?php echo esc_html( $number ); ?></h3><p><?php echo esc_html( $copy ); ?></p></article><?php endforeach; ?></div></div>
</section>

<section class="section">
	<div class="container split"><div><p class="eyebrow">Built for local service businesses</p><h2>Strategy shaped around your trade.</h2><p>Roofers do not sell like plumbers. Emergency services do not generate demand like remodeling. Our industry pages and campaigns are built around different sales cycles, search behavior, job values, service areas, and customer concerns.</p><a class="btn btn--dark" href="<?php echo esc_url( home_url( '/industries/' ) ); ?>">View industries served</a></div><div class="grid grid--3"><?php foreach ( array( 'Roofing', 'HVAC', 'Plumbing', 'Electrical', 'Remodeling', 'Landscaping', 'Concrete', 'Restoration', 'Garage Door' ) as $industry ) : ?><div class="card"><h3><?php echo esc_html( $industry ); ?></h3></div><?php endforeach; ?></div></div>
</section>
<?php get_footer(); ?>
