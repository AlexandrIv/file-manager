jQuery(document).ready(function () {
	var gobal_path;
	jQuery(document).on('click', '.folder', function(e){
		e.preventDefault();
		var folder = $(this).attr('href');
		gobal_path = folder;
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: {
				'folder' : folder,
			},
			success: function(result) {

				$('.content').html(result);
			},
		});
	});

	jQuery(document).on('click', '.file', function(e){
		e.preventDefault();
		var file = $(this).attr('href');
		gobal_path = file;
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: {
				'file' : file,
			},
			success: function(result) {
				$('.content').html(result);
			},
		});
	});

	jQuery(document).on('click', '.return', function(e){
		e.preventDefault();
		var path = $(this).attr('href');
		if (gobal_path) {
			var path = gobal_path;
		}
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: {
				'path' : path,
			},
			success: function(result) {
				$('.content').html(result);
			},
		});
	});


	jQuery(document).on('click', '#save', function(event) {
		var json;
		event.preventDefault();
		$.ajax({
			type: $(this).attr('method'),
			url: $(this).attr('action'),
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData: false,
			success: function(result) {
				json = jQuery.parseJSON(result);
				if (json.url) {
					window.location.href = '/' + json.url;
				} else {
					alert(json.status + ' - ' + json.message);
				}
			},
		});
	});

	jQuery(document).on('click', '.copy', function(e) {
		e.preventDefault();
		$('.copy-input').toggleClass('show');
	});



	$(document).on('change', '.copy-input', function () {
		var checklist = [];
		$('.table > .copy-input[type="checkbox"]').each(function (i) {
			console.log($(this).data('link'));
		});

	});



});




