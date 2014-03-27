<style>
td.best {
	background-color:#9ACD32;
}
td.best:hover {
	background-color:#9ACD32;
}
.right{
	float:right;
}
.left{
	float:left;
}
.clear{
	clear:both;
}
</style>
	<?php if(isset($_GET['demo'])): ?>
	<div id="demo-files">
		<div id="plan">
		<?php $decode['normal']="Normal file"; 
			 $decode['ex_roaming']="Excluding Roaming[Business Rule]";
			 $decode['2g']="2G Data[Business Rule]";
			 $decode['3g']='3G Data[Business Rule]';
			 $decode['3m_large']="Usage Data-3MB[Scalability]";
			 $decode['10m_large']="Usage Data-10MB[Scalability]";
			 $decode['18m_large']="Usage Data-18MB[Scalability]";
			 $decode['flextime']="Time flexibility format";
			 $decode['flexdata']="Data flexibility format";
			 $decode['freemin']="National Free minutes/seconds";
			 $decode['freeall']='Discount of a type: ALL';
				?>
			<div class="left">Demo for<b> <?php echo $decode[$_GET['file']]; ?></b></div>
			<div class="clear"></div>
			<div class="left"> Files :&nbsp; </div>
			<a href="<?php echo "./browser_uploads/demo/".$_GET['file']."_plans.txt" ?>" target="_blank"  class="left">Plans</a>
			<a class="left">&nbsp;|&nbsp; </a>
			<a href="<?php echo "./browser_uploads/demo/".$_GET['file']."_usage.txt" ?>" target="_blank"  class="left">Usage</a>
			<a class="left">&nbsp;|&nbsp; </a>
			<a href="<?php echo "./browser_uploads/demo/".$_GET['file']."_help.txt" ?>" target="_blank"  class="left">Help</a>
			<div class="clear"></div>
		</div>
	</div>
	<?php endif; ?>
	<br>
		<div class="alert alert-success">
	<div class="left">
		Result : Best plan for you is <b> <?php echo $planStr; ?></b> (<?php echo 'Rs '.$minValue; ?>)
	</div>
	<div class="right">
	Exec time : <?php	echo $usage_end_time-$usage_start_time." seconds";

					 ?>
	</div>
	<div class="clear">
	</div>
	</div>

