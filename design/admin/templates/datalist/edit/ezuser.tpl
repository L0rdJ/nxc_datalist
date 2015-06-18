{def $base = concat( 'nxc-data-list-edit-', $node.node_id, '-', $attribute.contentclass_attribute_identifier )}

<div class="block">
	<div class="element">
		<label for="{$base}-login">{'Username'|i18n( 'design/standard/content/datatype' )}:</label>
		<input id="{$base}-login" class="nxc-datalist-edit-reuired" type="text" name="nxc-data-list-edit[{$node.node_id}][{$attribute.contentclass_attribute_identifier}][login]" size="16" value="{$attribute.content.login}" />
	</div>

	{* Email. *}
	<div class="element">
		<label for="{$base}-email">{'Email'|i18n( 'design/standard/content/datatype' )}:</label>
		<input id="{$base}-email" class="nxc-datalist-edit-reuired nxc-datalist-edit-email" type="text" name="nxc-data-list-edit[{$node.node_id}][{$attribute.contentclass_attribute_identifier}][email]" size="28" value="{$attribute.content.email|wash( xhtml )}" />
	</div>
</div>

{undef $base}
