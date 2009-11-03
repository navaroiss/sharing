<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Phone manager</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>media/css/base.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>media/css/style.css" />
</head>
<body>

<table border=0>
<tr>
	<td width="500">
		<?php 
			if(count($xmlfiles)>=1){
		?>
		<table cellpadding="0" cellspacing="0" background="0">
		<tr>
			<td class="title">Time</td>
			<td class="title">Title</td>
			<?php if($user_group==1):?>
			<td class="title">Update</td>
			<td class="title">Delete</td>
			<td class="title">Script</td>
			<?php endif ?>
		</tr>
		<?php 
			$i=1;
			foreach($xmlfiles as $k=>$v)
			{
				$class = ($i%2==0)?"row1":"row2";
				?>
					<tr>
						<td class="<?php echo $class?>"><i><?php echo $v->datetime?></i></td>
						<td class="<?php echo $class?>">
							<?php
								if ($v->action ==""){
									$url=base_url()."index.php/report/index/query/$v->id";
								}else{
									$url=base_url()."index.php/report/special/$v->id";
								}
							?>
							<a href="<?php echo $url?>"><?php echo $v->title_name?></a>
						</td>
						<?php if($user_group==1):?>
						<td class="<?php echo $class?>"><a href="<?php echo base_url()?>index.php/report/index/update/<?php echo $v->id?>"><img src="<?php echo base_url()?>media/img/icon_changelink.gif"/></a></td>
						<td class="<?php echo $class?>"><a href="<?php echo base_url()?>index.php/report/delete/<?php echo $v->id?>" onclick="return confirm('Are you sure you want to delete?')"><img src="<?php echo base_url()?>media/img/icon-no.gif"/></a></td>
						<td class="<?php echo $class?>">
							<a href="<?php echo base_url()?>index.php/report/script/update/<?php echo $v->id?>">Update</a> / 
							<a href="<?php echo base_url()?>index.php/report/script/delete/<?php echo $v->id?>">Delete</a>
						</td>
						<?php endif ?>
					</tr>
				<?
				$i++;
			}
		?>
		</table>	
		<?php } ?>	
	</td>
	<td class="spr" valign="top">
		<?php 
			if($action=="delete"):
			$i=1;
		?>
		<form action="" method="POST">
		<table>
			<tr>
				<td colspan="2" class="title">Delete</td>
			</tr>
			<?php
				foreach($info as $k=>$v):
				?>
				<tr>
					<td><?php echo $k?></td>
					<td><?php echo $v?></td>
				</tr>
				<?php
				endforeach; 
			?>
			<tr>
				<td colspan="2">
				<input type="hidden" name="table_name" value="<?php echo $table_name?>" />
				<input type="submit" name="delete" value="Delete"/>
				</td>
			</tr>
		</table>
		</form>
		<?php 
			endif;
		?>
	</td>
</tr>
</table>

<hr/>
<div class="p1">
<a href="<?php echo base_url();?>index.php/report/index">Refesh</a> | 
<a href="javascript:history.back(-1)">Back</a> | 
<a href="<?php echo base_url();?>index.php/report/logout/">Logout</a>
</div> 
</body>
</html>