{ajaxheader modname='Sitemap' filename='sitemap_admin_order.js' nobehaviour=true noscriptaculous=true effects=true}

{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="gears" size="small"}
    <h3>{gt text='Order to display the content'}</h3>
</div>

<p class="z-informationmsg">
    {gt text='Notice:'}
    {if $smconf.layout neq 2}
    {gt text="You can't order a module before to have attribute it to the left/right column."}
    {/if}
{gt text='Blank field for order will automaticly set order to 99.'}</p>
<form class="z-form" action="{modurl modname='Sitemap' type='admin' func='updateorder'}" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" name="authid" value="{insert name='generateauthkey' module='Sitemap'}" />
    <fieldset>
        {if $smconf.layout eq 2}
        <legend>{gt text='Order per module'}</legend>
        {else}
        <legend>{gt text='Column and order per module'}</legend>
        {/if}
        {foreach key='smitemname' item='smitem' from=$smitems}
        <div class="z-formrow">
            <label for="sitemap_column_{$smitemname}">{$smitem.displayname} ({$smitemname})</label>
            <div id="sitemap_column_{$smitemname}" onLoad="showhide_order('{$smitemname}', 1)">
                {if $smconf.layout eq 1}
                <input id="sitemap_lcol_{$smitemname}" name="column_{$smitemname}" type="radio" value="2" onclick="showhide_order('{$smitemname}', 1)" {if $smitem.column eq 2}checked="checked" {/if}/>
                <label for="sitemap_lcol_{$smitemname}">{gt text='Left'}</label>
                <input id="sitemap_rcol_{$smitemname}" name="column_{$smitemname}" type="radio" value="4" onclick="showhide_order('{$smitemname}', 1)" {if $smitem.column eq 4}checked="checked" {/if}/>
                <label for="sitemap_rcol_{$smitemname}">{gt text='Right'}</label>
                <input id="sitemap_xcol_{$smitemname}" name="column_{$smitemname}" type="radio" value="" onclick="showhide_order('{$smitemname}', 0)" {if not $smitem.column}checked="checked" {/if}/>
                <label for="sitemap_xcol_{$smitemname}">{gt text='Alternatively'}</label>
                <label id="sitemap_ordermodinf_{$smitemname}" for="sitemap_ordermod_{$smitemname}"{if not $smitem.column} style = "display: none;"{/if}>{gt text='Order:'}</label>
                <input id="sitemap_ordermod_{$smitemname}" name="order_{$smitemname}" type="text" size="4" maxlength="2" value="{$smitem.order}" {if not $smitem.column}style = "display: none;" {/if}/>
                {/if}
                {if $smconf.layout eq 2}
                <label for="sitemap_ordermod2_{$smitemname}">{gt text='Order:'}</label>
                <input id="sitemap_ordermod2_{$smitemname}" name="order_{$smitemname}" type="text" size="4" maxlength="2" value="{$smitem.order}" />
                {/if}
            </div>
        </div>
        {/foreach}
    </fieldset>

    <div class="z-buttons z-formbuttons">
        {button src='button_ok.gif' set='icons/small' __alt='Update' __title='Update' __text='Update'}
        <a href="{modurl modname='Sitemap' type='admin' func='view'}">{img modname='core' src='button_cancel.gif' set='icons/small' __alt='Cancel' __title='Cancel'}{gt text='Cancel'}</a>
    </div>
</form>
