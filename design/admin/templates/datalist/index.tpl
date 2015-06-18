<div class="context-block datalist-two-columns">

	{include uri='design:datalist/current_filters.tpl' data_list_settings=$data_list_settings}

	<div class="datalist-my-filters-container">

		{include uri='design:datalist/my_filters.tpl' all_filters=$all_filters current_filter=$current_filter data_list_settings=$data_list_settings}

		{include uri='design:datalist/additional_columns.tpl' data_list_settings=$data_list_settings additional_attribute_group=$additional_attribute_group}

	</div>

	<div class="break"></div>

</div>

<div class="context-block">
	<form name="datalist_action_form" id="datalist-action-form" method="post" action="{'datalist/action'|ezurl( 'no' )}">

		<input type="hidden" name="datalist_get_nodes_url" value="{concat( 'datalist/ajax_get_nodes/', $data_list_settings.content_object_class.id, '/%offset%/%limit%' )|ezurl( 'no' )}"/>
		<input type="hidden" name="datalist_nodes_count" value="{$nodes_count}" />
		<input type="hidden" name="datalist_action_update" value="{'/datalist/update'|ezurl( 'no' )}"/>
		<input type="hidden" name="RedirectURIAfterPublish" value="/datalist/list/{$data_list_settings.content_object_class.identifier}" />
		<input type="hidden" name="RedirectIfDiscarded" value="/datalist/list/{$data_list_settings.content_object_class.identifier}" />

		<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
			<h1 class="context-title">
				{$data_list_settings.title} [{$nodes_count}]
				<a id="datalist-export-icon" href="{concat( 'datalist/export/', $data_list_settings.content_object_class.id )|ezurl( 'no' )}" title="{'Export'|i18n( 'design/admin/node/view/full' )}" target="_blank"><img  src="{'icons/print.gif'|ezimage( 'no' )}" alt="{'Export'|i18n( 'design/admin/node/view/full' )}" title="{'Export'|i18n( 'design/admin/node/view/full' )}" /></a>
				<a id="datalist-edit-mode-icon" href="{concat( 'datalist/ajax_toggle_edit_mode/', $data_list_settings.content_object_class.id )|ezurl( 'no' )}" title="{'Toggle edit mode'|i18n( 'extension/datalist' )}" target="_blank"><img  src="{'icons/edit.gif'|ezimage( 'no' )}" alt="{'Toggle edit mode'|i18n( 'extension/datalist' )}" title="{'Toggle edit mode'|i18n( 'extension/datalist' )}" /></a>
			</h1>
			<div class="header-subline"></div>
		</div></div></div></div></div></div>


		<div class="box-ml"><div class="box-mr"><div class="box-content">

			<div class="context-toolbar">
				<div class="block">
					<div class="left">
						<p id="paginator-items-per-page"></p>
					</div>
					<div class="break"></div>
				</div>
			</div>

			<div class="content-navigation-childlist">

				{if gt( $nodes_count, 0 )}
					<div id="datalist-list-nodes-wrapper">

						<div id="datalist-list-nodes-table-wrapper" style="display: none;">
							<table id="datalist-list-nodes-table" class="list" cellspacing="0" cellpadding="0">

								<thead>
									<tr>
										<th class="datalist-checkboxes"><img id="datalist-toggle-selected-nodes" src="{'icons/toggle.gif'|ezimage( 'no' )}" alt="{'Invert selection.'|i18n( 'design/admin/node/view/full' )}" title="{'Invert selection.'|i18n( 'design/admin/node/view/full' )}" /></th>
										{foreach $viewable_attributes as $viewable_attribute}
										<th class="{$viewable_attribute.identifier}">{$viewable_attribute.name}</th>
										{/foreach}
										<th class="datalist-edit">&nbsp;</th>
									</tr>
								</thead>

								<tbody id="datalist-list-nodes-table-body"></tbody>

							</table>
						</div>

						<img id="datalist-list-nodes-loader" alt="{'Loading...'|i18n( 'extension/datalist' )}" title="{'Loading...'|i18n( 'extension/datalist' )}" src="{'datalist-loader.gif'|ezimage( 'no' )}" width="220" />

					</div>
				{else}
					<p>{'There are no %class_name satisfying the filter requirements.'|i18n( 'extension/datalist', , hash( '%class_name', $data_list_settings.title ) )}</p>
				{/if}

			</div>

			<div class="context-toolbar">
				<div class="paginator">
					<div id="paginator-navigation-pages"></div>
					<div class="break"></div>
				</div>
			</div>

		</div></div></div>

		<div class="controlbar">
			<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
				<div class="block">
					{if gt( $nodes_count, 0 )}
					<select name="datalist_action_select" id="datalist-action-select">
						<option value="-1">{'- Not selected -'|i18n( 'extension/datalist' )}</option>
						{foreach $data_list_settings.multiple_actions as $action => $title}
							<option value="{$action}" {if and( eq( $edit_mode, false() ), eq( $action, '/datalist/update' ) )}style="display: none;"{/if}>{$title}</option>
						{/foreach}
					</select>
					<input type="submit" name="datalist_apply_action" value="{'Apply action'|i18n( 'extension/datalist' )}" class="button" />
					{/if}
				</div>
			</div></div></div></div></div></div>
		</div>
	</form>

	{if gt( $nodes_count, 0 )}
		<script type="text/javascript">
		{literal}
		window.messages = {
			'noSelectedCheckboxes' : '{/literal}{'Select at least one node'|i18n( 'extension/datalist' )}{literal}',
			'noSelectedAction'     : '{/literal}{'Select some action'|i18n( 'extension/datalist' )}{literal}',
			'confirmAction'        : '{/literal}{'Do you really want to %action selected nodes?'|i18n( 'extension/datalist' )}{literal}',
			'noValidEditInputs'    : '{/literal}{'Please check input fields'|i18n( 'extension/datalist' )}{literal}'
		};
		{/literal}
		</script>
		{ezscript_require( array( 'nxc.datalist.js' ) )}
	{/if}
</div>

{foreach $data_list_settings.additional_action_templates as $action_template}
	{include uri=concat( 'design:datalist/additional_actions/', $action_template, '.tpl') data_list_settings=$data_list_settings}
{/foreach}
