{def $base = concat( 'nxc-data-list-edit-', $node.node_id, '-', $attribute.contentclass_attribute_identifier )}
<div class="nxc-datalist-edit-time">
	<div class="element">
		<label for="{$base}-hour">{'Hour'|i18n( 'design/standard/content/datatype' )}:</label>
		<input id="{$base}-hour" type="text" name="nxc-data-list-edit[{$node.node_id}][{$attribute.contentclass_attribute_identifier}][hour]" size="3" value="{if $attribute.content.is_valid}{$attribute.content.hour}{/if}" class="nxc-datalist-edit-integer{if $attribute.contentclass_attribute.is_required} nxc-datalist-edit-reuired{/if}" />
	</div>

	<div class="element">
		<label for="{$base}-minute">{'Minute'|i18n( 'design/standard/content/datatype' )}:</label>
		<input id="{$base}-minute" type="text" name="nxc-data-list-edit[{$node.node_id}][{$attribute.contentclass_attribute_identifier}][minute]" size="3" value="{if $attribute.content.is_valid}{$attribute.content.minute}{/if}" class="nxc-datalist-edit-integer{if $attribute.contentclass_attribute.is_required} nxc-datalist-edit-reuired{/if}" />
	</div>

	{if $attribute.contentclass_attribute.data_int2|eq(1)}
		<div class="element">
			<label for="{$base}-second">{'Second'|i18n( 'design/standard/content/datatype' )}:</label>
		<input id="{$base}-second" type="text" name="nxc-data-list-edit[{$node.node_id}][{$attribute.contentclass_attribute_identifier}][second]" size="3" value="{if $attribute.content.is_valid}{$attribute.content.second}{/if}" class="nxc-datalist-edit-integer{if $attribute.contentclass_attribute.is_required} nxc-datalist-edit-reuired{/if}" />
	</div>
	{/if}
</div>
{undef $base}
