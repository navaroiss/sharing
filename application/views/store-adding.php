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
	if(isset($inputs))
	{
		if(count($inputs)>=1)
		{ 
?>
<?php echo form_open('/store/adding/'.$last_insert_id);?>
<table cellpadding="5" cellspacing="5" width="100%">
<tr>
	<td colspan="2">
		<b>Define SQL Query</b>:<br/><br/>
		<?php echo $sql?>
	</td>
</tr>
<?php 
	foreach($inputs as $k=>$v){
		?>
	<tr>
		<td valign="top" align="right">PARAM.<?php echo $k?>: </td>
		<td valign="top" align="left"><?php echo $v;?></td>
	</tr>
<? } ?>
	<tr>
		<td>
			<input type="hidden" name="step" value="define"/>
			<input type="hidden" name="last_insert_id" value="<?php echo $last_insert_id?>"/>
			<input type="submit" name="sm_define_sql" value="Define SQL"/>		
		</td>
		<td></td>
	</tr>
</table>
</form>
<?php } } ?>	

<?php if(!isset($inputs)) {?>
<?php echo form_open_multipart('/store/adding');?>
<div>
      <div>
		Upload file(xml only): <input type="file" name="xmlfile"/>
      </div>
<div class="submit-row" >
	<input type="submit" value="Upload XML " name="_addanother"  />
</div>
</div>
</form>
<?php } ?>
</div>

        <br class="clear" />
    </div>

    <!-- END Content -->

    <div id="footer"></div>
</div>
<!-- END Container -->

</body>
</html>
