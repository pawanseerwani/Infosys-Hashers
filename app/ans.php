<?php include(APP."constants.php"); ?>
<?php
/* displying errors  **/
ini_set('display_errors',1); 
error_reporting(2147483647);

date_default_timezone_set('Asia/Kolkata');


/** constants **/

?>
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

	?>


	<div id="all-usage">
	<table class="table table-hover table-condensed table-bordered">
		<caption><h4>Usage Table</h4></caption>
		<tr>
			<td><b>#</b></td>
			<?php foreach($plans as $name =>$value) : ?>
			<td class="<?php if(strpos($planStr,$name)!==false) echo 'best';?>"><b> <?php echo $name ?></b></td>
			<?php endforeach ?>
		</tr>
	
	<?php
	
	$usage_start_time=getTime();
	$usage_start_microtime=microtime();
	if(isset($_GET['demo']))
		$usage_handle = fopen(UPLOAD_DIR.'demo/'.$prefix."_usage.txt", "r") or die("Couldn't get handle");
	else
		$usage_handle = fopen(UPLOAD_DIR.START_SYSTEM_TIME."_usage.txt", "r") or die("Couldn't get handle");
	if ($usage_handle)
	{
		$usage=array();
		$k=0;
		while (!feof($usage_handle))
		{
			$single_usage=array();
			for($i=0;!feof($usage_handle) ;++$i)
			{
				$buffer = fgets($usage_handle, 4096); //Should I omit the length(4096) parameter here?
		//		echo "<pre>".var_dump($buffer)."</pre>";
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
			}
			$usage=$single_usage;
?>


<?php 
	$cost=array();
	$ans=array();
	$allCost=array();
//	$completeUsage=array();

//	foreach($usage as $i =>$use)	
	{
		$use=$usage;

		$ans[$use['UsageSet']]['cost']=PHP_INT_MAX;
		foreach($plans as $plan)
		{
			$amt=0;

			if($plan['hasNational']==true)
			{
				$discountOrder=array();

				$discountSMS=$plan['DiscountSMSNationalSMS'];
				$copyFreeSMS=$discountSMS;

				$usedSTDSMS=$use['SMSSTDSMS'];
				$usedLocalSMS=$use['SMSLocalSMS'];

				if( $plan['SMSLocalSMS'] < $plan['SMSSTDSMS']) 
				{
					$discountOrder[]='SMSLocalSMS';
					$discountOrder[]='SMSSTDSMS';
				}
				else
				{
					$discountOrder[]='SMSSTDSMS';
					$discountOrder[]='SMSLocalSMS';
				}
				
				if($copyFreeSMS!="ALL")
				{
					foreach($discountOrder as $single => $val)
					{
						if($copyFreeSMS > $use[$val])
						{
							$copyFreeSMS-=$use[$val];
							$use[$val]=0;
						}
						else
						{
							$use[$val]-=$copyFreeSMS;
							$copyFreeSMS=0;
							break;
						}
					}
				}
				else
				{
					$use['SMSSTDSMS']=0;
					$use['SMSLocalSMS']=0;
				}

			}

			if($plan['hasNationalSec']==true)
			{
				$discountOrder=array('OnNetLocalSEC','OffNetLocalSEC','OnNetSTDSEC','OffNetSTDSEC');

				$discountSec=$plan['DiscountNationalSec'];
				$copyFreeSec=$discountSec;

				$usedOnNetLocalSEC=$use['OnNetLocalSEC'];
				$usedOffNetLocalSEC=$use['OffNetLocalSEC'];
				$usedOnNetSTDSEC=$use['OnNetSTDSEC'];
				$usedOffNetSTDSEC=$use['OffNetSTDSEC'];
			

				for($i=0;$i<4;++$i)
					for($j=0;$j<$i;++$j)
					{
						if($plan[$discountOrder[$i]] <  $plan[$discountOrder[$j]])
						{
							$temp_var=$discountOrder[$i];
							$discountOrder[$i]=$discountOrder[$j];
							$discountOrder[$j]=$temp_var;
						}
					}
				
				if($copyFreeSec!="ALL")
				{
					foreach($discountOrder as $single => $val)
						{
							//echo $use[$val];
							if($copyFreeSec > $use[$val])
							{
								$copyFreeSec-=$use[$val];
								$use[$val]=0;
							}
							else
							{
								$use[$val]-=$copyFreeSec;
								$copyFreeSec=0;
								break;
							}
						}

				}
				else
				{
					$use['OnNetLocalSEC']=0;
					$use['OffNetLocalSEC']=0;
					$use['OnNetSTDSEC']=0;
					$use['OffNetSTDSEC']=0;
				}

			}



			foreach($use as $type => $value)
			{
				if(array_key_exists("Discount".$type,$plan))
				{
					
					if($plan["Discount".$type]!="ALL")
					{
						$allowed=$plan["Discount".$type];
						$temp=$value>$allowed?($value-$allowed)*$plan[$type]:0;
						$amt+=$temp;
					}
					else
					{
						$temp=0;
						$amt+=$temp;
					}
				}
				else if( $type!='UsageSet' && isset($plan[$type]))
				{
					$temp=$plan[$type]*$use[$type];
					$amt+=$temp;
				}
				
		//		if($type!='UsageSet')
		//		$completeUsage[$use['UsageSet']][$plan['PlanName']][$type]=$temp;


			}

			if($plan['hasNational']==true)
			{
				$plan['DiscountSMSNationalSMS']=$discountSMS;
				$use['SMSSTDSMS']=$usedSTDSMS;
				$use['SMSLocalSMS']=$usedLocalSMS;
			}
			if($plan['hasNationalSec']==true)
			{
				$use['OnNetLocalSEC']=$usedOnNetLocalSEC;
				$use['OffNetLocalSEC']=$usedOffNetLocalSEC;
				$use['OnNetSTDSEC']=$usedOnNetSTDSEC;
				$use['OffNetSTDSEC']=$usedOffNetSTDSEC;
			}

			$rent=$plan['MonthlyRental'];
			$cost[$use['UsageSet']][$plan['PlanName']]=$amt+$rent;
			
			if(!isset($allCost[$plan['PlanName']]))
			{
				$allCost[$plan['PlanName']]=0;
			}
			$allCost[$plan['PlanName']]+=$amt+$rent;
		//	$completeUsage[$use['UsageSet']][$plan['PlanName']]['MonthlyRental']=$rent;
		//	$completeUsage[$use['UsageSet']][$plan['PlanName']]['TotalMonthlyBill']=$rent+$amt;

			if($ans[$use['UsageSet']]['cost']>$cost[$use['UsageSet']][$plan['PlanName']])
			{

				$ans[$use['UsageSet']]['cost']=$cost[$use['UsageSet']][$plan['PlanName']];
				$ans[$use['UsageSet']]['PlanName']=$plan['PlanName'];
			}
		}
	}

//	echo "<pre>";	var_dump($completeUsage);	echo "</pre>";
//	echo "<pre>";	var_dump($cost);	echo "</pre>";
//	echo "<pre>";	var_dump($allCost);	echo "</pre>";
//	echo "<pre>";	var_dump($ans);	echo "</pre>";

?>

<?php foreach($cost as  $name => $value):?>
		<tr>
			<td><b><?php echo $name?></b></td>
			<?php foreach($value as $plan=>$cost) :?>
			<td class="<?php if(strpos($planStr,$plan)!==false) echo 'best';?>"><?php echo $cost?> <?php if($cost==$ans[$name]['cost']) echo "<span style='color:red;'>*</span>"?></td>
			<?php endforeach ?>
		</tr>
		<?php endforeach; ?>
	
<?php
	$minPlans=array();
	$minValue=PHP_INT_MAX;
	$planStr="";
	foreach($allCost as  $val )
	{
		if($minValue > $val)
			$minValue=$val;
	}
	foreach($allCost as $planName => $val)
	{
		if($minValue==$val)
		{
			$minPlans[]=$planName;
			$planStr.=$planName.",";
		}
	}
	$planStr=substr($planStr,0,-1);
	
	$minValue=sprintf("%.2f",$minValue);
//	echo "<pre>";	var_dump($minPlans);	echo "</pre>";
	$usage_end_time=getTime();
	$usage_end_microtime=microtime();

//	var_dump($planStr);
	include('out.php');
	printToFile();
	/** Output ends **/
?>
<?php

	}
		fclose($usage_handle);
	}
	?>

	<tr>
		<td><b>Total</b></td>
			<?php foreach($allCost as $name =>$value) : ?>
			<td class="<?php if(strpos($planStr,$name)!==false) echo 'best';?>"> <?php echo sprintf("%.2f",$value); ?> <?php if(strpos($planStr,$name)!==false) echo '<span style="color:red">*</span>';?></td>
			<?php endforeach; ?>
		</tr>
	
	</table><!--all_usage_table-->
	<p class="text-error">* represents best plan for each usage.</p>
</div><!--all-usage-->
