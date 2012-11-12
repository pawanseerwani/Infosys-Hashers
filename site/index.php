<!DOCTYPE html>
<html>
<head>
	<link href="./assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="./assets/css/b_cp.css" rel="stylesheet" media="screen">
</head>
<body>
<?php include("./constants.php");?>
<?php include(SITE."views/main.php"); ?>
<?php if(isset($_POST['submit'])):?>
<?php include(SITE."upload_file.php"); ?>
<?php endif;?>
<?php if(isset($_POST['submit']) || isset($_GET['demo'])):?>
<?php include(APP."ans.php"); ?>
<?php include(SITE."views/all_usage.php"); ?>
<?php endif;?>
</body>
</html>

