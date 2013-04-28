//Define the default modal behaviour handler class
var handlers = {};

var Modal = {
	paths: undefined,
	_title: 'Manage',
	modal: undefined,
	_width: null,
	_setup: function(path, modal){
		return false;
	},
	init: function(modal) {
		this.modal = modal;
		this.paths = this.modal.find("#data-sources").data();

		this.modal.find('.modal-header > h3').text(this._title);

		var options = {};

		if(this._width != null)
		{
			options.width = this._width;
		}

		this.modal.modal(options);

		this._setup(this.paths, this);
	},
	openModalStack: function(modal) {
		modal.modal({replace: false, modalOverflow: true, focusOn: 'input:first'});
	},
	initTabs: function(tabEl) {
		tabEl.find('a').click(function (e) {
			e.preventDefault();
			$(this).tab('show');
		});
	}
};

$(document).ready(function () {
	//register the modals
	var $modal = $('#modal-ajax');

	$('.modal-ajax').on('click', function(e){
		e.preventDefault();
		// create the backdrop and wait for next modal to be triggered
		$('body').modalmanager('loading');

		var handle = $(this);
		setTimeout(function(){
			$modal.load(handle.attr('href'), '', function(){
				//$modal.modal();
				handlers[handle.data('handler')].init($modal);
			});
		}, 1000);
	});

	//change user password
	$('#input-password').click(function(e){
		e.preventDefault();
		var path = {};
		// create the backdrop and wait for next modal to be triggered
		$('body').modalmanager('loading');
		$modal.load($(this).attr('href'), function(){
			path = $modal.find("#data-sources").data();
			$modal.modal();
			$('#modal-password-complete').click(function(e){
				e.preventDefault();

				var values = $('#form-password').serialize();
				$modal.modalmanager('loading');

				$.post(path.save, values, function(d,s,x){
					$modal.modalmanager('removeLoading');
					if(d.status == 'success') {
						var msg = {message: 'Password changed successfully', type: 'success'};
					}
					else {
						var msg = {message:'Error changing password: '+d.errors.join(','), type: 'danger'}
					}
					$modal.one('hidden', function(){
						$('#crud-notify').notify(msg).show();
					});
					$modal.modal('hide');
				});
			});
		});
	});

	//Handle user roles
	SelectBox.init('input-roles-list');
	SelectBox.init('input-roles');

	$('#role_in').click(function(e){
		e.preventDefault();
		$('#input-roles-list option:selected').each(function(){
			//remove the role from the select list
			SelectBox.move('input-roles-list', 'input-roles');
		});
	});
	$('#role_out').click(function(e){
		e.preventDefault();
		$('#input-roles option:selected').each(function(){
			SelectBox.move('input-roles', 'input-roles-list');
		});
	});

	$('#form-user-save').click(function(e){
		$('#input-roles option').each(function(){
			$(this).attr('selected', true);
		})
	});

	//remove all the assigned roles
	$('#input-roles-select').click(function(e){
		e.preventDefault();
		SelectBox.move_all('input-roles', 'input-roles-list');
	});

	//move all the available roles
	$('#input-roles-list-select').click(function(e){
		e.preventDefault();
		SelectBox.move_all('input-roles-list', 'input-roles');
	});


	//Filter all available roles
	$('#input-roles-filter').keyup(function(event){
		var from = document.getElementById('input-roles-list');
		// don't submit form if user pressed Enter
		if ((event.which && event.which == 13) || (event.keyCode && event.keyCode == 13)) {
			from.selectedIndex = 0;
			SelectBox.move('input-roles-list', 'input-roles');
			from.selectedIndex = 0;
			return false;
		}
		var temp = from.selectedIndex;
		SelectBox.filter('input-roles-list', document.getElementById('input-roles-filter').value);
		from.selectedIndex = temp;
		return true;
	});
	$('#input-roles-filter').keydown(function(event){
		var from = document.getElementById('input-roles-list');
		// right arrow -- move across
		if ((event.which && event.which == 39) || (event.keyCode && event.keyCode == 39)) {
			var old_index = from.selectedIndex;
			SelectBox.move('input-roles-list', 'input-roles');
			from.selectedIndex = (old_index == from.length) ? from.length - 1 : old_index;
			return false;
		}
		// down arrow -- wrap around
		if ((event.which && event.which == 40) || (event.keyCode && event.keyCode == 40)) {
			from.selectedIndex = (from.length == from.selectedIndex + 1) ? 0 : from.selectedIndex + 1;
		}
		// up arrow -- wrap around
		if ((event.which && event.which == 38) || (event.keyCode && event.keyCode == 38)) {
			from.selectedIndex = (from.selectedIndex == 0) ? from.length - 1 : from.selectedIndex - 1;
		}
		return true;
	});
});
var SelectBox = {
	cache: new Object(),
	init: function(id) {
		var box = document.getElementById(id);
		var node;
		SelectBox.cache[id] = new Array();
		var cache = SelectBox.cache[id];
		for (var i = 0; (node = box.options[i]); i++) {
			cache.push({value: node.value, text: node.text, displayed: 1});
		}
	},
	redisplay: function(id) {
		// Repopulate HTML select box from cache
		var box = document.getElementById(id);
		box.options.length = 0; // clear all options
		for (var i = 0, j = SelectBox.cache[id].length; i < j; i++) {
			var node = SelectBox.cache[id][i];
			if (node.displayed) {
				box.options[box.options.length] = new Option(node.text, node.value, false, false);
			}
		}
	},
	filter: function(id, text) {
		// Redisplay the HTML select box, displaying only the choices containing ALL
		// the words in text. (It's an AND search.)
		var tokens = text.toLowerCase().split(/\s+/);
		var node, token;
		for (var i = 0; (node = SelectBox.cache[id][i]); i++) {
			node.displayed = 1;
			for (var j = 0; (token = tokens[j]); j++) {
				if (node.text.toLowerCase().indexOf(token) == -1) {
					node.displayed = 0;
				}
			}
		}
		SelectBox.redisplay(id);
	},
	delete_from_cache: function(id, value) {
		var node, delete_index = null;
		for (var i = 0; (node = SelectBox.cache[id][i]); i++) {
			if (node.value == value) {
				delete_index = i;
				break;
			}
		}
		var j = SelectBox.cache[id].length - 1;
		for (var i = delete_index; i < j; i++) {
			SelectBox.cache[id][i] = SelectBox.cache[id][i+1];
		}
		SelectBox.cache[id].length--;
	},
	add_to_cache: function(id, option) {
		SelectBox.cache[id].push({value: option.value, text: option.text, displayed: 1});
	},
	cache_contains: function(id, value) {
		// Check if an item is contained in the cache
		var node;
		for (var i = 0; (node = SelectBox.cache[id][i]); i++) {
			if (node.value == value) {
				return true;
			}
		}
		return false;
	},
	move: function(from, to) {
		var from_box = document.getElementById(from);
		var to_box = document.getElementById(to);
		var option;
		for (var i = 0; (option = from_box.options[i]); i++) {
			if (option.selected && SelectBox.cache_contains(from, option.value)) {
				SelectBox.add_to_cache(to, {value: option.value, text: option.text, displayed: 1});
				SelectBox.delete_from_cache(from, option.value);
			}
		}
		SelectBox.redisplay(from);
		SelectBox.redisplay(to);
	},
	move_all: function(from, to) {
		var from_box = document.getElementById(from);
		var to_box = document.getElementById(to);
		var option;
		for (var i = 0; (option = from_box.options[i]); i++) {
			if (SelectBox.cache_contains(from, option.value)) {
				SelectBox.add_to_cache(to, {value: option.value, text: option.text, displayed: 1});
				SelectBox.delete_from_cache(from, option.value);
			}
		}
		SelectBox.redisplay(from);
		SelectBox.redisplay(to);
	},
	sort: function(id) {
		SelectBox.cache[id].sort( function(a, b) {
			a = a.text.toLowerCase();
			b = b.text.toLowerCase();
			try {
				if (a > b) return 1;
				if (a < b) return -1;
			}
			catch (e) {
				// silently fail on IE 'unknown' exception
			}
			return 0;
		} );
	},
	select_all: function(id) {
		var box = document.getElementById(id);
		for (var i = 0; i < box.options.length; i++) {
			box.options[i].selected = 'selected';
		}
	}
}