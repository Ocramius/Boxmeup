<table>
<?php
	$count = 0;
	$row_count = 0;
	foreach($this->data['Container']['slug'] as $slug => $checked) {
		if((boolean) $checked) {
			if($row_count % 4 == 0)
				echo $count > 0 && $count % 12 == 0 ? '</table><p style="page-break-before: always">&nbsp;</p><table><tr>' : '<tr>';
?>
			<td style="text-align: center;">
				<h4><?php echo $containers[$count]['Container']['name']; ?></h4>
				<?php echo $html->image('http://chart.apis.google.com/chart?cht=qr&chs=200x200&chl='.$Fullwebroot.'containers/view/'.$slug); ?>
			</td>
<?php
			$row_count++;
			$count++;
			if($row_count % 4 == 0)
				echo '</tr>';
		}
	}
?>
</table>