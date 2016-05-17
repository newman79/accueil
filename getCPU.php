<?php
	header('Content-type: application/json');

	$cmd = "vmstat 1 3 | tail -n 1";
	$output = rtrim(shell_exec($cmd));	
	$result = array();
	
	if (strlen($output) > 2)
	{
		$outputArray = split(' +', $output );
		try
		{
			// Cpu
			$result["IDLE"] = $outputArray[15];
			$result["SYS"] = $outputArray[14];
			$result["USR"] = $outputArray[13];
			$result["WAI"] = $outputArray[16];
			// Mémoire
			$result["FREE"] = $outputArray[4];
			$result["BUFF"] = $outputArray[5];
			$result["CACH"] = $outputArray[6];
		}
		catch(Exception $exception)
		{
			$result["result"] = "KO.exception";
		}
	}		
	else
	{
		$result["result"] = "KO";
	}
	echo json_encode($result,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);    				
?>