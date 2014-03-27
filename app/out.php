<?php
/*
function printToFile()
{
	global $fh,$usage_start_time,$usage_end_time,$planStr,$minValue;

	$outFile=COLLEGE."_".TEAM."_".START_SYSTEM_TIME.".txt";
	//$outFile=COLLEGE."_".TEAM.".txt";
	$fh = fopen($outFile, 'w') or die("can't open file");
	$str=TEAM;
	$str.=" | Start System Time | ";
	$str.=START_SYSTEM_TIME;
	$str.="| Start Usage Time | ";
	$str.=$usage_start_time;
	$str.=" | End Time | ";
	$str.=$usage_end_time;
	$str.=" | Time Taken | ";
	$str.=$usage_end_time-$usage_start_time;
	$str.=" | ".$planStr." | ".$minValue;
	$str.=NL;

	//echo "<pre>".var_dump($str)."</pre>";
	fwrite($fh, $str);
	fclose($fh);
}
*/
?>
