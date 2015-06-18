{def $children = fetch(
	'content', 'list', hash(
		'parent_node_id',     $node.node_id,
		'class_filter_type',  'include',
		'class_filter_array', array( $data_list_attribute.datalist_view_params.class ),
		'limitation',         array(),
		'depth',              false()
	)
) }

{if gt( count( $children ), 0 )}
	<ul>
	{foreach $children as $child}
		<li>{content_view_gui view=text_linked content_object=$child.object}</li>
	{/foreach}
	</ul>
{/if}

{undef $children}