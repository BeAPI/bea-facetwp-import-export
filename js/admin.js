jQuery(function($) {
	$( '#fwp-ie-select-all' ).click( function () {
		$( '#fwp-ie-form input[type="checkbox"]' ).prop('checked', this.checked)
	})
})