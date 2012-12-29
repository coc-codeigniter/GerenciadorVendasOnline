jQuery(function($){
    
    /*
    $.fn.checkEstoque =  function(options){
        var obj =  this;
        idCheck =  $(obj).attr('data-id');
        var defaults = {
            inputValue: $('.check-'+idCheck).val(),
            idThis        : idCheck
        }
        
        opt = $.extend(defaults, options);
        
        obj.bind('click',_check);
        
        
        
        
        
        function _check(e){
            e.preventDefault();
            
            alert(opt.idThis);
           return false;
           
           $.ajax({
                url : jsUrl+'vendas/checkEstoque',
                type:'POST',
                data:{
                      check:opt.inputValue , 
                      id_produto: opt.idThis},
               
                dataType:'json',
                success:function(json)
                {
                    alert(json.resp);
                }
            })
        }
        
    }
    */
  
    $('.formProduto').submit(function(ev){
      
        ev.preventDefault();
        var idThis   = $(this).find('input[type=submit]').attr('data-id');
        var qtdChech = $('.check-'+idThis).val();
        var forms    = this;
      
     
       
      
        $.ajax({
            url : jsUrl+'vendas/checkEstoque',
            type:'POST',
            data:{
                check:qtdChech , 
                id_produto: idThis
            },
               
            dataType:'json',
            success:function(json)
            {  
                if(json.resp == false){
                    _showMessage('error', 'Quantidade em estoque insuficiente');
                    return false;
                }else{
                    forms.submit();   
                }
                  
            }
        })
            
      
      
      
        function _showMessage(type,msg){
          
            var  markup = ['<div id="myAlert" class="alert alert-'+type+'">',msg,'</div>'].join('');
            if($('#myAlert').length == 1){
              
                $('#myAlert').remove();
            }
            $(markup).prependTo('body').slideDown().delay(4000).slideUp();
          
        }
            
      
    });
    $('#save-orcamento').tooltip({
        position:'topRight'
    });  
      $('.no-estoque').tooltip({ position:'topRight'});
    $('#save-finaliza').tooltip({
        position:'topRight',
        theme:'white'
    });
   
    $('#myAlert').slideDown().delay(4000).slideUp();
    $('.add_cart').tooltip({theme:'orange',position:'topRight'});
    
    $('.i-orcamento').bind('click',function(e){
        
        e.preventDefault();
        
        
        $.ajax({
            url : jsUrl+'vendas/listClientes',
            type:'POST',
            dataType:'json',
            success:function(json)
            {  
                
                var markup = '<div class="controls">'+
                '<select name="op-clientes" class="">';
                $.each(json.clientes, function(k,v){
                    markup += '<option data-id="'+v.nome+'" value="'+ v.codigo +'">'+v.nome+'</option>';
                    
                })
                
                markup +="</select></div>";
                var btns = '<button class="btn bnt-primary" id="save-select">Salvar</button>'
                opt = {
                    title:'Clientes',
                    body : markup,
                    footer:btns
                }
                showModal(opt);
                $('#save-select').bind('click',saveSession);  
            }
        })
        
        
        function saveSession(ev){
            
           
            
            $.ajax({
                url : jsUrl+'vendas/saveSessionDataCliente',
                type:'POST',
                data:{
                    id:$('select[name=op-clientes]').val(),
                    nome:$('select[name=op-clientes] option:selected').attr('data-id')
                    
                },
                dataType:'json',
                success:function(json)
                { 
                   window.location = jsUrl+'vendas'; 
                   
                }
            });
        }
    });
    
    
    function showModal(options){
        
        var defaults = {
            title :'Sem Titulo',
            body  :'Corpo',
            footer:'rodape'
        }
        
        var op = $.extend(defaults,options);
        
        var  markup = ['<div id="modalSistema" class="modal hide fade in" style="display: block; ">',
        '<div class="modal-header">',
        '<button class="close closeModalSistema" data-dismiss="modal">×</button>',
        '<h3>'+op.title+'</h3>',
        '</div><div class="modal-body">',
        op.body,
        '</div>',
        '<div class="modal-footer">',
        op.footer,
        '</div>',
        '</div>','</div>'].join('');
                
        
        $(markup).appendTo('body');
        $('.closeModalSistema').bind('click',closeModal);
       
       
    }
    
    function closeModal(){
        $('#modalSistema').fadeOut(300,function(){
               
                $(this).remove();
            });
    }
    
});

