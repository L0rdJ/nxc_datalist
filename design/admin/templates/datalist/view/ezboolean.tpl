{def $image_src = $attribute.data_int|choose( 'deactivate', 'activate' )}
<img class="datalist-boolean-attribute" id="datalist-boolean-attribute-{$node.node_id}-{$attribute.id}" src="{concat( 'icons/', $image_src, '.gif' )|ezimage( 'no' )}" />
{undef $image_src}

<script type="text/javascript">
{literal}
window.addEvent( 'domready', function() {
	var el      = document.id( 'datalist-boolean-attribute-{/literal}{$node.node_id}{literal}-{/literal}{$attribute.id}{literal}' );
	var options = {
		icons: {
			'active': '{/literal}{concat( 'icons/activate.gif' )|ezimage( 'no' )}{literal}',
			'deactive': '{/literal}{concat( 'icons/deactivate.gif' )|ezimage( 'no' )}{literal}'
		},
		ajaxURL: '{/literal}{concat( 'datalist/ajax_toggle_bool_attribute/', $node.node_id, '/', $attribute.contentclass_attribute_identifier )|ezurl( 'no' )}{literal}'
	};

	el.set( 'opacity', 1 );
	el.addEvent( 'click', function( e ) {
		e.stop();
		new Request.JSON( {
			'url'      : options.ajaxURL,
			'method'   : 'get',
			'onSuccess': function( response ) {
				if( response.status.toInt() === 1 ) {
					var icon = ( response.data.attributeNewValue.toInt() === 1 ) ? options.icons.active : options.icons.deactive;
					el.get( 'tween', { 'duration': 200 } ).start( 'opacity', 0.2 ).chain( function() {
						el.set( 'src', '' ).set( 'src', icon + '?' + Math.random() ).fade( 1 );
					} );
				} else {
					window.messageStack.showMessage( response.errors.toggle_bool_attribute, 'error' );
				}
			}
		} ).send();
	} );
} );
{/literal}
</script>