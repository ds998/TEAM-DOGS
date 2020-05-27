function dosearch(){
    var sf=document.searchform;
    var submitted=sf.search_textbox.value;
    var item=document.getElementById(submitted);
    if(item!=null) {
       location.href="#";
       location.href="#"+submitted;
    }
    return;
}