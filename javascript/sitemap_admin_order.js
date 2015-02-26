function showhide_order(modname, modcol) {
    if (modcol==1) {
        document.getElementById('sitemap_ordermod_'+modname).style.display = "inline";
        document.getElementById('sitemap_ordermodinf_'+modname).style.display = "inline";
    } else {
        document.getElementById('sitemap_ordermod_'+modname).style.display = "none";
        document.getElementById('sitemap_ordermodinf_'+modname).style.display = "none";
    }
}
