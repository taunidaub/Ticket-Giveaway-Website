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
		if($this->session->userdata("LoggedIn")==true){
			if($table==$this->config->item('ma')){
			
				if($gid!=''){
					$sql="select * from ".$this->config->item('ma')." where id ='".$gid."'";
					$query = $this->db->query($sql);
					
					
					foreach ($query->result() as $row)
					{
						if ($query->num_rows() == 1){
								if  ($row->restriction == '1')
									$yes='checked';
								else 
									$no='checked';
							
							$temp=explode(" ",$row->event_time);
							$temp1=explode("-",$temp[0]);
							$temp2=explode(":",$temp[1]);
							if($temp2[0]>12){
								$time="PM";
								$temp2[0]=$temp2[0]-12;
							}
							else if($temp2[0]==12)
								$time="PM";
							else
								$time="AM";
							$niceeventtime=$temp1[0]."-".$temp1[1]."-".$temp1[2]." " .$temp2[0].":".$temp2[1].":".$temp2[2]." ".$time;
							
							$temp=explode(" ",$row->entry_deadline);
							$temp1=explode("-",$temp[0]);
							$temp2=explode(":",$temp[1]);
							if($temp2[0]>12){
								$time="PM";
								$temp2[0]=$temp2[0]-12;
							}
							else if($temp2[0]==12)
								$time="PM";
							else
								$time="AM";
							$niceentrydate=$temp1[0]."-".$temp1[1]."-".$temp1[2]." " .$temp2[0].":".$temp2[1].":".$temp2[2]." ".$time;
							$temp=explode(" ",$row->draw_deadline);
							$temp1=explode("-",$temp[0]);
							$temp2=explode(":",$temp[1]);
							if($temp2[0]>12){
								$time="PM";
								$temp2[0]=$temp2[0]-12;
							}
							else if($temp2[0]==12)
								$time="PM";
							else
								$time="AM";
							$nicedrawingdate=$temp1[0]."-".$temp1[1]."-".$temp1[2]." " .$temp2[0].":".$temp2[1].":".$temp2[2]." ".$time;
							
							?>
							<h2 class="title" id="title">Edit <?php echo $row->giveaway_name ?> Details</h2>
							<div class="entry" id="entry">
							<br />
							<form id="myForm" action="<?php echo $this->config->item('base_url') ?>main/edit/<? echo $this->config->item('ma')?>/<? echo $gid ?>">
							<input type="hidden" name="EID" class="required" title="EID" value='<?php echo $this->session->userdata("EID")?>' />
							<p class="label">Giveaway Name: <input type="text" name="giveaway_name" size="50" title="Giveaway Name" value='<?php echo $row->giveaway_name ?>' /></p>
							<p class="label">Giveaway Description: <textarea name="description" cols="50" title="Description"><?php echo $row->description ?></textarea></p>
							<p class="label">Event Location: <input type="text" name="location" size="50" title="Location" value='<?php echo $row->location ?>' /></p>
							<p class="label">Event Date and Time: <input type="text" name="event_time" id="event_time"size="30" value='<?php echo $niceeventtime ?>' />&nbsp;&nbsp;<a href="javascript:NewCal('event_time','yyyymmdd','1','12')"><img src="<?php echo $this->config->item("base_url_noIndex")?>/images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a></p>
							<p class="label">Entry Deadline: <input type="text" name="entry_deadline" id="entry_deadline" size="30" value='<?php echo $niceentrydate ?>' />&nbsp;&nbsp;<a href="javascript:NewCal('entry_deadline','yyyymmdd','1','12')"><img src="<?php echo $this->config->item("base_url_noIndex")?>/images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a></p>
							<p class="label">Drawing Deadline: <input type="text" name="draw_deadline" id="draw_deadline" size="30" value='<?php echo $nicedrawingdate ?>' />&nbsp;&nbsp;<a href="javascript:NewCal('draw_deadline','yyyymmdd','1','12')"><img src="<?php echo $this->config->item("base_url_noIndex")?>/images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a></p>
							<p class="label">Web link: <input type="text" name="web_link" size="120" title="Web link" value='<?php echo $row->web_link ?>' /></p>
							<p class="label">Restriction on signup: <input type="radio" name="restriction" title="Restriction" <?php echo @$no ?> value='0' /> No <input type="radio" name="restriction" title="Restriction" <?php echo @$yes ?> value='1' /> Yes</p>
							
							<p class="label">Department: <select name="department">
							<?php
							$sql2="select department from ".$this->config->item('dt')." order by department asc ";
							
							$query2 = $this->db->query($sql2);
							foreach ($query2->result() as $row2)
								if ($row2->department==$row->department)
									echo "<option selected>".$row2->department."</option>";	
								else
									echo "<option>".$row2->department."</option>";
									
							?>
							</select>
						
						</p>
							<p class="label">Max number of tickets each employee may enter to win:  <input type="text" name="max_tickets" size="5" title="#" value='<?php echo $row->max_tickets ?>' /></p>
							<p><input type="hidden" name="table" value="<? echo $this->config->item('ma')?>" />
							<input type="hidden" name="entry_giveaway_id" value="<? echo $gid ?>" />
							<input name="submit" id="submit" type="submit" value="Edit Contest" onClick="javascript:edit_request('<? echo ($this->config->item('ma')."',".$gid) ?>);" /></p>
							<div id="myResult" style="visibility:hidden;"></div>
							
							</form>		
						<? 
						} //if ($query2->num_rows() == 0){
						if ($query->num_rows() == 1)
							echo "</div>";
					}//foreach ($query->result() as $row)
				}//if($gid!=''){
				else{
					?>
					<h2 class="title" id="title">New Giveaway Details</h2>
					<div class="entry" id="entry">
					<br />
					<form id="myForm" action="<?php echo $this->config->item('base_url') ?>main/insert_mailer/<? echo $this->config->item('ma')?>/">
					<input type="hidden" name="EID" class="required" title="EID" value='<?php echo $this->session->userdata("EID")?>' />
					<p class="label">Giveaway Name: <input type="text" name="giveaway_name"  size="50" title="Giveaway Name" value='' /></p>
					<p class="label">Giveaway Description: <textarea name="description" cols="50" title="Description"></textarea></p>
					<p class="label">Event Location: <input type="text" name="location"  size="50" title="Location" value='' /></p>
					<p class="label">Event Date and Time: <input type="text" name="event_time" id="event_time"  size="30" value='' />&nbsp;&nbsp;<a href="javascript:NewCal('event_time','yyyymmdd','1','12')"><img src="<?php echo $this->config->item("base_url_noIndex")?>/images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a></p>
					<p class="label">Entry Deadline: <input type="text" name="entry_deadline" id="entry_deadline"  size="30" value='' />&nbsp;&nbsp;<a href="javascript:NewCal('entry_deadline','yyyymmdd','1','12')"><img src="<?php echo $this->config->item("base_url_noIndex")?>/images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a></p>
					<p class="label">Drawing Deadline: <input type="text" name="draw_deadline" id="draw_deadline"  size="30" value='' />&nbsp;&nbsp;<a href="javascript:NewCal('draw_deadline','yyyymmdd','1','12')"><img src="<?php echo $this->config->item("base_url_noIndex")?>/images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a></p>
					<p class="label">Web link: <input type="text" name="web_link"  size="120" title="Web link" value='' /></p>
					<p class="label">Restriction on signup: <input type="radio" name="restriction" title="Restriction" <?php echo @$no ?> checked="checked" value='0' /> No <input type="radio" name="restriction" title="Restriction" <?php echo @$yes ?> value='1' /> Yes</p>
					
					<p class="label">Department: <select name="department">
					<?php
					$sql2="select department from ".$this->config->item('dt')." order by department asc ";
					
					$query2 = $this->db->query($sql2);
					foreach ($query2->result() as $row2)
						if ($row2->department==$row->department)
							echo "<option selected>".$row2->department."</option>";	
						else if ($row2->department=='Marketing')
							echo "<option selected>".$row2->department."</option>";	
						else
							echo "<option>".$row2->department."</option>";
							
					?>
					</select>
				
				</p>
					<p class="label">Max number of tickets each employee may enter to win:  <input type="text" name="max_tickets" size="5" title="#" value='' /></p>
					<input type="hidden" name="table" value="<? echo $this->config->item('ma')?>" />
					<input type="hidden" name="entry_giveaway_id" value="<? echo $gid ?>" />
					<input name="submit" id="submit" type="submit" value="Add Contest" onClick="javascript:new_request('<? echo $this->config->item('ma') ?>');" /></p>
					<div id="myResult" style="visibility:hidden;"></div>
					
					</form>	
				</div>	
				<? 
				
				} //else of if($gid!=''){
				
			}//if($table==$this->config->item('mu')){ 						
		}//if($this->session->userdata("LoggedIn")==true){
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
    requestOptions: {
      'spinnerTarget': myForm
	  
    },
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
