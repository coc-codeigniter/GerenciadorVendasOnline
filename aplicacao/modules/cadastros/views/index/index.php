<div id="container">

                      <div class="hero-unit" id="ListaModulos">
                      <h4 class="page-header">Cadastros</h4>
                      <?php 
					   load_modulos($modulos);
                       
                      
                         ?>
                       <div class="clearfix"></div>
        
                      </div>
</div> <!--- END CONTAINER-->
<?php 

add_js(array('plugins/jquery.center','plugins/jquery.qtip'),'top');
add_js(array('common/jquery.cadastros'));
add_css(array('commom/sistema'));
?>