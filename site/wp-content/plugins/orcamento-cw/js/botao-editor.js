(function() {
   /* Register the buttons */
   tinymce.create('tinymce.plugins.MyButtons', {
      init : function(ed, url) {
               /**
               * Inserts shortcode content
               */               
               ed.addButton( 'button_eek', {
                title : 'Orçamento CW',
                image : urlPlugin()+'/imagens/icon-editor.gif',
                onclick : function() {
                 jQuery('.modal-cw').show();
             }


         });

           },
           createControl : function(n, cm) {
             return null;
         },
     });
   /* Start the buttons */
   tinymce.PluginManager.add( 'my_button_script', tinymce.plugins.MyButtons );
})();