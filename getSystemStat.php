<?php
	header('Content-type: application/json');

	$d1	= $_GET['d1'];
	$d2	= $_GET['d2'];
	if (empty($d1))
	{
		echo "{}";
	}
	else
	{
		$cmd = "/home/pi/scripts/python/SystemStatGrabber.py  -get -d1 ".$d1." -d2 ".$d2;
		$output = rtrim(shell_exec($cmd));	
		$result=json_decode($output);		
		echo json_encode($result,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);    						
	}	
?>