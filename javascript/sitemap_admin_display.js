function showhide_displaymodext(modname, modyn) {
    if (modyn==1) {
        document.getElementById('sitemap_displaymodext_'+modname).style.display = "block";
    } else {
        document.getElementById('sitemap_displaymodext_'+modname).style.display = "none";
    }
}
function showhide_displaymodxml(modname, modyn) {
    if (modyn==1) {
        document.getElementById('sitemap_displaymodxml_'+modname).style.display = "block";
    } else {
        document.getElementById('sitemap_displaymodxml_'+modname).style.display = "none";
    }
}
