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
						$sql="select * from giveaways where id ='".$gid."'";
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
							<input name="submit" id="submit" type="submit" value="Send Verification Email" onClick="javascript:verify_email(<? echo ($gid) ?>);" />					
							<? } ?>		
							</form>
							<div id="myResult" style="visibility:hidden;"></div>
							<br><br>
							<form id="myForm2" action="<?php echo $this->config->item('base_url') ?>main/repick_winners/<? echo ($gid."/") ?>">
							<?php
							$sql2="select * from ".$this->config->item('mu')." where entry_giveaway_id ='".$row->id."' and status='selected'";
							$query2 = $this->db->query($sql2);
							$r=0;
							foreach ($query2->result() as $row2){		
							?>						
							<div class="label" style="width:45%"><strong>Name: </strong><?php echo $row2->entry_name ?></div><div class="label"><input type="radio" name="verify[<?php echo $r ?>]" value='declined' /> Repick &nbsp;<input type="radio" name="verify[<?php echo $r ?>]" value='verified' /> Verified</div><br>
							<?
								$r++;
							}//	foreach ($query2->result() as $row2){	
							if($r>0){
							?>
							<br><br>
							<input name="submit" id="submit" type="submit" value="Submit Selections" onClick="javascript:repick_winners(<? echo ($gid) ?>);" />					
							<? } ?>		
							</form>
							</div>
							<div id="myResult2" style="visibility:hidden;"></div>
						</div>
						
						<div style="float:left; width:45%;">
						<h3 class="title" id="title">Send winner email notification</h3>
							<div class="entry" id="entry"><br>
							<form id="myForm3" action="<?php echo $this->config->item('base_url') ?>main/send_winner_email/<? echo ($gid."/") ?>">
							<?php
							$sql2="select * from ".$this->config->item('mu')." where entry_giveaway_id ='".$row->id."' and status='selected'";
							$query2 = $this->db->query($sql2);
							$r=0;
							foreach ($query2->result() as $row2){		
							?>						
								<div class="label"><input type="checkbox" name="email[<?php echo $r ?>]" id="email[<?php echo $r ?>]" value='<?php echo $row2->entry_id ?>' checked="checked" /> &nbsp;<?php echo $row2->entry_name ?> </div>
							<?
								$r++;
							}//	foreach ($query2->result() as $row2){	
							if($r>0){
							?>
							<div class="label">Additional information (optional):<br><textarea cols="30" name="additional_message">Please add any additional comments to the email here.</textarea></div>
							<br><br>
							<input name="submit" id="submit" type="submit" value="Email Verified Winners" onClick="javascript:email_winners(<? echo ($gid) ?>);" />					
							<? } ?>		
							</form>
							<div id="myResult3" style="visibility:hidden;"></div>
							</div>
						</div>
						<div style="clear: both;"><br><hr width="90%"><br></div>
						<div style="float:left; width:45%;">
						<h3 class="title" id="title">Repick Selected Winners</h3>
							<div class="entry" id="entry"><br>
							<form id="myForm4" action="<?php echo $this->config->item('base_url') ?>main/repick/<? echo ($gid."/") ?>">
							<?php
							$sql2="select * from ".$this->config->item('mu')." where entry_giveaway_id ='".$row->id."' and status='emailed'";
							$query2 = $this->db->query($sql2);
							$r=0;
					
							foreach ($query2->result() as $row2){		
							?>						
								<div class="label"><input type="checkbox" name="repick[<?php echo $r ?>]" value='<?php echo $row2->entry_id ?>' /> &nbsp;<?php echo $row2->entry_name ?></div>
							<?
								$r++;
							}//	foreach ($query2->result() as $row2){	
							if($r>0){
							?><br><br>
							<input name="submit" id="submit" type="submit" value="Re-pick Selected Winners" onClick="javascript:repick_winners(<? echo ($gid) ?>);" />					
							<? } ?>		
							</form>
							</div>
						</div>
						<div id="myResult4" style="visibility:hidden;"></div>
						<div style="float:left; width:45%;">
						<h3 class="title" id="title">Mark Winners Confirmed</h3>
							<div class="entry" id="entry"><br>
							<form id="myForm5" action="<?php echo $this->config->item('base_url') ?>main/confirm/<? echo ($gid."/") ?>">
							<?php
							$sql2="select * from ".$this->config->item('mu')." where entry_giveaway_id ='".$row->id."' and status='emailed'";
							$query2 = $this->db->query($sql2);
							$r=0;
					
							foreach ($query2->result() as $row2){		
							?>						
								<div class="label"><input type="checkbox" name="confirm[<?php echo $r ?>]" value='<?php echo $row2->entry_id ?>' /> &nbsp;<?php echo $row2->entry_name ?></div>
							<?
								$r++;
							}//	foreach ($query2->result() as $row2){	
							if($r>0){
							?><br><br>
							<input name="submit" id="submit" type="submit" value="Confirm Selected Winners" onClick="javascript:confirm_winners(<? echo ($gid) ?>);" />					
							<? } ?>		
							</form>
							</div>
						</div>
						<div id="myResult5" style="visibility:hidden;"></div>
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

</body>
</html>
