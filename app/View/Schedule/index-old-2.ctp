

<?php
	echo $this->Html->css('fullcalendar/fullcalendar',array('inline' => false)); 
	echo $this->Html->script('../css/fullcalendar/lib/jquery.min');
	echo $this->Html->script('../css/fullcalendar/lib/moment.min',array('inline' => false));
	echo $this->Html->script('../css/fullcalendar/fullcalendar',array('inline' => false));
	echo $this->Html->script('../css/fullcalendar/locale/pt-br',array('inline' => false));
	echo $this->Html->script('View/Schedule.fullcalendar',array('inline' => false));
 ?>
 
 <?php // echo $this->Html->css('View/Schedule.index', array('inline' => false)); ?>
<?php echo $this->Html->script('util', array('inline' => false)); ?>
<?php echo $this->Html->script('View/Schedule.index', array('inline' => false)); ?>
 <script>

     var Base64 = {
            _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
            encode: function (input) {
                var output = "";
                var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
                var i = 0;
                input = Base64._utf8_encode(input);
                while (i < input.length) {
                    chr1 = input.charCodeAt(i++);
                    chr2 = input.charCodeAt(i++);
                    chr3 = input.charCodeAt(i++);
                    enc1 = chr1 >> 2;
                    enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                    enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                    enc4 = chr3 & 63;
                    if (isNaN(chr2)) {
                        enc3 = enc4 = 64;
                    } else if (isNaN(chr3)) {
                        enc4 = 64;
                    }
                    output = output + this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) + this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);
                }
                return output;
            },
            decode: function (input) {
                var output = "";
                var chr1, chr2, chr3;
                var enc1, enc2, enc3, enc4;
                var i = 0;
                input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
                while (i < input.length) {
                    enc1 = this._keyStr.indexOf(input.charAt(i++));
                    enc2 = this._keyStr.indexOf(input.charAt(i++));
                    enc3 = this._keyStr.indexOf(input.charAt(i++));
                    enc4 = this._keyStr.indexOf(input.charAt(i++));
                    chr1 = (enc1 << 2) | (enc2 >> 4);
                    chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                    chr3 = ((enc3 & 3) << 6) | enc4;
                    output = output + String.fromCharCode(chr1);
                    if (enc3 != 64) {
                        output = output + String.fromCharCode(chr2);
                    }
                    if (enc4 != 64) {
                        output = output + String.fromCharCode(chr3);
                    }
                }
                output = Base64._utf8_decode(output);
                return output;
            },
            _utf8_encode: function (string) {
                string = string.replace(/\r\n/g, "\n");
                var utftext = "";
                for (var n = 0; n < string.length; n++) {
                    var c = string.charCodeAt(n);
                    if (c < 128) {
                        utftext += String.fromCharCode(c);
                    }
                    else if ((c > 127) && (c < 2048)) {
                        utftext += String.fromCharCode((c >> 6) | 192);
                        utftext += String.fromCharCode((c & 63) | 128);
                    }
                    else {
                        utftext += String.fromCharCode((c >> 12) | 224);
                        utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                        utftext += String.fromCharCode((c & 63) | 128);
                    }
                }
                return utftext;
            },
            _utf8_decode: function (utftext) {
                var string = "";
                var i = 0;
                var c = c1 = c2 = 0;
                while (i < utftext.length) {
                    c = utftext.charCodeAt(i);
                    if (c < 128) {
                        string += String.fromCharCode(c);
                        i++;
                    }
                    else if ((c > 191) && (c < 224)) {
                        c2 = utftext.charCodeAt(i + 1);
                        string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                        i += 2;
                    }
                    else {
                        c2 = utftext.charCodeAt(i + 1);
                        c3 = utftext.charCodeAt(i + 2);
                        string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                        i += 3;
                    }
                }
                return string;
            }
        };

	//CONVERSOR BASE64
        function unserialize (data) {
           
            var that = this,
                    utf8Overhead = function (chr) {
                        // http://phpjs.org/functions/unserialize:571#comment_95906
                        var code = chr.charCodeAt(0)
                        if (code < 0x0080 || 0x00A0 <= code && code <= 0x00FF || [338, 339, 352, 353, 376, 402, 8211, 8212, 8216, 8217,
                                    8218, 8220, 8221, 8222, 8224, 8225, 8226, 8230, 8240, 8364, 8482
                                ].indexOf(code) != -1) {
                            return 0
                        }
                        if (code < 0x0800) {
                            return 1
                        }
                        return 2
                    }
            error = function (type, msg, filename, line) {
                throw new that.window[type](msg, filename, line)
            }
            read_until = function (data, offset, stopchr) {
                var i = 2,
                        buf = [],
                        chr = data.slice(offset, offset + 1)

                while (chr != stopchr) {
                    if ((i + offset) > data.length) {
                        error('Error', 'Invalid')
                    }
                    buf.push(chr)
                    chr = data.slice(offset + (i - 1), offset + i)
                    i += 1
                }
                return [buf.length, buf.join('')]
            }
            read_chrs = function (data, offset, length) {
                var i, chr, buf

                buf = []
                for (i = 0; i < length; i++) {
                    chr = data.slice(offset + (i - 1), offset + i)
                    buf.push(chr)
                    length -= utf8Overhead(chr)
                }
                return [buf.length, buf.join('')]
            }
            _unserialize = function (data, offset) {
                var dtype, dataoffset, keyandchrs, keys, contig,
                        length, array, readdata, readData, ccount,
                        stringlength, i, key, kprops, kchrs, vprops,
                        vchrs, value, chrs = 0,
                        typeconvert = function (x) {
                            return x
                        }

                if (!offset) {
                    offset = 0
                }
                dtype = (data.slice(offset, offset + 1))
                        .toLowerCase()

                dataoffset = offset + 2

                switch (dtype) {
                    case 'i':
                        typeconvert = function (x) {
                            return parseInt(x, 10)
                        }
                        readData = read_until(data, dataoffset, ';')
                        chrs = readData[0]
                        readdata = readData[1]
                        dataoffset += chrs + 1
                        break
                    case 'b':
                        typeconvert = function (x) {
                            return parseInt(x, 10) !== 0
                        }
                        readData = read_until(data, dataoffset, ';')
                        chrs = readData[0]
                        readdata = readData[1]
                        dataoffset += chrs + 1
                        break
                    case 'd':
                        typeconvert = function (x) {
                            return parseFloat(x)
                        }
                        readData = read_until(data, dataoffset, ';')
                        chrs = readData[0]
                        readdata = readData[1]
                        dataoffset += chrs + 1
                        break
                    case 'n':
                        readdata = null
                        break
                    case 's':
                        ccount = read_until(data, dataoffset, ':')
                        chrs = ccount[0]
                        stringlength = ccount[1]
                        dataoffset += chrs + 2

                        readData = read_chrs(data, dataoffset + 1, parseInt(stringlength, 10))
                        chrs = readData[0]
                        readdata = readData[1]
                        dataoffset += chrs + 2
                        if (chrs != parseInt(stringlength, 10) && chrs != readdata.length) {
                            error('SyntaxError', 'String length mismatch')
                        }
                        break
                    case 'a':
                        readdata = {}

                        keyandchrs = read_until(data, dataoffset, ':')
                        chrs = keyandchrs[0]
                        keys = keyandchrs[1]
                        dataoffset += chrs + 2

                        length = parseInt(keys, 10)
                        contig = true

                        for (i = 0; i < length; i++) {
                            kprops = _unserialize(data, dataoffset)
                            kchrs = kprops[1]
                            key = kprops[2]
                            dataoffset += kchrs

                            vprops = _unserialize(data, dataoffset)
                            vchrs = vprops[1]
                            value = vprops[2]
                            dataoffset += vchrs

                            if (key !== i)
                                contig = false

                            readdata[key] = value
                        }

                        if (contig) {
                            array = new Array(length)
                            for (i = 0; i < length; i++)
                                array[i] = readdata[i]
                            readdata = array
                        }

                        dataoffset += 1
                        break
                    default:
                        error('SyntaxError', 'Unknown / Unhandled data type(s): ' + dtype)
                        break
                }
                return [dtype, dataoffset - offset, typeconvert(readdata)]
            }

            return _unserialize((data + ''), 0)[2]
        }	
		
 function createToken() {
            var arraySend = {
                'secureNumbers': Math.floor(new Date().getTime() / 1000)
            };
            var json = JSON.stringify(arraySend);
            return Base64.encode(json);
        }

	$(document).ready(function() {
	 var query2 = "SELECT * FROM schedules inner join secondary_users on secondary_users.id = schedules.secondary_user_id;";

                    var conditions2 = {
                        'General': {
                            'query': query2
                        }
                    };
                    var postData2 = JSON.stringify(conditions2);

                    postData2 = {
                        'params': postData2
                    };
                    var url2 = 'https://secure.jezzy.com.br/api/General/get/query/' + createToken();

					console.log("URL: " + url2);
					
                    $.ajax({
                        method: "POST",
                        url: url2,
                        data: postData2

                    }).done(function (result) {
					
					//alert("RESULTADO: " + result);
					
					
                            if(result != "ImE6MDp7fSI=") {
							
								console.log("RESULT: " + result);
								

                                var objReturns2 = JSON.parse(JSON.stringify(result));
								
								console.log("JSON PARSE RETURN:" + objReturns2 );
                                var decodeObjReturns2 = Base64.decode(objReturns2);
								
								console.log("BASE64 RETURN:" + decodeObjReturns2 );
                                var convertedReturns2 = unserialize(JSON.parse(decodeObjReturns2));
								
								

                                var eventosTotal2 = new Array();
                                for (var n = 0; n < convertedReturns2.length; n++) {
                                    var evento2 = convertedReturns2[n];

										console.log(evento2);
                                    var dados2 = new Object();


                                    dados2.id = evento2.schedules.id;
                                    dados2.title = evento2.secondary_users.name+ ": \n"+evento2.schedules.subclasse_name+ " em "+evento2.schedules.client_name;
                                    dados2.end = moment(evento2.schedules.date + " " + evento2.schedules.time_end);
                                    dados2.start = moment(evento2.schedules.date + " " + evento2.schedules.time_begin);
                                    dados2.backgroundColor = evento2.secondary_users.color;
                                    dados2.color = '#84827F';
                                    dados2.borderColor = 'transparent';
                                    dados2.allDay = false;
                                    dados2.editable = true;

                                    eventosTotal2[n] = dados2;

                                } 
								
								console.log(eventosTotal2);
                                // CRIA O CALENDARIO COM OS EVENTOS DE OUTROS USUARIOS SEM A OPÇÃO DE REMOVER EVENTO.

                                for (var i = 0; i < eventosTotal2.length; i++) {

                                    $('#calendar').fullCalendar('renderEvent', eventosTotal2[i], true);
                                } 
								
								}
								
								});

	
	
	
	$('#calendar').fullCalendar({
	header: {
			left: 'prev,next today',
			center: 'title',
			//right: 'agendaDay,month,agendaWeek,listWeek'
		},
        selectable: true,
        selectHelper: true,
        nowIndicator: true,
    eventClick: function(event) {
	//	 $('#calendar').fullCalendar('removeEvents', event.id);

	$("#eventScheduleID").val(event.id);
    $('#confirm').modal('toggle');
	$('#confirm').modal('show');
	},
	 editable: true,
   eventDrop: function(event, delta, revertFunc) {
   
	$('#confirmChageDate').modal('toggle');
	$('#confirmChageDate').modal('show');

        //alert(event.title + " was dropped on " + event.start.format());
		var dayAndHour = event.start.format().split("T");
		var dayAndHourEnd = event.end.format().split("T");
		
		console.log(dayAndHourEnd);
		$("#updateScheduleDate").val(dayAndHour[0]);
		$("#updateScheduleHourStart").val(dayAndHour[1]);
		$("#updateScheduleHourEnd").val(dayAndHourEnd[1]);
		$("#updateScheduleID").val(event.id);

       // if (!confirm("Are you sure about this change?")) {
      //      revertFunc();
       // }

    },

	select: function (start, end) {
	var check = new Date(start);

    var today = new Date();
    if(check < today)
    {
        // Previous Day. show message if you want otherwise do nothing.
                // So it will be unselectable

				alert("Essa data não é válida");
    }
    else
    {
	
	var minhaData = new Date(start);

	minhaData.setHours(minhaData.getHours()+2);
	var myDate = new Date(minhaData).toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1");

	
	$('#myModal').modal('toggle');
				$('#myModal').modal('show');

	 var dia = start.toString().substr(4, 12);
					var diaformat = dia.split(" ");
                        var day = diaformat[1];
						 var mes = diaformat[0];
						 
						 var meuMes = minhaData.getMonth()+1;
						 var diaSelecionado = '';
						// if(strlen (minhaData.getDate()) != 1){
							 diaSelecionado = minhaData.getFullYear() + "-" + (minhaData.getMonth() + 1) + "-0" + minhaData.getDate();
							 //alert(diaSelecionado);
							// }else{
							//	diaSelecionado = minhaData.getFullYear() + "-" + (minhaData.getMonth() + 1) + "-0" + minhaData.getDate();
							 //}
						 $("#dateSchecule").val(diaSelecionado);
						 
						 var minutes = minhaData.getMinutes();
							if(minhaData.getMinutes() == 0){
								minutes = "00";
							}
							
						 $("#initialTimeSchecule").val(myDate);
						 
				/*	var endtimeevent = (16 + ":" + 30);
                        endtimeformat = (2016 + '-' + 10 + '-' + day + " " + endtimeevent);
                        endtimeformat = ((moment(endtimeformat, "YYYY-MM-DD HH:mm")));
						
					alert(start);
						
						var eventData;
                                eventData = {
                                    title:  ' BARBINHA LEGAL - SOLICITAÇÃO EM ANDAMENTO',
                                    start: start,
                                    end: endtimeformat,
                                    backgroundColor: 'rgba(36, 151, 172, 0.70)',
                                    Color: 'rgba(36, 151, 172, 0.70)',
                                    borderColor: 'transparent',
                                    id: start
                                };


                               $('#calendar').fullCalendar('renderEvent', eventData, true); */
					
	
	
}	}				
	  
});

$('#calendar').fullCalendar( 'changeView', 'agendaWeek');

//REMOVE SCHEDULE
$("#btnModalDeleteSchedule").click(function(){
		var id = $("#eventScheduleID").val();
		
		//alert(id);
		console.log( getControllerPath("schedule") + "ajaxRemoveSchedule");
		
		console.log(removeSchedules(id));
		
	   $('#calendar').fullCalendar('removeEvents', id);
	   $('#confirm').modal('toggle');
	   $('#confirm').modal('hide');
	
});


//ALTERA SCHEDULE
$("#btnModalUpdateSchedule").click(function(){

	$.ajax({			
			type: "POST",			
			data:{				
			scheduleDate: $("#updateScheduleDate").val(),
			scheduleTimeBegin: $("#updateScheduleHourStart").val(),
			scheduleTimeEnd : $("#updateScheduleHourEnd").val(),
			scheduleID : $("#updateScheduleID").val()
			},			
			url: "/../jezzy-portal/schedule/ajaxUpdateSchedule",
			success: function(result){	
				//$("#search-return").fadeIn(0);			
				//$('#search-return').html(result);
				//alert(result);
				console.log(result);
				 $('#confirmChageDate').modal('toggle');
	$('#confirmChageDate').modal('hide');
							
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			alert(errorThrown);
		}
	  });

});
});
</script>
<h1 class="page-header" id="code" style="margin-top: -38px;">Tabela de Agendamentos</h1>
<div id='calendar' style="height: 60%;"></div>
<div class="hide">
    <div class="col-md-12">
	<div>
		<span class="fontTextTopTargetOffer">
			<a href="scheduleReport">Ver relatórios de agendamentos...</a>
		</span>
	</div>
	<br/>
        <div class="btn-group">
		<label for="dateSchedule">Filtrar data de agendamento</label>
            <input name="dateSchedule" id="dateSchedule" type="date" class="form-control" id="dateSchedule"/>
        </div>
	</div>
		
	<div class="col-md-12">
	<br/>
        Funcionarios: <br/>

        <?php
        if (isset($secundary_users)) {
            foreach ($secundary_users as $secundary_user) {
			$secSplit = split(" ", $secundary_user['secondary_users']['name']);
                echo '
                    <div class="btn-group">
                        <button name="employee" type="button" class="btn btn-primary" id="' . $secundary_user['secondary_users']['id'] . '" title="' . $secundary_user['secondary_users']['name'] . '">' . $secSplit[0] . '</button>
                    </div>';
            }
        }
        ?>
        <div class="btn-group">
            <button name="limpar" type="button" class="btn-sm btn-default " id="limpar">Limpar</button>
        </div>
    </div>

</div>
<div class="row" id="columnsSchecule">
    <div class="col-md-3 marginTop15" id="colSchedule_1">

    </div>
    <div class="col-md-3 marginTop15" id="colSchedule_2">

    </div>
    <div class="col-md-3 marginTop15 " id="colSchedule_3">

    </div>
    <div class="col-md-3 marginTop15" id="colSchedule_4">

    </div>
</div>

<div id="myModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm" >
        <div class="modal-content" id="modelContent">
            <div class="modal-body">
                <div class="form-horizontal">
                    <legend>Novo Agendamento</legend>
								
					<div class="form-group">
                        <div class="col-sm-12">
							<label for="dateSchecule">Data</label>
                            <input type="date" class="form-control" id="dateSchecule" placeholder="Data">
                        </div>
                    </div>
										
                    <div class="form-group">
                        <div class="col-sm-12">
							<label for="initialTimeSchecule">Hora Inicio</label>
                            <input type="time" class="form-control" id="initialTimeSchecule" placeholder="Hora inicial">
                        </div>
                    </div>
					<div class="form-group" style="display: none;" id="endDateFormGroup">
                        <div class="col-sm-12">
							<label for="initialTimeSchecule">Hora Término</label>
                            <input type="time" class="form-control" id="endTimeSchecule" placeholder="Hora inicial">
                        </div>
                    </div>
					
					<div class="form-group">
						<div class="col-sm-12">
						<label for="initialTimeSchecule">Profissional</label>
							<select class="form-control" id="selectSecondUser" >
							<option>Selecione</option>
							<?php
        if (isset($secundary_users)) {
            foreach ($secundary_users as $secundary_user) {
			$secSplit = split(" ", $secundary_user['secondary_users']['name']);
                echo '           <option value="' . $secundary_user['secondary_users']['id'] . '" title="' . $secundary_user['secondary_users']['name'] . '">' . $secundary_user['secondary_users']['name']. '</option>';
            }
        }
        ?>
		</select>
						</div>
					</div>
		
                    <div class="form-group">
                        <div class="col-sm-12">
						<label for="serviceSchedule">Serviço a ser prestado</label>
                            <select class="form-control" id="serviceSchedule">
                                <option value="0" selected>Serviço</option>
                                <?php
                                if (isset($services)) {
                                    foreach ($services as $sevice) {
                                        echo '<option value="' . $sevice['services']['id'] . '">' . $sevice['subclasses']['name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
						<label for="valueSchedule">Valor do Serviço</label>
						
							<div class="input-group">
									<span class="input-group-addon">R$</span>
									<input id="valueSchedule" type="number" class="form-control"  placeholder="Valor"  aria-label="Amount (to the nearest dollar)">
									
							</div>
                           
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
						<label for="clientSchedule">Nome do Cliente</label>
                            <input id="clientSchedule" type="text" class="form-control" placeholder="Nome do cliente">
							<div class="content-names" id="content-names">
								
							</div>
                        </div>
						
                    </div>
					
					<div class="form-group">
						<div class="col-sm-12">
							<a href="#" class="see-user-profile" id="user-profile-link" onclick="showUserDetail()">ver perfil do cliente</a>
						</div>
					</div>
					
					<div class="form-group">
                        <div class="col-sm-12">
						<label for="emailSchedule">Email do Cliente</label>
                            <input id="emailSchedule" type="text" class="form-control" placeholder="Email do cliente">
                        </div>
                    </div>
					
                    <div class="form-group">
                        <div class="col-sm-12">
						<label for="phoneSchedule">Telefone do Cliente</label>
                            <input id="phoneSchedule" maxlength="15"  type="tel" class="form-control numbersOnly"  placeholder="Telefone do cliente">
                        </div>
						<br/><br/>
						<div class="col-sm-12">
                            <input id="newUserSchedule"  type="checkbox" class="checkbox pull-left" checked="checked">
							<span class="pull-left"> Novo Cliente</span>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12 buttonLocation text-center>
                                <input type="hidden" name="userId" id="userId" value="" />
								<input type="hidden" name="secondUserId" id="secondUserId" value="" />
                                <button type="button" class="btn btn-success" id="btnNewSchedule">Agendar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DETALHE DE USUÁRIO -->
<div id="myModalUserDetails" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-md" >
        <div class="modal-content" id="modelContent">
            <div class="modal-body">
			<form action="<?php echo $this->Html->url("addSubclass"); ?>" method="post">
			<legend>Detalhes do Usuário</legend>
                <div class="form-horizontal" id="recebe">
                    
                    <div class="form-group notification-body" id="notification-body">
                      <div class="col-md-4">
						<img src="http://coolspotters.com/files/photos/95058/jorge-garcia-profile.jpg" class="user-details-photo"/>
					  </div>
					  <div class="col-md-8">
						<h3>Jorge Michael</h3>
						<hr />
						<div>
							<span class="glyphicon glyphicon-envelope pull-left"></span>  <div class="description-info-user">jorge@michael.com</div>
							<span class="glyphicon glyphicon-user pull-left"></span> <div class="description-info-user">Masculino</div>
							<span class="glyphicon glyphicon-calendar pull-left"></span> <div class="description-info-user">11/08/1994</div>
							<span class="glyphicon glyphicon-home pull-left"></span><div class="description-info-user">De Ferraz de Vasconcelos - São Paulo, Rua Hermenegildo Barreto, 120 - 08540-500</div>
						</div>
					  </div>
                    </div>
					<div>
						<div class="info-user-galery">
							<div class="pull-left quad">
								<a href="#" class="thumbnail">
									<img src="http://www.pontoabc.com/wp-content/uploads/2014/01/quadrados-dentro-de-um-quadrado.jpg" alt="...">
								</a>
							</div>
							<div class="pull-left quad">
								<a href="#" class="thumbnail">
									<img src="http://www.pontoabc.com/wp-content/uploads/2014/01/quadrados-dentro-de-um-quadrado.jpg" alt="...">
								</a>
							</div>
							<div class="pull-left quad">
								<a href="#" class="thumbnail">
									<img src="http://www.pontoabc.com/wp-content/uploads/2014/01/quadrados-dentro-de-um-quadrado.jpg" alt="...">
								</a>
							</div>
							<div class="pull-left quad">
								<a href="#" class="thumbnail">
									<img src="http://www.pontoabc.com/wp-content/uploads/2014/01/quadrados-dentro-de-um-quadrado.jpg" alt="...">
								</a>
							</div>
							<div class="pull-left quad">
								<a href="#" class="thumbnail">
									<img src="http://www.pontoabc.com/wp-content/uploads/2014/01/quadrados-dentro-de-um-quadrado.jpg" alt="...">
								</a>
							</div>
							<div class="pull-left quad">
								<a href="#" class="thumbnail">
									<img src="http://www.pontoabc.com/wp-content/uploads/2014/01/quadrados-dentro-de-um-quadrado.jpg" alt="...">
								</a>
							</div>
							<div class="pull-left quad">
								<a href="#" class="thumbnail">
									<img src="http://www.pontoabc.com/wp-content/uploads/2014/01/quadrados-dentro-de-um-quadrado.jpg" alt="...">
								</a>
							</div>
							<div class="pull-left quad">
								<a href="#" class="thumbnail">
									<img src="http://www.pontoabc.com/wp-content/uploads/2014/01/quadrados-dentro-de-um-quadrado.jpg" alt="...">
								</a>
							</div>
						</div>
							<div class="panel-group" id="accordion">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion"
									href="#collapseOne">Compras</a>
							</h4>
						</div>
						<div id="collapseOne" class="panel-collapse collapse in">
							<div class="panel-body">
									
								<!-- checkout box-->
								<div class="col-md-4 checkouts-box">
									<div class="col-md-12 img-content" >
										<img src="http://bimg2.mlstatic.com/camiseta-adulto-e-infantil-zelda-triforce_MLB-F-219462707_2113.jpg" class="checkouts-box-img" />
									</div>
									
									<div class="col-md-12 checkouts-content">
										<div class="checkout-label">Camiseta qualquer por no brasil</div>
										<hr class="checkouts-divisor"/>
												
											<div class="checkouts-descriptions col-md-12">												
										<div>
											<div class="col-md-7 checkouts-collums left-collum">
											Quantidade:
										</div>
										<div class="col-md-5 checkouts-collums">
										3
										</div>
										
										
											<div class="col-md-7 checkouts-collums left-collum">
											Pagamento:
										</div>
										<div class="col-md-5 checkouts-collums">
										DÉBITO
										</div>
										
									
											<div class="col-md-7 checkouts-collums left-collum">
											Data:
										</div>
										<div class="col-md-5 checkouts-collums">
										21/12/2015
										</div>
										
										<div class="col-md-7 checkouts-collums left-collum">
											Status:
										</div>
										<div class="col-md-5 checkouts-collums">
											RECEBIDO
										</div>
										
										<div class="col-md-7 checkouts-collums left-collum">
											TOTAL:
										</div>
										<div class="col-md-5 checkouts-collums">
											R$ 1999,00
										</div>
										</div>
										</div>										
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
						</div>
                    </div>
				</form>
				<div class="form-group">
                            <div class=" buttonLocation">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
	
	
	<!-- MODAL EXCLUIR AGENDAMENTO-->
	<div class="modal fade " id="confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-target="#smallModal" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <h4>Deseja realmente excluir este agendamento?</h4>
				<input type="hidden" id="eventScheduleID" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-danger btn-ok" id="btnModalDeleteSchedule">Excluir</a>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ALTERAR DATA DO AGENDAMENTO-->
	<div class="modal fade " id="confirmChageDate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-target="#smallModal" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <h4>Deseja alterar este agendamento?</h4>
				<input type="hidden" id="updateScheduleDate" />
				<input type="hidden" id="updateScheduleHourStart" />
				<input type="hidden" id="updateScheduleHourEnd" />
				<input type="hidden" id="updateScheduleID" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-success btn-ok" id="btnModalUpdateSchedule">Alterar</a>
            </div>
        </div>
    </div>
</div>

<div id="div-loading" style="z-index: 99999; position: absolute; top: 25%; left: 45%; width: 100px; display: none;" >

	<?php echo $this->Html->image('loading.gif', array('width' => '100')); ?>

</div>
