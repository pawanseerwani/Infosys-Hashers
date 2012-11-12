<?php include(APP."constants.php"); ?>
<?php
/* displying errors  **/
ini_set('display_errors',1); 
error_reporting(2147483647);

date_default_timezone_set('Asia/Kolkata');


/** constants **/

?>
<?php include("read.php"); ?>
<?php 
	$cost=array();
	$ans=array();
	$allCost=array();
//	$completeUsage=array();

	foreach($usage as $i =>$use)	
	{
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

