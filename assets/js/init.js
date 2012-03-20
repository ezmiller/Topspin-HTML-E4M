$(document).ready(function() {
	
	$('input.erase').each(function() {
		$(this)
			.data('default', $(this).val())
			.addClass('inactive')
			.focus(function() {
				$(this).removeClass('inactive');
				if ($(this).val() == $(this).data('default') || '') {
					$(this).val('');
				}
			})
			.blur(function() {
				var default_val = $(this).data('default');
				if ($(this).val() == '') {
					$(this).addClass('inactive');
					$(this).val($(this).data('default'));
				}
		});
	});
	
	$('#signup').bind('submit', function(e) {
		e.preventDefault();
		$(this).ajaxSubmit({
			success: function() { $('#email').val('Thanks, Check Your Inbox!'); }			
		});
	});
	
});