// JavaScript Document


function login(){	

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
 // console.log('entering Submission AJAX');
  new Form.Request(myForm, myResult, {
    requestOptions: {
      'spinnerTarget': myForm
    },
	onSuccess: function(responseText,responseXML){ 
		console.log(responseText);
		if(responseText.get('html') === '1'){
			window.location.reload(true);
		}
	}
  });	

}

function refreshdiv(divId){ 
	//alert(divId);
	document.getElementById(divId).innerHTML="";

	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById(divId).innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET",baseURL+"main/"+divId+"/",true);
	xmlhttp.send();	
}


	
function updated(){

	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("livesearch").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET",baseURL+"main/updated/",true);
	xmlhttp.send();
}

function showResult(str)
{
	if (str.length>0)
	{
		new_rows();
	}
	function new_rows(){
		//var selected=
		var myElement = document.getElementById('livesearch');
		//alert('scripts/forms.php'+form_load);
		var myHTMLRequest = new Request({
		url: baseURL+"main/livesearch/"+str,
		method: 'get',
		onRequest: function() {
			myElement.innerHTML='';
		},
		onComplete: function(responseText) {
			var new_rows = new Element('div', {
				'html': responseText
			});
			// inject new fields at bottom
			myElement.innerHTML='';
			new_rows.inject(myElement,'bottom');
			
			//    remove loading image
		}
		}).send();
	}
}

function insert(table)
{
	
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
  //console.log('entering Submission AJAX');
  new Form.Request(myForm, myResult, {
    requestOptions: {
      'spinnerTarget': myForm
    },
	onSuccess: function(responseText,responseXML){ 
	 console.log(responseText.get('html'));
		if(responseText.get('html') === '1'){
			window.location.reload(true);
		}
	}
  });
}

function new_request(table) {

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
  console.log('entering Submission AJAX');
  new Form.Request(myForm, myResult, {
    requestOptions: {
      'spinnerTarget': myForm
    },
	onSuccess: function(responseText,responseXML){ 
	// console.log(responseText.get('html'));
		if(responseText.get('html') === '1'){
			window.location.reload(true);
		}
	}
  });

}

function edit_request(table, id) {

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
  console.log('entering Submission AJAX');
  new Form.Request(myForm, myResult, {
    requestOptions: {
      'spinnerTarget': myForm
    },
	onSuccess: function(responseText,responseXML){ 
	// console.log(responseText.get('html'));
		if(responseText.get('html') === '1'){
			window.location.reload(true);
		}
	}
  });

}


function eid_lookup(){
	var myElement = document.getElementById('behalf_eid');
	document.getElementById("behalf_eid").options.length = 0;
	var lookup=document.getElementById("behalf_email").value;
	if(lookup.length>5){
		var temp=lookup.split('@');
		var options = new Array();
		var myRequest = new Request.JSON({
			url: baseURL+"main/eidlookupbyemail/"+temp[0],
			method: 'get',
			onRequest: function(){
				myElement.set('value', 'loading...');
			},
			onSuccess: function(responseJSON){
			if(responseJSON.length>1){
					responseJSON.each (function (value){
					myElement.add(new Option(value.name+" "+value.department+" in "+value.division,value.eid));
					});
			}
			else
				responseJSON.each (function (value){
					myElement.add(new Option(value.name+" "+value.department+" in "+value.division,value.eid, true, true));
					});
			},
			onFailure: function(){
				myElement.set('value', 'Sorry, your request failed :(');
			}
		}).send();
	}

}
function eid_lookup2() {
	if((document.getElementById("behalf_email").value!='Email Address')&&(document.getElementById("behalf_email").value!=''))
	{
		var lookup=document.getElementById("behalf_email").value;
		temp=lookup.split('@');
		
		if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
		else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
		xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					var answer=xmlhttp.responseText;
					document.getElementById("behalf_eid").value=answer;
				}
			}
			xmlhttp.open("GET",baseURL+"main/eidlookupbyemail/"+temp[0]+"/",true);
			xmlhttp.send();

	}
	else
	alert("Please complete the email address field to use this functionality.");

}		
		

function logout() {
	new Request({
		url: baseURL + 'main/logout',
		onSuccess: function (responseText) {
			//console.log('Response: ' +responseText);
			if(responseText === '1'){
				window.location.reload(true);
			}
		}
	}).send();
}	

function display(field){	
	if(document.getElementById(field).style.display == 'none')
		document.getElementById(field).style.display = "";
	else if(document.getElementById(field).style.display == "")
		document.getElementById(field).style.display = 'none';
}

function enter_giveaway(id){	
	var url=baseURL + 'main/giveaway/'+id;
	window.location.href=url;
}
function add_giveaway(){	
	var url=baseURL + 'main/admin_giveaway/0';
	window.location.href=url;
}
function edit_giveaway(id){	
	var url=baseURL + 'main/admin_giveaway/'+id;
	window.location.href=url;
}

function gohome(){	
	window.location.href=baseURL;
}
function send_mail(){	
	var url=baseURL + 'main/send_mail/';
	window.location.href=url;
}

function select_winners(id){	
	var url=baseURL + 'main/select_winners/'+id;
	window.location.href=url;
}
function reselect_winners(id){	
	var url=baseURL + 'main/verify_winners/'+id;
	window.location.href=url;
}


function pick_winners(id){	
	
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
 // console.log('entering Submission AJAX');
  new Form.Request(myForm, myResult, {
    requestOptions: {
      'spinnerTarget': myForm
    },
	onSuccess: function(responseText,responseXML){ 
		if(responseText === '1'){
			window.location.reload(true);
		}
	}
  });	

}
function email_winners(id){	
	
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
 // console.log('entering Submission AJAX');
  new Form.Request(myForm, myResult, {
    requestOptions: {
      'spinnerTarget': myForm
    },
	onSuccess: function(responseText,responseXML){ 
		if(responseText === '1'){
			window.location.reload(true);
		}
	}
  });	

}

function repick_winners(id){	
	
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
 // console.log('entering Submission AJAX');
  new Form.Request(myForm, myResult, {
    requestOptions: {
      'spinnerTarget': myForm
    },
	onSuccess: function(responseText,responseXML){ 
		if(responseText === '1'){
			window.location.reload(true);
		}
	}
  });	

}

function verify_email(id){	
	
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
 // console.log('entering Submission AJAX');
  new Form.Request(myForm, myResult, {
	onSuccess: function(responseText,responseXML){ 
		if(responseText === '1'){
			window.location.reload(true);
		}
	}
  });	

}
function verify_all(id){	
	
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
 // console.log('entering Submission AJAX');
  new Form.Request(myForm, myResult, {
	onSuccess: function(responseText,responseXML){ 
		if(responseText === '1'){
			window.location.reload(true);
		}
	}
  });	

}

function verify_winners(id){	
	
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
 // console.log('entering Submission AJAX');
  new Form.Request(myForm, myResult, {
    requestOptions: {
      'spinnerTarget': myForm
    },
	onSuccess: function(responseText,responseXML){ 
		if(responseText === '1'){
			window.location.reload(true);
		}
	}
  });	

}

function confirm_winners(id){	
	
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
 // console.log('entering Submission AJAX');
  new Form.Request(myForm, myResult, {
    requestOptions: {
      'spinnerTarget': myForm
    },
	onSuccess: function(responseText,responseXML){ 
		if(responseText === '1'){
			window.location.reload(true);
		}
	}
  });	

}



