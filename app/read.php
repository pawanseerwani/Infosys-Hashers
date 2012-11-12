<?php
	
$exclude=isset($_POST['Exclude']['Roaming']) || $_GET['file']=='ex_roaming';
	$prefix=isset($_GET['file'])?$_GET['file']:"normal";
	/** Reading input files in respective arrays **/
	if(isset($_GET['demo']))
		$plans_handle = fopen(UPLOAD_DIR."demo/plans.txt", "r") or die("Couldn't get handle");
	else
		$plans_handle = fopen(UPLOAD_DIR.START_SYSTEM_TIME."_plans.txt", "r") or die("Couldn't get handle");

	if ($plans_handle)
	{
		$plans=array();
		while (!feof($plans_handle))
		{
			$single_plan=array();
			$single_plan['hasNational']=false;
			$single_plan['hasNationalSec']=false;
			for($i=0;!feof($plans_handle) ;++$i)
			{
				$buffer = fgets($plans_handle, 4096); //Should I omit the length(4096) parameter here?
				$parts=explode(' | ',$buffer,2);
				$parts[0]=str_replace('NUM','SMS',$parts[0]); //Replacing NUM with SMS
				if(strpos($parts[0],'National')!==FALSE)
					$single_plan['hasNational']=true;
				
				if(strpos($parts[0],'NationalSec')!==FALSE)
					$single_plan['hasNationalSec']=true;
				
				if(strpos($parts[0],'MIN')!==FALSE)
				{
					$parts[0]=str_replace('MIN','SEC',$parts[0]);
					if(strpos($parts[0],'Rs')!==FALSE)
						$parts[1]/=60;
					else
						$parts[1]*=60;
				}
				if(strpos($parts[0],'Gb')!==FALSE)
				{
					$parts[0]=str_replace('Gb','Mb',$parts[0]);
					
					if(strpos($parts[0],'Rs')!==FALSE)
						$parts[1]/=1000;
					else
						$parts[1]*=1000;

				}

				$parts[0]=str_replace('Rs','',$parts[0]); //Removing the 'Rs' string from keys of array
				$key=trim($parts[0]);
				if(isset($parts[1]))
					$value=trim($parts[1]);
				if($key=="")
					break;

				if( isset($exclude) && ($exclude==1 && strpos($key,'Roaming')!==FALSE))
					continue;

				$single_plan[$key]=$value;
			}

			$plans[$single_plan['PlanName']]=$single_plan;
		}
		
//		echo "<pre>";	var_dump($plans);	echo "</pre>";
		fclose($plans_handle);
	}
	
	$usage_start_time=getTime();
	$usage_start_microtime=microtime();
	if(isset($_GET['demo']))
		$usage_handle = fopen(UPLOAD_DIR.'demo/'.$prefix."_usage.txt", "r") or die("Couldn't get handle");
	else
		$usage_handle = fopen(UPLOAD_DIR.START_SYSTEM_TIME."_usage.txt", "r") or die("Couldn't get handle");
	if ($usage_handle)
	{
//		$all_usage=array();
		$usage=array();
		$k=0;
		while (!feof($usage_handle))
		{
			$single_usage=array();
			for($i=0;!feof($usage_handle) ;++$i)
			{
				$buffer = fgets($usage_handle, 4096); //Should I omit the length(4096) parameter here?
				$parts=explode(' | ',$buffer,2);
				if(strpos($parts[0],'MIN')!==FALSE)
				{
					$parts[0]=str_replace('MIN','SEC',$parts[0]);
					$parts[1]*=60;
				}

				
				if(strpos($parts[0],'Gb')!==FALSE)
				{
					$parts[0]=str_replace('Gb','Mb',$parts[0]);
					$parts[1]*=1000;
				}
				$key=trim($parts[0]);
				if(isset($parts[1]))
					$value=trim($parts[1]);
			
				if($key=="")
					break;
				if($key=='UsageSet')
				{
					$value.=$k;
					$k++;
				}
				$single_usage[$key]=$value;
//				if(!isset($all_usage[$key]))
//					$all_usage[$key]=0;
//				$all_usage[$key]+=$single_usage[$key];
			}
			$usage[]=$single_usage;
		}

//		$usage[]=$all_usage;
		
//		echo "<pre>";	var_dump($usage);	echo "</pre>";
		fclose($usage_handle);
	}

	/** End of reading the input files in array **/

	
?>
