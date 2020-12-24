<?php
	header('Content-type: application/json');

	$daemon	= $_GET['daemon'];
	$action	= $_GET['actionStartOrStop'];
	
	//------------------------------------------ 2 méthodes pour logguer le php ------------------------------------------//
	// > error_log(msg, msgType=0)	--> écrit par défaut dans /var/log/apache/error.log   	
	//		est-ce paramétrable ? oui : 
	//			1) en précisant type msgType=3 et le chemin vers le fichier de log que l'on souhaite (ATTENTION aux droits d'accès au chemin renseigné !!!)
	//			2) dans php.ini, error_log = syslog   
	//			3) dans php.ini, error_log = cheminVersFichierDeLog (ATTENTION aux droits d'accès au chemin renseigné !!!)
	// Exemples d'appel : error_log("MonLog", 0);    error_log("MonLog", 3, "/var/truc/bidule.log");
	
	// > syslog(loglevel, msg) 		--> écrit par défaut dans /var/log/syslog   			est-ce paramétrable ?
	// 		Remarque : syslog (en fait, c'est plutot rsyslogd ) est un daemon dédié à l'enregistrement des journaux ; il a sa propre conf (/etc/rsyslog.conf) ; on peut définir un fichier de log par programme, ou meme envoyer des mail à la place de logguer ...; on peut meme dans le cas de plusieurs serveurs, 
	//            mettre en place un mécanisme de clients de log ou serveur syslog pour la centralisation des logs ; on peut paramétrer la rotation des logs
	// 		Plus d'info ici :  https://adayinthelifeof.nl/2011/01/12/using-syslog-for-your-php-applications/
	
	// Exemple d'appel :  syslog(LOG_WARNING,"syslog daemon=".$daemon);   	
	
	$PAGE = "[StartOrStopDaemon.php] : ";
	
	error_log($PAGE . "daemon=".$daemon . "  action=".$action, 0);			
	try {/*$aa = 10 / 0;*/}	catch(Exception $e) {error_log($PAGE." Exception : ".$e->getMessage(), 0);}
	
	$result = array();	
	$result['daemon']=$daemon;
	$result['action']=$action;
	
	$cmd = "";
	
	if ($daemon == "Mysql") 					{ $cmd = 'sudo /usr/sbin/service mysql 						 '.$action.' 1>/dev/null 2>&1';  	}
	
	if ($daemon == "Cron") 						{ $cmd = 'sudo /usr/sbin/service cron 						 '.$action.' 1>/dev/null 2>&1';  	}
	
	if ($daemon == "Tomcat") 					{ $cmd = 'sudo service tomcat7 								 '.$action.' 1>/dev/null 2>&1';  	}
	
	if ($daemon == "Shellinaboxd") 				{ $cmd = 'sudo service shellinabox 							 '.$action.' 1>/dev/null 2>&1'; 	}

	if ($daemon == "Lirc") 						{ $cmd = 'sudo service lirc 								 '.$action.' 1>/dev/null 2>&1'; 	}

	if ($daemon == "Minidlna") 					{ $cmd = 'sudo service minidlna 							 '.$action.' 1>/dev/null 2>&1'; 	}
	
	if ($daemon == "Xtightvnc") 				{ $cmd = 'sudo service vncboot 							 	 '.$action.' 1>/dev/null 2>&1'; 	}
	
	if ($daemon == "XMS-RFReceptHandler") 		{ $cmd = 'service  xms_daemon_Grabber_RFsignals.sh			 '.$action.' 1>/dev/null 2>&1'; 	;  																				}
	
	if ($daemon == "XMS-CamGrabber") 			{ $cmd = 'service  xms_daemon_Grabber_Cam.sh				 '.$action.' 1>/dev/null 2>&1';  	}
	
	if ($daemon == "XMS-NetworkDeviceGrabber") 	{ $cmd = 'service  xms_daemon_Grabber_NetworkDevice.sh		 '.$action.' 1>/dev/null 2>&1';  	}
	
	if ($daemon == "XMS-SystemStatsGrabber") 	{ $cmd = 'service  xms_daemon_Grabber_SystemStats.sh 		 '.$action.' 1>/dev/null 2>&1';  	}

	if ($daemon == "XMS-TempHumGrabber") 		{ $cmd = 'service  xms_daemon_Grabber_TempHum.sh 			 '.$action.' 1>/dev/null 2>&1';  	}

	if ($daemon == "XMS-MaintainGoogleDrive") 	{ $cmd = 'service  xms_daemon_maintain_google_drive.sh		 '.$action.' 1>/dev/null 2>&1';  	}

	if ($daemon == "XMS-MaintainNas") 			{ $cmd = 'service  xms_daemon_maintain_nas.sh		 		 '.$action.' 1>/dev/null 2>&1';  	}

	if ($daemon == "XMS-MaintainLircd") 		{ $cmd = 'service  xms_daemon_Maintain_Lircd.sh		 		 '.$action.' 1>/dev/null 2>&1';  	}
	
	
	error_log($PAGE. "Executing following command : " . $cmd,0);
	
	shell_exec($cmd);	
	
	echo json_encode($result,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);    				
	
	error_log($PAGE." Finished : returned json : ". json_encode($result), 0);	
?>
