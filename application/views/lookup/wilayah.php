<select name="<?php echo $levelName; ?>" class="form-control select2">
	<option value="">-- <?php echo $levelTitle; ?> --</option>
	<?php foreach($result as $row):?>
	<option value="<?php echo $row['kode_wilayah'];?>" 
		<?php if($value == $row['kode_wilayah']) { ?>selected<?php } ?>
	>
		<?php echo $row['nama'];?>
	</option>
	<?php endforeach;?>
</select>