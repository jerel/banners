jQuery(function($) {
	
		$('#file-uploader form').fileUploadUI({
			maxFileSize		: '10000',
			imageTypes		: '/^image\/(jpeg|jpg|png)$/',
			uploadTable		: $('#uploader-queue'),
			buildUploadRow	: function(files, index){
				return $('<li>' +
						'<div class="filename"><label for="file-name">' + files[index].name + '</label>' +
						'<input class="file-name" type="hidden" name="name" value="'+files[index].name+'" />' +
						'</div>' +
						'<div class="file_upload_progress"><div></div></div>' +
						'<div class="file_upload_cancel buttons buttons-small">' +
						'<button class="button float-right cancel"><span>Cancel</span></button>' +
						'</div>' +
						'</li>');
			},
			onComplete: function(event, files, index, xhr, handler){
				var data = $.parseJSON(xhr.responseText);
				//append the newly uploaded thumbnail to the list of images
				if(data.status == 'success') {
					$.post(SITE_URL + 'banners/ajax/add_image', { banner_id : $('input[name="banner_id"]').val() , image_id : data.image_id },
							function(thumb){
								$('ul#images_list').append(thumb);
								$('div.no_data').remove();
										
								//refresh the prompt text
								init_prompt();
							});
					return true;
				}
				return alert(data.status);
			}
		});
		
		$('#uploader-queue :even').addClass('even');
	
});