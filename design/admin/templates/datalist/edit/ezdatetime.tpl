<script type="text/javascript">
{literal}
window.addEvent( 'domready', function() {
	var base    = '{/literal}nxc-data-list-edit-{$node.node_id}-{$attribute.contentclass_attribute_identifier}{literal}';
	var el      = document.id(  base );
	var picker  = document.id(  base + '-toggler' );
	var cleaner = document.id(  base + '-empty' );
	new DatePicker( el, {
		pickerClass: 'datepicker_vista',
		allowEmpty: true,
		format: 'd\.m\.Y H:i',
		timePicker: true,
		toggleElements: picker
	} );
	cleaner.addEvent( 'click', function( e ) {
		e.stop();
		el.set( 'value', '' ).getNext( 'input' ).set( 'value', '' );
	} );
} );
{/literal}
</script>
<div class="nxc-datalist-edit-datetime">
	<input class="datalist-filter-view-date" readonly="readonly" type="text" value="{if $attribute.has_content}{$attribute.content.timestamp}{/if}" name="nxc-data-list-edit[{$node.node_id}][{$attribute.contentclass_attribute_identifier}]" id="nxc-data-list-edit-{$node.node_id}-{$attribute.contentclass_attribute_identifier}" size="14" /> <img alt="{'Pop-up calendar'|i18n( 'extension/datalist' )}" title="{'Pop-up calendar'|i18n( 'extension/datalist' )}" src="{'datepicker/calendar.gif'|ezimage( 'no' )}" id="nxc-data-list-edit-{$node.node_id}-{$attribute.contentclass_attribute_identifier}-toggler" /> <img alt="{'Clear date'|i18n( 'extension/datalist' )}" title="{'Clear date'|i18n( 'extension/datalist' )}" src="{'datepicker/empty.png'|ezimage( 'no' )}" id="nxc-data-list-edit-{$node.node_id}-{$attribute.contentclass_attribute_identifier}-empty" class="datepicker-empty" />
</div>