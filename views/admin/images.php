<section class="title">
	<h4><?php echo sprintf(lang('banners:editing'), $banner->name); ?></h4>
</section>

<section class="item">
	<?php echo form_open(uri_string()); ?>
	
		<ul id="banners-images">
			<li id="uploader-item" class="<?php echo alternator('', 'even'); ?>">
				<h6><?php echo lang('banners:images_upload'); ?></h6>
				<div id="file-uploader">
					<div class="uploader-browser">
						<?php echo form_open_multipart('banners/ajax/upload/' . $banner->id); ?>
							<label for="userfile" class="upload"><?php echo lang('banners:click_to_select'); ?></label>
							<?php echo form_upload('userfile', NULL, 'class="no-uniform" multiple="multiple"'); ?>
						<?php echo form_close(); ?>
						<ul id="uploader-queue" class="ui-corner-all"></ul>
					</div>
				</div>
			</li>
			<li class="<?php echo alternator('', 'even'); ?> clear-both">
			<?php echo image('delete-all.png', 'banners', array('class' => 'delete-all-icon', 'title' => lang('banners:delete_all_images'), 'alt' => 'delete all images')); ?>
			<br style="clear:both"/>
			<h6><?php echo lang('inv.images_list'); ?></h6>
			<?php echo form_open() . form_hidden('banner_id', $banner->id) ?>
				<?php if ($banner->images): ?>
					<ul id="images_list">
						<?php foreach ( $banner->images as $image ): ?>
						<li>
							<?php echo image('delete.png', 'banners', array('class' => 'delete-icon', 'title' => lang('banners:delete_image'), 'alt' => 'delete image')); ?>
							<?php echo image('update.gif', 'banners', array('class' => 'loading-gif', 'alt' => 'updating')); ?>
							<img src="<?php echo site_url('files/thumb/'.$image->id.'/200/150/fill'); ?>" alt="<?php echo $image->description; ?>"/><br />
							<?php echo form_textarea('description', set_value('description', $image->description), 'class="prompt-text" title="' . lang('banners:description') . '"style="width:185px; height:100px; min-height: 0;"'); ?>
							<?php echo form_hidden('action_to[]', $image->id) . form_close(); ?>
						</li>
						<?php endforeach; ?>
					</ul>
				<?php else: ?>
					<div class="no_data">
						<?php echo lang('banners:no_images'); ?>
					</div>
				<?php endif; ?>
				<div class="clear-both"></div>
			</li>
		</ul>
</section>