<script type="text/javascript">
jQuery(function ($) {
	$(document).ready(function() {
		$('#dialog-delete-confirmation').dialog({
			autoOpen: false,
			resizable: false,
			width: 250,
			height: 160,
			modal: true,
			buttons: {
				'Excluir': function() {
					$( '#frmGrid' ).submit();
				},
				'Cancelar': function() {
					$( this ).dialog( 'close' );
				}
			}
		});
		$( '#dialog-no-rows-selected' ).dialog({
			autoOpen: false,
			modal: true,
			resizable: false,
			width: 345,
			buttons: {
				'Ok': function() {
					$(this).dialog('close');
				}
			}
		});
		$( 'input.delete-confirmation' ).click(function(e) {
			e.preventDefault();
			if ($('#frmGrid input:checkbox:checked').length > 0)
				$('#dialog-delete-confirmation').dialog('open');
			else
				$('#dialog-no-rows-selected').dialog('open');
		});
		$('tr.just_updated_row').effect('highlight', {}, 5000);
		$('tr.just_inserted_row').effect('highlight', {}, 5000);
	});
});
</script>
<div id="dialog-delete-confirmation" class="dialog-ui" title="Excluir Registros" style="display:none;">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>Confirma exclusão?</p>
</div>
<div id="dialog-no-rows-selected" class="dialog-ui" title="Erro" style="display:none;">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>Nenhum registro selecionado para exclusão.</p>
</div>