<script type="text/javascript">
jQuery(function ($) {
	$(document).ready(function() {
		$( '#dialog-must-fill-in-fields' ).dialog({
			autoOpen: false,
			resizable: false,
			width: 345,
			modal: true,
			buttons: {
				'Ok': function() {
					$( this ).dialog( 'close' );
				}
			}
		});

		$('div.tabs-ui').tabs();

		$('.must-fill-in').each(function() {
			if ($(this).val().trim() == '')
				$(this).addClass('must-fill-in-empty');
			else
				$(this).addClass('must-fill-in-ok');
		});

		$('input[type=text].must-fill-in, textarea.must-fill-in').keyup(function() {
			if ($(this).val().trim() != '') {
				$(this).addClass('must-fill-in-ok');
				$(this).removeClass('must-fill-in-empty');
			}
			else {
				$(this).addClass('must-fill-in-empty');
				$(this).removeClass('must-fill-in-ok');
			}
		});

		$('select.must-fill-in, input[type=file].must-fill-in').change(function() {
			if ($(this).val().trim() != '') {
				$(this).addClass('must-fill-in-ok');
				$(this).removeClass('must-fill-in-empty');
			}
			else {
				$(this).addClass('must-fill-in-empty');
				$(this).removeClass('must-fill-in-ok');
			}
		});

		$('input.must-fill-in-check').click(function(e) {
			$n_empty = 0;
			$('input.must-fill-in').each(function() {
				if ($(this).val().trim() == '')
					$n_empty++;
			});
			$('textarea.must-fill-in').each(function() {
				if ($(this).val().trim() == '')
					$n_empty++;
			});

			if ($n_empty > 0) {
				$('#dialog-must-fill-in-fields').dialog('open');
				e.preventDefault();
			}
		});

		$('form').submit(function() {
			$(this).find('input[type=submit]').prop('disabled', true);
		});
	});
});
</script>
<div id="dialog-must-fill-in-fields" class="dialog-ui" title="Erro" style="display:none;">
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>Preencha todos os <span class="must-fill-in-empty">campos obrigatórios</span>.</p>
</div>