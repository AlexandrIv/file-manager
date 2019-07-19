<div class="content-wrapper">
	<div class="container-fluid">
		<div class="card mb-3">
			<div class="card-header"><?php echo $title; ?></div>
			<div class="card-body">
				<div class="row">
					<div class="col-sm-12 data">
						<table class="table">
							<thead>
								<tr>
									<th>*</th>
									<th>Название</th>
									<th>Размер</th>
									<th>Тип</th>
									<th>Дата изменения</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($data as $key => $value): ?>
									<tr>
										<td><?php if ($key != 0): ?><input type="checkbox" class="copy-input" data-link="<?php echo $value['path_file']; ?>"><?php endif ?></td>
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