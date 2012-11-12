<?php

$target_path = SITE."browser_uploads/".START_SYSTEM_TIME."_plans.txt";

if(!move_uploaded_file($_FILES['plans']['tmp_name'], $target_path) || $_FILES["plans"]["error"] > 0): ?>
	<div class="alert alert-danger">
		There was an error uploading the plans file, please try again!	
	</div>
<?php exit(); endif; ?>

<?php $target_path = SITE."browser_uploads/".START_SYSTEM_TIME."_usage.txt";
 if(!move_uploaded_file($_FILES['usage']['tmp_name'], $target_path) || $_FILES["usage"]["error"] > 0): ?>
	<div class="alert alert-danger">
		There was an error uploading the usage file, please try again!	
	</div>
<?php exit(); endif; ?>
