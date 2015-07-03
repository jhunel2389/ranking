<?php

    include(dirname(__FILE__)."/core/db_config.php");
 	
 	$popularity = $db->get ('fw_popularity');
	/*$criteria = $db->get ('fm_criteria');*/
	//$ranking = $db->get ('fw_ranking');


	$userId = [];
	$result = [];
	$temp = 0;
	$temp2 = 0;
	foreach ($popularity as $popularityi) 
	{
		if(!in_array($popularityi['fw_user_id'], $userId))
		{
			$userId[count($userId)] = $popularityi['fw_user_id'];
		}
	}
	foreach ($userId as $userIdi) 
	{
		$db->where ("fw_user_id", $userIdi);
	  	$eventList = $db->get("fw_popularity");
	  	$total = 0;
	  	foreach ($eventList as $eventListi) {
	  		$db->where ("id", $eventListi['event_id']);
	  		$eventInfo = $db->getOne("fm_criteria");
	  		$percentage =  $eventInfo['percentage'];
	  		$count = $eventListi['count'];
	  		$total = $total + ($count * $percentage);
	  	}
		$ctr = count($result);
	  	$result[$ctr][0] = $userIdi;
	  	$result[$ctr][1] = $total;
	}
	for($x=0 ; $x < count($result) ; $x++)
	{
		for ($y=0 ; $y < count($result)-1 ; $y++) 
		{ 
			if($result[$y][1] < $result[$y+1][1])
			{
				$temp = $result[$y][1];
				$temp2 = $result[$y][0];
				$result[$y][1] = $result[$y+1][1];
				$result[$y][0] = $result[$y+1][0];
				$result[$y+1][1] = $temp;
				$result[$y+1][0] = $temp2;
			}
		}
	}
	
	//foreach ($result as $resulti) {
		$db->rawQuery('TRUNCATE TABLE fw_ranking');
		for($x=0 ; $x < count($result) ; $x++)
		{
			if($x < 100)
			{
				$data = Array (
		               "fw_user_id" => $result[$x][0],
		               "total" => $result[$x][1]
				);
				$db->insert('fw_ranking', $data);
			}
		}

		# code...
	//}
	//echo $result[0][0]."=====".$result[0][1];

?>
		
	   



