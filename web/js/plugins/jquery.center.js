;(function($){
    

    $.fn.center = function(){
        
        var element = this;
        // function load para navegadores safari e chrome.
        $(element).load(function(){
            changeCss();
            $(window).bind('resize',function(){
                changeCss();
            }) ; 
        }); 
        changeCss();
        $(window).bind('resize',function(){
            changeCss();
        }) ;
        
        function changeCss(){
            var eleHeight     = $(element).height();
            var eleWidth      = $(element).width();
            var windowHeight  = $(window).height();
            var windowWidth   = $(window).width();
        
            $(element).css({
                'position':'absolute',
                'left'    :(windowWidth/2) - (eleWidth/2),
                'top'     :(windowHeight/2)- (eleHeight/2)
            
            })
        
    
        }
        
       return this;
    }
    
})(jQuery)
