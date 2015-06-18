window.addEvent( 'domready', function() {
	if( $type( document.id( 'datalist-list-nodes-table-body' ) ) != 'element' ) {
		return false;
	}

	var options  = {
		'navigationBlocks'   : [ 'paginator-navigation-pages' ],
		'quantityBlocks'     : [ 'paginator-items-per-page' ],
		'possbileQuantities' : [ 10, 25, 50, 100, 150, 200, 500, 1000 ],
		'defaultQuantity'    : 50
	};
	var paginator = new NXC.Paginator.AJAX(
		document.getElement( 'input[name="datalist_get_nodes_url"]' ).get( 'value' ),
		'datalist-list-nodes-table-body',
		document.getElement( 'input[name="datalist_nodes_count"]' ).get( 'value' ),
		options
	);
	paginator.build();

	var wrapper      = document.id( 'datalist-list-nodes-wrapper' );
	var tableWrapper = document.id( 'datalist-list-nodes-table-wrapper' );
	var loader       = document.id( 'datalist-list-nodes-loader' );
	paginator.addEvent( 'onStartShowPage', function() {
		wrapper.setStyle( 'height', tableWrapper.getStyle( 'height' ).toInt() );
		tableWrapper.setStyle( 'display', 'none' );
		loader.setStyle( 'display', 'block' );
		new Fx.Scroll( window ).toElement( document.id( 'paginator-items-per-page' ) );
	} );
	paginator.addEvent( 'onCompleteShowPage', function() {
		wrapper.setStyle( 'height', 'auto' );
		tableWrapper.setStyle( 'display', 'block' );
		loader.setStyle( 'display', 'none' );
	} );

	window.messageStack = ( $type( window.messageStack ) === false ) ? new NXC.MessageStack() : window.messageStack;

	var toggler = document.id( 'datalist-toggle-selected-nodes' );
	toggler.addEvent( 'click', function( e ) {
		e.stop();
		var checkboxes = document.getElements( '#datalist-list-nodes-table tbody tr td.datalist-checkboxes input[type=checkbox]' );
		checkboxes.each( function( el ) {
			el.set( 'checked', !el.get( 'checked' ) );
		} );
	} );

	var actionForm   = document.id( 'datalist-action-form' );
	var actionSelect = document.id( 'datalist-action-select' );
	var updateAction = actionSelect.getElement( 'option[value="/datalist/update"]' );
	actionForm.addEvent( 'submit', function( e ) {
		var currentActionIsUpdate =
			$type( updateAction ) == 'element'
			&& updateAction.get( 'value' ) == actionSelect.get( 'value' );
		var checkboxes = document.getElements( '#datalist-list-nodes-table tbody tr td.datalist-checkboxes input[type=checkbox]' );
		var error      = false;

		if( actionSelect.get( 'value' ) == -1 ) {
			window.messageStack.showMessage( window.messages.noSelectedAction, 'error' );
			error = true;
		}

		var selected = false;
		checkboxes.each( function( el ) {
			if( el.get( 'checked' ) == true ) {
				selected = true;
			}
		} );
		if( selected === false ) {
			window.messageStack.showMessage( window.messages.noSelectedCheckboxes, 'error' );
			error = true;
		}

		// Validate edit inputs, only for selected checkboxes
		if( error === false ) {
			document.getElements( '#datalist-list-nodes-table tbody tr .nxc-datalist-edit-error' ).removeClass( 'nxc-datalist-edit-error' );
			if( currentActionIsUpdate ) {
				var notValidInputs = 0;
				checkboxes.each( function( checkbox ) {
					if( checkbox.get( 'checked' ) !== true ) {
						return true;
					}

					var inputs = checkbox.getParent().getParent().getElements( '.nxc-datalist-edit-reuired' );
					inputs.each( function( el ) {
						if( el.get( 'value' ) == '' ) {
							el.addClass( 'nxc-datalist-edit-error' );
							notValidInputs++;
						}
					} );
				} );
				if( notValidInputs > 0 ) {
					document.getElements( '#datalist-list-nodes-table tbody tr .nxc-datalist-edit-error' )
						.removeEvent( 'focus' )
						.addEvent( 'focus', function( e ) {
							this.removeClass( 'nxc-datalist-edit-error' );
						} );
					window.messageStack.showMessage( window.messages.noValidEditInputs, 'error' );
					error = true;
				}
			}
		}

		var message = window.messages.confirmAction.replace(
			'%action',
			actionSelect.getSelected()[0].get( 'html' ).toLowerCase()
		);
		if( ( error === false ) && ( confirm( message ) == false ) ) {
			error = true;
		}

		if( error === true ) {
			e.stop();
		}

		/*
		we need to use POST in update actions, so that`s
		why we can not use datalist/action view
		*/
		if( currentActionIsUpdate ) {
			actionForm.set(
				'action',
				document.getElement( 'input[name="datalist_action_update"]' ).get( 'value' )
			);
		}
	} );

	var toggleEditIcon = document.id( 'datalist-edit-mode-icon' );
	toggleEditIcon.addEvent( 'click', function( e ) {
		e.stop();

		new Request.JSON( {
			'url': this.get( 'href' ),
			'method': 'get',
			'onSuccess': function( r ) {
				window.messageStack.showMessage( r.message, 'notice' );
				if( $type( updateAction ) == 'element' ) {
					updateAction.setStyle( 'display', r.data.editMode == 1 ? 'block' : 'none' );
				}
				paginator.showPage();
			}
		} ).send();
	} );
} );