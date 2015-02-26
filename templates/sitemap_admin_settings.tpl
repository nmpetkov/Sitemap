{ajaxheader modname='Sitemap' filename='sitemap_admin_settings.js' nobehaviour=true noscriptaculous=true effects=true}

{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type='config' size='small'}
    <h3>{gt text='Settings'}</h3>
</div>

<p class="z-informationmsg">{gt text='By default Sitemap override cache settings then the cache is forced to be enable. Why? Because this module makes a lot of sql queries to generate the sitemap, so for better performance the result is cached during 24 hours by default.'}</p>

<form class="z-form" action="{modurl modname='Sitemap' type='admin' func='updatesettings'}" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" name="authid" value="{insert name='generateauthkey' module='Sitemap'}" />
    <fieldset>
        <legend>{gt text='Cache'}</legend>
        <div class="z-formrow">
            <label for="sitemap_cache_choice">{gt text='Override site cache settings'}</label>
            <div id="sitemap_cache_choice">
                <input id="sitemap_cache_on" name="cachestate" type="radio" value="1" {if $smconf.cachest eq 1}checked="checked" {/if}/>
                <label for="sitemap_cache_on">{gt text='Yes'}</label>
                <input id="sitemap_cache_off" name="cachestate" type="radio" value="0" {if $smconf.cachest eq 0}checked="checked" {/if}/>
                <label for="sitemap_cache_off">{gt text='No'}</label>
            </div>
        </div>
        <div id="sitemap_cache_lifetime_wrapper">
            <div class="z-formrow">
                <label for="sitemap_cache_lifetime">{gt text="Lifetime for cached pages"}</label>
                <input id="sitemap_cache_lifetime" name="cachelifetime" type="text" size="12" maxlength="7" value="{$smconf.cachelt}" />
                <em id="sitemap_cache_lifetimeinfo" class="z-formnote z-sub">{gt text='In seconds (e.g. 3600 = 1 hour, 86400 = 24 hours, 604800 = 1 week, 2678400 = 1 month).'}</em>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend>{gt text='Layout'}</legend>
        <div class="z-formrow">
            <label for="sitemap_layout_choice">{gt text='Select your favorite layout'}</label>
            <div id="sitemap_layout_choice">
                <input id="sitemap_layout_default" name="layout" type="radio" value="1" {if $smconf.layout eq 1}checked="checked" {/if}/>
                <label for="sitemap_layout_default">{gt text='Default'}</label>
                <input id="sitemap_layout_slickmap" name="layout" type="radio" value="2" {if $smconf.layout eq 2}checked="checked" {/if}/>
                <label for="sitemap_layout_slickmap">{gt text='SlickMap (graphical representation)'}</label>
            </div>
        </div>
        <div id="sitemap_layout_slickmaplines_wrapper">
            <div class="z-formrow">
                <label for="sitemap_layout_mpl">{gt text="Maximum modules per line"}</label>
                <input id="sitemap_layout_mpl" name="layout_mpl" type="text" size="4" maxlength="2" value="{$smconf.layout_mpl}" />
                <em id="sitemap_layout_mplinfo" class="z-formnote z-sub">{gt text='This value must be between 1 and 10 (default = 5).'}</em>
            </div>
        </div>
    </fieldset>

    <div class="z-buttons z-formbuttons">
        {button src='button_ok.gif' set='icons/small' __alt='Update' __title='Update' __text='Update'}
        <a href="{modurl modname='Sitemap' type='admin' func='view'}">{img modname='core' src='button_cancel.gif' set='icons/small' __alt='Cancel' __title='Cancel'}{gt text='Cancel'}</a>
    </div>
</form>
