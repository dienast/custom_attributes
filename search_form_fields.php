<fieldset class='box show_only'>
<?php 
foreach ($fields as $field) { 
	if (!$field['b_search']) continue;
	$field_id = $field['pk_i_id'];
	$range_boolean = $field['b_range'];
	$range = 'field_' . $field_id;
	$steps = $field['s_steps'];
	$range_options = $field['s_options'];
	$name = 'field_' . $field_id;
	$label = $field['s_label'];
	$type = $field['s_type'];
	$value = Params::getParam($same);
	$custom = $name;
?>
	<div class='row one_input'>
		<h6><?php echo $label; ?></h6>
<?php if ($type == 'text') { ?>
			<input type='text' name='<?php echo $name; ?>' value='<?php echo $value; ?>' />
<?php } else if ($type == 'checkbox') { ?>
<?php   $checked = ($value == 'checked') ? " checked='checked'" : ''; ?>
			<input class='search_checkbox' type='checkbox' id='<?php echo $name; ?>' name='<?php echo $name; ?>' value='checked'<?php echo $checked; ?> />
			<label class='search_label' for='<?php echo $name; ?>'><?php _e('Check to show listings', PLUGIN_NAME); ?></label>
<?php } else if ($type == 'select' || $type == 'radio') { 
			if ($range_boolean == 1) { 
				$minmax = explode(',', $range_options);
				$min = $minmax[0];
				$max = $minmax[1];											
?>																			
<style type="text/css">
    .right .selector {
        float: right;
    } 
</style>
<script type="text/javascript">
    $(function() {
        $("#custom-range<?php echo $custom; ?>").slider({
            range: true,
            min: <?php echo "$min";?>,
            max: <?php echo "$max"; ?>,
            step: <?php echo "$steps";?>,
            values: [<?php echo "$min,$max";?>],
            slide: function(event, ui) {
                $("#<?php echo $custom; ?>").attr("value", ui.values[0]+','+ ui.values[1]);
            }
        });            
        $("<?php echo $custom; ?>").val($("#custom-range<?php echo $custom; ?>").slider("values", 0) + ' - ' + $("#custom-range<?php echo $custom; ?>").slider("values", 1));
    });
</script>										
			<input type="hidden" id="<?php echo "$custom";?>" name="<?php echo $custom; ?>"  />						
	        <div class="slider" >
	            <div id="custom-range<?php echo $custom;?>"></div>
	        </div>																					
			<?php } if ($type == 'select' && $range_boolean == 0 ) { ?>
			<select class='search_select' name='<?php echo $range; ?>'> 
				<?php ca_select_options($field_id, $value); ?>
			</select>
	<?php 	}
		 else if ($type == 'radio' && $range_boolean == 0) { ?>	
		<?php ca_radio_buttons($field_id, $name, $value); ?>
	<?php } ?>
<?php } ?>	
	</div>
<?php } ?>
</fieldset>
<?php //END
