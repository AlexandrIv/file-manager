<div class="content-wrapper">
	<div class="container-fluid">
		<div class="card mb-3">
			<div class="card-header"><?php echo $title; ?></div>
			<input type="hidden" name="current" class="current_path" value="<?php echo ($current_path) ? $current_path : ''; ?>">
			<div class="card-body">
				<div class="row">
					<div class="col-sm-12 data">
						<table class="table">
							<thead>
								<tr>
									<th>Название</th>
									<th>Размер</th>
									<th>Тип</th>
									<th>Дата изменения</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($data as $key => $value): ?>
									<tr>
										<td><?php echo $value['name']; ?></td>
										<td><?php echo $value['size']; ?></td>
										<td><?php echo $value['format']; ?></td>
										<td><?php echo $value['time']; ?></td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>