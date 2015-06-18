{foreach $nodes as $node sequence array( 'bgdark', 'bglight' ) as $style}
<tr class="{$style}" id="datalist-node-{$node.node_id}">
	<td class="datalist-checkboxes">
		<input type="checkbox" name="datalist_action_nodes[]" value="{$node.node_id}" />
	</td>
	{foreach $viewable_attributes as $viewable_attribute}
	<td class="{$viewable_attribute.identifier}">{$node|datalist_view( $viewable_attribute )}</td>
	{/foreach}
	<td>
		<a class="datalist-edit-link" href="{concat( 'content/edit/', $node.contentobject_id )|ezurl( 'no' )}"><img src="{'icons/edit.gif'|ezimage( 'no' )}" alt="{'Edit'|i18n( 'extension/datalist' )}" title="{'Edit'|i18n( 'extension/datalist' )}" /></a>
	</td>
</tr>
{/foreach}

{* RedirectURIAfterPublish and RedirectIfDiscarded can`t be send through GET *}
<script type="text/javascript">
{literal}
window.addEvent( 'domready', function() {
	var form = document.id( 'datalist-action-form' );
	document.getElements( 'a.datalist-edit-link' ).addEvent( 'click', function( e ) {
		e.stop();
		form.set( 'action', this.get( 'href' ) );
		form.submit();
	} );
} );
{/literal}
</script>