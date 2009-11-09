<?php

/**
 * $Id: Nov 5, 2009 12:02:31 AM navaro $
 * 
 */
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="" xml:lang="" >
<head>
<?php include_once(dirname(__FILE__).'/header.php')?>

</head>


<body class="change-list">

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
    <div id="content" class="flex">
      <form action="" method="post" name="f1" id="f1">
      
<table cellspacing="0" width="100%" cellpadding="0">
<thead>
<tr>
<th width="5px">
<input type="checkbox" id="action-toggle" />
</th><th>
<a id="sort_title" href="#">
Title
</a></th>
<th width="150px">
<a id="sort_date" href="#">
Date
</a></th>
<?php if($user_group==1):?>
<th width="50px">Define</th>
<th width="50px">Remove</th>
<th width="50px">Scripting</th>
<?php endif ?>
</tr>
</thead>
<tbody>
<?php
	$i=0;
	foreach($xml_rows as $k=>$v)
	{ 
		if ($i==0 || $i%2==0)
			$class="row2";
		else
			$class="row1";
		?>
			<tr class="<?php echo $class?>">
			<td><input type="checkbox" class="action-select" value="<?php echo $v->id?>" name="selected_action[]" /></td>
			<th><a href="<?php echo base_url()?>index.php/store/export/<?php echo $v->id?>"><?php echo $v->title?></a></th>
			<td><?php echo $v->datetime?></td>
<?php if($user_group==1){?>
			<td align="right"><a href="<?php echo base_url()?>index.php/store/update/<?php echo $v->id?>"><img src="<?php echo base_url()?>media/img/icon_changelink.gif"/></a></td>
			<td align="right"><a href="<?php echo base_url()?>index.php/store/remove/<?php echo $v->id?>" onclick="return confirm('Are you sure you want to delete?')"><img src="<?php echo base_url()?>media/img/icon-no.gif"/></a></td>
			<td align="right"><a href="<?php echo base_url()?>index.php/script/delete/<?php echo $v->id?>">Delete</a></td>
<?php } ?>
			</tr>
		<?php
		$i++;
	}
?>

<tr>
	<td colspan=6>
		<select name="more_act" id="more_act">
			<option selected="selected" value="">______</option>
			<option value="delete">Delete</option>
		</select>
		<?php if($user_group==1):?><a class="addlink" href="<?php echo base_url()?>index.php/store/adding/">Upload XML</a><?php endif ?>
	</td>
</tr>
</tbody>
</table>
<input type="hidden" name="hide_act" value=""/>
      </form>
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
