(function($){
$.fn.loginSI =  function(options)
    {     
           var $e =  $(this),
                o = $.extend({},
                     {
                     urlvalidate :'',
                     urlredicet  :'', 
                     rulesValidate:{},
                     inputsName   :[],
                     textSuccess  :'Validado com sucesso, vamos redireciona-lo <i class="icon-ok login-success"></i>',
                     textError    :'Favor conferir os seus dados <i class="icon-remove login-error"></i>',
                     textLoading :'Aguarde validando os dados... <span class="loading"></span>',
                     delayRedirect:2000
                     }
                     ,options);
             
        function  getDataInputs(){
            var obj = {};
            var inputs    = o.inputsName;
            for(var i =0;i < inputs.length; i++ ){
                       obj[inputs[i]] = $('input[name="'+inputs[i]+'"], select[name="'+inputs[i]+'"]').val();    
            }
           return obj;   
        }
             
             
          //dependencias jquey.validade
        $e.validate({
        errorClass:'avisos',
        onblur:false,
        onleyup:false,
        rules:o.rulesValidate,
        submitHandler: function($e)
        {
            var dataInput =  getDataInputs();
            $.ajax({
                
                url:o.urlvalidate,
                type:'POST',
                data:dataInput,
                dataType:'json',
                beforeSend: function(){
                    $.display.open({
                        data:o.textLoading,
                        type:'info'
                    });
                },
                success:function(data){
                    $.display.close();
                    if(data.result){
                        $.display.open({
                            data:o.textSuccess
                        });
                        if(o.urlredicet != ''){
                            setTimeout(redirect,o.delayRedirect);
                        }
                         
                    }else{
                        $.display.open({
                            data:o.textError,
                            type:'error'
                        });
                        
                    }
                
                return false;
                }
                
                
            });
            
        }
        
        });// end validade
         
        function redirect(){
            window.location  = o.urlredicet;
        }
    };
$.display = {
        
        open:function(options){
        
            var obj =  $.extend({},{
                data:'',
                type:'success'
            },options);
            $.display.close();
            if($('#loader').length == 0){
                var markup  = '<div id="loader" class="alert alert-'+obj.type+'">'+obj.data+'</div>';
                $(markup)
                .css({
                    position: "absolute", 
                    top: "-40px", 
                    left: "5px", 
                    right:'5px'
                })
                .appendTo("#Autenticar").show();  
            }
              
        
        },
        close : function(){
            if($('#loader').length == 1){
                $('#loader').remove();
            }
        }

    };
    
})(jQuery);