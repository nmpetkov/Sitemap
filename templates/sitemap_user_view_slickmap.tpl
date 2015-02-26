{gt text='Sitemap' assign='templatetitle'}
{gt text='Sitemap of %s' tag1=$modvars.ZConfig.sitename assign='templatedesc'}
{pagesetvar name=title value=$templatetitle}
{setmetatag name='description' value=$templatedesc}
{pageaddvar name="stylesheet" value=$smlayout_css}
{insert name='getstatusmsg'}

<h2>{$templatetitle}</h2>
{modulelinks type='user'}
<div class="sitemap_view_slickmap z-clearfix">
    <ul id="sm_utility">
        <li><a href="{$registerurl}" title="{gt text='Register'}">{gt text='Register'}</a></li>
        <li><a href="{$loginurl}" title="{gt text='Log In'}">{gt text='Log In'}</a></li>
    </ul>
    {foreach name='smitems_lines' item='smitems_line' from=$smitems_lines}
    <ul class="sm_nav sm_col{$smlayout_mpl}">
        {if $smarty.foreach.smitems_lines.first}
        <li id="sm_home"><a href="{$homeurl}" title="{gt text='Homepage'}">{gt text='Homepage'}</a></li>
        {/if}
        {foreach name='smitems_line' item='smitem' from=$smitems_line}
        {if $smarty.foreach.smitems_lines.first && $smarty.foreach.smitems_line.first}
        <li id="sm_afterhome"><a href="{$smitem.url}" title="{$smitem.name}">{$smitem.name}</a>
        {elseif $smarty.foreach.smitems_lines.last && $smarty.foreach.smitems_line.last}
        <li id="sm_lastli"><a href="{$smitem.url}" title="{$smitem.name}">{$smitem.name}</a>
        {else}
        <li><a href="{$smitem.url}" title="{$smitem.name}">{$smitem.name}</a>
        {/if}
            {if $smitem.urlext or $smitem.contentext}
            <ul>
            {/if}
                {if $smitem.urlext}
                {foreach item='urlext' from=$smitem.urlext}
                <li class="sm_urlext"><a href="{$urlext.url}" title="{$urlext.name}">{$urlext.name}</a>
                    {if isset($urlext.sublinks) && $urlext.sublinks}
                    <ul>
                        {foreach item='sublink' from=$urlext.sublinks}
                        <li><a href="{$sublink.url}" title="{$urlext.name}">{$sublink.name}</a></li>
                        {/foreach}
                    </ul>
                    {/if}
                </li>
                {/foreach}
                {/if}

                {if $smitem.contentext}
                <li class="sm_contentext"><a href="{$smitem.url}" title="{gt text='Direct links'}">{gt text='Direct links'}</a>
                    <ul>
                        {foreach item='urlext' from=$smitem.contentext}
                        <li><a href="{$urlext.url}" title="{$urlext.name}">{$urlext.name}</a></li>
                        {/foreach}
                    </ul>
                </li>
                {/if}
            {if $smitem.urlext or $smitem.contentext}
            </ul>
            {/if}
        </li>
        {/foreach}
    </ul>
    {/foreach}
</div>

