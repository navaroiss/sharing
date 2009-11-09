<?php

/**
 * $Id: Nov 5, 2009 12:38:10 AM navaro $
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
<?php
	if(isset($row))
	{
?>
<?php echo form_open('/store/update/'.$row_id);?>
<table cellpadding="5" cellspacing="5" width="100%">
<tr>
	<td>Title</td>
	<td><input type="text" name="title" value="<?php echo $row->title?>"/></td>
</tr>
<tr>
	<td>Query</td>
	<td><textarea disabled="disabled" cols=70 rows=5><?php echo $row->sql?></textarea></td>
</tr>
<?php 
	if(count($param_fields)>=1)
	{
		$i=1;
		foreach($param_fields as $k=>$v)
		{
			?>
<tr>
	<td>PARAM.<?php echo $i?></td>
	<td><?php echo $v?></td>
</tr>
			<?
		$i++;
		}	
	}
?>
</table>
<div class="submit-row" >
	<input type="hidden" value="<?php echo $row_id?>" name="row_id" />
	<?php if(count($param_fields)>=1):?>
	<input type="submit" value="Define" name="define"  />
	<?php else: ?>
	<input type="submit" onclick="window.location='<?php echo base_url()?>index.php/store/showing'; return false;" value="Back to store" id="back_bt" name="sm"  />
	<?php endif ?>
	
</div>
</form>
<?php } ?>	

        <br class="clear" />
    </div>

    <!-- END Content -->

    <div id="footer"></div>
</div>
<!-- END Container -->

</body>
</html>
