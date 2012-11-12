<style type="text/css">
	.black,.black:hover{
		color:#333333;
		font-weight:bold;
	}
	.demo-links{
		margin-left:10px;
		padding-left:10px;
	}
</style>
<header class="jumbotron" id="overview">
<div class="container" >
<h1>I<span style="font-size:34px">nfosys</span> #<span style="font-size:34px;">ashers</span></h1>
</div>
</header>
<div class="span3 bs-docs-sidebar">
<ul class="nav nav-list bs-docs-sidenav">
<li><a href="#" class="black">Generic</a></li>
<li><a href="./index.php?features=1" >App features</a></li>
<li><a href="./index.php" >Upload Query Files</a></li>
<li><a href="index.php?file=normal&demo" class="demo-links" > Normal </a></li>
<li><a href="index.php?file=ex_roaming&demo" class="black">Different Business Rules </a></li>
<li><a  href="index.php?file=ex_roaming&demo"class="demo-links" >  Exclude Roaming </a></li>
<li><a href="index.php?file=2g&demo" class="demo-links" >With 2G Data </a></li>
<li><a href="index.php?file=3g&demo" class="demo-links" > With 3G Data </a></li>

<li><a href="#" class="black">Scalability</a></li>
<li><a href="index.php?file=3m_large&demo" class="demo-links"> Usage File(3 MB) </a></li>
<li><a href="index.php?file=10m_large&demo" class="demo-links" > Usage File (10 MB)</a></li>
<li><a href="index.php?file=18m_large&demo" class="demo-links" > Usage File (18 MB)</a></li>
<li><a href="#" class="black">Input Flexibility</a></li>
<li><a href="index.php?file=freemin&demo" class="demo-links" > Free National(SMS|SEC) </a></li>
<li><a href="index.php?file=freeall&demo" class="demo-links" > Discount type: ALL </a></li>
<li><a href="#" class="black">Extra Flexibility</a></li>
<li><a href="index.php?file=flextime&demo" class="demo-links" > Minutes/Seconds </a></li>
<li><a href="index.php?file=flexdata&demo" class="demo-links" > Gega/Mega bytes </a></li>
</ul>

</div>
<div class="span9" style="margin-top:30px;margin-left:60px;">
<?php if(isset($_GET['features'])): ?>
<?php include(SITE."views/features.php")?>
<?php exit(); echo "hi"; endif; ?>

<?php if(!isset($_GET['demo']) && !isset($_POST['submit'])) include(SITE.'views/form.php'); ?>

