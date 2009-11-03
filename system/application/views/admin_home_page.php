<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Phone manager</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>media/css/base.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>media/css/style.css" />
</head>
<body>

<?php 
	if($user_group==1){
?>
<div class="p1">
<?php echo form_open_multipart('/report/index');?>
Upload file(xml only): <input type="file" name="xmlfile"/>
<input type="submit" name="sm" value="Upload"/>
</form>
</div>
<?php 
	}
?>

<hr/>	
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
				if($action == 'query' && $row_id >= 1)
				{
					?>
					<form action="" method="post">
					<table>
						<tr>
							<td colspan="2"><b>Export data</b>:</td>
						</tr>
					<?php 
						foreach($info as $k=>$v)
						{
							echo "<tr><td>$k</td><td>$v</td></tr>";
						}
					?>
					<tr>
						<td colspan="2">
							<input type="hidden" name="row_id" value="<?php echo $row_id?>"/>
							<input type="hidden" name="filename" value="<?php echo $filename?>"/>
							<input type="hidden" name="step" value="export"/>
							<input type="submit" name="sm" value="Export" />
						</td>
					</tr>
					</table>
					</form>
					<?
				}
			?>
			<?php
				if(isset($inputs))
				{
					if(count($inputs)>=1)
					{ 
			?>
			<?php echo form_open('/report/index/'.$row_id);?>
			<table cellpadding="5" cellspacing="5" width="100%">
			<tr>
				<td colspan="2">
					<b>Define SQL Query</b>:<br/><br/>
					<?php echo "Query: ".$sql?>
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
						<input type="submit" name="sm" value="Define SQL"/>		
					</td>
					<td></td>
				</tr>
			</table>
			</form>
			<?php } } ?>			
	</td>
</tr>
</table>

<div class="p1">
<?php
	if(isset($export_file)){
		if(count($export_file)>=1 && $export_file[1]>=1)
		{
			echo "Export $export_file[1] records successful. <a href='".base_url()."reports/$export_file[0]'>|Download|</a>";	
		} 
	}
?>
</div>
<hr/>
<div class="p1">
<a href="<?php echo base_url();?>index.php/report/index">Refesh</a> | 
<a href="javascript:history.back(-1)">Back</a> | 
<a href="<?php echo base_url();?>index.php/report/logout/">Logout</a>
</div> 
</body>
</html>