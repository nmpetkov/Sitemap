{ajaxheader modname='Sitemap' filename='sitemap_admin_display.js' nobehaviour=true noscriptaculous=true effects=true}

{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="cubes" size="small"}
    <h3>{gt text='Content to display'}</h3>
</div>

<p class="z-informationmsg">{gt text='Notice: You have to set for each module if you want to display it into the sitemap and if you want to display links to the sections/content of each module. You can also set some xml information.'}</p>
<form class="z-form" action="{modurl modname='Sitemap' type='admin' func='updatedisplay'}" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" name="authid" value="{insert name='generateauthkey' module='Sitemap'}" />
    {foreach key='smitemname' item='smitem' from=$smitems}
    <fieldset>
        <legend>&nbsp;<strong>{$smitem.displayname} ({$smitemname})</strong>&nbsp;</legend>
        <div class="z-formrow">
            <label for="sitemap_displaymod_choice_{$smitemname}">{gt text='Display this module'}</label>
            <span id="sitemap_displaymod_choice_{$smitemname}">
                <input id="sitemap_displaymod_on_{$smitemname}" name="displaymod_{$smitemname}" type="radio" value="1" onclick="showhide_displaymodext('{$smitemname}', 1)" {if $smitem.displaymod eq 1}checked="checked" {/if}/>
                <label for="sitemap_displaymod_on_{$smitemname}">{gt text='Yes'}</label>
                <input id="sitemap_displaymod_off_{$smitemname}" name="displaymod_{$smitemname}" type="radio" value="0" onclick="showhide_displaymodext('{$smitemname}', 0)" {if not $smitem.displaymod}checked="checked" {/if}/>
                <label for="sitemap_displaymod_off_{$smitemname}">{gt text='No'}</label>
            </span>
        </div>
        <div id='sitemap_displaymodext_{$smitemname}'{if not $smitem.displaymod} style="display: none;"{/if}>{* begin hidden *}
        {foreach from=$languages key='code' item='language'}
            <div class="z-formrow">
                <label for="sitemap_sitemapname_{$code}_{$smitemname}">{gt text='Sitemap name'} ({$language})</label>
                <input id="sitemap_sitemapname_{$code}_{$smitemname}" name="sitemapname_{$code}_{$smitemname}" type="text" value="{if isset($smitem.sitemapname.$code)}{$smitem.sitemapname.$code}{/if}" size="40" maxlength="255" />
            </div>
        {/foreach}
        {if $smitem.urlext neq 4}
        <div class="z-formrow">
            <label for="sitemap_displayurl_{$smitemname}">{gt text='Display links to the sections'}</label>
            <input id="sitemap_displayurl_{$smitemname}" name="displayurl_{$smitemname}" type="checkbox" value="1" {if $smitem.urlext eq 1}checked="checked" {/if}/>
        </div>
        {/if}

        {if $smitem.contentext neq 4}
        <div id="sitemap_displaycont_wrapper_{$smitemname}">
            <div class="z-formrow">
                <label for="sitemap_displaycont_{$smitemname}">{gt text='Display links to the content'}</label>
                <input id="sitemap_displaycont_{$smitemname}" name="displaycont_{$smitemname}" type="checkbox" value="1" {if $smitem.contentext eq 1}checked="checked" {/if}/>
            </div>
            <div class="z-formrow">
                <label for="sitemap_contextmax_{$smitemname}">{gt text='Maximum links to content for user map'}</label>
                <input id="sitemap_contextmax_{$smitemname}" name="contextmax_{$smitemname}" type="text" size="6" maxlength="3" value="{$smitem.contextmax}" />
                <em class="z-sub z-formnote">{gt text='Blank = no limit.'}</em>
            </div>
        </div>
        {/if}

        <fieldset>
            <legend>{gt text='Xml sitemap'}</legend>
            <div class="z-formrow">
                <label for="sitemap_displaymodxml_choice_{$smitemname}">{gt text='Display in XML map'}</label>
                <span id="sitemap_displaymodxml_choice_{$smitemname}">
                    <input id="sitemap_displaymodxml_on_{$smitemname}" name="displaymodxml_{$smitemname}" type="radio" value="1" onclick="showhide_displaymodxml('{$smitemname}', 1)" {if $smitem.displaymodxml eq 1}checked="checked" {/if}/>
                    <label for="sitemap_displaymodxml_on_{$smitemname}">{gt text='Yes'}</label>
                    <input id="sitemap_displaymodxml_off_{$smitemname}" name="displaymodxml_{$smitemname}" type="radio" value="0" onclick="showhide_displaymodxml('{$smitemname}', 0)" {if not $smitem.displaymodxml}checked="checked" {/if}/>
                    <label for="sitemap_displaymodxml_off_{$smitemname}">{gt text='No'}</label>
                </span>
            </div>
            <div id='sitemap_displaymodxml_{$smitemname}'{if not $smitem.displaymodxml} style="display: none;"{/if}>{* begin hidden 1 *}
            <div class="z-formrow">
                <label for="sitemap_contextmaxxml_{$smitemname}">{gt text='Maximum links to content for XML map'}</label>
                <input id="sitemap_contextmaxxml_{$smitemname}" name="contextmaxxml_{$smitemname}" type="text" size="6" maxlength="3" value="{$smitem.contextmaxxml}" />
                <em class="z-sub z-formnote">{gt text='(Blank = no limit).'}</em>
            </div>
            <div class="z-formrow">
                <label for="sitemap_changefreq_{$smitemname}">{gt text='Set change frequency for this module'}</label>
                <select id="sitemap_changefreq_{$smitemname}" name="changefreq_{$smitemname}">
                    <option value="">{gt text="Don't specify change frequency"}</option>
                    <option value="1"{if $smitem.changefreq eq 1} selected="selected"{/if}>{gt text='Always'}</option>
                    <option value="2"{if $smitem.changefreq eq 2} selected="selected"{/if}>{gt text='Hourly'}</option>
                    <option value="3"{if $smitem.changefreq eq 3} selected="selected"{/if}>{gt text='Daily'}</option>
                    <option value="4"{if $smitem.changefreq eq 4} selected="selected"{/if}>{gt text='Weekly'}</option>
                    <option value="5"{if $smitem.changefreq eq 5} selected="selected"{/if}>{gt text='Monthly'}</option>
                    <option value="6"{if $smitem.changefreq eq 6} selected="selected"{/if}>{gt text='Yearly'}</option>
                    <option value="7"{if $smitem.changefreq eq 7} selected="selected"{/if}>{gt text='Never'}</option>
                </select>
            </div>
            <div class="z-formrow">
                <label for="sitemap_priority_{$smitemname}">{gt text='Set priority for this module'}</label>
                <select id="sitemap_priority_{$smitemname}" name="priority_{$smitemname}">
                    <option value="">{gt text="Don't specify priority"}</option>
                    <option value="1"{if $smitem.priority eq 1} selected="selected"{/if}>0.0</option>
                    <option value="2"{if $smitem.priority eq 2} selected="selected"{/if}>0.1</option>
                    <option value="3"{if $smitem.priority eq 3} selected="selected"{/if}>0.2</option>
                    <option value="4"{if $smitem.priority eq 4} selected="selected"{/if}>0.3</option>
                    <option value="5"{if $smitem.priority eq 5} selected="selected"{/if}>0.4</option>
                    <option value="6"{if $smitem.priority eq 6} selected="selected"{/if}>0.5</option>
                    <option value="7"{if $smitem.priority eq 7} selected="selected"{/if}>0.6</option>
                    <option value="8"{if $smitem.priority eq 8} selected="selected"{/if}>0.7</option>
                    <option value="9"{if $smitem.priority eq 9} selected="selected"{/if}>0.8</option>
                    <option value="10"{if $smitem.priority eq 10} selected="selected"{/if}>0.9</option>
                    <option value="11"{if $smitem.priority eq 11} selected="selected"{/if}>1.0</option>
                </select>
            </div>
            </div>{* end hidden 1 *}
        </fieldset>
        </div>{* end hidden *}
    </fieldset>

    <a href="#bottom" style="float: right;">{img modname='core' src='agt_update_misc.gif' set='icons/small' __alt='Go to bottom' __title='Go to bottom'}</a>
    <br />
    {/foreach}

    <div class="z-buttons z-formbuttons">
        {button src='button_ok.gif' set='icons/small' __alt='Update' __title='Update' __text='Update'}
        <a href="{modurl modname='Sitemap' type='admin' func='view'}">{img modname='core' src='button_cancel.gif' set='icons/small' __alt='Cancel' __title='Cancel'}{gt text='Cancel'}</a>
    </div>
</form>
<a id="bottom" accesskey="b"></a>
