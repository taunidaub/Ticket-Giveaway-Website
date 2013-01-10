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
			if($table==$this->config->item('mu')){
				$sql="select * from ".$this->config->item('ma')." where id ='".$gid."'";
				$query = $this->db->query($sql);
				
				
				foreach ($query->result() as $row)
				{
					if ($query->num_rows() == 1){
						?>
						<h2 class="title" id="title"><?php echo $row->giveaway_name ?> Submission</h2>
						<div class="entry" id="entry">
						<?php 
					} //if ($query->num_rows() == 1){
					
					$sql2="select * from ".$this->config->item('mu')." where entry_giveaway_id=".$row->id." and ".$this->config->item('mu').".entry_email = '".$this->session->userdata('Email')."'";
					$query2 = $this->db->query($sql2);
					if ($query2->num_rows() == 0){
						?>
						<br />
						<h3 class="title">Verify your contact information:</h3>
						<br />
						<form id="myForm" action="<?php echo $this->config->item('base_url') ?>main/insert_mailer/<? echo $this->config->item('mu')?>">
						<input type="hidden" name="EID" class="required" title="EID" value='<?php echo $this->session->userdata("EID")?>' />
						<p class="label">Name: <input type="text" name="entry_name" class="required" size="50" title="Name" value='<?php echo $this->session->userdata("Name") ?>' /></p>
						<p class="label">Email: <input type="text" name="entry_email" class="required" size="80" title="Email" value='<?php echo $this->session->userdata("Email") ?>' /></p>
						<p class="label">Telephone #: <input type="text" name="entry_phone" class="required" size="25" title="Phone" value='<?php echo $this->session->userdata("Phone") ?>' /></p>
						<p class="label">Location: <input type="text" name="entry_location" class="required" size="50" title="Location" value='<?php echo $this->session->userdata("Location") ?>' /></p>
						<p class="label">Department: <input type="text" name="entry_department" class="required" size="50" title="Department" value='<?php echo $this->session->userdata("Department") ?>' /></p>
						<p class="label">Number of Tickets: <select name="entry_tickets_num">
						<?php
						$sql2="select max_tickets from ".$this->config->item('ma')." where id ='".$gid."'";
						echo $sql;
						$query2 = $this->db->query($sql2);
						if ($query2->num_rows() == 1)
							foreach ($query2->result() as $row2)
								$max=$row2->max_tickets;
						
						for($x=$max;$x>=1;$x--)
							echo "<option>".$x."</option>";
								
						?>
						</select>
						</p>
						<p><input type="hidden" name="table" value="<? echo $this->config->item('mu')?>" />
						<input type="hidden" name="entry_giveaway_id" value="<? echo $gid ?>" />
						<input name="submit" id="submit" type="submit" value="Enter Contest" onClick="javascript:insert(<? echo $this->config->item('mu')?>);" /></p>
						<div id="myResult" style="visibility:hidden;"></div>
						
						</form>		
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
					<? 
					} //if ($query2->num_rows() == 0){
					else{
						?>
						<br />
						<h3 class="title">Thank you for entering this contest.</h3>
						<p>Please check your email for your confirmation.</p>
						<br><br><br><br>
						<?					
					}
					if ($query->num_rows() == 1)
						echo "</div>";
				}//foreach ($query->result() as $row)
			}//if($table==$this->config->item('ma')){ 	
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

	
</body>
</html>
