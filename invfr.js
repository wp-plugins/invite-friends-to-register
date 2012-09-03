/* Invite Friends to Register JS */

jQuery(function(jQuery) {
	
	jQuery('.invfr_add').live('click', function() {
		// clone
		var row = jQuery('#invfr_form tr:last');
		var clone = row.clone();
		clone.find(':text').val('');
		row.after(clone);
		// increment name and id
		clone.find('input')
			.attr('name', function(index, name) {
				return name.replace(/(\d+)/, function(fullMatch, n) {
					return Number(n) + 1;
				});
			})
			.attr('id', function(index, name) {
				return name.replace(/(\d+)/, function(fullMatch, n) {
					return Number(n) + 1;
				});
			});
		//
		return false;
	});
	
	jQuery('.invfr_remove').live('click', function(){
		jQuery(this).closest('tr').remove();
		return false;
	});
	
});