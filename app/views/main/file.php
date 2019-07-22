<div class="content-wrapper">
	<div class="container-fluid">
		<div class="card mb-3">
			<div class="card-header"><?php echo $title ?></div>
			<div class="card-body">
				<div class="row">
					<div class="col-sm-12 data">
					<?php if (!empty($file) && file_exists($file)): ?>
						<?php if (in_array($format, $text)): ?>
							<form action="/file/save" method="post" id="save">
								<input type="hidden" name="path" value="<?php echo $file; ?>">
								<textarea style="width: 100%; height: 600px;" name="content"><?php echo file_get_contents($file); ?></textarea>
								<button style="width: 100px;" type="submit" class="btn btn-primary btn-block" onclick="edit_save()">Сохранить</button>
							</form>
						<?php elseif(in_array($format, $image)): ?>
							<img src="file://<?php echo $file; ?>" alt="">
						<?php else: ?>
							<p>Файловый менеджер не поддерживает данный формат файла.</p>
						<?php endif ?>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>
</div>
</div>