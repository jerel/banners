<li>
	<?php echo Asset::img('module::delete.png', 'alt="delete image"', array('class' => 'delete-icon')); ?>
        <?php echo Asset::img('module::update.gif', 'alt="update image"', array('class' => 'loading-gif')); ?>
	<img src="<?php echo site_url('files/thumb/'.$image->id.'/200/150/fill'); ?>" alt="<?php echo $image->description; ?>"/><br />
	<?php echo form_textarea('description', set_value('description', $image->description), 'class="prompt-text" title="' . lang('banners:description') . '"style="width:185px; height:100px; min-height: 0;"'); ?>
	<?php echo form_hidden('action_to[]', $image->id) . form_close(); ?>
</li>