<?php
	header('Content-type: application/json');

	$result = array();
			
	$result["Cron"] 					= rtrim(shell_exec('ps aux | grep /usr/sbin/cron | grep -v grep | wc -l'),"\n");
	$result["Mysql"] 					= rtrim(shell_exec('ps aux | grep "mysqld.pid --socket" | grep -v grep | wc -l'));	 
	//$result["Mysql"] 					= shell_exec('mysqladmin ping > /dev/null 2>&1 && echo -n 1  || echo -n 0');
	$result["Tomcat"] 					= rtrim(shell_exec('ps aux | grep tomcat | grep -v grep | wc -l'),"\n");	
	$result["Shellinaboxd"] 			= rtrim(shell_exec('[ $(pidof shellinaboxd | wc -l) -ge 1 ] && echo -n 1 || echo -n 0'));	
	$result["Xtightvnc"] 				= rtrim(shell_exec('ps aux | grep Xtightvnc | grep -v grep | wc -l'),"\n");
	$result["Minidlna"] 				= rtrim(shell_exec('ps aux | grep /usr/bin/minidlna | grep -v grep | wc -l'),"\n");
	$result["Lirc"] 					= rtrim(shell_exec('ps aux | grep lircd | grep -v grep | wc -l'),"\n");

	
	
	$result["NetBIOS (nmbd)"] 			= rtrim(shell_exec('ps aux | grep /usr/sbin/nmbd | grep -v grep | wc -l'),"\n");
	$result["PartageSamba (smbd)"] 		= rtrim(shell_exec('ps aux | grep /usr/sbin/smbd | grep -v grep | wc -l'),"\n");
	
	$result["XMS-RFReceptHandler"] 		= rtrim(shell_exec('ps aux | grep "/home/pi/src/RadioFrequence/RFReceptHandler" | grep -v grep | wc -l'),"\n");
	$result["XMS-CamGrabber"] 			= rtrim(shell_exec('ps aux | grep CamGrabber | grep -v grep | wc -l'),"\n");
	$result["XMS-NetworkDeviceGrabber"] = rtrim(shell_exec('ps aux | grep NetworkDeviceGrabber.py | grep -v grep | wc -l'),"\n");
	$result["XMS-SystemStatsGrabber"] 	= rtrim(shell_exec('ps aux | grep "SystemStatGrabber.py -i" | grep -v grep | wc -l'),"\n");
	$result["XMS-TempHumGrabber"] 		= rtrim(shell_exec('ps aux | grep "TempHumGrabber.py --i" | grep -v grep | wc -l'),"\n");
	$result["XMS-MaintainGoogleDrive"] 	= rtrim(shell_exec('ps aux | grep "MaintainGoogleDrive.sh" | grep -v grep | wc -l'),"\n");
	$result["XMS-MaintainNas"] 			= rtrim(shell_exec('ps aux | grep "MaintainNas.sh" | grep -v grep | wc -l'),"\n");
			
	echo json_encode($result,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);    				

?>
