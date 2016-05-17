<?php
	header('Content-type: application/json');

	$output = split("\n", shell_exec('netstat -eltu --numeric-ports'));
	
	array_shift($output);
	array_shift($output);
	array_pop($output);
	
	$result = array();
	
	$i = 0;
	foreach ($output as $value)
	{		
		$lineArray = split(' +',$value);
		
		$result[$i]["Prot"] = $lineArray[0];		
		$port = preg_replace('/.*\:/',"",$lineArray[3]);
		$result[$i]["Port"] = $port;
		$ipAddr = preg_replace('/:.*/',"",$lineArray[3]);
		$result[$i]["Interface"] = "ALL";
		if ($ipAddr != "0.0.0.0") {	$result[$i]["Interface"] = $ipAddr;	}
		
		$result[$i]["User"] = $lineArray[6];
		//$shCmd = 'sudo lsof -i :'.$port . " | tail -n 1 | cut -d' ' -f1";
		//$result[$i]["Pgrm"] = shell_exec($shCmd);
		$i = $i + 1;
	}	
	
	echo json_encode($result,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);    				
?>