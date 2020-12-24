<?php
	header('Content-type: application/json');

	#$output = split("\n", shell_exec('sudo netstat -eltu --numeric-ports'));
	$output = split("\n", shell_exec('sudo netstat -tulpn'));
	
	
	array_shift($output);
	array_shift($output);
	array_pop($output);
	
	$result = array();
	
	$i = 0;
	foreach ($output as $value)
	{	
		$value = str_replace("LISTEN","",$value);
		$lineArray = split(' +',$value);
		
		$result[$i]["Prot"] = $lineArray[0];								# result col affectation
		
		$port = preg_replace('/.*\:/',"",$lineArray[3]);					# result col affectation
		$result[$i]["Port"] = $port;
		
		$result[$i]["Interface"] = "ALL";									# result col affectation
		$ipAddr = preg_replace('/:.*/',"",$lineArray[3]);					
		if ($ipAddr != "0.0.0.0") {	$result[$i]["Interface"] = $ipAddr;	}	
		
		$pidProcessArray = split('/',$lineArray[5]);		
		$result[$i]["PID"] = $pidProcessArray[0];							# result col affectation
		
		$result[$i]["Process"] = $pidProcessArray[1];						# result col affectation
		//$shCmd = 'sudo lsof -i :'.$port . " | tail -n 1 | cut -d' ' -f1";
		//$result[$i]["Pgrm"] = shell_exec($shCmd);
		$i = $i + 1;
	}
	
	foreach ($result as $index => $row) 
	{
		$Prot[$index]  			= $row['Prot'];
		$Port[$index] 			= $row['Port'];
		$Interface[$index]  	= $row['Interface'];
		$PID[$index] 			= $row['PID'];
		$Process[$index] 		= $row['Process'];
	}

	array_multisort($Prot, SORT_ASC, $Process, SORT_ASC, $Port, SORT_ASC, $result);	
	
	
	echo json_encode($result,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);    				
?>