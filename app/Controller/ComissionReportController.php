<?php

/**
 * All action about clients report
 */
class ComissionReportController extends AppController {


					public function __construct($request = null, $response = null) {
						 $this->layout = 'default_business';
        $this->set('title_for_layout', 'Rel. Comissionamento');
        parent::__construct($request, $response);
					}

					/**
					 * Check the session every time the class is call, exepts on 'logout' 
					 */
					public function beforeFilter() {
						if ($this->action !== "logout") {
							
						}
					}

    public function index() {
	
		$secondsUsers  =$this->getSecondaryUsers();
		$checkouts = $this->getCheckoutsByCommissionedSecondaryUsers($secondsUsers);

		$this->set("secondsUsers", $secondsUsers);
		$this->set("checkouts", $checkouts);
    }
	
	public function getSecondaryUsers(){
	
		$company = $this->Session->read('CompanyLoggedIn');
		$sql = "SELECT * FROM secondary_users WHERE company_id = {$company['Company']['id']}";
		 $params = array('User' => array('query' => $sql));
        $users = $this->AccentialApi->urlRequestToGetData('users', 'query', $params);
		return $users;
	}
	
	/**
	* Busca todas as vendas da empresa e da empresa JEZZY 
	* que o usuário secundário fora indicado para comissão
	*
	* A primeira chave do array (após contagem normal) será o ID do usuario secundario,
	* o valor será os respectivos checkouts
	*/
	public function getCheckoutsByCommissionedSecondaryUsers($secondsUsers){
	
		$checkoutsByUsers = '';
		$contador = 0;
		$dateMonth = date('m');
		$dateYear = date('Y');
		foreach($secondsUsers as $user){
		
			$sql = "SELECT * FROM checkouts 
						INNER JOIN offers on offers.id = checkouts.offer_id 
						INNER JOIN users on users.id = checkouts.user_id
						INNER JOIN payment_states on payment_states.id = checkouts.payment_state_id
						LEFT JOIN financial_parameters_results on financial_parameters_results.checkout_id = checkouts.id
						WHERE comissioned_secondary_user_id = {$user['secondary_users']['id']}
						and checkouts.payment_state_id = 4
						and MONTH(checkouts.date) = {$dateMonth}
						and YEAR(checkouts.date) = {$dateYear};";
						$params = array('User' => array('query' => $sql));
        $checkouts = $this->AccentialApi->urlRequestToGetData('users', 'query', $params);
		
		$checkoutsByUsers[$user['secondary_users']['id']] = $checkouts;
		$contador++;
		}
		return $checkoutsByUsers;
	}
	
	/**
	* Busca todas as vendas da empresa e da empresa JEZZY 
	* que o usuário secundário fora indicado para comissão
	*
	* A primeira chave do array (após contagem normal) será o ID do usuario secundario,
	* o valor será os respectivos checkouts
	*/
	public function getCheckoutsByCommissionedSecondaryUsersByMonth(){
			$this->layout = false;
		
			$secondaryUserID = $this->request->data['userID'];
			$month = $this->request->data['month'];
			$dateYear = date('Y');
			$checkoutsByUsers = '';
			$sql = "SELECT * FROM checkouts 
						INNER JOIN offers on offers.id = checkouts.offer_id 
						INNER JOIN users on users.id = checkouts.user_id
						INNER JOIN payment_states on payment_states.id = checkouts.payment_state_id
						LEFT JOIN financial_parameters_results on financial_parameters_results.checkout_id = checkouts.id
						WHERE comissioned_secondary_user_id = {$secondaryUserID}
						and checkouts.payment_state_id = 4
						and MONTH(checkouts.date) = {$month}
						and YEAR(checkouts.date) = {$dateYear};";
						$params = array('User' => array('query' => $sql));
        $checkouts = $this->AccentialApi->urlRequestToGetData('users', 'query', $params);
		
		$checkoutsByUsers[$secondaryUserID] = $checkouts;

		$this->set("secondaryUserID", $secondaryUserID);
		$this->set("checkouts", $checkoutsByUsers);
		// print_r($checkoutsByUsers);
	}
	
	}

	
	