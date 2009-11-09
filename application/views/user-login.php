<?php

/**
 * $Id: Nov 4, 2009 10:07:40 PM navaro $
 * 
 */
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="" xml:lang="" >
<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<?php include_once(dirname(__FILE__).'/header.php')?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>/media/css/login.css" />
</head>

<body class="login">

<!-- Container -->

<div id="container">
    <!-- Header -->
    <div id="header">
        <div id="branding">
        
		<h1 id="site-name">Phoning manager</h1>

        </div>
        
    </div>
    <!-- END Header -->

    <!-- Content -->
    <div id="content" class="colM">

<div id="content-main">
<form action="" method="post" id="login-form">
  <div class="form-row">
    <label for="id_username">Username:</label> <input type="text" name="username" id="id_username" />
  </div>
  <div class="form-row">

    <label for="id_password">Password:</label> <input type="password" name="password" id="id_password" />
    <input type="hidden" name="this_is_the_login_form" value="1" />
  </div>
  <div class="submit-row">
    <label>&nbsp;</label><input type="submit" value="Log in" />
  </div>
</form>

<script type="text/javascript">
	document.getElementById('id_username').focus()
</script>
</div>
        
        <br class="clear" />
    </div>
    <!-- END Content -->

    <div id="footer"></div>
</div>
<!-- END Container -->

</body>
</html>

