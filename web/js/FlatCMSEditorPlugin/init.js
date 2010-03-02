$(document).ready(function() {
  /* Handle links inside editable area. */
  $('.editable-area > a').bind('click', function() {
      $(this).parent().trigger('click');
      return false; 
  });
  
  
  $('.editable').editable(superglue.settings.FlatCMSEditorPlugin.saveURL, {
    submitdata: {path: superglue.settings.FlatCMSEditorPlugin.currentPath},
    method: 'PUT',
    cancel: 'Cancel',
    submit: 'OK',
    tooltip: 'Click to edit...',
    type: 'text'
  });
  
  /*
    TODO abstract out configuration
  */
  $('.editable-area').each(function(){
    $(this).editable(superglue.settings.FlatCMSEditorPlugin.saveURL, {
      submitdata: {path: superglue.settings.FlatCMSEditorPlugin.currentPath},
      method: 'PUT',
      cancel: 'Cancel',
      submit: 'OK',
      onblur: 'ignore',
      tooltip: 'Click to edit...',
      type: 'tinymce',
      width: $(this).outerWidth(),
      height: "300px",  //arbitrary number, need to work out a better way for this
      tinymce: {
        script_url: './js/FlatCMSEditorPlugin/tinymce/jscripts/tiny_mce/tiny_mce.js',
        // General options
        theme: "advanced",
        plugins: "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

        // Theme options
        theme_advanced_buttons1: "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4: "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: true,
        theme_advanced_resizing_use_cookie: false

        // Example content CSS (should be your site CSS)
        //content_css: "css/content.css"
      }
    });
  });
});