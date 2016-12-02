var contadorHelpersModal = 1;

$(document).ready(function () {

$("#linkedLogo").attr("href", "https://"+window.location.host+"/jezzy-portal/dashboard");


		var isFirstLogin = $("#userFirstLogin").val();
	
	if(isFirstLogin == 1){
	
	  introJs().start();
	  
	  $.ajax({
        type: "POST",
        data: {
        },
        url: "/../jezzy-portal/service/isNotFirst",
        success: function(result) {

        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("Houve algume erro no processamento dos dados desse usuÃ¡rio, atualize a pÃ¡gina e tente novamente!");
        }
    });
	
	}
	
	$(".nextHelper").click(function(){
		

			$('.modal').modal('hide');
			
			contadorHelpersModal++;
		
			
			 $('#myModalHelper'+contadorHelpersModal).modal('show');
		
		});
		
		$(".finishHelper").click(function(){
		
			$('.modal').modal('hide');
			
				 $.ajax({
        type: "POST",
        data: {
        },
        url: "/../jezzy-portal/service/isNotFirst",
        success: function(result) {

        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("Houve algume erro no processamento dos dados desse usuÃ¡rio, atualize a pÃ¡gina e tente novamente!");
        }
    });
		
		});









    $("#serviceForm").submit(function (event) {
        $('tr:has(input)').each(function () {
            var row = this;
            var valuesText = [];
            var valuesCheckbox = [];
            $('input', this).each(function () {
                if( this.type === "checkbox" && this.checked === true ){
                    valuesCheckbox.push($(this).val());
                }
                if(this.type === "text" && $(this).val() !== ""){
                    valuesText.push($(this).val());
                }
            });
            if( (valuesText.length === 2 && valuesCheckbox.length === 0) 
                    ||  (valuesCheckbox.length !== 0 && valuesText.length < 2) ){
                alert(" '"+ $(row).find('td:not(:empty):first').html() + "' Não preenchida corretamente");
                event.preventDefault();
                return false;
            }
        });
    });
	
	$('html').click(function() {
			$("#search-return").fadeOut(0);
			$("#serviceByNameTb").fadeOut(0);	
		});
	
	 $("#searchService").keyup(function(){
			
		var valor = $("#searchService").val();
		
		if(valor === ''){
			$("#search-return").fadeOut(0);
		}else{
		
		$.ajax({			
			type: "POST",			
			data:{				
			searchService: valor},			
			url: "/../jezzy-portal/service/getServiceByName",
			success: function(result){	
				$("#search-return").fadeIn(0);			
				$('#search-return').html(result);
				
				
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			alert(errorThrown);
		}
	  });}
		
		}); 
	
});

function clickInSearch(ClassName, SubclassId){
		
    //document.getElementsByName(''+ClassName+'')[0].style.display='block';
	$("[name='"+ClassName+"']").collapse('toggle');
	//document.getElementsByName(''+ClassName+'')[0].collapse('toggle');
    document.getElementById(''+SubclassId+'_0').style.color="red";
    
    
}