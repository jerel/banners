<li>
	<?php echo image('delete.png', 'banners', array('class' => 'delete-icon', 'title' => lang('banners:delete_image'), 'alt' => 'delete image')); ?>
	<?php echo image('update.gif', 'banners', array('class' => 'loading-gif', 'alt' => 'updating')); ?>
	<img src="<?php echo site_url('files/thumb/'.$image->id.'/200/150/fill'); ?>" alt="<?php echo $image->description; ?>"/><br />
	<?php echo form_textarea('description', set_value('description', $image->description), 'class="prompt-text" title="' . lang('banners:description') . '"style="width:185px; height:100px; min-height: 0;"'); ?>
	<?php echo form_hidden('action_to[]', $image->id) . form_close(); ?>
</li>