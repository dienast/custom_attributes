<?php
// Process actions
$action = Params::getParam('plugin_action');
$group_id = Params::getParam('group_id');
if (!empty($action)) {
	switch($action) {
		case('add_field'): 
			$label = Params::getParam('field_label');
			if (!empty($label)) {
				$type = Params::getParam('field_type');
				$options = Params::getParam('field_options');
				$range = Params::getParam('field_range');
				$steps = Params::getParam('field_steps');
				$required = Params::getParam('field_required');
				$search = Params::getParam('field_search');
				$field_id = Attributes::newInstance()->insertField($type, $label, $options, $range, $steps, $required, $search);
				if (!empty($group_id) && !empty($field_id)) {
					Attributes::newInstance()->insertMeta($group_id, $field_id);
				}
				osc_add_flash_ok_message( __('Attribute added', PLUGIN_NAME), PLUGIN_NAME);
			}
			break;
		case('delete_field'):
			$attribute = Params::getParam('field_select');
			if(!empty($attribute)) {
				Attributes::newInstance()->deleteField($attribute);
				osc_add_flash_ok_message( __('Attribute deleted', PLUGIN_NAME), PLUGIN_NAME);
			}
			break;
		case('order_attributes'):
			$order_type = Params::getParam('order_type');
			if (empty($group_id)) {
				osc_set_preference('order_type', $order_type, PLUGIN_NAME, 'STRING');
				osc_reset_preferences();
			} else {
				Attributes::newInstance()->setGroupOrderType($group_id, $order_type);
			}
			$fields = Attributes::newInstance()->getFields($group_id);
			foreach ($fields as $field) {
				$order = Params::getParam('field_' . $field['pk_i_id']);
				if (!empty($order)) {
					Attributes::newInstance()->setFieldOrder($field['pk_i_id'], intval($order));
				}
			}
			osc_add_flash_ok_message( __('Attribute order saved', PLUGIN_NAME), PLUGIN_NAME);
			break;
		case('edit_attribute'):
			$field_id = Params::getParam('field_id');
			$type = Params::getParam('edit_type');
			$label = Params::getParam('edit_label');
			$options = Params::getParam('edit_options');
			$range = Params::getParam('edit_range');
			$steps = Params::getParam('edit_steps');
			$required = Params::getParam('edit_required');
			$search = Params::getParam('edit_search');
			if (!empty($label)) {
				Attributes::newInstance()->setField($field_id, $type, $label, $options, $range, $steps, $required, $search);
				osc_add_flash_ok_message( __('Attribute saved', PLUGIN_NAME), PLUGIN_NAME);
			} else {
				osc_add_flash_warning_message( __('Attribute label required', PLUGIN_NAME), PLUGIN_NAME);
			}
			break;
	}
}

// Get groups and fields
$group_id = Params::getParam('group_id');
$groups = Attributes::newInstance()->getGroups();
$fields = Attributes::newInstance()->getFields($group_id);
?>
<script type='text/javascript' src='<?php echo osc_plugin_url(__FILE__); ?>js/attributes.js'></script>
<?php osc_show_flash_message(PLUGIN_NAME); ?>
<div id='custom_attributes'>
	<h2 class='render-title heading'><?php _e('Custom Attributes', PLUGIN_NAME); ?></h2>
	<div class='config_column'>
<?php	if (count($groups) > 0) { ?>
		<div class='select_group'>
			<h2 class='render-title sub_heading'><?php _e('Select Group', PLUGIN_NAME); ?></h2>
			<form method='post' action='<?php echo osc_admin_base_url(true); ?>'>
				<input type='hidden' name='page' value='plugins' />
				<input type='hidden' name='action' value='renderplugin' />
				<input type='hidden' name='file' value='<?php echo osc_plugin_folder(__FILE__); ?>conf.php' />
				<input type='hidden' name='plugin_action' value='select_group' />	
				<select name='group_id'>
					<option class='select_option' value=''><?php _e('Select a group', PLUGIN_NAME); ?></option>
<?php
$selected_id = Params::getParam('group_id');
foreach ($groups as $group) {
	$id = $group['pk_i_id'];
	$name = trim($group['s_name']);
	if ($id == $selected_id) {
		$selected = " selected='selected'";
	} else {
		$selected = '';
	}
?>
					<option value='<?php echo $id; ?>'<?php echo $selected; ?>><?php echo $name; ?></option>
<?php	}	?>
				</select>
				<p><button class='btn btn-mini' type='submit'><?php _e('Edit', PLUGIN_NAME); ?></button></p>
			</form>
		</div>	
<?php } ?>		
		<div class='add_attribute'>
			<h2 class='render-title sub_heading'><?php _e('Add Attribute', PLUGIN_NAME); ?></h2>
			<form method='post' action='<?php echo osc_admin_base_url(true);?>'>
				<input type='hidden' name='page' value='plugins' />
				<input type='hidden' name='action' value='renderplugin' />
				<input type='hidden' name='file' value='<?php echo osc_plugin_folder(__FILE__); ?>conf.php' />
				<input type='hidden' name='plugin_action' value='add_field' />
				<input type='hidden' name='group_id' value='<?php echo $group_id; ?>' />
				<label><?php _e('Attribute name', PLUGIN_NAME); ?></label><br />
				<input class='text_input' type='text' name='field_label' value='' />
				<p class='field_options'>
					<label><?php _e('Attribute options or range', PLUGIN_NAME); ?></label><br />
					<input class='text_input' type='text' name='field_options' value='' />
					<span class='options_text'><?php _e('Separate options with commas (eg. a, b, c).<br/> If you check Range below then give only two <br/>values: min and max (e.g. 0,5000)', PLUGIN_NAME); ?></span>									
					<label><?php _e('Range', PLUGIN_NAME); ?><input class='checkbox_input' type='checkbox' name='field_range' value='1' /></label><br />
					<br/><label><?php _e('Steps', PLUGIN_NAME); ?></label><br /><label><input class='text_input' type='text' name='field_steps' value='' /></label><span class="options_text">If checked Range fill in the step size (e.g. if<br/> range is 0,5000 then an appropriate size <br/>would be 100)</span>
				</p>
				<p>
					<select class='field_type' name='field_type'>
						<option value='text'><?php _e('Text', PLUGIN_NAME); ?></option>
						<option value='select'><?php _e('Select', PLUGIN_NAME); ?></option>
						<option value='radio'><?php _e('Radio', PLUGIN_NAME); ?></option>
						<option value='checkbox'><?php _e('Checkbox', PLUGIN_NAME); ?></option>
					</select>
				</p>
				<p>
					<label class='field_required'><input class='checkbox_input' type='checkbox' name='field_required' value='1' /><?php _e('Require', PLUGIN_NAME); ?></label>
					<label><input class='checkbox_input' type='checkbox' name='field_search' value='1' checked='checked' /><?php _e('Search', PLUGIN_NAME); ?></label>
				</p>
				<p><button class='btn btn-mini' type='submit'><?php _e('Create', PLUGIN_NAME); ?></button></p>
			</form>
		</div>
<?php	if (count($fields) > 0) { ?>
		<div class='delete_attribute'>
			<h2 class='render-title sub_heading'><?php _e('Delete Attribute', PLUGIN_NAME); ?></h2>
			<form method='post' action='<?php echo osc_admin_base_url(true); ?>'>
				<input type='hidden' name='page' value='plugins' />
				<input type='hidden' name='action' value='renderplugin' />
				<input type='hidden' name='file' value='<?php echo osc_plugin_folder(__FILE__); ?>conf.php' />
				<input type='hidden' name='plugin_action' value='delete_field' />
				<input type='hidden' name='group_id' value='<?php echo $group_id; ?>' />				
				<select name='field_select'>
					<option class='select_option' value=''><?php _e('Select an attribute', PLUGIN_NAME); ?></option>
<?php
foreach ($fields as $field) {
	$id = $field['pk_i_id'];
	$label = trim($field['s_label']);
?>
					<option value='<?php echo $id; ?>'><?php echo $label; ?></option>
<?php	}	?>
				</select>
				<p><button class='btn btn-mini' type='submit'><?php _e('Delete', PLUGIN_NAME); ?></button></p>
			</form>
		</div>
<?php } ?>
	</div>
<?php	if (count($fields) > 0) { ?>
	<div class='config_column'>
		<h2 class='render-title sub_heading'><?php _e('Edit Attributes', PLUGIN_NAME); ?></h2>
			<div class='list-categories'>
				<ul class='attribute_list'>
<?php
foreach ($fields as $field) { 
	$field_id = $field['pk_i_id'];
	$type = $field['s_type'];
	$label = trim($field['s_label']);
	$options = $field['s_options'];
	$range = $field['b_range'];
	$steps = $field['s_steps'];
	$required = $field['b_required'];
	$search = $field['b_search'];
	$order = $field['i_order'];
	$items = Attributes::newInstance()->getItems($field_id);
?>
					<li class='category_div'>
						<div class='attribute_label'>
							<?php echo $label; ?>
							<img class='arrow_open' src='<?php echo osc_plugin_url(__FILE__); ?>css/arrow_open.png' alt='' />
							<img class='arrow_close' src='<?php echo osc_plugin_url(__FILE__); ?>css/arrow_close.png' alt='' />
						</div>
						<ul class='attribute_panel'>
							<form method='post' action='<?php echo osc_admin_base_url(true);?>'>
								<input type='hidden' name='page' value='plugins' />
								<input type='hidden' name='action' value='renderplugin' />
								<input type='hidden' name='file' value='<?php echo osc_plugin_folder(__FILE__); ?>conf.php' />
								<input type='hidden' name='plugin_action' value='edit_attribute' />		
								<input type='hidden' name='field_id' value='<?php echo $field_id; ?>' />								
								<input type='hidden' name='group_id' value='<?php echo $group_id; ?>' />								
								<li><input class='edit_label' type='text' name='edit_label' value='<?php echo $label; ?>' /></li>
<?php if (!empty($options)) { ?>								
								<li>
									<input type='text' class='edit_options' name='edit_options' value='<?php echo $options; ?>' /><br />
									<span class='options_text'><?php _e('Separate options with commas (eg. a, b, c).', PLUGIN_NAME); ?></span>							
								</li>
								<li>
								<input type='text' class='edit_steps' name='edit_steps' value='<?php echo $steps; ?>' /><br />
									<span class='options_text'><?php _e('If checked Range fill in the step size (e.g. if
range is 0,5000 then an appropriate size
would be 100)', PLUGIN_NAME); ?></span>
									</li>
<?php } ?>
								<li>
									<select class='field_type' name='edit_type'>
										<option value='text'<?php if ($type == 'text') echo " selected='selected'"; ?>><?php _e('Text', PLUGIN_NAME); ?></option>
										<option value='select'<?php if ($type == 'select') echo " selected='selected'"; ?>><?php _e('Select', PLUGIN_NAME); ?></option>
										<option value='radio'<?php if ($type == 'radio') echo " selected='selected'"; ?>><?php _e('Radio', PLUGIN_NAME); ?></option>
										<option value='checkbox'<?php if ($type == 'checkbox') echo " selected='selected'"; ?>><?php _e('Checkbox', PLUGIN_NAME); ?></option>
									</select>
								</li>
								<li>
									<label class='field_required'><input class='checkbox_input' type='checkbox' name='edit_required' value='1'<?php if ($required) echo " checked='checked'"; ?> /><?php _e('Require', PLUGIN_NAME); ?></label>
									<label><input class='checkbox_input' type='checkbox' name='edit_search' value='1'<?php if ($search) echo " checked='checked'"; ?> /><?php _e('Search', PLUGIN_NAME); ?></label><br />
									<label class="field_range"><input class='checkbox_input' type='checkbox' name='edit_range' value='1'<?php if ($range) echo " checked='checked'"; ?> /><?php _e('Range', PLUGIN_NAME); ?></label>
								</li>
								<li><button class='btn btn-mini' type='submit'><?php _e('Save', PLUGIN_NAME); ?></button></li>
							</form>
						</ul>
					</li>
<?php } ?>
				</ul>
			</div>
	</div>
	<div class='config_column'>
		<div>
			<h2 class='render-title sub_heading'><?php _e('Order Attributes', PLUGIN_NAME); ?></h2>
			<form method='post' action='<?php echo osc_admin_base_url(true);?>'>
				<input type='hidden' name='page' value='plugins' />
				<input type='hidden' name='action' value='renderplugin' />
				<input type='hidden' name='file' value='<?php echo osc_plugin_folder(__FILE__); ?>conf.php' />
				<input type='hidden' name='plugin_action' value='order_attributes' />	
				<input type='hidden' name='group_id' value='<?php echo $group_id; ?>' />	
<?php 
if (empty($group_id)) {
	$order_type = osc_get_preference('order_type', PLUGIN_NAME); 
} else {
	$order_type = Attributes::newInstance()->getGroupOrderType($group_id);
}
if ($order_type == 'custom') {
	$alpha_checked = '';
	$custom_checked = " checked='checked'";
} else {
	$alpha_checked = " checked='checked'";
	$custom_checked = '';
}
?>
				<label><input id='alpha_order' type='radio' name='order_type' value='alpha'<?php echo $alpha_checked; ?> /><?php _e('Alphabetical', PLUGIN_NAME); ?></label>
				<label class='radio_label'><input id='custom_order' type='radio' name='order_type' value='custom'<?php echo $custom_checked; ?> /><?php _e('Custom', PLUGIN_NAME); ?></label>
				<div class='list-categories custom_order'>
					<ul class='sortable ui-sortable'>
<?php	
$counter = 0;
$custom_fields = Attributes::newInstance()->getFields($group_id, 'custom');
foreach ($custom_fields as $field) { 
	$field_id = $field['pk_i_id'];
	$label = trim($field['s_label']);
	$counter++;
?>
						<li class='sortable_item'>
							<div class='category_div enabled'>
								<div class='category_row'>					
									<div class='handle ico ico-32 ico-droppable'></div>
									<div class='name-cat'><?php echo $label; ?></div>
									<input type='hidden' name='field_<?php echo $field_id; ?>' value='<?php echo $counter; ?>' />
								</div>
							</div>
						</li>
<?php } ?>		
					</ul>
				</div>
				<p><button class='btn btn-mini' type='submit'><?php _e('Save', PLUGIN_NAME); ?></button></p>
			</form>
		</div>	
	</div>		
<?php } ?>
<!-- end custom_attributes --></div>
<?php //END
