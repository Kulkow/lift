function Itlift(){
    return{
    	url:  function(_id, action, target){
    		if(action){
    			action = '/' + action;
    		}else{
    			var action = '';
    		}
            if(! target){
                target = 'lift'
            }
    		return window.location.protocol +'//' + window.location.hostname + '/'+target+'/' + _id + action; 
    	},
    	_preset : function(l, current, level){
    		l.data('current', current).attr('data-current', current);
    		l.data('level', level).attr('data-level', level);
    		var b = this.options.h * (current - 1);
    		b = parseInt(b);
    		l.css({bottom:b});
    	 },
    	init : function(self,options){
    		var _this = this;
    		_this.options = $.extend({
    			speed: 100, // скорость лифта
                h:202,  // высота этажа
                l_w:80, // ширина лифта
                l_h:125, // высота лифта
                token:null,
    		}, options);
    		_this.self = $(self);
    		_this.get_lift();
    		return _this;
    	},
    	get_lift : function(){
    		var _this = this;
    		_this._id = _this.self.data('id');
    		_this.current = +_this.self.data('current');// на каком этаже лифт сейчас
    		_this.level = +_this.self.data('level'), //на какой едет
    		_this.updated = _this.self.data('updated'); //когда последний раз обновлялось
    		_this.status = _this.self.data('status'); //куда едет лифт - up вверх, down вниз
    		return _this;
    	},
    	update : function(opt){
    		var _this = this;
    		if(opt.level){
    			_this.self.attr('data-level',opt.level).data('level',opt.level);
                _this.level = +opt.level;
    		}
    		if(opt.status){
    			_this.self.attr('data-status',opt.status).data('status',opt.status);
    			//_this.self.attr('class','lift ' + opt.status);
                _this.status = +opt.status;
    		}
    		if(opt.current){
    			_this.self.attr('data-current',opt.current).data('current',opt.current);
                _this.current = +opt.current;
    		}
    		if(opt.updated){
    			_this.self.attr('data-updated',opt.updated).data('updated',opt.updated);
                _this.updated = opt.updated;
    		}
    		return _this;
    	},
        
    	open : function(l){
	    console.log('open');
    	    var self = $(l); 
            var o_lift = this;
    		var url = window.location.protocol +'//' + window.location.hostname + '/lift/' + o_lift._id + '/open';  
    		o_lift.post(url,{}, function(json){
		    self.addClass('open');
		    o_lift.update({status:2});
		    var w = o_lift.options.l_w + 30;
		    self.stop().animate({'width':w},300,'linear', function(){
			console.log('animamte');
			$('.action .l',l).removeClass('a');
			self.find('.action').removeClass('hidden');
			self.removeClass('go');
		    });
		    /*var _level = $('#level_'+ o_lift.level).find('A[data-lift='+o_lift._id+']');*/
                    var _level = $('#level_'+ o_lift.level).find('.actions A');
		    _level.removeClass('active');
	        })
    	 },
         close : function(l){
	    console.log('close');
            var self = $(l);
            self.removeClass('open').removeClass('go');
            var w = this.options.l_w;
            self.stop().animate({'width':w},300,'linear', function(){
                $('.action',self).addClass('hidden');
                $('.action .l',l).removeClass('a');
            });
         },
         error : function(errors) {
            var text = '';
            for(var error in errors){
                text +=  error + '-' +errors[error];
                console.log(error); 
            }
            alert(text);
        },
        log : function(_logs) {
            var text = '';
            for(var _log in _logs){
                text +=  _log + '-' +_logs[_log];
            }
            $('#logs .wrapper').append('<div class="log">'+text+'</div>');
        },
        post: function(url,data, callback){
            $.ajax({url: url,
                    type:'post',
                    dataType:'json',
                    data:data,
        			beforeSend: function(xhr) {
            			//_this.self.addClass('load');
                    }
            	}).success(function(json){
            	   callback(json);
            	});
        }
    }
};

function Itpost(url,data, callback){
    $.ajax({url: url,
            type:'post',
            dataType:'json',
            data:data,
			beforeSend: function(xhr) {
    			//_this.self.addClass('load');
            }
    	}).success(function(json){
    	   callback(json);
    	});
}

function liftgo(l, level){
        
        var lift = new Itlift();
		var o_lift = lift.init(l);
        /*if(o_lift.status == 0){*/
        console.log('lift:' + o_lift.current+ '<>'+ o_lift.level+ ':' + level);
        if(1){    
            if(o_lift.level != level){
                o_lift.update({level:level});
                l.addClass('go').removeClass('free');
		o_lift.close(l);
            }
            if(l.hasClass('open')){
                o_lift.close(l);
            }
            if(o_lift.current != o_lift.level){
                if(o_lift.level > o_lift.current){
                    var f_level = o_lift.current + 1;
                }else{
                    var f_level = o_lift.current - 1;
                }
                
                var b = o_lift.options.h * (f_level - 1);
                b = parseInt(b);
                var d = 100*(b/o_lift.options.speed);
                d = parseInt(d);   
                l.stop().animate({bottom:b}, d,'linear', function(){
                    o_lift = o_lift.init(l);
                    o_lift.update({current:f_level});
                    /*o_lift.setlift(l);*/
                    setTimeout(function(){
                         liftgo(l,level);       
                    }, 15);      
                });
            }else{
		setTimeout(function(){
		    o_lift.open(l);
		    o_lift.log({lift: o_lift._id, event: 'open'});
		    return;
		},20);
            }
        }else{
            o_lift.log({lift: o_lift._id, event: 'no free'});
        }    
}

/**rest*/
Itrest = function(house, timeout){
	var url = window.location.protocol +'//' + window.location.hostname + '/house/' + house + '/lifts'; 
	var c_lifts = {}, c_ids = [], index = 0;
	$('.lift').each(function(i, item){
		var _l = $(item);
        var lift = new Itlift();
		var _cl = lift.init(_l);
        
		c_lifts[_cl._id] = _cl;
		index++;
		c_ids[index] = _cl._id;
	})
	Itpost(url, {}, function(json){
		if(json.lifts){
			for (var _id in json.lifts) {
				var _lift = json.lifts[_id];
				if(c_lifts[_id]){
					var c_lift = c_lifts[_id];
                    /*console.log('rest:'+_lift.id + ':s:' +_lift.status);*/
					if(c_lift.updated != _lift.updated){
						if(! c_lift.self.hasClass('go')){
                            c_lift.update(_lift);
    						if(c_lift.status == 1){
    							if(! c_lift.self.hasClass('go')){
    								liftgo(c_lift.self, c_lift.level);
    							}
    						}
                            if(_lift.status == 0){
                                c_lift.close(c_lift.self);
                            }
                            
    						if(_lift.status != c_lift.status){
    							console.log('update status');
    						}
                        }    
					}
				}else{
					console.log('add lift');
				}
			}
		}
		setTimeout(function(){
			Itrest(house, timeout);
		}, timeout)
	})	
}