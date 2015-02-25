var Itlift = {
	url:  function(_id, action){
		if(action){
			action = '/' + action;
		}else{
			var action = '';
		}
		return window.location.protocol +'//' + window.location.hostname + '/lift/' + _id + action; 
	},
	_preset = function(l, current, level){
		l.data('current', current).attr('data-current', current);
		l.data('level', level).attr('data-level', level);
		var b = It_lift.options.h * (current - 1);
		b = parseInt(b);
		l.css({bottom:b});
	 },
	init : function(self,options){
		var _this = this;
		_this.options = $.extend({
			speed: 30, // скорость лифта
            h:202,  // высота этажа
            l_w:80, // ширина лифта
            l_h:125, // высота лифта
            token:null,
		}, options);
		_this.self = $(self);
		_this.get_lift(_this.self);
		return _this;
	},
	get_lift = function(self){
		var _this = this;
		_this.self: self;
		_this._id: self.data('id'),
		_this.current: self.data('current'),// на каком этаже лифт сейчас
		_this.level: self.data('level'), //на какой едет
		_this.updated: self.data('updated'), //когда последний раз обновлялось
		_this.status: self.data('status'), //куда едет лифт - up вверх, down вниз
		return _this;
	},
	update = function(opt){
		var _this = this;
		if(opt.level){
			_this.self.attr('data-level',opt.level).data('level',opt.level);
		}
		if(opt.status){
			_this.self.attr('data-status',opt.status).data('status',opt.status);
			_this.self.attr('class','lift ' + opt.status);
		}
		if(opt.current){
			_this.self.attr('data-current',opt.current).data('current',opt.current);
		}
		if(opt.updated){
			_this.self.attr('data-updated',opt.updated).data('updated',opt.updated);
		}
		return _this;
	}
	open : function(l){
		var self = $(l); 
		var o_lift = It_lift.get_lift(l);
		var url = window.location.protocol +'//' + window.location.hostname + '/lift/' + o_lift._id + '/open';  
		Itlift.post(url,{}, function(json){
				self.attr('class','lift open');
				self.find('.action').removeClass('hidden');
				self.removeClass('go');
				It_lift.update(self, {status:2});
				var w = It_lift.options.l_w + 30;
				self.animate({'width':w},300,'linear', function(){
					$('.action .l',l).removeClass('a');
				});
				var _level = $('#level_'+ o_lift.level).find('A[data-lift='+o_lift._id+']');
				_level.removeClass('active');
			}
		)
	 }

}