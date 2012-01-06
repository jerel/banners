jQuery(function($) {

	$('ul#images_list').sortable({
		handle: 'img',
		start: function(event, ui) {
			ui.helper.find('a').unbind('click').die('click');
		},
		update: function() {
			order = new Array();
			$('li', this).each(function(){
				order.push( $(this).find('input[name="action_to[]"]').val() );
			});
			order = order.join(',');

			$.post(SITE_URL + 'banners/ajax/ajax_update_order', { order: order });
		}
	});
		
	//delete one image
	$('ul#images_list .delete-icon').live('click', function(){
		var element = this;
		$.post(SITE_URL + 'banners/ajax/delete_image', { id : $(element).parent().find('input[name="action_to[]"]').val(),
															banner_id : $('input[name="banner_id"]').val() },
			function(){
				$(element).parent().remove();
			});
	});
		
	//delete all images for this banners
	$('ul#banners-images .delete-all-icon').live('click', function(){
		if(confirm('You are about to delete all images for this banner set. Are you sure?'))
		{
			$.post(SITE_URL + 'banners/ajax/delete_set', { id : $('input[name="banner_id"]').val() },
				function(){
					$('ul#images_list').children().remove();
					$('.qq-upload-list').children().remove();
				});
		}
	});
		
	window.init_prompt = function() {
		//delay for the description update
		var typedelay = (function(){
			var timer = 0;
			return function(callback, ms){
				clearTimeout (timer);
				timer = setTimeout(callback, ms);
			}  
		})();

		//update the db 1500 ms after they quit typing
		$('ul#images_list textarea[name="description"]').keydown(function () {
			var textbox = this;
			var input	= $(textbox).parent().find('input[name="action_to[]"]');
			typedelay(function () {
				//update the description <milliseconds> after they quit typing
				$(textbox).parent().find('.loading-gif').show();
				$.post(SITE_URL + 'banners/ajax/update_description', {
					id 		: $(input).val(),
					description : ($(textbox).val() === $(textbox).attr('title')) ? '' : $(textbox).val() },
					function(){
						$(textbox).parent().find('.loading-gif').hide();
					});
			}, 1500);
		});
		
		//remove text on focus
		$(".prompt-text").focus(function(srcc)
		{
			if ($(this).val() == $(this)[0].title)
			{
				$(this).removeClass("prompt-text-active");
				$(this).val("");
			}
		});
		
		//add prompt text when losing focus
		$(".prompt-text").blur(function()
		{
			if ($(this).val() == "")
			{
				$(this).addClass("prompt-text-active");
				$(this).val($(this)[0].title);
			}
		});
			
		//make sure none of the text areas are in focus on load
		$(".prompt-text").blur();
	}
	$(document).ready(function(){
		init_prompt();
	});	
});