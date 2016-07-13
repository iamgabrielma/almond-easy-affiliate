jQuery(document).ready(function($){
	/* Default state on page load */
	$('#id-to-edit').hide();
	$('#ok-id-to-edit').hide();
	$('#ok-id-to-edit').hide();
	$('#ok-to-edit-button').hide();
	$('#add-image-field-input').hide();
	$('#ok-add-image-button').hide();
	$('#upload-image-button').hide();
	$('#show-more-info').hide();

	
	/* Toggles visibility on hidden fields */
	$('#edit-button').click(function(event){
		//console.log('clicked');
		$('#id-to-edit').toggle();
		$('#ok-edit-field').toggle();
		$('#ok-to-edit-button').toggle();
		
		// togles the main save button
		if ($('#save').is(':visible') ) {
			
			$('#save').hide();
		
		} else {

			$('#save').show();
		}

		
		
		event.preventDefault();
	});


	$('#add-image-button').click(function(event){
		$('#add-image-field-input').toggle();
		$('#ok-add-image-button').toggle();
		$('#upload-image-button').toggle();
		
		// Hide or not the save buttons that shouldn't be pressed if editing DB rows
		if ($('#save').is(':visible') && $('#add-image-field-input').is(':visible') ) {
			
			$('#save').hide();
			$('#affiliate-text').hide();
			$('#affiliate-link').hide();
			$('#edit-button').hide();
			$('#id-to-edit').show();
		
		} else {
			
		 	$('#save').show();
		 	$('#affiliate-text').show();
		 	$('#affiliate-link').show();
			$('#edit-button').show();
			$('#id-to-edit').hide();
		}

	});

	/* Toggles welcome screen... */
	$('#hide-button').click(function(){
		console.log('clicked');
		$('#main-options-page-welcome-panel').toggle();
		
		if ($('#main-options-page-welcome-panel').is(':visible')) {

			$('#show-more-info').hide();
			$('#hide-more-info').show();

		} else {
			
			$('#show-more-info').show();
			$('#hide-more-info').hide();
		}


		/* ...and save state of the toggle on page refresh (1/2). CSS modifiers. */
		localStorage.setItem('display', $('#main-options-page-welcome-panel').is(':visible'));

	});
	/* ...and save state of the toggle on page refresh (2/2). CSS modifiers. */
	var block = localStorage.getItem('display');
	if (block == 'true') {
		$('#main-options-page-welcome-panel').show();
	}

	/* Display thickbox when click on upload image */
	formfield = null;
	$('#upload-image-button').click(function(){
		
		$('html').addClass('Image');
		tb_show('', 'media-upload.php?type=file&amp;TB_iframe=true');
		
		return false;
	});

});
