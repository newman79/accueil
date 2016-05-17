<?php
	header('Content-type: application/json');

	$result = array();
		
	
	//$result["Mysql"] 				= shell_exec('mysqladmin ping > /dev/null 2>&1 && echo -n 1  || echo -n 0');
	$result["Mysql"] 				= rtrim(shell_exec( 'ps aux | grep "mysqld.pid --socket" | grep -v grep | wc -l'));
	 
	$result["Tomcat"] 				= rtrim(shell_exec('ps aux | grep tomcat | grep -v grep | wc -l'),"\n");

	$result["RFReceptHandler"] 		= shell_exec('[ $(ps aux | grep RFReceptHandler | wc -l) -ge 1 ] && echo -n 1 || echo -n 0');
	$result["Shellinaboxd"] 		= shell_exec('[ $(pidof shellinaboxd | wc -l) -ge 1 ] && echo -n 1 || echo -n 0');
	
	$result["CamGrabber"] 			= rtrim(shell_exec('ps aux | grep CamGrabber | grep -v grep | wc -l'),"\n");
	$result["NetworkDeviceGrabber"] = rtrim(shell_exec('ps aux | grep NetworkDeviceGrabber.py | grep -v grep | wc -l'),"\n");
	$result["MaintainLircd"] 		= rtrim(shell_exec('ps aux | grep MaintainLircd | grep -v grep | wc -l'),"\n");
	$result["SystemStatsGrabber"] 	= rtrim(shell_exec('ps aux | grep "/home/pi/scripts/python//SystemStatGrabber.py -i" | grep -v grep | wc -l'),"\n");
	
	echo json_encode($result,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);    				
?>