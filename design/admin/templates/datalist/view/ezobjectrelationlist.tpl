{if gt( $attribute.content.relation_list|count(), 0 )}
<ul>
	{foreach $attribute.content.relation_list as $relation}
	{if $relation.in_trash|not()}
	<li>{content_view_gui view=text_linked content_object=fetch( content, object, hash( object_id, $relation.contentobject_id ) )}</li>
	{/if}
	{/foreach}
<ul>
{/if}