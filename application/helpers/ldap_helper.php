<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*

	Class: LDAPHelper

	File: ldap_helper.php
		
*/



if ( ! function_exists('LDAPConnect'))
{
/*
Function: LDAPConnect


	Queries the LDAP server and returns a link to that server

	Parameters: 
		string - Username, 
		string - Server, 
		string - Password 

	Returns: 
		object 	- if valid 
		int 	- 0 if not valid 
*/

	function LDAPConnect($ADUsername,$ADPassword){


		$ADhost			= '';
		$ADhost2		= '';
		
		$ds = ldap_connect($ADhost)														// connect to ldap server
		    or die($ds = ldap_connect($ADhost2) or die("Could not connect to LDAP server."));
		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

		if ($ds) {																		// If connection is good then attempt to bind to server
			$ldapbind = ldap_bind ( $ds, 'CORP\\'.$ADUsername, $ADPassword);			// Binding to ldap server

			if ($ldapbind) {															// If We bind ok then return the Link ID
				return $ds ;
			}else{
				return '0';
			}
		}
    }
}
if ( ! function_exists('LDAPClose'))
{
/*
Function: LDAPClose

	Internal function to close the LDAP connection.

	Parameters:
		object	- the connection object	
		
	Returns:
		null
	
	Example:
		(code)
			LDAPClose($ds);
		(end)

*/
	function LDAPClose($ds){
		ldap_close($ds);
    }
}
if ( ! function_exists('getRealName'))
{
/*
Function: getRealName

	Queries the LDAP server and returns a users Full Name

	Parameters:
		string 	- Users EID
		string	- Users Password
		
	Returns:
		string	- Lastname,Firstname
	
	Example:
		(code)
		(end)

*/
    function getRealName($EID,$Password){															
		$ds = LDAPConnect($EID,$Password);
		$dn = 'OU=Company Divisions,DC=corp,DC=domain,DC=com';								// Set the base DN
		$filter='(&(objectCategory=*)(cn='.$EID.'*))';									// Create the filter (more Detal = faster)
		$justthese = array("displayname");												// what we would like to see from the results

		$sr=ldap_search($ds, $dn, $filter,$justthese);									// Build the search string and object

		$info = ldap_get_entries($ds, $sr);												// go fetch the results
		LDAPClose($ds);
		return $info[0]['displayname'][0];


	}
}
if ( ! function_exists('getEmail'))
{
/*
Function: getEmail

	Queries the LDAP server and returns a users email address

	Parameters:
		string 	- Users EID
		string	- Users Password
		
	Returns:
		string - Users e-mail address
	
	Example:
		(code)
			$Email = getEmail($EID,$Password);
		(end)

*/
    function getEmail($EID,$Password){															
		$ds = LDAPConnect($EID,$Password);
		$Perms = 3;
		$dn = 'OU=Company Divisions,DC=corp,DC=domain,DC=com';								// Set the base DN
		$filter='(&(objectCategory=*)(cn='.$EID.'*))';									// Create the filter (more Detal = faster)
		$justthese = array("mail");														// what we would like to see from the results

		$sr=ldap_search($ds, $dn, $filter,$justthese);									// Build the search string and object

		$info = ldap_get_entries($ds, $sr);												// go fetch the results
		LDAPClose($ds);
		return $info[0]['mail'][0];

	}
}
if ( ! function_exists('getTitle'))
{
/*
Function: getTitle

	Queries the LDAP server and returns a users Title

	Parameters:
		string 	- Users EID
		string	- Users Password	
		
	Returns:
		string - Users Title
	
	Example:
		(code)
			$Title = getTitle($EID,$Password);
		(end)

*/
    function getTitle($EID,$Password){															
		$ds = LDAPConnect($EID,$Password);
		$Perms = 3;
		$dn = 'OU=Company Divisions,DC=corp,DC=domain,DC=com';								// Set the base DN
		$filter='(&(objectCategory=*)(cn='.$EID.'*))';									// Create the filter (more Detal = faster)
		$justthese = array("title");													// what we would like to see from the results

		$sr=ldap_search($ds, $dn, $filter,$justthese);									// Build the search string and object
		$info = ldap_get_entries($ds, $sr);												// go fetch the results
		LDAPClose($ds);
		return $info[0]['title'][0];

	}
}
if ( ! function_exists('getFirstName'))
{
/*
Function: getFirstName

	Queries the LDAP server and returns a users First Name Only

	Parameters:
		string 	- Users EID
		string	- Users Password	
		
	Returns:
		string	 Users First Name
	
	Example:
		(code)
			$FN = getFirstName($EID,$Password);
		(end)

*/
    function getFirstName($EID,$Password){														
		$ds = LDAPConnect($EID,$Password);
		$Perms = 3;
		$dn = 'OU=Company Divisions,DC=corp,DC=domain,DC=com';								// Set the base DN
		$filter='(&(objectCategory=*)(cn='.$EID.'*))';									// Create the filter (more Detal = faster)
		$justthese = array("givenname");												// what we would like to see from the results

		$sr=ldap_search($ds, $dn, $filter,$justthese);									// Build the search string and object

		$info = ldap_get_entries($ds, $sr);												// go fetch the results
		LDAPClose($ds);
		return $info[0]['givenname'][0];

	}
}
if ( ! function_exists('getLastName'))
{
/*
Function: getLastName

	Queries the LDAP server and returns a users Last name only

	Parameters:
		string 	- Users EID
		string	- Users Password	
		
	Returns:
		string	- Users Last Name
	
	Example:
		(code)
			$LN = getLastName($EID,$Password);
		(end)

*/
    function getLastName($EID,$Password){															

		$ds = LDAPConnect($EID,$Password);
		$Perms = 3;
		$dn = 'OU=Company Divisions,DC=corp,DC=domain,DC=com';								// Set the base DN
		$filter='(&(objectCategory=*)(cn='.$EID.'*))';									// Create the filter (more Detal = faster)
		$justthese = array("sn");														// what we would like to see from the results

		$sr=ldap_search($ds, $dn, $filter,$justthese);									// Build the search string and object

		$info = ldap_get_entries($ds, $sr);												// go fetch the results
		LDAPClose($ds);
		return $info[0]['sn'][0];
	}
}
if ( ! function_exists('getPhone'))
{
/*
Function: getPhone

	Queries the LDAP server and returns a users Extention or telephone #.

	Parameters:
		string 	- Users EID
		string	- Users Password	
		
	Returns:
		string	- Telephone number or extention. Which ever is placed in the Tn field for the user.
	
	Example:
		(code)
			$PhoneDetails = getPhone($EID.$Password);
		(end)

*/
    function getPhone($EID,$Password){															
		$ds = LDAPConnect($EID,$Password);
		$Perms = 3;
		$dn = 'OU=Company Divisions,DC=corp,DC=domain,DC=com';								// Set the base DN
		$filter='(&(objectCategory=*)(cn='.$EID.'*))';									// Create the filter (more Detal = faster)
		$justthese = array("tn");														// what we would like to see from the results

		$sr=ldap_search($ds, $dn, $filter,$justthese);									// Build the search string and object
		$info = ldap_get_entries($ds, $sr);												// go fetch the results
		$phone=@$info[0]['tn'][0];
		LDAPClose($ds);
		return $phone;
	}
}
if ( ! function_exists('getLocation'))
{
/*
Function: getLocation

	Queries the LDAP server and returns a users location

	Parameters:
		string 	- Users EID
		string	- Users Password	
		
	Returns:
		object	- Address information as well as dept and division of the requested user
	
	Example:
		(code)
			$Location = getLocation($EID,$Password);
		(end)

*/
    function getLocation($EID,$Password){																
		$ds = LDAPConnect($EID,$Password);
		$Perms = 3;
		$dn = 'OU=Company Divisions,DC=corp,DC=domain,DC=com';									// Set the base DN
		$filter='(&(objectCategory=*)(cn='.$EID.'*))';										// Create the filter (more Detal = faster)
		$justthese = array("l","st","streetaddress","postalcode","department","division");	// what we would like to see from the results

		$sr=ldap_search($ds, $dn, $filter,$justthese);										// Build the search string and object

		$info = ldap_get_entries($ds, $sr);													// go fetch the results

		$Location  = $info[0]['department'][0]. '<br> ';
		$Location .= $info[0]['division'][0]. '<br> ';
		$Location .= $info[0]['streetaddress'][0]. '<br> ';
		$Location .= $info[0]['l'][0]. ',';
		$Location .= $info[0]['st'][0] . ' ';
		$Location .= $info[0]['postalcode'][0];
		LDAPClose($ds);
		return $Location;
	}
}
if ( ! function_exists('getDepartment'))
{
/*
Function: getLocation

	Queries the LDAP server and returns a users location

	Parameters:
		string 	- Users EID
		string	- Users Password	
		
	Returns:
		object	- Address information as well as dept and division of the requested user
	
	Example:
		(code)
			$Location = getLocation($EID,$Password);
		(end)

*/
    function getDepartment($EID,$Password){																
		$ds = LDAPConnect($EID,$Password);
		$Perms = 3;
		$dn = 'OU=Company Divisions,DC=corp,DC=domain,DC=com';									// Set the base DN
		$filter='(&(objectCategory=*)(cn='.$EID.'*))';										// Create the filter (more Detal = faster)
		$justthese = array("department");	// what we would like to see from the results

		$sr=ldap_search($ds, $dn, $filter,$justthese);										// Build the search string and object

		$info = ldap_get_entries($ds, $sr);													// go fetch the results

		$Department  = $info[0]['department'][0];
		LDAPClose($ds);
		return $Department;
	}
}
if ( ! function_exists('getTown'))
{
/*
Function: getTown

	Queries the LDAP server and returns a users location

	Parameters:
		string 	- Users EID
		string	- Users Password	
		
	Returns:
		object	- Address information as well as dept and division of the requested user
	
	Example:
		(code)
			$Location = getTown($EID,$Password);
		(end)

*/
    function getTown($EID,$Password){																
		$ds = LDAPConnect($EID,$Password);
		$Perms = 3;
		$dn = 'OU=Company Divisions,DC=corp,DC=domain,DC=com';									// Set the base DN
		$filter='(&(objectCategory=*)(cn='.$EID.'*))';										// Create the filter (more Detal = faster)
		$justthese = array("l","st","streetaddress","postalcode","department","division");	// what we would like to see from the results

		$sr=ldap_search($ds, $dn, $filter,$justthese);										// Build the search string and object

		$info = ldap_get_entries($ds, $sr);													// go fetch the results

		$Location = $info[0]['l'][0];
		
		LDAPClose($ds);
		return $Location;
	}
}
if ( ! function_exists('getGroups'))
{
/*
Function: getGroups

	Queries the LDAP server and returns a users Title

	Parameters:
		string 	- Users EID
		string	- Users Password		
		
	Returns:
		array	- Array of all groups
	
	Example:
		(code)
			$Groups = getGroups($EID,$Password);
		(end)

*/
    function getGroups($EID,$Password){
			$ds = LDAPConnect($EID,$Password);
			$dn = 'OU=Company Divisions,DC=corp,DC=domain,DC=com';							// Set the base DN
			$filter='(&(objectCategory=*)(cn='.$EID.'*))';                              // Create the filter (more Detal = faster)
			$justthese = array("memberof");                                             // what we would like to see from the results
			$sr=ldap_search($ds, $dn, $filter,$justthese);                              // Build the search string and object
			$info = ldap_get_entries($ds, $sr); 

		foreach($info[0]['memberof'] as $MO){     										// go fetch the results                                      
			$wingit = split(",",$MO);
			$item['group'] = substr($wingit[0],3);										//Trim off the unneeded OUinfo
			$groups[]= $item;
		}
		LDAPClose($ds);
		return $groups;

	}
}
if ( ! function_exists('getOthersName'))
{
/*
Function: getOthersNamee

	Queries the LDAP server and returns another users full name

	Parameters:
		string 	- Users EID
		string	- Users Password
		string  - EID of the person you are trying to lookup
		
	Returns:
		string	 Users First Name concatinated with their Last Name
	
	Example:
		(code)
			$Full_Name = getOthersName($EID,$Password,$OEID);
		(end)

*/
    function getOthersName($EID,$Password,$OEID){														
		$ds = LDAPConnect($EID,$Password);
		$Perms = 3;
		$dn = 'OU=Company Divisions,DC=corp,DC=domain,DC=com';								// Set the base DN
		$filter='(&(objectCategory=*)(cn='.$OEID.'*))';									// Create the filter (more Detal = faster)
		$justthese = array("displayname");												// what we would like to see from the results

		$sr=ldap_search($ds, $dn, $filter,$justthese);									// Build the search string and object

		$info = ldap_get_entries($ds, $sr);												// go fetch the results
		LDAPClose($ds);
		return $info[0]['displayname'][0];
		
	}
}
if ( ! function_exists('getOthersEmail'))
{
/*
Function: getOthersEmail

	Queries the LDAP server and returns another users full name

	Parameters:
		string 	- Users EID
		string	- Users Password
		string  - EID of the person you are trying to lookup
		
	Returns:
		string - Users e-mail address
	
	Example:
		(code)
			$Email = getOthersEmail($EID,$Password,$OEID);
		(end)

*/
    function getOthersEmail($EID,$Password,$OEID){														
		$ds = LDAPConnect($EID,$Password);
		$Perms = 3;
		$dn = 'OU=Company Divisions,DC=corp,DC=domain,DC=com';								// Set the base DN
		$filter='(&(objectCategory=*)(cn='.$OEID.'*))';									// Create the filter (more Detal = faster)
		$justthese = array("mail");														// what we would like to see from the results

		$sr=ldap_search($ds, $dn, $filter,$justthese);									// Build the search string and object

		$info = ldap_get_entries($ds, $sr);												// go fetch the results
		LDAPClose($ds);
		return $info[0]['mail'][0];

	}
}
if ( ! function_exists('getOthersTitle'))
{
/*
Function: getOthersEmail

	Queries the LDAP server and returns another users full name

	Parameters:
		string 	- Users EID
		string	- Users Password
		string  - EID of the person you are trying to lookup
		
	Returns:
		string - Users e-mail address
	
	Example:
		(code)
			$Email = getOthersEmail($EID,$Password,$OEID);
		(end)

*/
    function getOthersTitle($EID,$Password,$OEID){														
		$ds = LDAPConnect($EID,$Password);
		$Perms = 3;
		$dn = 'OU=Company Divisions,DC=corp,DC=domain,DC=com';			// Set the base DN
		$filter='(&(objectCategory=*)(cn='.$OEID.'*))';				// Create the filter (more Detal = faster)
		$justthese = array("title");								// what we would like to see from the results

		$sr=ldap_search($ds, $dn, $filter,$justthese);				// Build the search string and object
		$info = ldap_get_entries($ds, $sr);							// go fetch the results
		LDAPClose($ds);
		return $info[0]['title'][0];

	}
}
if ( ! function_exists('LDAP_authUser'))
{
/*
Function: LDAP_authUser

	Attempts to bind to the LDAP server to validate a users login.

	Parameters:
		string - EID
		string - Password
	Returns:
		int - 1 if valid 0 if not valid
	
	Example:
		(code)
			if(LDAP_authUser($EID,$Password)){
				loadMain();
			}else{
				fireEvent('invalid');
			}
		(end)

*/
    function LDAP_authUser($EID,$Password){

		
		$ADhost			= '';
		$ADhost2		= '';
		
		$ds = ldap_connect($ADhost)														// connect to ldap server
		    or die($ds = ldap_connect($ADhost2) or die("Could not connect to LDAP server."));
		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

		if ($ds) {																		// If connection is good then attempt to bind to server
			if (ldap_bind ( $ds, 'CORP\\'.$EID, $Password)) {						// If We bind ok then return 1.
				return 1 ;
			}else{
				return 0;																// If we do not bind ok then return 0.
			}
		}

	}
}
if ( ! function_exists('getList'))
{
/*
Function: getList

	Queries the LDAP server and returns a list for a drop down

	Parameters:
		string 	- Users EID
		string	- Users Password
		
	Returns:
		string - Users e-mail address
	
	Example:
		(code)
			$List = getList($EID,$Password);
		(end)

*/
    function getList($EID,$Password){															
		$ds = LDAPConnect($EID,$Password);
		$Perms = 3;
		$dn = 'OU=Company Divisions,DC=corp,DC=domain,DC=com';					// Set the base DN
		$filter='(&(objectCategory=*)(cn=E*))';						// Create the filter (more Detal = faster)
		$justthese = array("cn", "displayname");	
																			// what we would like to see from the results
		$options='';
		$sr=ldap_search($ds, $dn, $filter,$justthese);						// Build the search string and object
		$info = ldap_get_entries($ds, $sr);
		for($x=0;$x<count($info);$x++)
			$options.="<option value='".$info[$x]['cn'][0]."'>".$info[$x]['displayname'][0]."<option>";
			
										// go fetch the results
		LDAPClose($ds);
		
		return  $options;

	}
}
/* End of file ldap_helper.php */
/* Location: ./system/helpers/html_helper.php */

?>