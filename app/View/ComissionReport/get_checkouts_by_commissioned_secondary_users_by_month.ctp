
                        <?php
                        if (is_array($checkouts[$secondaryUserID])) {
							$totalCommisioned = 0;
                            foreach ($checkouts[$secondaryUserID] as $saleAll) {
							
								
								$isCommissioned = '';
								$comissionValue  = "<small>venda própria</small>";
								if($saleAll['checkouts']['commissioned_company_id'] != 0){
									$isCommissioned = 'commissionedRegister';
									$comissionValue = 'R$'.$saleAll['financial_parameters_results']['secondary_user_commission'];
									$totalCommisioned = $totalCommisioned+$saleAll['financial_parameters_results']['secondary_user_commission'];
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
                                        <td class="text-center"> R$' . $saleAll['checkouts']['total_value'] . '</td>
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
						
                 