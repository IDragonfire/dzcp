function selectFile(url) {
    var fileName = '';
    if (url.length == 0) {
        fileName =  ''; 
    } else {
        var pos = url.lastIndexOf("/inc/tinymce_files");
        if (pos >= 0) {
            fileName = ".." + url.substr(pos);
        } else {
            fileName = url;
        }
    }
    window.opener.document.getElementById(elementId).value = fileName;
    window.close();
}
