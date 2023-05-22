<?php 

	////////////////////////////////////////////////////////////////////////////////
	// TODO: Please follow the instructions to configure the settings for your use case
	
	// Create an Azure AD B2C application in the Azure Portal, then configure the following settings
	$tenant = "alantestsoft";
	$clientID = "ac7e3bcc-15d9-4de2-9a31-dd6c4025c805"; 
	$client_secret = ""; // the client secret for B2C application that you created, only fill this in if you want to use confidential client flow
	$redirect_uri = urlencode("localhost:8080/login"); 
	
	// Decide which authentication flow you would like to follow
	// To use Implicit Flow (recommended), set response_type to "id_token"
	// To use Confidential Client Flow (supported for future flexibility), set response_type "to code"
	$response_type = "id_token"; 
	$response_mode = "form_post"; 
	$scope = "openid"; 
	
	// Create one or more B2C policies in the Azure Portal. 
	// This example code uses 3 policies - 
	// 1. a generic sign in or sign up policy (no MFA)
	// 2. an optional administrator sign in or sign up policy (with MFA)
	// 3. a profile editing policy
	$generic_policy = "B2C_1_SignUpSignIn";
	$admin_policy = "B2C_1_SignUpSignIn";
	$edit_profile_policy = "B2C_1_EditProfile";
	
	// List of admins' email addresses. You can also leave this empty.
	$admins = array("");
	
	
	
	// END OF CONFIGURABLE SETTINGS /////////////////////////////////////////////////////////////////////////////
	$metadata_endpoint_begin = 'https://login.microsoftonline.com/'.
						 $tenant.
						 '.onmicrosoft.com/v2.0/.well-known/openid-configuration?p=';
						 

?>