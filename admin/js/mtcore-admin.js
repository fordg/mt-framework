window.CMB2 = window.CMB2 || {};

(function(window, document, $, cmb, undefined) {

	'use strict';

	cmb.adjust_wysiwyg_container_size = function () {
		if ( $('.cmb-type-wysiwyg').length < 1 ) {
			return;
		}
		$('.cmb-type-wysiwyg').each( function() {
			var wrapper		= $(this).parents('.hidden-parts-fields'),
					wrapperW	= $(this).closest('.inside').width() - 20;
			wrapper.width(wrapperW);
			$('.cmb-th', wrapper).width(wrapperW-20);
			$('.cmb-td', wrapper).width(wrapperW-20);
		});
	};

  cmb.change_condition_required = function ( parent ) {
    var current = $( parent ),
        value   = current.val(),
        type    = current.attr('type'),
        data_id = current.attr('id').replace(/(_wds_builder_template_|_wds_builder_page_builder_header_template_|_wds_builder_layout_template_)(\d)_/, ''),
        wrapper = current.closest('.cmb-repeatable-grouping'),
        repeat  = wrapper.data('iterator'),
        childs  = $('[data-required-child]', wrapper).filter( function () {
          return $(this).data('required-child') == data_id;
        }),
        operators = {
          '=': function(a, b) { return a == b },
          '!': function(a, b) { return a != b },
        };

    if ( type == 'checkbox' ) {
      value = current.is(':checked') ? 'true' : 'false';
    }

    childs.each( function () {
      var op = ( $(this).attr('data-required-reverse') ) ? '!' : '=',
          el = $(this).closest('.hidden-parts-fields').length ?
               $(this).closest('.hidden-parts-fields') : $(this).closest('.cmb-row:not(.cmb-repeat-row)');
      el.css('display', '');
      if ( operators[op]( $(this).data('required-value'), value ) && ! el.hasClass( 'hidden' ) ) {
        el.css('display', 'inline-block');
      } else {
        el.css('display', 'none');
      }
    });
  };

  // custom conditional show/hide field
  cmb.conditional_required_fields = function () {
    if ( $('input[data-required-parent], select[data-required-parent]').length < 1 ) {
			return;
		}
    var parent = $('input[data-required-parent], select[data-required-parent]');

    parent.each( function ( i, e ) {

      var ids = $( e ).attr('id');

      $('body').on( 'change', '#'+ids, function () {
        cmb.change_condition_required( '#'+ids );
      });

      $('body').on( 'change', '.wds-simple-page-builder-template-select', function () {
        $( '#'+ids ).trigger( 'change' );
      });

      $( '#'+ids ).trigger( 'change' );
    });
  };

	$(document).ready( function() {
    var $metabox     = cmb.metabox();
		var $repeatGroup = $metabox.find('.cmb-repeatable-group');
		cmb.adjust_wysiwyg_container_size();
		cmb.conditional_required_fields();
    if ( $repeatGroup.length ) {
      $repeatGroup.on( 'cmb2_add_row', function () {
        cmb.adjust_wysiwyg_container_size();
    		cmb.conditional_required_fields();
      });
    }
	});

	// Bind event listener
  $(window).on( 'load', cmb.conditional_required_fields );
	$(window).on( 'resize', cmb.adjust_wysiwyg_container_size );
	$(document).on( 'postbox-toggled', cmb.adjust_wysiwyg_container_size );

})(window, document, jQuery, CMB2);
