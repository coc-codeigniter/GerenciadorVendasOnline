    <div id="Autenticar">
      
      <form class="form-horizontal well" method="post">
         <h3 class="titulo"><img src="<?php echo site_url().'web/images/log-si.png'; ?>" /></h3>
         <div class="span7">
         <label class="required"><i class="icon-user"></i></a> Login</label>
         <input  name="login" type="text" class="input-large" />
         </div>
      
      <div class="span7">
         <label class="required password"><i class="icon-lock"></i> Senha </label>
         <input  name="password" type="password" class="input-large" />
         </div>
        <div class="pull-right">
         <input type="submit" class="btn btn-primary" value="Acessar" name="logar" />
        </div> 
         <div class="clearfix"></div>
      </form>
    </div>
    


<?php
//add js and css 
add_js(array('common/jquery.autenticar'));
add_js(array('plugins/jquery.center','plugins/validate/jquery.validate','plugins/login/jquery.login'),'top');

?>