<?php
use yii\helpers\Html;
?>

<?php if (empty($links) === false): ?>
	<table class="table table-bordered">
		<thead>
			<th>Domain</th>
			<th><?= $sort->link('domain.creation_date') ?></th>
			<th><?= $sort->link('countDomain') ?></th>
		</thead>
		<tbody>
			<?php foreach ($links as $link): ?>
				<tr>
					<td>
						<?= $link->domain->title ?>
					</td>
					<td>
						<?php if (empty($link->domain->creation_date) === false): ?>
							<?= date("Y-m-d",$link->domain->creation_date) ?>	
						<?php endif ?>
						
					</td>
					<td>
						<?= $link->countDomain ?>
					</td>

				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
<?php endif ?>