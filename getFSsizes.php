<?php
	putenv('LANG=en_US.UTF-8');
	header('Content-type: application/json');
	
	set_error_handler('exceptions_error_handler');
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function exceptions_error_handler($severity, $message, $filename, $lineno) 
	{
		if (error_reporting() == 0) {return;}
		if (error_reporting() & $severity) {throw new ErrorException($message, 0, $severity, $filename, $lineno);}
	}
	
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function ProcessAndReturnMountedFS($dirPath) 
	{
		global $result;
		
		$outputArray = array();
		$return_var = 0;
		
		$rawIp = shell_exec("mount | grep $dirPath  | sed -e 's/.*addr\=\\([^\,]*\\).*/\\1/g'");
		$ip = trim($rawIp);
		if (empty($ip))		{$ip = "N.A"; $return_var = 128;}
		else				exec("timeout 0.02 ping -c1 -w1 $ip 1>/dev/null 2>&1", $outputArray, $return_var);
		if ($return_var != 0)	// host is down
		{
			$filesystem = array();
			$filesystem["dev"] 	= $dirPath;
			$filesystem["fs"] 	= $dirPath;
			$filesystem["size"] = "N.A";
			$filesystem["used"] = "N.A";
		}
		else
		{
			exec("mountpoint -q ".$dirPath, $outputArray, $return_var);
			if ($return_var != 0)	// // fs is not mounted
			{
				$filesystem = array();
				$filesystem["dev"] 	= $dirPath;
				$filesystem["fs"] 	= $dirPath;
				$filesystem["size"] = "N.A";
				$filesystem["used"] = "N.A";
			}		
			else 
			{
				$cmd = "df -aH -P  ".$dirPath; #  l  ==> local filesystems
				$output = rtrim(shell_exec($cmd));
				$outputarray = split("\n", $output);
				$header = $outputarray[0];
				array_shift($outputarray);			
				$filesystem = generateFileSystemFromLine($outputarray[0],$header);
			}
		}		
		return $filesystem;
	}
	
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function generateFileSystemFromLine($line,$header) 
	{
		// $indexTaille 	= strrpos($header, "Taille");		// $indexUtil 		= strrpos($header, "Util.");		// $indexDispo 	= strrpos($header, "Dispo");		// $indexUtilP 	= strrpos($header, "Uti%");		// $indexMont 		= strrpos($header, "Mont");
		$indexTaille 	= strrpos($header, "Size");
		$indexUtil 		= strrpos($header, "Used");
		$indexDispo 	= strrpos($header, "Avail");
		$indexUtilP 	= strrpos($header, "Use%");
		$indexMont 		= strrpos($header, "Mounted");
		
		$filesystem = array();
		$filesystem["dev"]	= trim(substr($line,0,$indexTaille));
		$filesystem["size"]	= trim(substr($line,$indexTaille,$indexUtil-$indexTaille-1));
		$fs_util			= trim(substr($line,$indexUtil+1,$indexDispo-$indexUtil-1));
		$fs_dispo			= trim(substr($line,$indexDispo+1,$indexUtilP-$indexDispo-1));
		$filesystem["used"]	= trim(substr($line,$indexUtilP,$indexMont-$indexUtilP));
		$filesystem["fs"]	= trim(substr($line,$indexMont));
		return $filesystem;
	}
	
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	$cmd = "df -alH"; #  l  ==> local filesystems
	$output = rtrim(shell_exec($cmd));	
	global $result;
	$result = array();
	
	$cpt=0;
	try
	{		
		if (strlen($output) > 2)
		{			
			$outputarray = split("\n", $output);
			$header = $outputarray[0];
			array_shift($outputarray);			
			foreach ($outputarray as $line)
			{					
				$filesystem = generateFileSystemFromLine($line,$header);
				$result[$cpt] = $filesystem;
				$cpt = $cpt + 1;
			}			
			
			$filesystem = ProcessAndReturnMountedFS("/media/xms-fixe");			$result[$cpt] = $filesystem;			$cpt = $cpt + 1;
			$filesystem = ProcessAndReturnMountedFS("/media/dlink-2a629f");		$result[$cpt] = $filesystem;			$cpt = $cpt + 1;
			$filesystem = ProcessAndReturnMountedFS("/media/freebox");			$result[$cpt] = $filesystem;			$cpt = $cpt + 1;
			$filesystem = ProcessAndReturnMountedFS("/media/xms-fixe-mus");		$result[$cpt] = $filesystem;			$cpt = $cpt + 1;
				
			//------------------------------ Trie (ca se passe toujours en 2 étapes : ------------------//
			// A partir du tableau de plusieurs colonne à trier : 
			//    - 1) 	création des colonnes (tableau à une dimension)
			//    - 2) 	Trie à l'aide des colonnes créées
			foreach ($result as $index => $row) 
			{
				$fs[$index]  			= $row['fs'];
				$size[$index] 			= $row['size'];
				$used[$index]  			= $row['used'];
			}
			array_multisort($fs, SORT_ASC, $size, SORT_ASC, $result);	
		}		
		else { $filesystem = array(); $filesystem["dev"] = $filesystem["fs"] = $filesystem["size"] = $filesystem["used"] = "N.A"; $result["result"] = $filesystem; }
	}
	catch(Exception $exception)
	{
		$result["result"] = "KO.exception".$exception;
	}
	echo json_encode($result,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);    				
?>
