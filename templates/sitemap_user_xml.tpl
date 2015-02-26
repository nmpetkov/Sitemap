{nocache}{php}header("Content-type: text/xml");{/php}{/nocache}
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{$homepageurl}</loc>
        <lastmod>{$curdatetime}</lastmod>
    </url>

{foreach item='curitem' from=$smitems}
    <url>
        <loc>{$curitem.url}</loc>
        {if $curitem.lastmod}
        <lastmod>{$curitem.lastmod}</lastmod>
        {/if}
        {if $curitem.changefreq}
        <changefreq>{$curitem.changefreq}</changefreq>
        {/if}
        {if $curitem.priority}
        <priority>{$curitem.priority}</priority>
        {/if}
    </url>
{/foreach}
</urlset>
