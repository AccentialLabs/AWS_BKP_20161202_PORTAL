	<?php 
	echo $this->Html->script('View/ComissionReport', array('inline' => false));
	?>

	<div>
		<h1 class="page-header letterSize"><span>Rel. de Comissionamento</span></h1>
	</div>

	<br/>

	<ul class="nav nav-tabs">
		<?php 
				$contador = 0;
				if(!empty($secondsUsers)){
				
					foreach($secondsUsers as $user){ ?>
					<li role="presentation" class="<?php if($contador == 0){echo "active";}?>"><a data-toggle="tab" href="#<?php echo $user['secondary_users']['id']; ?>"><?php echo $user['secondary_users']['name']; ?></a></li>
				<?php			$contador++;}
		
				
				}
			?>

	</ul>
	

	<div class="tab-content">

		<?php 
				$contador = 0;
				if(!empty($secondsUsers)){
				
					foreach($secondsUsers as $user){ ?>
	<div id="<?php echo $user['secondary_users']['id']; ?>" class="tab-pane fade <?php if($contador == 0){echo "in active"; }?>">
		<h3><?php echo $user['secondary_users']['name']; ?><br/><small>Relatório do mês: <span class="monthRelatorio"><?php  echo date('m'); ?></span></small></h3>
		
		<!-- AQUI VEM O CONTEUDO DA TAB, ONDE COLOCAREMOS UMA TABELA COM OS VALORES DAS VENDAS E COMISSÕES -->
		
			 <table class="table table-bordered table-condensed small" id="tableAllSales">
					<thead>
					<tr>
					<th colspan="4"></th>
						<th colspan="2" class="text-right">
							<select class="form-control col-md-5 monthToCommissioned" id="select-<?php echo $user['secondary_users']['id']; ?>" name="<?php echo $user['secondary_users']['id']; ?>">
								<option value="0">Selecione o mês</option>
								<option value="1">Jan</option>
				<option value="2">Fev</option>
				<option value="3">Mar</option>
				<option value="4">Abr</option>
				<option value="5">Mai</option>
				<option value="6">Jun</option>
				<option value="7">Jul</option>
				<option value="8">Ago</option>
				<option value="9">Set</option>
				<option value="10">Out</option>
				<option value="11">Nov</option>
				<option value="12">Dez</option>
								?>
							</select>
						</th>
					</tr>
                    
                        <tr>
                            <th >Data</th>
                            <th class="text-center">Status</th>
                            <th class="col-md-3">Produto</th>
                            <th class="text-center">Comprador</th>
                            <th class="text-center">Comissão Total</th>
							<th class="text-center">Comissão Profissional</th>

                        </tr>
                    </thead>
                    <tbody class="searchable" id="tbody-<?php echo $user['secondary_users']['id']; ?>">
                        <?php
                        if (is_array($checkouts[$user['secondary_users']['id']])) {
							$totalCommisioned = 0;
                            foreach ($checkouts[$user['secondary_users']['id']] as $saleAll) {
							
								$valorTotal = 0;
								$isCommissioned = '';
								$comissionValue  = "<small>venda própria</small>";
								if($saleAll['checkouts']['commissioned_company_id'] != 0){
									$isCommissioned = 'commissionedRegister';
									$comissionValue = 'R$'.$saleAll['financial_parameters_results']['secondary_user_commission'];
									$totalCommisioned = $totalCommisioned+$saleAll['financial_parameters_results']['secondary_user_commission'];
									
									$valorTotal = $saleAll['financial_parameters_results']['vl_salao'];
									
								}
								
								if($valorTotal == 0){
								
									$valorTotal =  $saleAll['checkouts']['total_value'];
								}
							
                                $payment_state_id = "";
                                switch ($saleAll['checkouts']['payment_state_id']) {
                                    case 1:
                                        $payment_state_id = "AUTORIZADO";
                                        break;
                                    case 2:
                                        $payment_state_id = "INICIADO";
                                        break;
                                    case 3:
                                        $payment_state_id = "BOLETO IMPRESSO";
                                        break;
                                    case 4:
                                        $payment_state_id = "CONCLUIDO";
                                        break;
                                    case 5:
                                        $payment_state_id = "CANCELADO";
                                        break;
                                    case 6:
                                        $payment_state_id = "EM ANALISE";
                                        break;
                                    case 7:
                                        $payment_state_id = "ESTORNADO";
                                        break;
                                    case 8:
                                        $payment_state_id = "EM REVISAO";
                                        break;
                                    case 9:
                                        $payment_state_id = "REEMBOLSADO";
                                        break;
                                    case 14:
                                        $payment_state_id = "INICIO DA TRANSACAO";
                                        break;
                                    case 73:
                                        $payment_state_id = "BOLETO IMPRESSO";
                                        break;
                                }


                                echo '
                                    <tr class="'.$isCommissioned.'">
                                        <td>' . date('d/m/Y', strtotime($saleAll['checkouts']['date'])) . '</td>
                                        <td class="text-center">' . $payment_state_id . '</td>
                                        <td>' . $saleAll['offers']['title'] . '</td>
                                        <td class="text-center">' . $saleAll['users']['name'] . '</td>
                                        <td class="text-center"> R$' .  $valorTotal . '</td>
										<td class="text-center">'.$comissionValue.'</td>
                                    </tr>';
                            }?>
								<tr>
									<td  colspan="5" class="text-right"><strong><h4>TOTAL DE COMISSÃO:<br/><small>mês <?php echo date('m');?></small></h4></strong></td>
									<td class="text-center"><h3><?php echo 'R$'.str_replace(".", ",", $totalCommisioned); ?></h3></td>
								</tr>
							<?php
                        }
                        ?>
						
                    </tbody>
                </table>
		
		<!-- FIM DO CONTEUDO DA TAB -->
		
	  </div>
					<?php $contador++;}
					}?>

	</div>