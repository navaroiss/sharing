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


<body class="papershare-request change-form">
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
    
<div class="breadcrumbs">
     <a href="<?php echo base_url()?>index.php/dashboard/indexing">Home</a> &rsaquo;
     <a href="<?php echo base_url()?>index.php/store/showing">XML</a>
     
</div>
    <!-- Content -->
    <div id="content" class="colM">
        <div id="content-main">
		<form action="" method="post">
		<?php if(count($params)>=1):?>
		<table width=100%>
		<?php 
			foreach($params as $k=>$v)
			{
			?>
				<tr>
					<td><?php echo $k?></td>
					<td><?php echo $v?></td>
				</tr>
			<?php 	
			}
		?>	
		</table>
		<?php endif ?>
        <br class="clear" />
<div class="submit-row" >
	<input type="hidden" name="table_name" value="<?php echo $table_name?>"/>
	<input type="submit" value="Delete" name="delete"  />
</div>

</form>
    </div>

    <!-- END Content -->

    <div id="footer"></div>
</div>
<!-- END Container -->

</body>
</html>
