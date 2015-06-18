<!-- additional columns -->
{if gt( $data_list_settings.attribute_groups|count(), 1 )}
<form method="post" action="" name="datalist_additional_attribute_group_form" id="datalist-additional-attribute-group-form">
	<div class="box-header additional-columns-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
		<h1 class="context-title">{'Additional columns'|i18n( 'extension/datalist' )}</h1>
		<div class="header-mainline"></div>
	</div></div></div></div></div></div>

	<div class="box-ml"><div class="box-mr"><div class="box-content">
		<div class="context-toolbar"><div class="break"></div></div>

		<div class="additional-attributes-container">
			<select name="datalist_additional_attribute_group" id="datalist-additional-attribute-group">
				<option value="-1">-- None --</option>
				{foreach $data_list_settings.attribute_groups as $attribute_group}
					{if eq( $attribute_group.has_viewable_attribute, false() )}
						{continue}
					{/if}
					{if ne( $attribute_group.identifier, $data_list_settings.main_attribute_group )}
					<option value="{$attribute_group.identifier}" {if eq( $additional_attribute_group, $attribute_group.identifier)}selected="selected"{/if}>{$attribute_group.name}</option>
					{/if}
				{/foreach}
			</select>
		</div>

		<div class="context-toolbar"><div class="break"></div></div>
	</div></div></div>

	<div class="controlbar">
		<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
			<div class="block">
				<div class="left">
				</div>
				<div class="float-right">
					<input class="button" type="submit" value="{'Apply'|i18n( 'extension/datalist' )}" name="datalist_additional_attribute_group_apply" />
				</div>
				<div class="break"></div>
			</div>
		</div></div></div></div></div></div>
	</div>
</form>
{/if}
<!-- end additional columns -->