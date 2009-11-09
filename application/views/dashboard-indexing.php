<?php

/**
 * $Id: Nov 4, 2009 10:43:37 PM navaro $
 * 
 */
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="" xml:lang="" >
<head>
<?php include_once(dirname(__FILE__).'/header.php')?>
</head>
<body class="dashboard">
<!-- Container -->
<div id="container">
    <!-- Header -->
    <div id="header">
        <div id="branding">
<h1 id="site-name">BDD Phoning manager</h1>
        </div>
        <div id="user-tools">
            Welcome,
            <strong><?php echo $username?></strong>.
                 	/
                    <a href="<?php echo base_url()?>index.php/user/logout/"> Log out</a>
        </div>
    </div>
    <!-- END Header -->
    <!-- Content -->
    <div id="content" class="colMS">
        <h1>Site administration</h1>
		<div id="content-main">
     	
        <div class="module">
        <table summary="Models available in the Papershare application.">
        <caption><a href="" class="section">Tools</a></caption>
            <tr>
                <th scope="row"><a href="<?php echo base_url()?>index.php/store/showing/">XML</a></th>
                <td><?php if($user_group==1):?><a href="<?php echo base_url()?>index.php/store/adding/" class="addlink">Upload</a><?php endif?></td>
                <!-- <td><a href="<?php echo base_url()?>index.php/store/modifying/" class="changelink">Change</a></td> -->
            </tr>
            <tr>
                <th scope="row"><a href="<?php echo base_url()?>index.php/report/showing/">Report</a></th>
                <!-- <td><a href="<?php echo base_url()?>index.php/report/adding/" class="addlink">Add</a></td>-->
                <!-- <td><a href="<?php echo base_url()?>index.php/report/modifying/" class="changelink">Change</a></td> -->
            </tr>
        </table>

        </div>
</div>



        <br class="clear" />
    </div>
    <!-- END Content -->

    <div id="footer"></div>
</div>
<!-- END Container -->

</body>
</html>
