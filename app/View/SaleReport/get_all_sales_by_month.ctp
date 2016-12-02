 <table class="table table-bordered table-condensed small" id="tableId">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Status</th>
                            <th class="col-md-3">Produto</th>
                            <th>Comprador</th>
                            <th>Valor Total</th>
							<th>Comissão</th>
                            <th>Comentario</th>
                            <th>Etiqueta</th>
							<th>Detalhe</th>
                        </tr>
                    </thead>
                    <tbody class="searchable">
                        <?php
                        if (is_array($allSales)) {
                            foreach ($allSales as $saleAll) {
							
								$isCommissioned = '';
								$comissionValue  = "<small>venda própria</small>";
								if($saleAll['checkouts']['commissioned_company_id'] != 0){
									$isCommissioned = 'commissionedRegister';
									$comissionValue = 'R$'.$saleAll['financial_parameters_results']['vl_salao'];
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

                                if ($saleAll['offers_comments']) {
                                    $offerComment = substr($saleAll['offers_comments']['description'], 0, 300);
                                } else {
                                    $offerComment = "Não possui comentário.";
                                }
								
								
								
                                echo '
                                    <tr class="'.$isCommissioned.'">
                                        <td class="registerDate">' . date('d/m/Y', strtotime($saleAll['checkouts']['date'])) . '</td>
                                        <td>' . $payment_state_id . '</td>
                                        <td>' . $saleAll['offers']['title'] . '</td>
                                        <td>' . $saleAll['users']['name'] . '</td>
                                        <td> R$' . $saleAll['checkouts']['total_value'] . '</td>
											<td>'.$comissionValue.'</td>
                                        <td>' . $offerComment . '</td>
                                        <td><span id="' . $saleAll['checkouts']['id'] . '" class="glyphicon glyphicon-tags"></span></td>
										<td><a href="#" onclick="showCheckoutDetail('.$saleAll['checkouts']['id'] .')"><span class="glyphicon glyphicon-plus"></span></a></td>
                                    </tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>