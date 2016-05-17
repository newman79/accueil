<?php
	header('Content-type: application/json');

	$cmd = "ps aux --sort=-pcpu | awk 'BEGIN {} {  } ($3 > 0.5) { print $2\"   \"$3\"   \"$11 $12 } END {}'";
	//$cmd="ls";
	$output = split("\n", shell_exec($cmd));
	//$output = [ "zaezea, eazeaze ,azeazeaz" ];
	
	array_shift($output);
	// array_shift($output);
	// array_pop($output);
	
	$result = array();	
	$i = 0;
	foreach ($output as $value)
	{	
		$value=rtrim($value);
		if (strlen ($value) > 2)
		{
			try
			{
				$lineArray = split(' +',$value);
				$result[$i]["PID"] = $lineArray[0];		
				$result[$i]["CPU"] = $lineArray[1];
				$result[$i]["CMD"] = $lineArray[2];
				$i = $i + 1;
			}
			catch(Exception $exception)
			{
				$result[$i]["ERROR"] = $value;
				$i = $i + 1;
			}
				
			}
	}		
	echo json_encode($result,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);    				
?>