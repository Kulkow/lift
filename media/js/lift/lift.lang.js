var lang = {lift:{
                  'up'      :'вверх',
                  'down'    :'вниз',
                  'free'    :'свободен',
                  'lift_up' :'едет вверх',
                  'lift_up' :'едет вниз',
                  'open'    :'открыл двери',
                  'close'   :'закрыл двери',      
}
}

/*
It_lift.lift2 = function(l, level){
        
        var o_lift = It_lift.get_lift(l);
        if(o_lift.level != level){
            It_lift.update(l,{level:level});
        }
        if(o_lift.current != o_lift.level){
            if(o_lift.level > o_lift.current){
                var f_level = o_lift.current + 1;
            }else{
                var f_level = o_lift.current - 1;    
            }
            var b = It_lift.options.h * (f_level - 1);
            b = parseInt(b);
            var d = 100*(b/It_lift.options.speed);
            d = parseInt(d);   
            l.animate({bottom:b}, d,'linear', function(){
                It_lift.update(l,{current:f_level});
                
                o_lift = It_lift.get_lift(l);
                  It_lift.setlift(l);
                  // отправляем запрос на этом этаже
                  var url = window.location.protocol +'//' + window.location.hostname + '/lift/' + o_lift._id + '/lift/';  
                  It_lift.post(url,{}, function(html){
                    var json = $.parseJSON(html);
                    if(json.errors){
                       It_lift.error(json.errors); 
                    }else{
                        if(json.status == 'open'){
                            It_lift.open(l);
                            if(json.level != json.currnet){
                               setInterval(function(){
                                    It_lift.lift(l,json.level);
                                }, 1000); 
                            }
                            
                            
                        }else{
                            if(json.level != json.currnet){
                             It_lift.lift(l,json.level);       
                           } 
                        }
                        
                        
                        if(json.status == 'open'){
                            It_lift.close(lift);
                            It_lift.update(lift,{level:level});
                            It_lift.lift(lift,level);                    
                        }
                        
                    }
                   });
                
                //setInterval(function(){
                    //It_lift.lift(l,level);       
                //}, 15)       
            });
        }else{
            It_lift.open(l);
        }
        
*/