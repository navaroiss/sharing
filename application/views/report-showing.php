<?php

/**
 * $Id: Nov 7, 2009 10:13:43 PM navaro $
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
     <a href="<?php echo base_url()?>index.php/report/showing">Report</a>
     
    </div>
    <!-- Content -->
    <div id="content" class="flex">
      <form action="" method="post" name="f1" id="f1">
      
<table cellspacing="0" width="100%" cellpadding="0">
<thead>
<tr>
<th width="5px">
<input type="checkbox" id="action-toggle" />
</th>
<th width="auto">Date</th>
<th>Title</th>
<th>User</th>
<th width="auto">Filename</th>
<th width="50px">Remove</th>
</tr>
</thead>
<tbody>
<?php
	
	$i=0;
	foreach($data_rows as $k=>$v)
	{ 
		if ($i==0 || $i%2==0)
			$class="row2";
		else
			$class="row1";
		?>
			<tr class="<?php echo $class?>">
			<td><input type="checkbox" class="action-select" value="<?php echo $v->id?>" name="selected_action[]" /></td>
			<td><?php echo $v->datetime?></td>
			<td><?php echo $v->title?></td>
			<td><?php echo $v->usr_name?></td>
			<td><a href="<?php echo base_url()?>reports/<?php echo $v->filename?>"><?php echo $v->filename?></a></td>
			<td align="right"><a href="<?php echo base_url()?>index.php/report/remove/<?php echo $v->id?>" onclick="return confirm('Are you sure you want to delete?')"><img src="<?php echo base_url()?>media/img/icon-no.gif"/></a></td>
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
