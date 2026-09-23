# Migration and redirect plan

The user explicitly directed this implementation to skip review of the live site. As a result, **no legacy URL inventory or redirect mapping is fabricated in this repository**. Complete the crawl and mapping before replacing production.

## Required inventory columns

| Legacy URL | Status | Title | Meta description | H1 | Canonical | Index state | Internal links | Backlinks | Organic landing sessions | Replacement URL | Action |
|---|---:|---|---|---|---|---|---:|---:|---:|---|---|

Actions are: retain, improve in place, 301 to a closely matching replacement, 410 when intentionally and permanently removed with no equivalent, or investigate.

## Implementation

Create `wp-content/mu-plugins/contractor-build-redirects.php` in the deployed WordPress instance:

```php
<?php
add_filter( 'cbg_redirect_map', function ( $map ) {
    $map['/legacy-path'] = '/relevant-new-path/';
    return $map;
} );
```

The growth plugin applies mappings only on a 404 and issues a server-side 301. Prefer web-server redirects for high-volume rules where hosting access permits. Test every rule and keep a single hop.
