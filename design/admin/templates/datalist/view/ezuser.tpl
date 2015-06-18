<div class="element">
	{'Username'|i18n( 'design/standard/content/datatype' )}:{$attribute.content.login|wash( xhtml )}
</div>
<div class="element">
	{'Email'|i18n( 'design/standard/content/datatype' )}:<a href="mailto:{$attribute.content.email}">{$attribute.content.email}</a>
</div>
<div class="element">
	{'Account status'|i18n( 'design/admin/content/datatype/ezuser' )}:
	{if $attribute.content.is_enabled}
	<span class="userstatus-enabled">{'enabled'|i18n( 'design/standard/content/datatype' )}</span>
	{else}
	<span class="userstatus-disabled"> {'disabled'|i18n( 'design/standard/content/datatype' )}</span>
	{/if}
	{if $attribute.content.is_locked}
	(<span class="userstatus-disabled">{'locked'|i18n( 'design/standard/content/datatype' )}</span>)
	{/if}
</div>