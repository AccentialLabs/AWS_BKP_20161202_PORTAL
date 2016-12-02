$(function(){

		$("#selectSecondUser").change(function(){
			var id = $("#selectSecondUser").val();
			
			//alert(id);
			 $("#secondUserId").val(id);
			
			 $.ajax({
                        type: "POST",
                        data: {
                            id: id
                        },
                        url: "/../jezzy-portal/dashboard/getServiceBySecondUser",
                        success: function(result) {

						//alert(result);
						
                         $("#serviceSchedule").html(result);
						 $("#serviceSchedule").prop('disabled', false);

                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            alert("Houve algume erro no processamento dos dados desse usuário, atualize a página e tente novamente!");
                        }
                    });
			
		});
		
		

});