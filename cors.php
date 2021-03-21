<?php
	/* Command Injection
	 * Method Tampering
	 * Cross Site Scripting
	 * HTML Injection */

	try {
    	switch ($_SESSION["security-level"]){
    		case "0": // This code is insecure. No input validation is performed.
				$lEnableJavaScriptValidation = FALSE;
				$lEnableHTMLControls = FALSE;
    		break;

    		case "1": // This code is insecure. No input validation is performed.
				$lEnableJavaScriptValidation = TRUE;
				$lEnableHTMLControls = TRUE;
    		break;

	   		case "2":
	   		case "3":
	   		case "4":
    		case "5": // This code is fairly secure
				$lEnableHTMLControls = TRUE;
    			$lEnableJavaScriptValidation = TRUE;
    		break;
    	}// end switch
	}catch(Exception $e){
	    echo $CustomErrorHandler->FormatError($e, "Error setting up configuration on page cors.php");
	}// end try
?>

<div class="page-title">Cross-origin Resource Sharing (CORS)</div>

<?php include_once (__ROOT__.'/includes/back-button.inc');?>
<?php include_once (__ROOT__.'/includes/hints/hints-menu-wrapper.inc');?>

<!-- BEGIN HTML OUTPUT  -->
<script type="text/javascript">
	var onSubmitOfForm = function(){

		lText = document.getElementById('idMessageInput').value;

		<?php
		if($lEnableJavaScriptValidation){
			echo "var lOSCommandInjectionPattern = /[;&|<>]/;";
			echo "var lCrossSiteScriptingPattern = /[<>=()]/;";
		}else{
			echo "var lOSCommandInjectionPattern = /[]/;";
			echo "var lCrossSiteScriptingPattern = /[]/;";
		}// end if
		?>

		if(lText.search(lOSCommandInjectionPattern) > -1){
			alert("Malicious characters are not allowed.\n\nDo not listen to security people. Everyone knows if we just filter dangerous characters, injection is not possible.\n\nWe use JavaScript defenses combined with filtering technology.\n\nBoth are such great defenses that you are stopped in your tracks.");
			return false;
		}else if(lText.search(lCrossSiteScriptingPattern) > -1){
			alert("Characters used in cross-site scripting are not allowed.\n\nDon\'t listen to security people. Everyone knows if we just filter dangerous characters, injection is not possible.\n\nWe use JavaScript defenses combined with filtering technology.\n\nBoth are such great defenses that you are stopped in your tracks.");
			return false;
		}// end if

        var lXMLHTTP = new XMLHttpRequest();


        lXMLHTTP.onreadystatechange = function() {
            if (this.readyState == 4) {
               // Typical action to be performed when the document is ready:
               document.getElementById("idMessageOutput").innerHTML = lXMLHTTP.responseText;
            }
        };
        lXMLHTTP.open("GET", "http://cors.mutillidae.local/webservices/rest/cors-server.php?message="+encodeURIComponent(lText), true);
        lXMLHTTP.send();

	};// end JavaScript function onSubmitOfForm()
</script>

<a href="index.php?page=content-security-policy.php">
    <img src="images/shield-icon-75-75.png" />
    <span class="label">Switch to Content Security Policy (CSP)</span>
</a>

<table>
	<tr><td></td></tr>
	<tr>
		<td colspan="2" class="form-header">Enter message to echo</td>
	</tr>
	<tr><td></td></tr>
	<tr>
		<td class="label">Message</td>
		<td>
			<input 	type="text" id="idMessageInput" name="message" size="20"
					autofocus="autofocus" onkeypress="if(event.keyCode==13){onSubmitOfForm();}"
					<?php
						if ($lEnableHTMLControls) {
							echo('minlength="1" maxlength="20" required="required"');
						}// end if
					?>
			/>
		</td>
	</tr>
	<tr><td></td></tr>
	<tr>
		<td colspan="2" style="text-align:center;">
			<input onclick="onSubmitOfForm();"
			name="echo-php-submit-button" class="button" type="button" value="Echo Message" />
		</td>
	</tr>
	<tr><td></td></tr>
</table>
<div id="idMessageOutput"></div>