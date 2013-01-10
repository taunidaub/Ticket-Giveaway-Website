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
				$sql="select * from ".$this->config->item('ma')." where draw_deadline<='".$date."' and event_time>='".$twoweek."' order by event_time";

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
						echo '<a href="javascript:select_winners(\''.$row->id.'\')">'.$row->giveaway_name.' '.$row->event_time.'</a>';
						echo '<br><strong>Signed up</strong>: '.$row2->total.' &nbsp; &nbsp;<strong>Event Location</strong>: '.$row->location.'&nbsp; &nbsp;<strong>Draw Deadline</strong>: '.substr($row->draw_deadline,0, 10).'<br>';
						$sql3="select count(status) as total, status from ".$this->config->item('mu')." where entry_giveaway_id ='".$row->id."' group by status";
						$query3 = $this->db->query($sql3);
						$old_status='';
						foreach ($query3->result() as $row3){
							if($old_status!=$row3->status)
								echo "<strong>".ucfirst($row3->status)."</strong>: ".$row3->total."<br>";
							$old_status=$row3->status;
						}
							
					}
				}//foreach ($query->result() as $row)	
				if ($query->num_rows() >= 1)
					echo "<br></div>";
				
			}//if($table==$this->config->item('ma')){ 	
			
			if($table==$this->config->item('mu')){
				
				if($gid!=''){
					
					$sql="select * from ".$this->config->item('ma')." where id ='".$gid."'";
					$query = $this->db->query($sql);
					foreach ($query->result() as $row)
					{
						$sql2="select count(*) as total from ".$this->config->item('mu')."  where entry_giveaway_id ='".$row->id."'";
						$query2 = $this->db->query($sql2);
						foreach ($query2->result() as $row2){
	
						?>
							<h2 class="title" id="title">Select winners for <?php echo $row->giveaway_name ?></h2>
							<br><br>
							<div class="entry" id="entry" style="height:400px;">
							<form id="myForm" action="<?php echo $this->config->item('base_url') ?>main/pick_winners/<? echo ($gid."/") ?>">
							<div class="label">Number of Winners:  <input type="text" name="max_winners" id="max_winners" class="required" size="5" title="#" value='' /></div>
							<div class="label"><?php echo $row2->total ?> Entries</div> 
							<br>
							<input name="submit" id="submit" type="submit" value="Pick Winners" onClick="javascript:pick_winners(<? echo ($gid) ?>);" />					
							</form>
							</div>
							<div id="myResult" style="visibility:hidden;"></div>
					<?
						}//	foreach ($query2->result() as $row2){	
					}//foreach ($query->result() as $row)
				}//if(($gid!=''){
				
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
    requestOptions: {
      'spinnerTarget': myForm
	  
    },
	onSuccess: function(responseText,responseXML){ 
	 //console.log(responseText.get('html'));
		if(responseText.get('html') > '0'){
			var id=responseText.get('html');
			window.location.href= "<?php echo $this->config->item('base_url') ?>main/verify_winners/"+id;
		}
	}
  });

});
</script>
	
</body>
</html>
