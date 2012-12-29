<div id="container">

                      <div class="hero-unit" id="ListaModulos">
                      <h4 class="page-header">Módulos</h4>
                      <?php 
					   load_modulos($modulos);
                         ?>
                       <div class="clearfix"></div>
        <h4 class="page-header novidades-titulo">Novidades / Atualizações</h4>
                       <?php 
                         
                        if(isset($atualizacoes)){
                            echo '<ul class="thumbnails">';
                            foreach($atualizacoes as $at):
                               echo '<li class="itemProdutoDestaque"><span class="produtoDestaque label label-info">NOVO</span><img class="imgDestque" src="'.base_url('web/images/produtos/'.$at->imagem).'" /><div class="destaqueDescription"><strong>'.$at->name.'</strong><p>'.$at->descricao.'</p></div></li>';
                            endforeach;
                            echo '</ul>';
                        } 
                       
                       ?>       
        
                      </div>
</div> <!--- END CONTAINER-->
<?php 

add_js(array('plugins/jquery.center','plugins/jquery.qtip'),'top');
add_js(array('common/jquery.sistema'));
add_css(array('commom/sistema'));
?>