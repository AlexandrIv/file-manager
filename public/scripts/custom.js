jQuery(document).ready(function () {
	var global_path;
	jQuery(document).on('click', '.folder', function(e){
		e.preventDefault();
		var folder = $(this).attr('href');
		global_path = folder;
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: {
				'path' : folder,
				'type': 'folder',
			},
			success: function(result) {
				$('.content').html(result);
				$('.return').attr('href', global_path);
			},
		});
	});

	jQuery(document).on('click', '.file', function(e){
		e.preventDefault();
		var file = $(this).attr('href');
		global_path = file;
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: {
				'path' : file,
				'type': 'file',
			},
			success: function(result) {
				$('.content').html(result);
				$('.return').attr('href', global_path);
			},
		});
	});

	jQuery(document).on('click', '.return', function(e){
		e.preventDefault();
		if(!global_path) {
			alert('Вы в корневой папке');
		} else {
			global_path = $(this).attr('href');
			$.ajax({
				type: 'POST',
				url: $(this).attr('action'),
				data: {
					'path' : global_path,
					'type': 'return',
				},
				success: function(result) {
					$('.content').html(result);
					global_path = $('.current_path').val();
					$('.return').attr('href', global_path);
				},
			});
		}
		
	});

	$(document).on('submit', '#save', function(event) {
		event.preventDefault();
		$.ajax({
			type: $(this).attr('method'),
			url: $(this).attr('action'),
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData: false,
			success: function(status) {
				if (status) {
					alert("Фаил успешно сохранен");
				}
			},
		});
	});

	function edit_save() {
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
				if (result) {
					alert("Фаил успешно сохранен");
				}
			},
		});
	}


	$(function() {
		$.contextMenu({
			selector: '.context', 
			callback: function(key, options) {
				switch(key) {
					case 'edit':
					edit(options);
					break;
					case 'cut':
					cut(options);
					break;
					case 'copy':
					copy(options);
					break;
					case 'paste':
					paste(options);
					break;
					case 'delete':
					delet(options);
					break;
					default:
					return 'context-menu-icon context-menu-icon-quit';
				}
			},
			items: {
				"edit": {name: "Edit", icon: "edit"},
				"cut": {name: "Cut", icon: "cut"},
				copy: {name: "Copy", icon: "copy"},
				"paste": {name: "Paste", icon: "paste"},
				"delete": {name: "Delete", icon: "delete"},
				"sep1": "---------",
				"quit": {name: "Quit", icon: function(){
					return 'context-menu-icon context-menu-icon-quit';
				}}
			}
		});
	});


	function edit(options) {
		file = options.$trigger.attr("href");
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
	}

	function cut(options) {
		copy_href = options.$trigger.attr("href");
		console.log(copy_href);
		localStorage.setItem('copy_href', copy_href);
		localStorage.setItem('type', 'cut');
	}

	function copy(options) {
		copy_href = options.$trigger.attr("href");
		console.log(copy_href);
		localStorage.setItem('copy_href', copy_href);
		localStorage.setItem('type', 'copy');
	}

	function paste(cut_path) {
		var type = localStorage.getItem('type');
		var copy_href = localStorage.getItem('copy_href');
		var past_href = global_path;
		console.log(copy_href);
		console.log(past_href);
		$.ajax({
			type: 'POST',
			url: '/past',
			data: {
				'copy_href' : copy_href,
				'past_href' : past_href,
				'type' : type,
			},
			success: function(status) {
				if(status) {
					$.ajax({
						type: 'POST',
						url: $(this).attr('action'),
						data: {
							'path' : past_href,
							'type': 'folder',
						},
						success: function(result) {
							$('.content').html(result);
							localStorage.removeItem("copy_href");
							localStorage.removeItem("type");
						},
					});
				} else {
					alert('Не удалось копировать фаил!');
				}
			},
		});
	}

	function delet(options) {
		href = options.$trigger.attr("href");
		$.ajax({
			type: 'POST',
			url: '/delete',
			data: {
				'href' : href,
			},
			success: function(status) {
				console.log(status);
				if(status) {
					$.ajax({
						type: 'POST',
						url: $(this).attr('action'),
						data: {
							'path' : global_path,
							'type': 'folder',
						},
						success: function(result) {
							$('.content').html(result);
						},
					});
				} else {
					alert('Не удалось удалить фаил!');
				}
			},
		});
	}

});











