/*
 * jQuery dform plugin
 * Copyright (C) 2012 David Luecke <daff@neyeon.com>, [http://daffl.github.com/jquery.dform]
 * 
 * Licensed under the MIT license
 */
(function($)
{
	var _getOptions = function(type, options)
		{
			return $.withKeys(options, $.keyset($.ui[type]["prototype"]["options"]));
		}

	$.dform.addType("bootstrap_tabs",
		/**
		 * Returns a container for jQuery UI tabs.
		 *
		 * @param options The options as in jQuery UI tab
		 */
		function(options)
		{
			return $("<div>").dform('attr', options);
		}, $.isFunction($.fn.tab));

	$.dform.subscribe('info', function(options, type){

		if($.inArray(type, ['select', 'text', 'number', 'password', 'radio', 'checkbox', 'file', 'url', 'tel', 'email', 'checkboxes', 'radiobuttons']))
			this.after('<div class="help-block">'+options+'</div>');
		else
			this.append('<div class="help-block">'+options+'</div>');
	});
	$.dform.subscribe("entries",
		/**
		 *  Create entries for the accordion type.
		 *  Use the <elements> subscriber to create subelements in each entry.
		 *
		 * @param options All options for the container div. The <caption> will be
		 * 	turned into the accordion or tab title.
		 * @param type The type. This subscriber will only run for accordion
		 */
		function(options, type) {
			if(type == "bootstrap_tabs")
			{
				var scoper = this;

				scoper.append("<ul>");
				scoper.append('<div>');

				var ul = $(scoper).children("ul:first").addClass('nav nav-tabs');
				var panes = $(scoper).children("div").addClass('tab-content');

				$.each(options, function(index, options) {
					var id = options.id ? options.id : index;

					$.extend(options, { "type" : "div", "id" : id, "class": 'tab-pane' });

					var link = $("<a>").attr("href", "#" + id).html(options.caption);

					if('help' in options) {
						if(options.help != '') {
							link.attr('title', options.help).data('toggle', 'tooltip').tooltip({container: 'body'});
						}
						delete options['help'];
					}
					var li = $('<li>').html(link);
					$(ul).append(li);

					delete options['caption'];
					$(panes).dform('append', options);


				});
			}
		}, $.isFunction($.fn.tab));

	$.dform.subscribe("[post]",
		/**
		 * Bootstrap_tabs elements will be initialized
		 * with their options.
		 *
		 * @param options All options that have been passed for creating the element
		 * @param type The type of the element
		 */
		function(options, type)
		{
			if(type === "bootstrap_tabs") {
				$('#'+options.id+' > ul > li:first').addClass('active');
				$('#'+options.id+' > .tab-content > div.tab-pane:first').addClass('active');

				$('#'+options.id+' a').click(function (e) {
					e.preventDefault();
					$(this).tab('show');
				});
			}
		});

})(jQuery);
