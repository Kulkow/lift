
 var It_lift = {status:'free',
                options:{}
 }
 It_lift.ini = function(options){
    It_lift.options = $.extend({
			speed: 30, // скорость лифта
            h:202,  // высота этажа
            l_w:80, // ширина лифта
            l_h:125, // высота лифта
            token:null,
		}, options);
 }
 
 It_lift._preset = function(l, current, level){
    l.data('current', current).attr('data-current', current);
    l.data('level', level).attr('data-level', level);
    var b = It_lift.options.h * (current - 1);
    b = parseInt(b);
    l.css({bottom:b});
 }
 
 It_lift.open = function(l){
    var self = $(l);
    self.attr('class','lift open');
    var w = It_lift.options.l_w + 30;
    self.animate({'width':w},300,'linear', function(){
        $('.action .l',l).removeClass('a');
    });
 }
 
 It_lift.close = function(l){
    var self = $(l);
    self.attr('class','lift');
    var w = It_lift.options.l_w;
    self.animate({'width':w},300,'linear', function(){
        $('.action .l',l).removeClass('a');
    });
 } 
 
 It_lift.update = function(self,opt){
        if(opt.level){
            self.attr('data-level',opt.level).data('level',opt.level);
        }
        if(opt.status){
            self.attr('data-status',opt.status).data('status',opt.status);
            self.attr('class','lift ' + opt.status);
        }
        if(opt.current){
            self.attr('data-current',opt.current).data('current',opt.current);
        }
    }

It_lift.get_lift = function(self){
    return {self: self,
            _id: self.data('id'),
            current: self.data('current'),// на каком этаже лифт сейчас
            level: self.data('level'), //на какой едет
            //up: self.data('up').split(','), //на каких этажи есть вызовы при движении вверх
            //down: self.data('down').split(','), //на каких этажи есть вызовы при движении вниз
            status: self.data('status'), //куда едет лифт - up вверх, down вниз
    }
}

It_lift.lift = function(l, level){
        
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
                    console.log(json);
                    if(json.errors){
                       It_lift.error(json.errors); 
                    }else{
                        console.log('It_lift');
                        console.log(It_lift);
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
                        
                        /*
                        if(json.status == 'open'){
                            It_lift.close(lift);
                            It_lift.update(lift,{level:level});
                            It_lift.lift(lift,level);                    
                        }
                        */
                    }
                   });
                
                //setInterval(function(){
                    //It_lift.lift(l,level);       
                //}, 15)       
            });
        }else{
            It_lift.open(l);
        }
        
}

/**
* setlift
*/

It_lift.setlift = function (l){
    It_lift.lift = l;
    var s = It_lift.get_lift(l);
    It_lift._lift = s;
    return It_lift._lift; 
}

/*
* error
*/
It_lift.error = function(errors) {
    var text = '';
    for(var error in errors){
        text +=  error + '-' +errors[error];
        console.log(error); 
    }
    alert(text);
}


/** 
ajax
*/
It_lift.post = function(url,data, callback){
    if(! data.token){
        data.token = It_lift.options.token;  
    }
    $.ajax({url: url,
            type:'post',
            typeDate:'json',
            data:data,
			beforeSend: function( xhr ) {
			if(It_lift.lift){
				  It_lift.lift.addClass('processing');
			}
            }
    	}).done(callback);
}


jQuery.fn.lift = function(data,options) {
    var self = $(this);
    var l = {self: self,
             current: self.data('current'),// на каком этаже лифт сейчас
             level: self.data('level'), //на какой едет
             up: self.data('up').split(','), //на каких этажи есть вызовы при движении вверх
             down: self.data('down').split(','), //на каких этажи есть вызовы при движении вниз
             status: self.data('status'), //куда едет лифт - up вверх, down вниз
    }
    l.current = parseInt(l.current);
    l.level = parseInt(l.level);
    
    l.far = function(level){
        var b = options.h * (level - 1);
        var d = 100*(b/options.speed);
        d = parseInt(d);   
        l.self.self.animate({bottom:b}, d);
    } 
    
   
    
    /* обновляет Element lift */
    l.update = function(opt){
        if(opt.level){
            l.self.data('level',opt.level);
        }
        if(opt.status){
            l.self.data('status',opt.status);
            l.self.attr('class','left ' + opt.status);
        }
        if(opt.current){
            l.self.data('current',opt.current);
        }
    }
    
    options = $.extend({
			speed: 20, // скорость лифта
            h:153,  // высота 
			level: 1,
            current:1
		}, options);
    var far = false;
    if(l.status == 'up'){
        far = l.current < l.level;
        l.current = l.current++;
    }else{
        far = l.current > l.level;
        l.current = l.current--;
    }
    /*
    if(far){
        l.update(l);
    }  */  
    
    console.log(far);
    console.log(l);
    /*    
    var b = options.h * (options.level - 1);
    var d = 100*(b/options.speed);
    d = parseInt(d);   
    self.animate({bottom:b}, d);
    */     
}





               
