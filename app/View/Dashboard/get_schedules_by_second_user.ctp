
						<div class="col-sm-12">
								<label for="initialTimeSchecule">Horário</label>
								<select class="form-control" id="initialTimeSchecule">
							<?php 
								$start = $company['Company']['open_hour'];
$end = $company['Company']['close_hour'];

$tStart = strtotime($start);
$tEnd = strtotime($end);
$tNow = $tStart;

while($tNow <= $tEnd){
	$disabled = '';
	$agendado  = '';
	
	if(!empty($schedules)){
	foreach($schedules as $sch){
	
		if(date("H:i",$tNow) >= $sch['schedules']['time_begin'] && date("H:i",$tNow) <= $sch['schedules']['time_end'] ){
			$disabled = "disabled";
			$agendado = " - ocupado";
		
		}
	
	}}
  echo '<option value="'.date("H:i",$tNow).'" '.$disabled.'>'.date("H:i",$tNow).$agendado.'</option>';
  $tNow = strtotime('+15 minutes',$tNow);
}
							?>
							</select>
							<small><span id="showTimeErrorSchedule" class="textError"></span></small>
						</div>
					