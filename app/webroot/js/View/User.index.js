var contadorHelpersModal = 1;
$(document).ready(function () {

	/*$("#saveEdited").click(function(){
	
		var id = $("#editSecondaryUserID").val();
			var secUserName = $("#secundary_user_name").val();
			var secUserEmail = $("#secundary_user_email").val();
			var secUserType = $("#secundary_user_type").val();
			
			var perCompany = $("#rate_per_company_product").val();
			var perJezzy = $("#rate_per_jezzy_product").val();
			var perService = $("#rate_per_service").val();
			
			$.ajax({
				method: "POST",
				url: "/../jezzy-portal/user/editSecondaryUser",
				data: {SecondaryUser: {name: secUserName, email: secUserEmail, type: secUserType, id: id, perCompany: perCompany, perJezzy: perJezzy, perService: perService}}
			});
			$('#myModal').modal('hide');
	
	}); */

	var isFirstLogin = $("#userFirstLogin").val();
	
	if(isFirstLogin == 1){
	
				  introJs().start();
				  
				   $.ajax({
        type: "POST",
        data: {
        },
        url: "/../jezzy-portal/user/isNotFirst",
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
        url: "/../jezzy-portal/user/isNotFirst",
        success: function(result) {

        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("Houve algume erro no processamento dos dados desse usuÃ¡rio, atualize a pÃ¡gina e tente novamente!");
        }
    });
		
		});
	
	
	$(".editCommissionamentFee").click(function(){
	
		var id = $(this).attr("id");
		
		 $.ajax({
        type: "POST",
        data: {
            id: id
        },
        url: "/../jezzy-portal/user/getEditSecondUserCommissionament",
        success: function(result) {

		console.log(result);
		$("#editCommissionamentModalBody").html(result);
		$('#modalEditCommissionamentSecondary').modal('toggle');
			 $('#modalEditCommissionamentSecondary').modal('show');
		
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("Houve algume erro no processamento dos dados desse usuÃ¡rio, atualize a pÃ¡gina e tente novamente!");
        }
    });
	
	});
	
	
	
	$("#btnSaveEditCommissionament").click(function(){
	
		var ratePerCompany = $("#EDIT_rate_per_company_product").val();
		var ratePerJezzy = $("#EDIT_rate_per_jezzy_product").val();
		var ratePerService = $("#EDIT_rate_per_service").val();
		var id = $("#EDIT_commissionament_id").val();
		
		 $.ajax({
        type: "POST",
        data: {
            rate_per_company_product: ratePerCompany,
			rate_per_jezzy_product: ratePerJezzy,
			rate_per_service: ratePerService,
			commissionament_id: id
        },
        url: "/../jezzy-portal/user/updateSecondUserCommissionament",
        success: function(result) {

		location.reload(); 
		
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("Houve algume erro no processamento dos dados desse usuÃ¡rio, atualize a pÃ¡gina e tente novamente!");
        }
    });
	
	});

	//executa verificaçãpo de email durante digitação
	$("#secundary_user_email").focusout(function(){
		verificaSecondaryUser();
		//verificaSecondaryUserByCompanyEmail();
	});

    $("#buttonAddNewUser").click(function () {
        $("#secundary_user_name").val("");
        $("#secundary_user_email").val("");
        $("#secundary_user_type").val("");
		
		$("#rate_per_company_product").val(0);
		$("#rate_per_jezzy_product").val(0);
		$("#rate_per_service").val(0);
		
        $('#myModal').modal('show');
		$("#editSecondaryUser").val("false");
		$("#editSecondaryUserID").val(" ");
		
		$("#recebeBtn").html('<br /><button id="userModalButom" type="button" class="btn btn-success" disabled="true" onclick="valideButtonNewUser()">Salvar</button>');
    });

    $("#userModalButom").click(function () {
	
	//	alert('show');
	
		$(".loading-image").fadeIn(200);
		
		var isEdit = $("#editSecondaryUser").val();
			 $('#deleteUserModal').modal('toggle');
			 $('#deleteUserModal').modal('show');
	
		if(isEdit == "false"){
	
        var secUserName = $("#secundary_user_name").val();
        var secUserEmail = $("#secundary_user_email").val();
        var secUserType = $("#secundary_user_type").val();
        if (secUserName === "" || secUserEmail === "" || secUserType === "") {
            alert("Todos os campos são obrigatórios.");
            return false;
        }
        addNewLineOnSecondUser(secUserName, secUserEmail, secUserType).done(function (msg) {
            if (msg !== "0") {
			
				/**
					FIX
				console.log(msg);
				var strTxt = JSON.stringify(msg);
				console.log(strTxt);
				var user = jQuery.parseJSON(msg);
			
                console.log(user.secondary_users);
                var tableline =
                        '<tr>' +
                            '<td>' + user.secondary_users.name + '</td>' +
                            '<td>' + user.secondary_users.email + '</td>' +
                            '<td>' + user.secondary_users_types.name + '</td>' +
							 '<td><span id="edit-' + user.secondary_users.name + '" class="glyphicon glyphicon-pencil glyph-button" onclick="editUser(' + user.secondary_users.name + ', ' + user.secondary_users.email + ', '+ user.secondary_users_types.name +', ' + user.secondary_users.id + ')"></span></td>' +
                            '<td><span id="' + user.secondary_users.id + '" class="glyphicon glyphicon-remove-sign"></span></td>' +
                        '</tr>';
                $("#secundaryUserTable").append(tableline);
				 $(".loading-image").fadeOut(200); **/
				 window.location.reload();
            } else {
                alert("Não foi");
            }
        });
        $('#myModal').modal('hide');
		 $('#deleteUserModal').modal('hide');
		}else{
			
			var id = $("#editSecondaryUserID").val();
			var secUserName = $("#secundary_user_name").val();
			var secUserEmail = $("#secundary_user_email").val();
			var secUserType = $("#secundary_user_type").val();
			
			var perCompany = $("#rate_per_company_product").val();
			var perJezzy = $("#rate_per_jezzy_product").val();
			var perService = $("#rate_per_service").val();
			
			$.ajax({
				method: "POST",
				url: "/../jezzy-portal/user/editSecondaryUser",
				data: {SecondaryUser: {name: secUserName, email: secUserEmail, type: secUserType, id: id, perCompany: perCompany, perJezzy: perJezzy, perService: perService}}
			});
			$('#myModal').modal('hide');
		}
		
		
    });

	//CLIQUE EM REMOVER
    $("#secundaryUserTable").on("click", ".glyphicon-remove-sign", function () {
        var userId = $(this).children("td").context.id;
		
		$("#userIdDelete").val(userId);
		$('#deleteUserModal').modal('toggle');
		$('#deleteUserModal').modal('show');
		
      /*  removeSecondUser(userId).done(function (msg) {
            if (msg !== "0") {
                $($("#" + userId)).closest('tr').remove();
                alert("Usuário removido");
            } else {
                alert("Não foi possivel remover o usuário. Regarregue a pagina e tente novamente");
            }
        }); */
    });
	
	$("#btnDeleteUser").click(function(){
		var userId = $("#userIdDelete").val();
		
		removeSecondUser(userId).done(function (msg) {
            if (msg !== "0") {
                $($("#" + userId)).closest('tr').remove();
                alert("Usuário removido");
            } else {
                alert("Não foi possivel remover o usuário. Regarregue a pagina e tente novamente");
            }
        });
			$('#deleteUserModal').modal('toggle');
			 $('#deleteUserModal').modal('hide');
	});
	
	$("#btnReativeUser").click(function(){
		var userId = $("#userIdReative").val();
		
		
		reativeSecondUser(userId).done(function (msg) {
            if (msg !== "0") {
                $($("#reactive-" + userId)).closest('tr').remove();
				$('#reativeUserModal').modal('toggle');
	$('#reativeUserModal').modal('hide');
                alert("Usuário reativado");
				
            } else {
                alert("Não foi possivel reativar o usuário. Regarregue a pagina e tente novamente");
            }
        });
			$('#deleteUserModal').modal('toggle');
			 $('#deleteUserModal').modal('hide');
	});
	
	
	$("#associatedUserEmailRequisition").keyup(function(){
	
		var email = $("#associatedUserEmailRequisition").val();
		
				alert(email);
	
		     $.ajax({
            type: "POST",
            data: {
                email: email
            },
            url: "/../jezzy-portal/user/searchAssociatedUserByEmail",
            success: function(result) {
			
				alert(result);
				console.log(result);
			
			if(result != 0){
				var jsonReturn = JSON.parse(result);
				alert('sucesso');
				
				$("#associatedSolicitationName").html(jsonReturn.associated_users.name);
				$("#associatedSolicitationEmail").html(jsonReturn.associated_users.email);
				$("#associatedSolicitationUserId").val(jsonReturn.associated_users.id);
				
				$("#associatedSolicitationError").fadeOut(0);
				$("#associatedSolicitationInfosUser").fadeIn(200);
				$("#btnSendAssociatedRequisition").fadeIn(300);
				
				}else{
				alert('error');
					$("#associatedSolicitationInfosUser").fadeOut(0);
					$("#associatedSolicitationError").fadeIn(300);
					$("#btnSendAssociatedRequisition").fadeOut(200);
				
				}
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Houve algume erro no processamento dos dados desse usuário, atualize a página e tente novamente!");
            }
        });
	});
	
	
	$("#btnSendAssociatedRequisition").click(function(){
	
		alert("clicou");
		
			var id = $("#associatedSolicitationUserId").val();

alert(id);			
		
			$.ajax({
            type: "POST",
            data: {
                associatedUserId: id
            },
            url: "/../jezzy-portal/user/createAssociatedUserSolicitation",
            success: function(result) {
			
				$("#modalBodyAssociatedUser").html("<h3>Solicilitação enviada com sucesso!</h3>");
				$("#btnSendAssociatedRequisition").fadeOut(300);
	
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Houve, atualize a página e tente novamente!");
            }
        });
	
	});
	
	$(".associatedSolicitationRemove").click(function(){
	
		var id = $(this).attr("id");
		
			$.ajax({
            type: "POST",
            data: {
                solicitationId: id
            },
            url: "/../jezzy-portal/user/removeAssociatedUserSolicitation",
            success: function(result) {
			
			$("#associatedSolicitation" + id).closest('tr').remove();
	
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Houve, atualize a página e tente novamente!");
            }
        });
	
	});

});


function reativeUser(id){

	$("#userIdReative").val(id);
	$('#reativeUserModal').modal('toggle');
	$('#reativeUserModal').modal('show');
}

function addNewLineOnSecondUser(userName, userEmail, userType) {

	var ratePerCompanyProduct= $("#rate_per_company_product").val();
	var ratePerJezzyProduct= $("#rate_per_jezzy_product").val();
	var rate_per_service= $("#rate_per_service").val();
	
	if(ratePerCompanyProduct === ""){
		ratePerCompanyProduct = 0;
	}
	
	if(ratePerJezzyProduct === ""){
		ratePerJezzyProduct = 0;
	}
	
		if(rate_per_service === ""){
		rate_per_service = 0;
	}
	
    return $.ajax({
        method: "POST",
        url: getControllerPath("User") + "addSecondaryUver",
        data: {SecondaryUser: {name: userName, email: userEmail, type: userType, ratePerCompanyProduct:ratePerCompanyProduct, ratePerJezzyProduct:ratePerJezzyProduct, rate_per_service:rate_per_service}}
    });
}

function removeSecondUser(userId) {
    return $.ajax({
        method: "POST",
        url: getControllerPath("User") + "removeSecondUser",
        data: {SecondaryUser: {id: userId}}
    });
}

function reativeSecondUser(userId){

	 return $.ajax({
        method: "POST",
        url: getControllerPath("User") + "reativeSecondUser",
        data: {SecondaryUser: {id: userId}}
    });
}

function verificaSecondaryUser(){

		var email = $("#secundary_user_email").val();
		
		 $.ajax({
            type: "POST",
            data: {
                email: email
            },
            url: "/../jezzy-portal/user/searchAssociatedUserByEmail",
            success: function(result) {

				console.log(result);
			
			if(result != 0){
				var jsonReturn = JSON.parse(result);
				
				$("#associatedSolicitationName").html(jsonReturn.associated_users.name);
				$("#associatedSolicitationEmail").html(jsonReturn.associated_users.email);
				$("#associatedSolicitationUserId").val(jsonReturn.associated_users.id);
					
					$("#cadSecondUserShowError").html("<div class='col-sm-2'></div><p class='col-sm-6'>Existe um <i>Profissional Associado</i> à esse email</p> <button type='button' class='btn btn-primary' id='btnSendAssociatedRequisition' style='display: inline-block;' onclick='sendSolicitationToAssociated()'>Enviar solicitação</button>");
					$("#cadSecondUserShowError").fadeIn();
					
					$("#userModalButom").prop("disabled", true);
					//break;
				
				}else{
				
				    $.ajax({
            type: "POST",
            data: {
                email: email
            },
            url: "/../jezzy-portal/user/verificaSecondaryUser",
            success: function(result) {
			
				//alert(result);
			
                if(result == true){
				//	alert('usuario ja cadastrado');
				$("#cadSecondUserShowError").html("<div class='col-sm-2'></div><p class='col-sm-6'>Este email já está sendo usado por outro usuário.</p>");
				$("#cadSecondUserShowError").fadeIn();
				$("#userModalButom").prop("disabled", true);
				}else{
				//	alert('usuario nao cadastrado');
				$("#cadSecondUserShowError").fadeOut(100);
				$("#userModalButom").prop("disabled", false);
				
				 $.ajax({
            type: "POST",
            data: {
                email: email
            },
            url: "/../jezzy-portal/user/verificaSecondaryUserWithCompanyEmail",
            success: function(result) {
			
				//alert(result);
			
                if(result == true){
				//	alert('usuario ja cadastrado');
				$("#cadSecondUserShowError").html("<div class='col-sm-2'></div><p class='col-sm-6'>Este email já está sendo usado por outro usuário.</p>");
				$("#cadSecondUserShowError").fadeIn();
				$("#userModalButom").prop("disabled", true);
				}else{
				//	alert('usuario nao cadastrado');
				$("#cadSecondUserShowError").fadeOut(100);
				$("#userModalButom").prop("disabled", false);
				}
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Houve algume erro no processamento dos dados desse usuário, atualize a página e tente novamente!");
            }
        });
				
				}
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Houve algume erro no processamento dos dados desse usuário, atualize a página e tente novamente!");
            }
        });
				
				
				}
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Houve algume erro no processamento dos dados desse usuário, atualize a página e tente novamente!");
            }
        });

}

function verificaSecondaryUserByCompanyEmail(){

		var email = $("#secundary_user_email").val();
		//alert(email);
	     $.ajax({
            type: "POST",
            data: {
                email: email
            },
            url: "/../jezzy-portal/user/verificaSecondaryUserWithCompanyEmail",
            success: function(result) {
			
				//alert(result);
			
                if(result == true){
				//	alert('usuario ja cadastrado');
				$("#cadSecondUserShowError").fadeIn();
				$("#userModalButom").prop("disabled", true);
				}else{
				//	alert('usuario nao cadastrado');
				$("#cadSecondUserShowError").fadeOut(100);
				$("#userModalButom").prop("disabled", false);
				}
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Houve algume erro no processamento dos dados desse usuário, atualize a página e tente novamente!");
            }
        });
}

function editUser(name, email, cargo, id, perCompany, perJezzy, perService){
	
	$("#editSecondaryUserID").val(id);
	$("#editSecondaryUser").val("true");
	$("#secundary_user_name").val(name);
	$("#secundary_user_email").val(email);
	$("#secundary_user_type").val(cargo);
	
	//comissionamento
	$("#rate_per_company_product").val(perCompany);
	$("#rate_per_jezzy_product").val(perJezzy);
	$("#rate_per_service").val(perService);
	
	$("#recebeBtn").html('<br /><button id="saveEdited" type="button" class="btn btn-default" onclick="saveEditedUser()">Salvar edição</button>');
	
	$('#myModal').modal('show');
}


function saveEditedUser(){

	var id = $("#editSecondaryUserID").val();
			var secUserName = $("#secundary_user_name").val();
			var secUserEmail = $("#secundary_user_email").val();
			var secUserType = $("#secundary_user_type").val();
			
			var perCompany = $("#rate_per_company_product").val();
			var perJezzy = $("#rate_per_jezzy_product").val();
			var perService = $("#rate_per_service").val();
			
			perCompany = perCompany.replace(",", ".");
			perJezzy = perJezzy.replace(",", ".");
			perService = perService.replace(",", ".");
			
			$.ajax({
            	method: "POST",
				url: "/../jezzy-portal/user/editSecondaryUser",
				data: {SecondaryUser: {name: secUserName, email: secUserEmail, type: secUserType, id: id, perCompany: perCompany, perJezzy: perJezzy, perService: perService}},
            success: function(result) {
			
			$('#myModal').modal('hide');
			location.reload();
			
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Houve algume erro no processamento dos dados desse usuário, atualize a página e tente novamente!");
            }
        });
			
			/*$.ajax({
				method: "POST",
				url: "/../jezzy-portal/user/editSecondaryUser",
				data: {SecondaryUser: {name: secUserName, email: secUserEmail, type: secUserType, id: id, perCompany: perCompany, perJezzy: perJezzy, perService: perService}}
			}); */
		
	

}

function sendSolicitationToAssociated(){

		
			var id = $("#associatedSolicitationUserId").val();
					
			$.ajax({
            type: "POST",
            data: {
                associatedUserId: id
            },
            url: "/../jezzy-portal/user/createAssociatedUserSolicitation",
            success: function(result) {
			
				alert("Solicilitação enviada com sucesso!");
				//$("#myModal").
				$('#myModal').modal('hide');
	
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Houve, atualize a página e tente novamente!");
            }
        });

}


function valideButtonNewUser(){

	
		$(".loading-image").fadeIn(200);
		
		var isEdit = $("#editSecondaryUser").val();
			 $('#deleteUserModal').modal('toggle');
			 $('#deleteUserModal').modal('show');
	
		if(isEdit == "false"){
	
        var secUserName = $("#secundary_user_name").val();
        var secUserEmail = $("#secundary_user_email").val();
        var secUserType = $("#secundary_user_type").val();
        if (secUserName === "" || secUserEmail === "" || secUserType === "") {
            alert("Todos os campos são obrigatórios.");
            return false;
        }
        addNewLineOnSecondUser(secUserName, secUserEmail, secUserType).done(function (msg) {
            if (msg !== "0") {
			
				/**
					FIX
				console.log(msg);
				var strTxt = JSON.stringify(msg);
				console.log(strTxt);
				var user = jQuery.parseJSON(msg);
			
                console.log(user.secondary_users);
                var tableline =
                        '<tr>' +
                            '<td>' + user.secondary_users.name + '</td>' +
                            '<td>' + user.secondary_users.email + '</td>' +
                            '<td>' + user.secondary_users_types.name + '</td>' +
							 '<td><span id="edit-' + user.secondary_users.name + '" class="glyphicon glyphicon-pencil glyph-button" onclick="editUser(' + user.secondary_users.name + ', ' + user.secondary_users.email + ', '+ user.secondary_users_types.name +', ' + user.secondary_users.id + ')"></span></td>' +
                            '<td><span id="' + user.secondary_users.id + '" class="glyphicon glyphicon-remove-sign"></span></td>' +
                        '</tr>';
                $("#secundaryUserTable").append(tableline);
				 $(".loading-image").fadeOut(200); **/
				 window.location.reload();
            } else {
                alert("Não foi");
            }
        });
        $('#myModal').modal('hide');
		 $('#deleteUserModal').modal('hide');
		}else{
			
			var id = $("#editSecondaryUserID").val();
			var secUserName = $("#secundary_user_name").val();
			var secUserEmail = $("#secundary_user_email").val();
			var secUserType = $("#secundary_user_type").val();
			
			var perCompany = $("#rate_per_company_product").val();
			var perJezzy = $("#rate_per_jezzy_product").val();
			var perService = $("#rate_per_service").val();
			
			$.ajax({
				method: "POST",
				url: "/../jezzy-portal/user/editSecondaryUser",
				data: {SecondaryUser: {name: secUserName, email: secUserEmail, type: secUserType, id: id, perCompany: perCompany, perJezzy: perJezzy, perService: perService}}
			});
			$('#myModal').modal('hide');
		}

}	