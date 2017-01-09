
window.CMB2 = window.CMB2 || {};

(function(window, document, $, cmb, undefined) {

	'use strict';

	/* Deal with columns and sizes */
	cmb.adjust_element_size = function () {
		$('.link-picker div').attr('style','');
		$('.cmb-type-link-picker').each( function() {
			var wrapper		= $(this).closest('.hidden-parts-fields'),
					wrapperW	= $(this).closest('.inside').width() - 15;
			wrapper.width(wrapperW);
		});
	};

	$(document).ready( function() {

		/* Open the Link Window*/

		var url   = $('body');
		var text = $('body');
		var blank = $('body');

		$('body').on('click', '.js-insert-link', function(event) {
			event.preventDefault ? event.preventDefault() : event.returnValue = false;
			event.stopPropagation();
			url            = $(this).closest('.link-picker').find('input.cmb_text_url');
			text           = $(this).closest('.link-picker').find('input.cmb_text');
			blank          = $(this).closest('.link-picker').find('input.cmb_checkbox');
			wpActiveEditor = true;
			wpLink.open( text.attr('id') );
			wpLink.newattr = text.attr('id');
			wpLink.textarea = url;

			return false;
		});

		$('body').on('click', '#wp-link-cancel, #wp-link-backdrop, #wp-link-close', function(event) {

			wpLink.textarea = url;
			wpLink.close();
			event.preventDefault ? event.preventDefault() : event.returnValue = false;
			event.stopPropagation();
			return false;
		});

		$('body').on('click', '#wp-link-submit', function(event) {
			console.log(text)
			var linkAtts = wpLink.getAttrs();

			linkAtts.text = $('#wp-link-text').val();

			url.val(linkAtts.href);

			if( linkAtts.text != '' ) {
				text.val(linkAtts.text);
			}

			if (linkAtts.target == '_blank') {
				blank.prop('checked', true);
			} else {
				blank.prop('checked', false);
			}

			wpLink.textarea = url;
			wpLink.close();
			event.preventDefault ? event.preventDefault() : event.returnValue = false;
			event.stopPropagation();
			return false;
		});

    // Execute on load
    cmb.adjust_element_size();
    // Bind event listener
    $(window).resize( cmb.adjust_element_size );
		$(document).on( 'postbox-toggled', cmb.adjust_element_size );
	});

})(window, document, jQuery, CMB2);
