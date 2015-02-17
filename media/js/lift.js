
 var It_lift = {status:'free',
                options:{}
 }
 It_lift.open = function(){
    var self = $(this);
    self.addClass('open');
    self.animate({})
 } 


jQuery.fn.lift = function(options) {
    var self = $(this);
    options = $.extend({
			speed: 10, // скорость лифта
            h:153,  // высота 
			level: 1
		}, options);
    var b = options.h * (options.level - 1);
    var d = 100*(b/options.speed);   
    console.log(d); 
    self.animate({bottom:b}, d);    
}





               
$(function(){
    var house = $('.house').eq(0);
    $('.lift',house).each(function(i, it){
        var l = $(it);
        var s = l.data('status');
        l.html('<div class="status">'+lang.lift[s]+'</div>')
    })
    $('.cell .actions a',house).click(function(){
        var a = $(this), actions = a.closest('.actions'), level = a.data('level'), lift_id = a.data('lift'), event = a.data('event');
        var lift = $('#lift_' + lift_id);
        if(! a.hasClass('active')){
            
            $.ajax({
					url: a.attr('href'),
                    type:'post',
                    data:{lift:lift_id, level:level},
					beforeSend: function( xhr ) {
						//list.addClass('processing');
					}
				}).done(function(response) {
				    //var result = $('.product-list .items', response);
                    //result = result.eq(0).get();
                    a.addClass('active');
                    lift.attr('class', 'lift '+ event);
                    lift.lift({level:level});
					
				});
            /*a.addClass('active');
            lift.attr('class', 'lift '+ event);
            lift.lift({level:level});*/
        }
        return !1;
    })
})