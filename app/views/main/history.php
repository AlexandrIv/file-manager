<div class="content-wrapper">
	<div class="container-fluid">
		<div class="card mb-3">
			<div class="card-header"><?php echo $title ?></div>
			<div class="card-body">
				<div class="row">
					<div class="col-sm-12">
						<?php if (!empty($data)): ?>
						<table class="table">
							<thead>
								<tr>
									<th scope="col">#</th>
									<th scope="col">Событие</th>
									<th scope="col">Описание</th>
									<th scope="col">Тип</th>
									<th scope="col">Дата</th>
									<th scope="col">Путь</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($data as $key => $val): ?>
									<tr>
										<th scope="row"><?php echo $key+1; ?></th>
										<td><?php echo $val['event']; ?></td>
										<td><?php echo $val['value']['text']; ?></td>
										<td><?php echo $val['type']; ?></td>
										<td><?php echo $val['dates']; ?></td>
										<td><?php echo $val['value']['path']; ?></td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
						<?php echo $pagination; ?>
						<?php else: ?>
							<h3>История пуста</h3>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>