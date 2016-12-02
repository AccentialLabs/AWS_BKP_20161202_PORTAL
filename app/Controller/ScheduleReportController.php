<?php

/**
 * All action about schedule report
 */
class ScheduleReportController extends AppController {

    public function __construct($request = null, $response = null) {
        $this->layout = 'default_business';
        $this->set('title_for_layout', 'Rel.Agendamento');
        parent::__construct($request, $response);
    }

    /**
     * Show populated view
     */
    public function index() {
        $company = $this->Session->read('CompanyLoggedIn');
        $this->set('allSchedules', $this->getAllSchecule($company));
        $this->set('allSchedulesNext', $this->getAllSchecule($company, " > "));
        $this->set('allSchedulesPrevious', $this->getAllSchecule($company, " < "));
		$this->set('secondaryUsers', $this->getSecondaryUsers($company));
    }

    // <editor-fold  defaultstate="collapsed" desc="Private Methods">
    /**
     * Gets all schecule for this company
     * @param type $company
     * @return array whith all schecule
     */
    private function getAllSchecule($company, $dateComparison = "=") {
	date_default_timezone_set("America/Sao_Paulo");
        $scehduleSQL = "
            SELECT schedules.*, secondary_users.*
            FROM schedules
            INNER JOIN secondary_users
                ON schedules.secondary_user_id = secondary_users.id
            WHERE schedules.companie_id = '" . $company['Company'] ['id'] . "'
            AND schedules.date " . $dateComparison . " '" . date('Y-m-d') . "'";
        $scheduleParam = array(
            'Schedule' => array(
                'query' => $scehduleSQL
            )
        );
        return $this->AccentialApi->urlRequestToGetData('schedules', 'query', $scheduleParam);
    }

    // </editor-fold>
    // <editor-fold  defaultstate="collapsed" desc="REMOVE ON PRODUCTION">
    private function setTestVariable() {
        return Array
            (
            0 => Array
                (
                'Schedule' => Array
                    (
                    'id' => 75,
                    'user_id' => 290,
                    'company_id' => 119,
                    'status' => 'DONE',
                    'date' => '2014-10-03 22:20:30',
                    'hour' => '22:22',
                    'value' => 393.70
                ),
                'User' => Array
                    (
                    'id' => 290,
                    'name' => 'Matheus 1'
                ),
                'Sevices_sub_categories' => Array
                    (
                    'id' => 3,
                    'name' => 'Corte Marculino'
                ),
                'Secondary_users' => Array
                    (
                    'id' => 290,
                    'name' => 'Joaozinho'
                )
            ),
            1 => Array
                (
                'Schedule' => Array
                    (
                    'id' => 75,
                    'user_id' => 290,
                    'company_id' => 119,
                    'status' => 'DONE',
                    'date' => '2012-12-03 22:20:30',
                    'hour' => '10:22',
                    'value' => 393.70
                ),
                'User' => Array
                    (
                    'id' => 290,
                    'name' => 'Matheus 13'
                ),
                'Sevices_sub_categories' => Array
                    (
                    'id' => 3,
                    'name' => 'Secagem de unnha'
                ),
                'Secondary_users' => Array
                    (
                    'id' => 290,
                    'name' => 'Mariazinha'
                )
            ),
            2 => Array
                (
                'Schedule' => Array
                    (
                    'id' => 75,
                    'user_id' => 290,
                    'company_id' => 119,
                    'status' => 'DONE',
                    'date' => '2011-11-03 22:20:30',
                    'hour' => '11:22',
                    'value' => 393.70
                ),
                'User' => Array
                    (
                    'id' => 290,
                    'name' => 'Matheus 2'
                ),
                'Sevices_sub_categories' => Array
                    (
                    'id' => 3,
                    'name' => 'Corte Feminino'
                ),
                'Secondary_users' => Array
                    (
                    'id' => 290,
                    'name' => 'Pedrinho'
                )
            )
        );
    }
	
	public function getScheduleDetail(){
		
		$this->layout= '';
		 $scehduleSQL = "
            SELECT schedules.*, secondary_users.*
            FROM schedules
            INNER JOIN secondary_users
                ON schedules.secondary_user_id = secondary_users.id
            WHERE schedules.id = ". $this->request->data['checkoutId'].";";
        $scheduleParam = array(
            'Schedule' => array(
                'query' => $scehduleSQL
            )
        );
       $schedule =  $this->AccentialApi->urlRequestToGetData('schedules', 'query', $scheduleParam);

		$this->set('schedule', $schedule);
		
	}
	
	public function getUserDetail(){
		
		$this->layout= '';
		 $scehduleSQL = "
            SELECT schedules.*, secondary_users.*
            FROM schedules
            INNER JOIN secondary_users
                ON schedules.secondary_user_id = secondary_users.id
            WHERE schedules.id = 19;";
        $scheduleParam = array(
            'Schedule' => array(
                'query' => $scehduleSQL
            )
        );
       $schedule =  $this->AccentialApi->urlRequestToGetData('schedules', 'query', $scheduleParam);

		$this->set('schedule', $schedule);
		
	}
	
	public function getSecondaryUsers($company){
	
		$companyId = $company['Company'] ['id'];
		$sql = "SELECT * FROM secondary_users WHERE company_id = {$companyId} and excluded = 0; ";
		 $scheduleParam = array(
            'Schedule' => array(
                'query' => $sql
            )
        );
       $users =  $this->AccentialApi->urlRequestToGetData('schedules', 'query', $scheduleParam);
	   return $users;
	
	}
	
	public function removeSchedule(){
		$this->autoRender = false;
	
		$id = $this->request->data['scheduleID'];
		$sql = "UPDATE schedules set `status` = 2 WHERE id = {$id};";
		$scheduleParam = array(
            'Schedule' => array(
                'query' => $sql
            )
        );
      $this->AccentialApi->urlRequestToGetData('schedules', 'query', $scheduleParam);
	
	}
	
	public function saveEditSchedule(){
		$this->autoRender = false;
		
		$id = $this->request->data['scheduleID'];
		$date = $this->request->data['scheduleDate'];
		$hour = $this->request->data['scheduleHour'];
		$timeEnd = '';
		$originalDate = '';
		$sql = '';
		
		
		$select = "select * from schedules inner join services on services.id = schedules.service_id where schedules.id = {$id};";
		$param = array(
            'Schedule' => array(
                'query' => $select
            )
        );
      $schedule = $this->AccentialApi->urlRequestToGetData('schedules', 'query', $param);
	
		$plusMinutes = "+".$schedule[0]["services"]["time"]." minutes";
		$time = strtotime($hour);
	
		$timeEnd = date("H:i", strtotime($plusMinutes, $time));
		
		//caso data seja diferente de vazia
		if(!empty($date)){
		
			if(strpos($date, '/') !== false){
			
				$arrayDate = explode("/", $date);	
				$originalDate = $arrayDate[2].'-'.$arrayDate[1].'-'.$arrayDate[0];
			
			}else if(strpos($date, '-') !== false){
				$originalDate = $date;
			}
			
			$sql = "UPDATE schedules SET `date` = '{$originalDate}', `time_begin` = '{$hour}', `time_end` = '{$timeEnd}' WHERE id = {$id};";
		
		//caso data vazia
		}else{
			
			$sql = "UPDATE schedules SET `time_begin` = '{$hour}', `time_end` = '{$timeEnd}' WHERE id = {$id};";
		
		}
		
		$scheduleParam = array(
            'Schedule' => array(
                'query' => $sql
            )
        );
      $this->AccentialApi->urlRequestToGetData('schedules', 'query', $scheduleParam);
		//echo $timeEnd;
	}

    //</editor-fold>
}
