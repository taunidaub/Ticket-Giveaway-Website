<!DOCTYPE html>
<html>
<head>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo $this->config->item('app_title')?></title>
<link href="<?php echo $this->config->item('base_url_noIndex')?>css/style.css" rel="stylesheet" type="text/css" media="screen" />
<script src="<?php echo $this->config->item('base_url_noIndex')?>js/mootools-core.js"></script>
<script src="<?php echo $this->config->item('base_url_noIndex')?>js/mootools-more.js"></script>
<script src="<?php echo $this->config->item('base_url_noIndex')?>js/csshorizontalmenu.js"></script>
<script src="<?php echo $this->config->item('base_url_noIndex')?>js/main.js"></script>
<script src="<?php echo $this->config->item('base_url_noIndex')?>js/datetimepicker.js"></script>
<script type="text/javascript">
var baseURL = '<?php echo $this->config->item("base_url")?>';
var noIndexURL = '<?php echo $this->config->item("base_url_noIndex")?>';
var user_eid = '<?php echo $this->session->userdata('EID') ?>';
</script>
</head>
<?php include ('cssmenubar.php'); ?>
<div id="wrapper">
	<div id="header">
		<div id="logo">
			<h1><?php echo $this->config->item('app_title')?></h1>
		</div>
	</div>
</div>
<div style="clear: both;">&nbsp;</div>
<div id="page">	
	<div id="content">
		<div id="post">
		
			<?php 
			if($this->session->userdata("Admin")!=''){
				if($table==$this->config->item('ma')){
					$twoweek=date('Y-m-d',mktime(0,0,0,date('m'),date('d'),date('Y'))-1209600);
					$sql="select * from ".$this->config->item('ma')." where draw_deadline<='".$date."' and event_time>='".$twoweek."'";
				
					$query = $this->db->query($sql);
				
					if ($query->num_rows() >= 1){
						?>
						<h2 class="title" id="title">Select Giveaway</h2>
						<div class="entry" id="entry">
						<?php 
					} //if ($query->num_rows() == 1){
					foreach ($query->result() as $row)
					{
						$sql2="select count(*) as total from ".$this->config->item('mu')." where entry_giveaway_id ='".$row->id."'";
						$query2 = $this->db->query($sql2);
						foreach ($query2->result() as $row2){
							echo '<a href="javascript:verify_winners(\''.$row->id.'\')">'.$row->giveaway_name.' '.$row->event_time.'</a>';
							echo '<br>'.$row2->total.' Signed up. &nbsp; &nbsp;Event Location: '.$row->location.'&nbsp; &nbsp;Event Date: '.substr($row->event_time,0, 10).'<br><br>';
							$sql3="select * from ".$this->config->item('mu')." where entry_giveaway_id ='".$row->id."' and status='selected'";
							$query3 = $this->db->query($sql3);
							$r=1;
							foreach ($query3->result() as $row3){
								if($r%4==0)
									echo "<br>";
								echo $row3->entry_name;
							}//foreach ($query3->result() as $row3){
						}//foreach ($query2->result() as $row2){
					}//foreach ($query->result() as $row)	
					if ($query->num_rows() >= 1)
						echo "</div>";
					
				}//if($table==$this->config->item('ma')){ 	
	
				if($table==$this->config->item('mu')){
					if($gid!=''){
						$sql="select * from ".$this->config->item('ma')." where id ='".$gid."'";
						$query = $this->db->query($sql);
						
						foreach ($query->result() as $row)
						{
							?>
						<h2 class="title" id="title" style="color:#333333;"><?php echo $row->giveaway_name ?></h2><br>
					
						<div style="float:left; width:45%;">
						<h3 class="title" id="title">Verify Winners</h3>
							<div class="entry" id="entry"><br>
							<form id="myForm" action="<?php echo $this->config->item('base_url') ?>main/verify_email/<? echo ($gid."/") ?>">
							<?php
							$sql2="select * from ".$this->config->item('mu')." where entry_giveaway_id ='".$row->id."' and status='selected'";
							$query2 = $this->db->query($sql2);
							$r=0;
							foreach ($query2->result() as $row2){		
							?>						
								<div class="label"><?php echo $row2->entry_name ?> </div>
							<?
								$r++;
							}//	foreach ($query2->result() as $row2){	
							if($r>0){
							?>
							<br>
							Add an email to be copied: <input type="text" name="add_email" id="add_email" value="" size="30" />
							<br><br>
							<input name="submit" id="submit" type="submit" value="Send Verification Email" onClick="javascript:verify_email(<? echo ($gid) ?>);" />					
							<? } ?>		
							</form>
							
							<br><br>
							<form id="myForm1" action="<?php echo $this->config->item('base_url') ?>main/verify_all/<? echo ($gid."/") ?>">
							<input name="submit" id="submit" type="submit" value="Verify Everyone" onClick="javascript:verify_all(<? echo ($gid) ?>);" />					
							</form>
							<div id="myResult1" style="visibility:hidden;"></div>
							
							<div id="myResult" style="visibility:hidden;"></div>
							<br><br>
							<form id="myForm2" action="<?php echo $this->config->item('base_url') ?>main/repick_winners/<? echo ($gid."/") ?>">
							<?php
							$sql2="select * from ".$this->config->item('mu')." where entry_giveaway_id ='".$row->id."' and status='selected'";
							$query2 = $this->db->query($sql2);
							$r=0;
							foreach ($query2->result() as $row2){		
							?>						
							<div class="label" style="width:45%"><strong>Name: </strong><?php echo $row2->entry_name ?></div><div class="label"><input type="radio" name="verify[<?php echo $r ?>]" value='declined:<?php echo $row2->entry_id ?>' /> Repick &nbsp;<input type="radio" name="verify[<?php echo $r ?>]" value='verified:<?php echo $row2->entry_id ?>' /> Verified</div><br>
							<?
								$r++;
							}//	foreach ($query2->result() as $row2){	
							if($r>0){
							?>
	
							<input name="submit" id="submit" type="submit" value="Submit Selections" onClick="javascript:repick_winners(<? echo ($gid) ?>);" />					
							<? } ?>		
							</form>
							</div>
							<div id="myResult2" style="visibility:hidden;"></div>
							
						</div>
						
						<?
						
						}//foreach ($query->result() as $row)
					}//if(($gid!='')&&(count($winners)==0)){
				}//if($table==$this->config->item('mu')){		
			}//if($this->session->userdata("Admin")!=''){
			else{
			echo "<div style='height:300px;'>Please login above to continue.</div>";
			}
			?>
			</div>
		</div>
	
	<!-- end #content -->
	<div style="clear: both;">&nbsp;</div>
<!-- end #page -->
</div>
<div id="footer">
	<p>Copyright (c) <?php echo (date('Y')) ?> <a href="timewarnercable.com">Time Warner Cable</a>. All rights reserved.</p>

</div>
	<!-- end #footer -->
<script language="javascript" type="text/javascript">

window.addEvent('domready', function(){

  // The elements used.
  var myForm = document.id('myForm'),
    myResult = document.id('myResult');

  // Labels over the inputs.
  myForm.getElements('[type=text], textarea,[type=password]').each(function(el){
    new OverText(el);
  });

  // Validation.
  new Form.Validator.Inline(myForm);

  // Ajax (integrates with the validator).
  //console.log('entering Login AJAX');
  new Form.Request(myForm, myResult, {
	onSuccess: function(responseText,responseXML){ 
	 //console.log(responseText.get('html'));
		if(responseText.get('html') === '1'){
			window.location.reload(true);
		}
	}
  });

});
</script>
<script language="javascript" type="text/javascript">

window.addEvent('domready', function(){

  // The elements used.
  var myForm = document.id('myForm1'),
    myResult = document.id('myResult1');

  // Labels over the inputs.
  myForm.getElements('[type=text], textarea,[type=password]').each(function(el){
    new OverText(el);
  });

  // Validation.
  new Form.Validator.Inline(myForm);

  // Ajax (integrates with the validator).
  //console.log('entering Login AJAX');
  new Form.Request(myForm, myResult, {
	onSuccess: function(responseText,responseXML){ 
	 //console.log(responseText.get('html'));
		if(responseText.get('html') === '1'){
			window.location.reload(true);
		}
	}
  });

});
</script>

<script language="javascript" type="text/javascript">

window.addEvent('domready', function(){

  // The elements used.
  var myForm = document.id('myForm2'),
    myResult = document.id('myResult2');

  // Labels over the inputs.
  myForm.getElements('[type=text], textarea,[type=password]').each(function(el){
    new OverText(el);
  });

  // Validation.
  new Form.Validator.Inline(myForm);

  // Ajax (integrates with the validator).
  //console.log('entering Login AJAX');
  new Form.Request(myForm, myResult, {
	onSuccess: function(responseText,responseXML){ 
	 //console.log(responseText.get('html'));
		if(responseText.get('html') === '1'){
			window.location.reload(true);
		}
	}
  });

});
</script>
	
		
	
</body>
</html>
