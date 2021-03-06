<?php
    use PHP_CodeSniffer\Tokenizers\PHP;

    // デフォルトのレイアウトを無効
    $this->layout = false;
    // headerタグからbodyタグid="header"を呼び出し
    echo $this->element('default_head');
?>
		<div id="main">
			<div class="paging_list">
				<p>TOP > Jリーグ 日程・結果</p>
			</div>
			<div class="title">
				<h1>J1リーグ <?php echo $this->MatchSchedule->getSeasonYear($this->request->getQuery('season')); ?>シーズン | 日程・結果</h1>
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
<?php if (!empty($target_anker_id)) { ?>
			<div class="match_day">
				<h5><span>Next Match</span></h5>
			</div>
			<div id="target_anker_next_match">
				<a class="jumpbutton" href="#<?= $target_anker_id ?>">
					<span>第<?= $target_anker_id ?>節<br>
<?php   foreach ($match_schedule_nextmatchday as $nextmatchday) { ?>
						<?= $nextmatchday['MatchDay']->i18nFormat('YYYY/MM/dd').'　'; ?>
<?php   } ?>
            		</span>
				</a>
			</div>
<?php } ?>
			<div id="match_schedule_data">
				<?= $this->Flash->render() ?>

<?php
    // 節数表示用変数初期化
    $match_num = 0;
    // 試合日時表示用変数初期化
    $match_day_time = null;
    foreach ($match_schedule_data as $data) {
        // 節数表示
        if ($match_num != $data->MatchNum) {
            // 節数が異なる場合、節数表示
            $match_num = $data->MatchNum;
?>
				<div class="matchday_num">
					<h4 id="<?= $data->AnkerId ?>"><a href="#"><span>J1リーグ 第<?= $match_num ?>/34節</span></a></h4>
				</div>
<?php
        }

        // 試合日表示
        if (!empty($data->MatchDayTime) && date('Y/m/d', strtotime($match_day_time)) != date('Y/m/d', strtotime($data->MatchDayTime))) {
            $match_day_time = date('Y/m/d', strtotime($data->MatchDayTime));
?>
				<div class="match_day">
					<h5><span><time><?= $match_day_time ?></time></span></h5>
				</div>
<?php   } ?>
				<table class="match_schedule_table">
					<tbody>
						<tr class="table_tr_set1">
							<td class="td_set1" rowspan="2"><!-- HomeTeam名表示 start -->
<?php   if (!empty($data->HomeTeamFullName)) { ?>
								<span><?= $data->HomeTeamFullName ?></span>
<?php   } else { ?>
								<span><?= $data->HomeTeam ?></span>
<?php   } ?>
							</td>
							<td class="td_set2">
								<span><?= $data->HomeGetPoint ?></span>
							</td>
    						<td class="td_set5">
    							<ul>
<?php   if (empty($data->MatchDayTime)) { ?>
            						<li id="time_and_date_undecided">日時未定</li>
<?php } else { ?>
									<li><time><?= date('H:i', strtotime($data->MatchDayTime)) ?></time></li>
<?php } ?>
								</ul>
								<ul>
    								<li id=match_state><?= $data->MatchState; ?></li>
								</ul>
    						</td>
    						<td class="td_set6">
								<span><?= $data->AwayGetPoint ?></span>
							</td>
    						<td class="td_set3" rowspan="2"><!-- AwayTeam名表示 start -->
<?php if (!empty($data->AwayTeamFullName)) { ?>
								<span><?= $data->AwayTeamFullName ?></span>
<?php } else { ?>
								<span><?= $data->AwayTeam ?></span>
<?php } ?>
    						</td>
						</tr>
						<tr class="table_tr_set2">
							<td class="td_set7" colspan="3">
								<img class="stadium_icon" src="<?php echo $this->Url->build("/img/icn-stadium.png"); ?>" width="11" height="13.5" alt="サッカースタジアムアイコン">
    							<span><?= $data->StadiumName ?></span>
    						</td>
						</tr>
					</tbody>
				</table>
<?php } ?>
			</div>
		</div>
<?php
    // headerタグからbodyタグid="header"を呼び出し
    echo $this->element('default_footer');
?>
