<?php $this->Html->css('View/ScheduleReport.index', array('inline' => false)); ?>
<?php $this->Html->script('View/ScheduleReport', array('inline' => false)); ?>
<?php $this->Html->css('View/ClientReport.index', array('inline' => false)); ?>
<h1 class="page-header" >Relatório de Agendamentos</h1>

<div class="row">
<div class="col-md-4">
	<label>Escolha o profissional</label>
	<select class="form-control" id="filterProfessionalID">
		<option value="0">Todos</option>
		<?php foreach($secondaryUsers as $second){?>
			<option value="<?php echo $second['secondary_users']['name']; ?>"><?php echo $second['secondary_users']['name']; ?></option>
		<?php }?>
	</select>
	<br/><br/>
	</div>
</div>
<div>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#sectionA">Agendamentos Hoje</a></li>
        <li><a data-toggle="tab" href="#sectionB">Agendamentos Passados</a></li>
        <li><a data-toggle="tab" href="#sectionC">Agendamentos Futuros</a></li>
    </ul>
    <div class="tab-content">
        <div id="sectionA" class="tab-pane fade in active">
            <table class="table table-bordered table-condensed small" id="tableToday">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Servico</th>
                        <th>Data</th>
                        <th>Horario</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th>Profissional</th>
						<th>Detalhes</th>
						<th></th>
						<th></th>
                    </tr>
                </thead>
                <tbody class="searchable">
                    <?php
                    if (is_array($allSchedules)) {
                        foreach ($allSchedules as $schedule) {
							$isCanceled = '';
						
                            if ($schedule['schedules']['status'] == 1) {
                                $scheduleStatus = "AGENDADO";
                            } else if($schedule['schedules']['status'] == 0){
                                $scheduleStatus = "REALIZADO";
                            }else if($schedule['schedules']['status'] == 2){
                                $scheduleStatus = "CANCELADO";
								$isCanceled = 'canceled-schedule';
                            }else{
								 $scheduleStatus = "NÃO INFORMADO";
							}
							$secUser = split(" ", $schedule['secondary_users']['name']);
                            echo '
                                <tr class='.$isCanceled.'>
                                    <td><a href="#" onclick="showUserDetail('.$schedule['schedules']['user_id'].')">' . $schedule['schedules']['client_name'] . '</a></td>
                                    <td><a href="#">' . $schedule['schedules']['subclasse_name'] . '</a></td>
                                    <td><span id="scheduleDate'.$schedule['schedules']['id'].'">' . implode("/", array_reverse(explode("-", $schedule['schedules']['date']))) . '</span></td>
                                    <td><span id="scheduleHour'.$schedule['schedules']['id'].'">' . substr($schedule['schedules']['time_begin'], 0, 5) . '</span></td>
                                    <td>R$ ' . number_format($schedule['schedules']['valor'], 2, ",", ".") . '</td>
                                    <td>' . $scheduleStatus . '</td>
                                    <td title="' . $schedule['secondary_users']['name'] . '">' . $schedule['secondary_users']['name'] . '</td>
									<td><a href="#" onclick="showScheduleDetail('.$schedule['schedules']['id'].')")"><span class="glyphicon glyphicon-plus"></span></a></td>
									<td><a href="#"><span class="glyphicon glyphicon-pencil edit-schedule" id="'.$schedule['schedules']['id'].'"></span> <span class="glyphicon glyphicon-ok save-edit-schedule"  id="'.$schedule['schedules']['id'].'"></span></a></td>
									<td><a href="#"><span class="glyphicon glyphicon-remove remove-schedule" style="cursor: pointer;"  id="'.$schedule['schedules']['id'].'"></span></a></td>
                                </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div id="sectionB" class="tab-pane fade">
            <table class="table table-bordered table-condensed small" id="tablePass">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Servico</th>
                        <th>Data</th>
                        <th>Horario</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th>Profissional</th>
						<th>Detalhe</th>
						<th>Repetir<br/>Agendamento</th>
                    </tr>
                </thead>
                <tbody class="searchable">
                    <?php
                    if (is_array($allSchedulesPrevious)) {
                        foreach ($allSchedulesPrevious as $schedule) {
                              if ($schedule['schedules']['status'] == 1) {
                                $scheduleStatus = "AGENDADO";
                            } else if($schedule['schedules']['status'] == 0){
                                $scheduleStatus = "REALIZADO";
                            }else if($schedule['schedules']['status'] == 2){
                                $scheduleStatus = "CANCELADO";
								$isCanceled = 'canceled-schedule';
                            }else{
								 $scheduleStatus = "NÃO INFORMADO";
							}
							$secUs = split(" ", $schedule['secondary_users']['name']);
                            echo '
                                <tr>
                                    <td><a href="#" onclick="showUserDetail('.$schedule['schedules']['user_id'].')">' . $schedule['schedules']['client_name'] . '</a></td>
                                    <td><a href="#">' . $schedule['schedules']['subclasse_name'] . '</a></td>
                                    <td>' . implode("/", array_reverse(explode("-", $schedule['schedules']['date']))) . '</td>
                                    <td>' . substr($schedule['schedules']['time_begin'], 0, 5) . '</td>
                                    <td>R$ ' . number_format($schedule['schedules']['valor'], 2, ",", ".") . '</td>
                                    <td>' . $scheduleStatus . '</td>
                                    <td title="' . $schedule['secondary_users']['name'] . '">' . $schedule['secondary_users']['name']. '</td>
									<td><a href="#" onclick="showScheduleDetail('.$schedule['schedules']['id'].')")"><span class="glyphicon glyphicon-plus"></span></a></td>
									<td><a href="#" onclick="addNewSchedule(
											\''. $schedule['schedules']['service_id'].'\', 
											\''. $schedule['schedules']['valor'].'\', 
											\''. $schedule['schedules']['client_name'].'\', 
											\''. $schedule['schedules']['client_phone'] .'\',
											'. $schedule['schedules']['user_id'].',
											'. $schedule['schedules']['secondary_user_id'].'
									);" ><span class="glyphicon glyphicon-repeat repeat-icon" ></span></a></td>
                                </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div id="sectionC" class="tab-pane fade">
            <table class="table table-bordered table-condensed small" id="tableFuture">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Servico</th>
                        <th>Data</th>
                        <th>Horario</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th>Profissional</th>
						<th>Detalhe</th>
						<td></td>
						<td></td>
                    </tr>
                </thead>
                <tbody class="searchable">
                    <?php
                    if (is_array($allSchedulesNext)) {
                        foreach ($allSchedulesNext as $schedule) {
                            if ($schedule['schedules']['status'] == 1) {
                                $scheduleStatus = "AGENDADO";
                            } else if($schedule['schedules']['status'] == 0){
                                $scheduleStatus = "REALIZADO";
                            }else if($schedule['schedules']['status'] == 2){
                                $scheduleStatus = "CANCELADO";
								$isCanceled = 'canceled-schedule';
                            }else{
								 $scheduleStatus = "NÃO INFORMADO";
							}
							$secUs = split(" ", $schedule['secondary_users']['name']);
                            echo '
                                <tr>
                                    <td><a href="# "onclick="showUserDetail('.$schedule['schedules']['user_id'].')">' . $schedule['schedules']['client_name'] . '</a></td>
                                    <td><a href="#">' . $schedule['schedules']['subclasse_name'] . '</a></td>
                                    <td><span id="scheduleDate'.$schedule['schedules']['id'].'">' . implode("/", array_reverse(explode("-", $schedule['schedules']['date']))) . '</span></td>
                                    <td><span id="scheduleHour'.$schedule['schedules']['id'].'">' . substr($schedule['schedules']['time_begin'], 0, 5) . '</span></td>
                                    <td>R$ ' . number_format($schedule['schedules']['valor'], 2, ",", ".") . '</td>
                                    <td>' . $scheduleStatus . '</td>
                                    <td title="' . $schedule['secondary_users']['name'] . '">' . $schedule['secondary_users']['name'] . '</td>
									<td><a href="#" onclick="showScheduleDetail('.$schedule['schedules']['id'].')")"><span class="glyphicon glyphicon-plus"></span></a></td>
									<td><a href="#"><span class="glyphicon glyphicon-pencil edit-schedule" id="'.$schedule['schedules']['id'].'"></span> <span class="glyphicon glyphicon-ok save-edit-schedule"  id="'.$schedule['schedules']['id'].'"></span></a></td>
									<td><a href="#"><span class="glyphicon glyphicon-remove remove-schedule" style="cursor: pointer;"  id="'.$schedule['schedules']['id'].'"></span></a></td>
									
                                </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>




<!-------------------------------->

<div id="myModalSchedulesRequisitions" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-md" >
        <div class="modal-content" id="modelContent">
            <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Agendamento</h4>
				</div>
			<div class="modal-body" id="recebe">
				<div class=" row">
				<div class="col-md-12">
				<h5>Cliente</h5>
					 <div class="col-md-3">
					 <img src="../portal/app/img/jezzy_images/user.png"  class="schedule-detail"/></div>
					 <div class="col-md-9" >
						<span class='label'>Matheus Odilon</span><br/>
						<small>(11) 90000-0000</small><br/>
						<span class="labely label-primary">Masculino</span><br/>
						<span> Rua hermenegildo nº120 barreto bl 1 apto 33 - Fazando Itajuibe, Ferraz de Vasconcelos - SP - 08540500 </span>
					 </div>
				</div>
				</div>
				<hr />
				<div class=" row">
				<div class="col-md-12">
				<h5>Profissional</h5>
					 <div class="col-md-3">
					 <img src="../portal/app/img/jezzy_images/professional.png"  class="schedule-detail"/></div>
					 <div class="col-md-9" >
						<span class='label'>Matheus Odilon</span><br/>
						<span class="labely label-success">Funcionário</span><br/>
						<span><small><a href="mailto:matheusodilon0@gmail">matheusodilon0@gmail.com</a></small></span><br/>

					 </div>
				</div>
				</div>
				
				<hr />
				<div class=" row">
				<div class="col-md-12">
				<h5>Serviço</h5>
					 <div class="col-md-12" >
						<span class='label'>Banho de Brilho</span><br/>
						<span class='label'>Data: </span>12/01/2016<br/>
						<span class='label'>Hora: </span>12h<br/>
						<span class="label">TOTAL: </span>R$12220,00


					 </div>
				</div>
				</div>
				
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
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
                <div class="form-horizontal" id="recebe-user-details">
                    
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

	<!-- REPEAT SCHEDULE -->
	<div id="myModalRepeatSchedule" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
		<div class="modal-dialog modal-sm" >
			<div class="modal-content" id="modelContent">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Repetir Agendamento</h4>
			</div>
				<div class="modal-body">
									
					<div class="col-md-7 checkouts-collums left-collum">Data:</div>
					<input type="date" id="repeatScheduleDate" class="form-control"/><br/>
					<div class="col-md-7 checkouts-collums left-collum">Horário:</div>
					<input type="time" id="repeatScheduleHour" class="form-control"/>
					
					
					<input type="text" id="repeatService" class="modal-form-repeat"/>
					<input type="text" id="repeatPrice" class="modal-form-repeat"/>
					<input type="text" id="repeatClient" class="modal-form-repeat"/>
					<input type="text" id="repeatPhone" class="modal-form-repeat"/>
					<input type="text" id="repeatUserId" class="modal-form-repeat"/>
					<input type="text" id="repeatSecondaryID" class="modal-form-repeat"/>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					 <button type="button" class="btn btn-success" id="btnNewSchedule">Agendar</button>
				</div>
			</div>
        </div>
    </div>

	


	
