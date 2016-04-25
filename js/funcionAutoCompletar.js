$(function(){
		var patentes = [ 

		

		];

		// setup autocomplete function pulling from patentes[] array
		$('#autocomplete').autocomplete({
		lookup: patentes,
		onSelect: function (suggestion) {
		var thehtml = '<strong>patente: </strong> ' + suggestion.value + ' <br> <strong>ingreso: </strong> ' + suggestion.data;
		$('#outputcontent').html(thehtml);
		$('#botonIngreso').css('display','none');
			console.log('aca llego');
		}
		});


		});