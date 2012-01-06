<section class="title">
	<h4><?php echo lang('banners:banners'); ?></h4>
</section>

<section class="item">
	<?php echo form_open('admin/banners/delete');?>
	
	<?php if (!empty($banners)): ?>
	
		<table border="0" class="table-list">
			<thead>
				<tr>
					<th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
					<th><?php echo lang('banners:name'); ?></th>
					<th><?php echo lang('banners:text'); ?></th>
					<th><?php echo lang('banners:images'); ?></th>
					<th><?php echo lang('banners:manage'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="5">
						<div class="inner"><?php echo $pagination['links']; ?></div>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach($banners as $banner): ?>
				<tr>
					<td><?php echo form_checkbox('action_to[]', $banner->id); ?></td>
					<td><?php echo $banner->name; ?></td>
					<td><?php echo ($banner->text > '' ? substr(strip_tags($banner->text), 0, 40).'...' : ''); ?></td>
					<td><?php echo $banner->image_count; ?></td>
					<td>
						<?php echo
						anchor('admin/banners/images/'		. $banner->id, 	lang('banners:images')) 					. ' | ' .
						anchor('admin/banners/edit/'		. $banner->id, 	lang('global:edit')) 					. ' | ' .
						anchor('admin/banners/delete/' 	. $banner->id, 	lang('global:delete'), array('class'=>'confirm')); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
		</div>
		
	<?php else: ?>
		<div class="no_data">			
			<?php echo lang('banners:no_banners'); ?>
		</div>
	<?php endif;?>
	
	<?php echo form_close(); ?>
</section>