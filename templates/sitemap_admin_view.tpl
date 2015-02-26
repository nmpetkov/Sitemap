{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="home" size="small"}
    <h3>{gt text='Sitemap overview'}</h3>
</div>

    <p>{gt text='Welcome into the administration of the sitemap module.'}</p>

<form class="z-form">
    <fieldset>
        <legend>&nbsp;<strong>{gt text='Links to your xml sitemap'}</strong>&nbsp;</legend>
        <ul>
            {foreach item='smurl' from=$smurls}
            <li><a href="{$smurl.short}">{$smurl.full}</a></li>
            {/foreach}
        </ul>
    </fieldset>
    <fieldset>
        <legend>&nbsp;<strong>{gt text='Links to your xml sitemap per module'}</strong>&nbsp;</legend>
        {foreach key='smmodname' item='smmodinfos' from=$smmodurls}
        <p>{$smmodname}</p>
        <ul>
            {foreach item='smmodinfo' from=$smmodinfos}
            <li>{$smmodinfo.lgname} -- <a href="{$smmodinfo.short}">{$smmodinfo.full}</a></li>
            {/foreach}
        </ul>
        {/foreach}
    </fieldset>
    <fieldset>
        <legend>&nbsp;<strong>{gt text='Submit xml sitemap'}</strong>&nbsp;</legend>
        <p>{gt text='If you click on the bottom links, it will submit the xml sitemap in the corresponding language to the search engine.'}</p>
        {foreach key='se_name' item='se_url' from=$searcheng}
        <ul>
            {foreach key='langshort' item='language' from=$languages}
            <li>{$se_name} -- <a href="{$se_url}{$smurls.$langshort.full|urlencode}" target="_blank" title="{$se_name} -- {$language}">{$language}</a></li>
            {/foreach}
        </ul>
        {/foreach}
    </fieldset>
</form>
