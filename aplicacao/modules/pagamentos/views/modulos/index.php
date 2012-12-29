<ul class="thumbnails OptionsPayments">
<?php 
  if(isset($modulos)){
    foreach($modulos as $mod){
        
        $options =  json_decode($mod->opcoesModulo);
        
        echo '<li class="span5"><img class="moduloOptionImagem" src="'.site_url().'web/'.$options->imagem.'"/><span class="rotulo">'.$mod->rotuloModulo.'</span><br/>';
             foreach($options->parcelas as $op)
             { 
                  echo '<div class="lblOptionPagamento"><input id="'.uniqid('inputRadio-').'" class="" type="radio" name="paymentOption[]" value="'.$mod->nomeModulo.'-'.$op->value.'"/>'.$op->label.'</div>'; 
             }
        echo'</li>';
    }
  }
add_css('common/pagamentos');
?>
</ul>

