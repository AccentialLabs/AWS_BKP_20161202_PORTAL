

	$(function(){

		
		/**
		*clique no botão para desativar plano
		**/
		$(".inactivePlan").click(function(){
				
				var planCode = $(this).attr("id");
				var funcao = 'inactivate';
				
				  $.ajax({
			method: "POST",
			url: '/jezzy-master/portal/MasterJezzySignatures/activeOrInactivePlan',
			data:{
				activeOrInactive:funcao,
				planCode: planCode
			}
		}).done(function(result) {

			location.reload(); 

		}).error(function (XMLHttpRequest, textStatus, errorThrown) {
			alert(errorThrown);
		});
		});
		
		
		/**
		*clique no botão para ativar plano
		**/
		$(".activePlan").click(function(){
		
			var planCode = $(this).attr("id");
			var funcao = 'activate';
			
			  $.ajax({
			method: "POST",
			url: '/jezzy-master/portal/MasterJezzySignatures/activeOrInactivePlan',
			data:{
				activeOrInactive:funcao,
				planCode: planCode
			}
		}).done(function(result) {

				location.reload(); 

		}).error(function (XMLHttpRequest, textStatus, errorThrown) {
			alert(errorThrown);
		});
		
		});

	});