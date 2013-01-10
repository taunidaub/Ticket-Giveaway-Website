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
				if($table==$this->config->item('mu')){
					if($gid!=''){
						$sql="select * from giveaways where id ='".$gid."'";
						$query = $this->db->query($sql);
						
						foreach ($query->result() as $row)
						{
							?>
							<h3 class="title" id="title">R.S.V.Ps for <? echo $row->giveaway_name?></h3>
							<div class="entry" id="entry"><br>
							<?php
							$sql2="select * from ".$this->config->item('mu')." where entry_giveaway_id ='".$row->id."' and status='confirmed'";
							$query2 = $this->db->query($sql2);
							$r=0;
							foreach ($query2->result() as $row2){		
							?>					
								<div class="label" style="width:45%"><?php echo $row2->entry_name ." - ". $row2->status ?></div><br>
							<?
								$r++;
							}//	foreach ($query2->result() as $row2){	
							
							?>		
					
							</div>
						<div id="myResult" style="visibility:hidden;"></div>
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
	
	
</body>
</html>
