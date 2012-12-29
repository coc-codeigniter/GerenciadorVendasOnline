$.validator.setDefaults({
    highlight: function(input) {
        $(input).addClass("ui-state-highlight");
    },
    unhighlight: function(input) {
        $(input).removeClass("ui-state-highlight");
    }
});

$(document).ready(function()
{
    $('div#Autenticar').center().delay(300).show();
    $('.required').append('<strong>*</strong>');
       $('form').loginSI({urlvalidate  : url_base + 'autenticar/validate',
                       urlredicet   :"sistema",
                       inputsName   :['login','password'],  
                       rulesValidate:{
                                      'login':{required:true},
                                      'password':{required:true},
                                     }
                       });
});