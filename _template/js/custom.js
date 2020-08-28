$(document).ready(function () {
	$('.subItemAtivo').addClass('active').closest('div.collapse').addClass('show');

	$('.dataTable').DataTable({
		"language": {
            "sEmptyTable": "Nenhum registro encontrado",
			"sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
			"sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
			"sInfoFiltered": "(Filtrados de _MAX_ registros)",
			"sInfoPostFix": "",
			"sInfoThousands": ".",
			"sLengthMenu": "_MENU_ resultados por página",
			"sLoadingRecords": "Carregando...",
			"sProcessing": "Processando...",
			"sZeroRecords": "Nenhum registro encontrado",
			"sSearch": "Pesquisar",
			"oPaginate": {
				"sNext": "Próximo",
				"sPrevious": "Anterior",
				"sFirst": "Primeiro",
				"sLast": "Último"
			},
			"oAria": {
				"sSortAscending": ": Ordenar colunas de forma ascendente",
				"sSortDescending": ": Ordenar colunas de forma descendente"
			},
			"select": {
				"rows": {
					"_": "Selecionado %d linhas",
					"0": "Nenhuma linha selecionada",
					"1": "Selecionado 1 linha"
				}
			},
			"buttons": {
				"copy": "Copiar para a área de transferência",
				"copyTitle": "Cópia bem sucedida",
				"copySuccess": {
					"1": "Uma linha copiada com sucesso",
					"_": "%d linhas copiadas com sucesso"
				}
			}
        },
		"initComplete": function(settings, json) {
			setTimeout(function(){
			var hddnTableId = $('#HDDN_JSON_TABLE_IDS');
			var jsonTableId = JSON.parse(hddnTableId.val());

			jQuery.each(jsonTableId, function(tbId, filterVal) {
				if(filterVal != ''){
					var input = $('#' + tbId).closest('.card-body').find('.dataTables_filter input')[0];
					if(typeof input !== 'undefined'){
						input.value = filterVal;
						$('#' + tbId).DataTable().search(input.value).draw();
					}
				}
			});
		}, 200);
		}
	}).on('search.dt', function () {
		// var input = $(this).closest('.dataTables_wrapper').find('.dataTables_filter input')[0];
		// console.log(input.value);
	});

	$('.table-link').click(function () {
		var homeUrl = $('body').data('base-url');
		var tbId    = $(this).closest('table').attr('id');
		var route   = $(this).data('route');

		if (route != '' && tbId != '') {
			var input  = $(this).closest('.dataTables_wrapper').find('.dataTables_filter input')[0];
			var action = homeUrl + route;
			var form = $("<form id='frmTableLink' mothod='post' action='" + action + "'></form>");
			
			form.append('<input type="hidden" name="hddnTableId" value="' + tbId + '" />');
			form.append('<input type="hidden" name="hddnTableFilter" value="' + input.value + '" />');
			$('body').append(form);

			// @todo: submit desse form manda as var hidden na URL
			// tentar tirar de lá
			setTimeout(function () {
				$('#frmTableLink').submit();
			}, 250);
		}
	});

	$('.table-link-confirm').click(function () {
		var homeUrl = $('body').data('base-url');
		var msg     = $(this).data('message');
		var route   = $(this).data('route');

		var html  = '<div id="modalTbLinkConfirm" class="modal fade" role="dialog">';
		html     += '  <div class="modal-dialog modal-dialog-centered" role="document">';
		html     += '    <div class="modal-content">';
		html     += '      <div class="modal-header">';
		html     += '        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>';
		html     += '        <button type="button" class="close" data-dismiss="modal" aria-label="Close">';
		html     += '          <span aria-hidden="true">&times;</span>';
		html     += '        </button>';
		html     += '      </div>';
		html     += '      <div class="modal-body">';
		html     += '    ' + msg;
		html     += '      </div>';
		html     += '      <div class="modal-footer">';
		html     += '        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>';
		html     += '        <button type="button" class="btn btn-primary" onClick="document.location.href=\''+homeUrl+route+'\'">Confirmar</button>';
		html     += '      </div>';
		html     += '    </div>';
		html     += '  </div>';
		html     += '</div>';

		$('body').find('#modalTbLinkConfirm').remove();
		$('body').append(html);
		setTimeout(function () {
			$('#modalTbLinkConfirm').modal('show');
		}, 200);
	});
});
