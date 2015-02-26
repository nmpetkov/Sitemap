Event.observe(window, 'load', sitemap_settings_init, false);

function sitemap_settings_init()
{
    Event.observe('sitemap_cache_on', 'click', sitemap_cache_onchange, false);
    Event.observe('sitemap_cache_off', 'click', sitemap_cache_onchange, false);
    if ( $('sitemap_cache_off').checked) {
        $('sitemap_cache_lifetime_wrapper').hide();
    }

    Event.observe('sitemap_layout_default', 'click', sitemap_layout_onchange, false);
    Event.observe('sitemap_layout_slickmap', 'click', sitemap_layout_onchange, false);
    if ( $('sitemap_layout_default').checked) {
        $('sitemap_layout_slickmaplines_wrapper').hide();
    }
}

function sitemap_cache_onchange()
{
    radioswitchdisplaystate('sitemap_cache_choice', 'sitemap_cache_lifetime_wrapper', true);
}

function sitemap_layout_onchange()
{
    radioswitchdisplaystate('sitemap_layout_choice', 'sitemap_layout_slickmaplines_wrapper', false);
}

