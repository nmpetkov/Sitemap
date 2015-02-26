{gt text='Sitemap' assign='templatetitle'}
{gt text='Sitemap of %s' tag1=$modvars.ZConfig.sitename assign='templatedesc'}
{pagesetvar name=title value=$templatetitle}
{setmetatag name='description' value=$templatedesc}
{pageaddvar name="stylesheet" value=$smlayout_css}
{insert name='getstatusmsg'}

<div class="sitemap_view z-clearfix">
    <h2>{$templatetitle}</h2>
    {modulelinks type='user'}
    <div id="sm_left">
        <div class="sm_item">
            <h4><a href="{$homeurl}" title="{gt text='Homepage'}">{gt text='Homepage'}</a></h4>
            <ul class="urlext">
                <li><a href="{$registerurl}" title="{gt text='Register'}">{gt text='Register'}</a></li>
                <li><a href="{$loginurl}" title="{gt text='Log In'}">{gt text='Log In'}</a></li>
            </ul>
        </div>
        {foreach item='smitem' from=$smitems_left}
        <div class="sm_item">
            <h4><a href="{$smitem.url}" title="{$smitem.name}">{$smitem.name}</a></h4>
            {if $smitem.urlext}
            <ul class="sm_urlext">
                {foreach item='urlext' from=$smitem.urlext}
                <li><a href="{$urlext.url}" title="{$urlext.name}">{$urlext.name}</a>
                    {if isset($urlext.sublinks) && $urlext.sublinks}
                    <ul>
                        {foreach item='sublink' from=$urlext.sublinks}
                        <li><a href="{$sublink.url}" title="{$urlext.name}">{$sublink.name}</a></li>
                        {/foreach}
                    </ul>
                    {/if}
                </li>
                {/foreach}
            </ul>
            {/if}
            {if $smitem.contentext}
            <ul class="sm_contentext">
                {foreach item='urlext' from=$smitem.contentext}
                <li><a href="{$urlext.url}" title="{$urlext.name}">{$urlext.name}</a></li>
                {/foreach}
            </ul>
            {/if}
        </div>
        {/foreach}
    </div>
    <div id="sm_right">
        {foreach item='smitem' from=$smitems_right}
        <div class="sm_item">
            <h4><a href="{$smitem.url}" title="{$smitem.name}">{$smitem.name}</a></h4>
            {if $smitem.urlext}
            <ul class="sm_urlext">
                {foreach item='urlext' from=$smitem.urlext}
                <li><a href="{$urlext.url}" title="{$urlext.name}">{$urlext.name}</a>
                    {if isset($urlext.sublinks) && $urlext.sublinks}
                    <ul>
                        {foreach item='sublink' from=$urlext.sublinks}
                        <li><a href="{$sublink.url}" title="{$urlext.name}">{$sublink.name}</a></li>
                        {/foreach}
                    </ul>
                    {/if}
                </li>
                {/foreach}
            </ul>
            {/if}
            {if $smitem.contentext}
            <ul class="sm_contentext">
                {foreach item='urlext' from=$smitem.contentext}
                <li><a href="{$urlext.url}" title="{$urlext.name}">{$urlext.name}</a></li>
                {/foreach}
            </ul>
            {/if}
        </div>
        {/foreach}
    </div>
</div>
