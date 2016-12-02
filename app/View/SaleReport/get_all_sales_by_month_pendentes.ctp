<table class="table table-bordered table-condensed small" id="tableAllSalesPending">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Status</th>
                            <th class="col-md-3">Produto</th>
                            <th>Comprador</th>
                            <th>Valor</th>
                            <th>Comentario</th>
                            <th>Etiqueta</th>
							<th>Detalhe</th>
                        </tr>
                    </thead>
                    <tbody class="searchable">
                        <?php
                        if (is_array($allSalesPending)) {

							
                            foreach ($allSalesPending as $salePending) {
							
							if($salePending['checkouts']['payment_state_id'] != 4 && $salePending['checkouts']['payment_state_id'] != 5 && $salePending['checkouts']['payment_state_id'] != 8
							&& $salePending['checkouts']['payment_state_id'] != 9){
							
                                $payment_state_id = "";
                                switch ($salePending['checkouts']['payment_state_id']) {
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
                                        $payment_state_id = "CARTÃO DE CRÉDITO";
                                        break;
                                    case 6:
                                        $payment_state_id = "EM ANALISE";
                                        break;
                                    case 7:
                                        $payment_state_id = "CARTÃO DE CRÉDITO";
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

                               // if (!empty($salePending['offers_comments'])) {
                                //    $offerComment = substr($salePending['offers_comments']['description'], 0, 300);
                               // } else {
                                    $offerComment = "Não possui comentário.";
                                //}
                                echo '
                                    <tr>
                                        <td>' . date('d/m/Y', strtotime($salePending['checkouts']['date'])) . '</td>
                                        <td>' . $payment_state_id . '</td>
                                        <td>' . $salePending['offers']['title'] . '</td>
                                        <td>' . $salePending['users']['name'] . '</td>
                                        <td> R$' . $salePending['checkouts']['total_value'] . '</td>
                                        <td>' . $offerComment . '</td>
                                        <td><span id="' . $salePending['checkouts']['id'] . '" class="glyphicon glyphicon-tags"></span></td>
										<td><a href="#" onclick="showCheckoutDetail('.$salePending['checkouts']['id'] .')"><span class="glyphicon glyphicon-plus"></span></a></td>
                                    </tr>';
                            }}
                        }
                        ?>
                    </tbody>
                </table>