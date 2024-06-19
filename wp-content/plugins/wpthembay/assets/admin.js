jQuery(document).ready(function ($) {
	var tbay_hide_shortcode_field = function() {
		var selected = jQuery('#tbay_block_type').val() || 'none';
		jQuery( '.tbay-options-table' ).removeClass().addClass( 'tbay-options-table widefat tbay-selected-template-type-' + selected );
	}

	jQuery(document).on( 'change', '#tbay_block_type', function( e ) {
		tbay_hide_shortcode_field();
	});

	tbay_hide_shortcode_field();
});
