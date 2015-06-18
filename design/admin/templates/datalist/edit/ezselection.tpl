{def
	$selected_id_array = $attribute.content
	$options           = $attribute.class_content.options
}

<select name="nxc-data-list-edit[{$node.node_id}][{$attribute.contentclass_attribute_identifier}][]" {if $attribute.class_content.is_multiselect}multiple="multiple"{/if}>
{foreach $options as $option}
	<option value="{$option.name}" {if $selected_id_array|contains( $option.id )}selected="selected"{/if}>{$option.name|wash( xhtml )}</option>
{/foreach}
</select>

{undef $selected_id_array}
