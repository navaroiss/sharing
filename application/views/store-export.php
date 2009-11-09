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
		<form action="" method="post">
		<?php if(count($params)>=1):?>
		<table width=100%>
		<tr>
			<td colspan=2><h1><?php echo $export_xml_title?></h1></td>
		</tr>
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
		<?php else: ?>
		<div>Not need to select any params. Just press Export button.</div>
		<?php endif ?>
        <br class="clear" />
<div class="submit-row" >
	<input type="hidden" name="special_export_function" value="<?php echo $special_export_function?>" />
	<input type="submit" value="Export" name="sm"  />
</div>

<div>
<br/>
<?php if(count($export_file)==2){?>
<?php if($export_file[1]>=2):?>
<a href="<?php echo base_url()?>reports/<?php echo $export_file[0]?>">Download</a>. Export <?php echo $export_file[1]?> rows successful.
<?php else: ?>
No match any record.
<?php endif ?>
<?php } ?>
</div>
</form>
    </div>

    <!-- END Content -->

    <div id="footer"></div>
</div>
<!-- END Container -->

</body>
</html>
