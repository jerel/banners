<section class="title">
	<h4><?php echo lang('banners:'.$this->method); ?></h4>
</section>

<section class="item">

	<?php echo form_open_multipart($this->uri->uri_string(), 'class="form_inputs"'); ?>
		<ul>
			<li class="<?php echo alternator('', 'even'); ?>">
				<label for="name"><?php echo lang('banners:name'); ?></label>
				<div class="input"><?php echo form_input('name', set_value('name', $banner->name)); ?>
									<span class="required-icon tooltip">Required</span>
				</div>
			</li>

			<li class="<?php echo alternator('', 'even'); ?>">
				<label for="slug"><?php echo lang('banners:slug'); ?></label>
				<div class="input"><?php echo form_input('slug', set_value('slug', $banner->slug)); ?>
									<span class="required-icon tooltip">Required</span>
				</div>
			</li>
			
			<li class="<?php echo alternator('', 'even'); ?>">
				<label for="pages"><?php echo lang('banners:pages'); ?></label>
				<div class="input"><?php echo form_multiselect('pages[]', array(0 => lang('banners:no_pages')) + $banner->all_pages, set_value('pages[]', $banner->pages)); ?></div>
			</li>

			<li class="<?php echo alternator('', 'even'); ?>">
				<label></label>
				<div class="input">
					<span class="show_options"><?php echo lang('banners:show'); ?></span>
					<span style="display:none" class="hide_options"><?php echo lang('banners:hide'); ?></span>
				</div>
			</li>

			<li class="advanced <?php echo alternator('', 'even'); ?>">
				<label for="urls"><?php echo lang('banners:urls'); ?></label>
				<div class="input"><?php echo form_textarea('urls', set_value('urls', $banner->urls)); ?></div>
			</li>

			<li class="<?php echo alternator('', 'even'); ?>">
				<label for="text"><?php echo lang('banners:text'); ?></label>
				<div class="input"><?php echo form_textarea('text', set_value('text', $banner->text), 'class="wysiwyg-simple"'); ?></div>
			</li>
		</ul>
		
		<div class="buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
		</div>
		
	<?php echo form_close(); ?>

</section>