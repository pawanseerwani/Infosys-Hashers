<style>
	input[type=file] {
		height:30px;
		margin-left:5px;
	}
	label.control-label {
		padding-top:5px;
	}
</style>
<form action="index.php" method="post" enctype="multipart/form-data" name="plan-form">
<label for="plans" class="control-label" style="float:left;">Plans File:</label>
<input type="file" name="plans" id="focusedInput" placeholder="plans.txt" style="float:left;"/>
<div style="clear:both;"></div>
<br />
<label for="usage" class="control-label" style="float:left;">Usage File:</label>
<input type="file" name="usage" id="focusedInput" /style="float:left">
<div style="clear:both;"></div>
<br />
<label class="checkbox">
<input type="checkbox" name="Exclude[Roaming]" value="1" >
Exclude roaming charges
</label>
<br />
<input type="submit" name="submit" value="Submit" class="btn btn-primary"/>
</form>

<div class="text-error"> * Various <b>Business Rules</b> have already been applied.</div>
