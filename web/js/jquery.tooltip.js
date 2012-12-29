;
(function($){
    
    $.fn.extend({
        tooltip:function(options){
            var defaults = {
                theme:'blue',
                position:'topLeft',
                content :false,
                width   :'300px'
            } 
            
            var opts = $.extend(defaults,options);
            
            
            return this.each(function(){
                
                var  o = opts;
                var  obj = $(this);
                
                var theme    = o.theme;
                var posTool  = o.position;
                var _attr    = ($(this).attr('alt') != "" ? $(this).attr('alt') :false);
                var _content = ((o.content == false || o.content == undefined ) ? obj.attr('title') : o.content);
                var _origin  = ((o.content == false || o.content == undefined ) ? obj.attr('data-title' ,obj.attr('title')).removeAttr('title'):null);
                 
                
                obj.hover(function(){
                    $('<div class="_toltip"´><p>'+
                       _content + '</p></div>').appendTo('body').hide();
                                  
                    var posEle = $(this).offset();
                    var _width = $(this).width();
                    var _eleHeight = $(this).height();
                     
                    var tooltpWidth = $('._toltip').width(); 
                     
                            
                    
                    $('._toltip').addClass(theme);
                               
                    //set positions
                    switch(posTool){
                        case 'topLeft':
                            $('._toltip')
                            .css({
                               'left':(posEle.left - (_width + tooltpWidth) )+"px",
                                'top':(posEle.top - (_eleHeight +  30))+"px"
                            })
                            break;
                        case 'topRight':
                            $('._toltip')
                            .css({
                                'left':(posEle.left + _width + 15 )+"px",
                                'top':(posEle.top - (_eleHeight +  30))+"px"
                            })
                            break; 
                        case 'bottomRight':
                            $('._toltip')
                            .css({
                                'left':(posEle.left + _width + 15 )+"px",
                                'top':(posEle.top + (_eleHeight) + 15 )+"px"
                            })
                            break;    
                        case 'bottomLeft':
                            $('._toltip')
                            .css({
                                'left':(posEle.left - (_width + tooltpWidth) )+"px",
                                'top':(posEle.top + (_eleHeight) + 10)+"px"
                            })
                            break;
                       case 'lineLeft':
                            $('._toltip')
                            .css({
                                'left':(posEle.left - (_width + 20) )+"px",
                                'top':(posEle.top)+"px"
                            })
                       break;    
                       
                      case 'lineRight':
                            $('._toltip')
                            .css({
                                'left':(posEle.left + (_width + 30) )+"px",
                                'top':(posEle.top)+"px"
                            })
                       break;     
                       
                        
                    }
                        
                            
                    $('._toltip')
                    .fadeIn('slow');
                },function(){
                    $('._toltip').remove(); 
                
                });
               function loadeContent(){
                   
                   
               }
                
            });
            
            
            
        }
        
    });
    
    
})(jQuery);

