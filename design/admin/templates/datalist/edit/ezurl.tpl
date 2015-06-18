{def $base = concat( 'nxc-data-list-edit-', $node.node_id, '-', $attribute.contentclass_attribute_identifier )}

<div class="block">
	<label for="{$base}-url">{'URL'|i18n( 'design/standard/content/datatype' )}:</label>
	<input id="{$base}-url" type="text" size="40" name="nxc-data-list-edit[{$node.node_id}][{$attribute.contentclass_attribute_identifier}][url]" value="{$attribute.content|wash( xhtml )}" {if $attribute.contentclass_attribute.is_required}class="nxc-datalist-edit-reuired"{/if} />
</div>

<div class="block">
	<label for="{$base}-text">{'Text'|i18n( 'design/standard/content/datatype' )}:</label>
	<input id="{$base}-text" type="text" size="40" name="nxc-data-list-edit[{$node.node_id}][{$attribute.contentclass_attribute_identifier}][text]" value="{$attribute.data_text|wash( xhtml )}" {if $attribute.contentclass_attribute.is_required}class="nxc-datalist-edit-reuired"{/if} />
</div>

{undef $base}
