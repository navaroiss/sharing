<?php
 
/**
 * $Id: Nov 5, 2009 10:46:46 AM navaro $
 *
 */
?> 
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<title>BDD Phoning manager</title>

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>/media/css/base.css" />
<!--[if lte IE 7]><link rel="stylesheet" type="text/css" href="<?php echo base_url()?>/media/css/ie.css" /><![endif]-->
<meta name="robots" content="NONE,NOARCHIVE" />
<script src="<?php echo base_url()?>media/js/jquery-1.3.2.min.js"></script>
<script src="<?php echo base_url()?>media/js/jquery.cookie.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>/media/css/dashboard.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>/media/css/forms.css" />

<script type="text/javascript">
	var options = { path: '/', expires: 1 };
	var DESC = 'desc';
	var ASC = 'asc';
	
	this.sort_columns = function(){
		if($.cookie('clicked')=='null')
			$.cookie('clicked', DESC, options);
		$('#sort_title').click(function(e){
			if($.cookie('clicked')==DESC)
			{
				$.cookie('clicked', ASC, options);
			}else{
				$.cookie('clicked', DESC, options);
			}
			window.location = '<?php echo base_url()?>index.php/store/showing/title/' + $.cookie('clicked');
		});
		$('#sort_date').click(function(e){
			if($.cookie('clicked')==DESC)
			{
				$.cookie('clicked', ASC, options);
			}else{
				$.cookie('clicked', DESC, options);
			}
			window.location = '<?php echo base_url()?>index.php/store/showing/datetime/' + $.cookie('clicked');
		});
	}
	
	this.checkall_radio = function(){
		$('#action-toggle').click(function(e){
			s = this.checked;
			$('.action-select').each(function(e){
				this.checked = s; 
			})
		});	
	}

	this.more_action = function()
	{
		$('#more_act').change(function(){
			if(this.value=='delete')
			{
				var d = 0;
				$('.action-select').each(function(e){
					if(this.checked == true)
					{
						d = d + 1;
					} 
				})
				if(d >= 1 && confirm('Are you sure to delete there rows?'))
				{
					document.f1.submit();
				}
			}
		});
	}
	
	$(document).ready(function(){
		sort_columns(); // sort data
		checkall_radio(); // check, un-check radio 
		more_action(); // delete action...
	});
</script>