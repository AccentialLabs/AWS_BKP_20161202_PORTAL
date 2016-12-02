var $mytblToday;
var $mytblPass;
var $mytblFuture;
var found = false;



$(function(){

 (function ($) {

 
 
 
        $('#filterProfessionalID').change(function () {
			
			var secondaryUser = $(this).val();
            var rex = new RegExp($(this).val(), 'i');
			//alert(rex);
            $('.searchable tr').hide();
            $('.searchable tr').filter(function () {
                return rex.test($(this).text());
            }).show();

        })

    }(jQuery));

	  $mytblToday = $('#tableToday').clone();
	  $mytblPass = $('#tablePass').clone();
	  $mytblFuture = $('#tableFuture').clone();
	   
	/* $("#filterProfessionalID").change(function(){
	
		var secondaryUser = $(this).val();
		 found = false;
		alert(secondaryUser);
		searchTablePass(secondaryUser);
		searchTableFuture(secondaryUser);
		searchTableToday(secondaryUser);
	
	}); */

	$(".edit-schedule").click(function(){
	
		var id = $(this).attr("id");

		var scheduleDate = $("#scheduleDate"+id).html();
		var scheduleHour = $("#scheduleHour"+id).html();
			
		$("#scheduleDate"+id).html("<input type='date' value='"+scheduleDate+"' id='inputEditScheduleDate"+id+"' class='form-control'/>");
		$("#scheduleHour"+id).html("<input type='hour' value='"+scheduleHour+"' id='inputEditScheduleHour"+id+"'  class='form-control'/>");
		
		$(this).fadeOut(0);
		$("#"+id+".save-edit-schedule").fadeIn(200);
	});
	
	$(".save-edit-schedule").click(function(){
		
		var id = $(this).attr("id");
		
		var date = $("#inputEditScheduleDate"+id).val();
		var hour = $("#inputEditScheduleHour"+id).val();
		
		
			$.ajax({			
			type: "POST",			
			data:{
				scheduleID:id,
				scheduleDate : date,
				scheduleHour: hour
			},			
			url: "/../jezzy-portal/scheduleReport/saveEditSchedule",
			success: function(result){	
			
			var realDate = '';
			var arrayDate = '';
			if(date.indexOf("-") != -1){
			
				arrayDate = date.split("-");
				realDate = arrayDate[2]+"/"+arrayDate[1]+"/"+arrayDate[0];
			
			}else{
				realDate = date;
			}
				
			$("#scheduleDate"+id).html(realDate);
			$("#scheduleHour"+id).html(hour);
			$(this).fadeOut(0);
			$("#"+id+".edit-schedule").fadeIn(200);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			alert("Houve algume erro no processamento dos dados desse usuário, atualize a página e tente novamente!");
		}
	  })
		
		
	
	});
	
	$(".remove-schedule").click(function(){
	
		var id = $(this).attr("id");
		alert(id);
		
			$.ajax({			
			type: "POST",			
			data:{
				scheduleID:id
			},			
			url: "/../jezzy-portal/scheduleReport/removeSchedule",
			success: function(result){	
				
			location.reload();
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			alert("Houve algume erro no processamento dos dados desse usuário, atualize a página e tente novamente!");
		}
	  });
	
	});
	
	$("#btnNewSchedule").click(function(){

		var time = $("#repeatScheduleHour").val();
		var chooseDay = $("#repeatScheduleDate").val();
		var service= 	$("#repeatService").val();
		var price =	$("#repeatPrice").val();
		var client =	$("#repeatClient").val();
		var phone =	$("#repeatPhone").val();
		var userId =	$("#repeatUserId").val();
		var secondaryId =	$("#repeatSecondaryID").val();
			
			
		$.ajax({
        method: "POST",
        url: "/../jezzy-portal/schedule/ajaxAddSchedule",
        data: {Schedule: {
                schedulehour: time,
                serviceId: service,
                schedulePrice: price,
                scheduleClient: client,
                schedulePhone: phone,
                scheduleSecondaryUser: secondaryId,
                scheduleDate: chooseDay,
				userId: userId
            }
        },
		success: function(result){	
			alert("sucesso");
		$('#myModalRepeatSchedule').modal('toggle');
	$('#myModalRepeatSchedule').modal('hide');
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			alert("ERROR: " + textStatus);
		}
    }); 
	
	
			
		});
	
});

function showUserDetail(id){
		
	$.ajax({			
			type: "POST",			
			data:{
				userId:id
			},			
			url: "/../jezzy-portal/clientReport/getClienteDetail",
			success: function(result){	
				
			$("#recebe-user-details").html(result);
			$('#myModalUserDetails').modal('toggle');
			 $('#myModalUserDetails').modal('show');
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			alert("Houve algume erro no processamento dos dados desse usuário, atualize a página e tente novamente!");
		}
	  });
	
}

function showScheduleDetail(id){
	
	$.ajax({			
			type: "POST",			
			data:{
				checkoutId:id
			},			
			url: "/../jezzy-portal/scheduleReport/getScheduleDetail",
			success: function(result){	
				
				//alert(result);
			$("#recebe").html(result);
			$('#myModalSchedulesRequisitions').modal('toggle');
			 $('#myModalSchedulesRequisitions').modal('show');
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			alert("Houve algume erro no processamento dos dados dessa compra, atualize a página e tente novamente!");
		}
	  });
}

function addNewSchedule(service, price, client, phone, userId, secondaryID) {
	$("#repeatService").val(service);
	$("#repeatPrice").val(price);
	$("#repeatClient").val(client);
	$("#repeatPhone").val(phone);
	$("#repeatUserId").val(userId);
	$("#repeatSecondaryID").val(secondaryID);
	
	$('#myModalRepeatSchedule').modal('toggle');
	$('#myModalRepeatSchedule').modal('show');

}

/**
BUSCA NA TABELA HOJE
*/
function searchTableToday(key) {
            $mytblToday.find('td').each(function (i, v) {
                if (key && $(v).html().contains(key)) {
                    var $th = $mytblToday.find('th').eq($(v).index());
                    var $tbl = $('<table />');
                    $tbl.append('<tr><th>' + $th.html() + '</th></tr>');
                    $tbl.append('<tr><td>' + $(v).html() + '</td></tr>');

                    $('#tableToday').html('');
                    $('#tableToday').html($tbl.html());
                    //found = true;
                }
                else {
                    if (!found) {
                        if (!key) {
                            $('#tableToday').html('');
                            $('#tableToday').html($mytbl.html());
                        }
                        else {
                            var $tbl = $('<table />');
                            $tbl.append('<tr><th>' + 'Result' + '</th></tr>');
                            $tbl.append('<tr><td>' + 'No matching records found' + '</td></tr>');

                            $('#tableToday').html('');
                            $('#tableToday').html($tbl.html());
                        }
                    }
                }
            });
        }
		
		/**
		BUSCA NA TABELA PASSWORD
		**/
		function searchTablePass(key) {
            $mytblPass.find('td').each(function (i, v) {
                if (key && $(v).html().contains(key)) {
                    var $th = $mytblPass.find('th').eq($(v).index());
                    var $tbl = $('<table />');
                    $tbl.append('<tr><th>' + $th.html() + '</th></tr>');
                    $tbl.append('<tr><td>' + $(v).html() + '</td></tr>');

                    $('#tablePass').html('');
                    $('#tablePass').html($tbl.html());
                    //found = true;
                }
                else {
                    if (!found) {
                        if (!key) {
                            $('#tablePass').html('');
                            $('#tablePass').html($mytbl.html());
                        }
                        else {
                            var $tbl = $('<table />');
                            $tbl.append('<tr><th>' + 'Result' + '</th></tr>');
                            $tbl.append('<tr><td>' + 'No matching records found' + '</td></tr>');

                            $('#tablePass').html('');
                            $('#tablePass').html($tbl.html());
                        }
                    }
                }
            });
        }

		/**
		BUSCA NA TABELA FUTURO
		**/
		function searchTableFuture(key) {
            $mytblFuture.find('td').each(function (i, v) {
                if (key && $(v).html().contains(key)) {
                    var $th = $mytblFuture.find('th').eq($(v).index());
                    var $tbl = $('<table />');
                    $tbl.append('<tr><th>' + $th.html() + '</th></tr>');
                    $tbl.append('<tr><td>' + $(v).html() + '</td></tr>');

                    $('#tableFuture').html('');
                    $('#tableFuture').html($tbl.html());
                    found = true;
                }
                else {
                    if (!found) {
                        if (!key) {
                            $('#tableFuture').html('');
                            $('#tableFuture').html($mytbl.html());
                        }
                        else {
                            var $tbl = $('<table />');
                            $tbl.append('<tr><th>' + 'Result' + '</th></tr>');
                            $tbl.append('<tr><td>' + 'No matching records found' + '</td></tr>');

                            $('#tableFuture').html('');
                            $('#tableFuture').html($tbl.html());
                        }
                    }
                }
            });
        }

