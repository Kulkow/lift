$(function() {
	$('[data-placeholder]').each(function() {
		var input = $(this), placeholder = input.data('placeholder'), password = input.prop('type') == 'password';
		input.focus(function(){
			if (input.val() == placeholder) {
				if (password) input.prop('type', 'password');
			}
		}).blur(function(){
			if (input.val() == '') {
				if (password) input.prop('type', 'text');
			}
		});
		input.closest('form').submit(function() {
			if (input.val() == placeholder) input.val('');
		});
		if (input.val() == '' || input.val() == placeholder) {
			if (password) input.prop('type', 'text');
		}
	});
});