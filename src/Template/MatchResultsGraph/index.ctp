<?php
    // デフォルトのレイアウトを無効
    $this->layout = false;
    // headerタグからbodyタグid="header"を呼び出し
    echo $this->element('default_head');
?>
		<div id="main">
			<div class="paging_list">
				<p>TOP > Jリーグ 順位</p>
			</div>
			<div class="title">
				<h1>J1リーグ 2018シーズン | 順位</h1>
			</div>
			<!-- div id="container"></div -->
			<div id="container-winning-point"></div>

			<div class="table_title">
				<h2>J1リーグ 2018シーズン | 順位表</h2>
			</div>
			<div class="table_caution">
				<p>※第<?php echo $target_match_num; ?>節終了時点</p>
			</div>
			<table>
				<tr>
					<th>順位</th><th>クラブ</th><th>試合数</th><th>ゴール数</th><th>失点数</th><th>得失点差</th><th>勝ち点</th>
				</tr>
<?php
    // 順位設定変数初期化
	$rank = 1;
	foreach ($table_data as $data) {
?>
				<tr>
					<td><?php echo $rank; // 順位 ?></td>
					<td><?php echo $data->TeamName; // クラブ名 ?></td>
					<td><?php echo $data->$table_data_key_name_played; // 総試合数 ?></td>
					<td><?php echo $data->$table_data_key_name_totalgoalscore; // 総ゴール数 ?></td>
					<td><?php echo $data->$table_data_key_name_totallostgoalscore; // 総失点数 ?></td>
					<td><?php echo $data->$table_data_key_name_goaldifference; // 得失点差 ?></td>
					<td><?php echo $data->$table_data_key_name_resultsumpoint; // 勝ち点 ?></td>
				</tr>
<?php
        // 順位を昇順に表示するため+1
        $rank = $rank + 1;
	}
?>
			</table>
		</div>
<?php
    // footerを呼び出し
    echo $this->element('default_footer');
?>

