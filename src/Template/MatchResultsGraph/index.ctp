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
				<h1>J1リーグ  <?php echo $this->MatchSchedule->getSeasonYear($this->request->getQuery('season')); ?>シーズン | 順位</h1>
			</div>
			<div class="filter">
				<h5><span>Filter</span></h5>
			</div>
			<div class="match_day">
<?php
            // ドロップダウンの表示
            echo $this->Form->input(
                "Season", [
                    "type" => "select",
                    "options" => [
                        [
                            "value" => $season_filter[2018]['value'],
                            "text" => $season_filter[2018]['text'],
                            "selected" => $season_filter[2018]['selected']],
                        [
                            "value" => $season_filter[2017]['value'],
                            "text" => $season_filter[2017]['text'],
                            "selected" => $season_filter[2017]['selected']],
                        [
                            "value" => $season_filter[2016]['value'],
                            "text" => $season_filter[2016]['text'],
                            "selected" => $season_filter[2016]['selected']],
                        [
                            "value" => $season_filter[2015]['value'],
                            "text" => $season_filter[2015]['text'],
                            "selected" => $season_filter[2015]['selected']],
                        [
                            "value" => $season_filter[2014]['value'],
                            "text" => $season_filter[2014]['text'],
							"selected" => $season_filter[2014]['selected']],
						[
							"value" => $season_filter[2013]['value'],
							"text" => $season_filter[2013]['text'],
							"selected" => $season_filter[2013]['selected']],
						[
							"value" => $season_filter[2012]['value'],
							"text" => $season_filter[2012]['text'],
							"selected" => $season_filter[2012]['selected']],
						[
							"value" => $season_filter[2011]['value'],
							"text" => $season_filter[2011]['text'],
							"selected" => $season_filter[2011]['selected']],
						[
							"value" => $season_filter[2010]['value'],
							"text" => $season_filter[2010]['text'],
							"selected" => $season_filter[2010]['selected']],
						[
							"value" => $season_filter[2009]['value'],
							"text" => $season_filter[2009]['text'],
							"selected" => $season_filter[2009]['selected']],
						[
							"value" => $season_filter[2008]['value'],
							"text" => $season_filter[2008]['text'],
							"selected" => $season_filter[2008]['selected']],
						[
							"value" => $season_filter[2007]['value'],
							"text" => $season_filter[2007]['text'],
							"selected" => $season_filter[2007]['selected']],
						[
							"value" => $season_filter[2006]['value'],
							"text" => $season_filter[2006]['text'],
							"selected" => $season_filter[2006]['selected']],
						[
							"value" => $season_filter[2005]['value'],
							"text" => $season_filter[2005]['text'],
							"selected" => $season_filter[2005]['selected']],
                    ],
            ]);
?>
			</div>
			<div class="title-graph">
<?php if ($this->MatchSchedule->getSeasonYear($this->request->getQuery('season')) == 2016) { ?>
					<h2>J1リーグ  <?php echo $this->MatchSchedule->getSeasonYear($this->request->getQuery('season')); ?>シーズン | 年間勝点順位グラフ</h1>
<?php } else { ?>
					<h2>J1リーグ  <?php echo $this->MatchSchedule->getSeasonYear($this->request->getQuery('season')); ?>シーズン | 順位グラフ</h1>
<?php } ?>
			</div>
			<div id="container-winning-point"></div>

			<div class="title-table">

<?php if ($this->MatchSchedule->getSeasonYear($this->request->getQuery('season')) == 2016) { ?>
					<h2>J1リーグ <?php echo $this->MatchSchedule->getSeasonYear($this->request->getQuery('season')); ?>シーズン | 年間勝点順位表</h2>
<?php } else { ?>
					<h2>J1リーグ <?php echo $this->MatchSchedule->getSeasonYear($this->request->getQuery('season')); ?>シーズン | 順位表</h2>
<?php } ?>
			</div>
			<div class="title-caution">
				<p>※第<?php echo $target_match_num; ?>節終了時点</p>
			</div>
			<table class="rank-table">
				<tr>
					<th>順位</th><th>クラブ</th><th>勝ち点</th><th>試合数</th><th>ゴール数</th><th>失点数</th><th>得失点差</th>
				</tr>
<?php
    // 順位設定変数初期化
	$rank = 1;
	foreach ($table_data as $data) {
	    if ($rank == 1) {
?>
				<tr class="rank-afc">
<?php } else if ($rank == 2 || $rank == 3) { ?>
				<tr class="rank-afc-playoff">
<?php } else if ($rank == 16) { ?>
				<tr class="rank-j2playoffrelegation">
<?php } else if ($rank == 17 || $rank == 18) { ?>
				<tr class="rank-j2relegation">
<?php } else { ?>
				<tr>
<?php } ?>
					<td><?php echo $rank; // 順位 ?></td>
					<td><?php echo $data->TeamName; // クラブ名 ?></td>
					<td><?php echo $data->$table_data_key_name_resultsumpoint; // 勝ち点 ?></td>
					<td><?php echo $data->$table_data_key_name_played; // 総試合数 ?></td>
					<td><?php echo $data->$table_data_key_name_totalgoalscore; // 総ゴール数 ?></td>
					<td><?php echo $data->$table_data_key_name_totallostgoalscore; // 総失点数 ?></td>
					<td><?php echo $data->$table_data_key_name_goaldifference; // 得失点差 ?></td>
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

