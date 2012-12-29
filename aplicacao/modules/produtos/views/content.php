<div id="container">
		<div class="container_12">
			<div class="grid_12" id="header"><h2>Sistema de Vendas - Home</h2> </div>
			<div class="grid_12" id="menu_modulos">
					   <?php 
					   load_modulos($modulos);
					   ?>
			         </div>
			<!--menu_modulos -->
		    <div id="atualizacoes" class="grid_12">
		    	
		    	<h4 class="separator">Atualizações
		    		<span class="separator">&nbsp;</span>
		    		</h4>
		    	<div class="display_atualizacao">
		    		<?php 
		    	     get_atualizacoes($atualizacoes);
		    	     ?>
		        </div> <!--- END display_atualizacao --->
		    	
	            <div id="dados_usuario">
	            	<h4 class="separator">Dados do Usuário
		    		<span class="separator">&nbsp;</span>
		    		</h4>
		    		<div id="meta_vendas" class="meta_vendas">
		    			<h5>Vendas</h5><span class="arrow_meta">&nbsp;</span>
		    			<div class="display_meta">
		    				<span>Sua</span>
		    				<h3>Meta</h3>
		    				<span class="meta_valor">100 0000,00</span>
		    			</div><!-- END display_meta  -->
		    		
		    		</div> <!--- END meta vendas -->
		    		<div class="ultimos_pedidos">
		    			<h5>Ultimos Pedidos</h5>
		    			<div class="display_pedidos">
		    			 <div class="display_header_pedidos">
		    			 	<b class="lbl_pedidos">Pedidos</b>
		    			 	<b class="lbl_valor">Valor</b>
		    			 	<b class="lbl_nome_cliente">Cliente</b>
		    			 	<b class="data_pedidos" >Data Pedido</b>
		    			  </div>
		    			
		    			  <!-- DATA PEDIDOS-->
		    			  <div class="display_content_pedidos"> 
		    			  <span class="numero_pedido">56699900</span>
		    			  <span class="valor_pedido">198,50</span>	
		    			  <span class="cliente_pedido">Jhonson & Jhonson LTDA</span>
		    			  <span class="data_pedido">20-10-2011</span>		
		    			  </div>
		    			  <!-- END DATA PEDIDOS-->
		    			  
		    			  <div class="display_content_pedidos"> 
		    			  <span class="numero_pedido">56699900</span>
		    			  <span class="valor_pedido">198,50</span>	
		    			  <span class="cliente_pedido">Jhonson & Jhonson LTDA</span>
		    			  <span class="data_pedido">20-10-2011</span>		
		    			  </div>
		    			  
		    		</div>
		    	 </div> <!--- END dados_usuario  -->	
	                	
		    </div><!-- END atualizacao -->
		    <div class="clear"></div>
		    <div class="creditos">
		    	Versão do sistema 1.0   Direitos de Reservados da SI sistemas @2011  
		    </div>
		</div> <!---  END class container_12 -->
		</div> <!--- END CONTAINER-->