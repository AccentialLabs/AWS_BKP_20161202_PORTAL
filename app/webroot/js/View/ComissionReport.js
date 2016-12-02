$(function(){


	$(".monthToCommissioned").change(function(){
	
		var userID = $(this).attr("name");
		var month = $(this).val();
		
		   $.ajax({
            type: "POST",
            data: {
                userID: userID,
				month: month
            },
            url: "/../jezzy-portal/comissionReport/getCheckoutsByCommissionedSecondaryUsersByMonth",
            success: function(result) {

                $("#tbody-"+userID).html(result);
				$(".monthRelatorio").html(month);
				console.log(result);

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Houve algume erro no processamento dos dados desse usuário, atualize a página e tente novamente!");
            }
        });
	
	});


});