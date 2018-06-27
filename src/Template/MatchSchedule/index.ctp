<?php
    // デフォルトのレイアウトを無効
    $this->layout = false;
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html charset=utf-8">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<meta http-equiv="Content-Script-Type" content="text/javascript">
		<title>青い蹴球</title>
		<link rel="icon" type="image/x-icon" href="./Data/img/favicon.ico">
		<!-- css 読み込み -->
		<?php echo $this->Html->css('style'); ?>
		<?php echo $this->Html->css('base'); ?>
		<?php echo $this->Html->css('index'); ?>
		<link rel="stylesheet" type="text/css" href="./Web/css/index.css">
		<!-- javascript 読み込み -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<!-- script src="https://unpkg.com/infinite-scroll@3/dist/infinite-scroll.pkgd.min.js"></script -->
		<!-- script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script -->
		<!-- script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script -->
		<!-- <?php echo $this->Html->script('scroll', array('inline' => false)); ?> -->
	</head>
	<body>
		<div id="header">
			<div id="header_logo">
				<a href="./" title="青い蹴球">
				<div id="logo_wrapper">
					<img id="header-img1" src="<?php echo $this->Url->build("/img/logo-icon-img.png"); ?>" alt="青い蹴球 サイトロゴ" width="49px" height="45px">
					<span id="logo_name">青い蹴球</span>
				</div>
				</a>
			</div>
		</div>
		<div id="main">
			<div class="title">
				<h3>Jリーグ 日程・結果</h3>
			</div>
			<div id="target_anker_next_match">
				<a href="#<?= $target_anker_id ?>">
					<p>次回は第<?= $target_anker_id ?>節</p>
				</a>
			</div>
			<div id="match_schedule_data">
				<?= $this->Flash->render() ?>
<?php
    // 節数表示用変数初期化
    $match_num = 0;
    // 試合日時表示用変数初期化
    $match_day_time = null;
    foreach ($match_schedule_data as $data):
        // 節数表示
        if ($match_num != $data->MatchNum) {
            // 節数が異なる場合、節数表示
            $match_num = $data->MatchNum;
?>
				<h4 id="<?= $data->AnkerId ?>" class="matchday_num"><a href="#"><span>Matchday <?= $match_num ?></span></a></h4>
<?php
        }

        // 試合日表示
        if (!empty($data->MatchDayTime) && date('Y/m/d', strtotime($match_day_time)) != date('Y/m/d', strtotime($data->MatchDayTime))) {
            $match_day_time = date('Y/m/d', strtotime($data->MatchDayTime));
?>
				<h5><?= $match_day_time ?></h5>
<?php   } ?>
				<table class="match_schedule_table">
					<tbody>
						<tr class="table_tr_set1">
							<td class="td_set1"><!-- HomeTeam名表示 start -->
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
									<li><?= date('H:i', strtotime($data->MatchDayTime)) ?></li>
<?php } ?>
								</ul>
								<ul>
    								<li id=match_state><?= $data->MatchState; ?></li>
								</ul>
    						</td>
    						<td class="td_set6">
								<span><?= $data->AwayGetPoint ?></span>
							</td>
    						<td class="td_set3"><!-- AwayTeam名表示 start -->
<?php if (!empty($data->AwayTeamFullName)) { ?>
								<span><?= $data->AwayTeamFullName ?></span>
<?php } else { ?>
								<span><?= $data->AwayTeam ?></span>
<?php } ?>
    						</td>
    						<td class="td_set4">
    							<span><?= $data->StadiumName ?></span>
    						</td>
						</tr>
					</tbody>
				</table>
<?php
    endforeach;
?>
			</div>
		</div>
<!--
		<div class="page-load-status">
			<p class="infinite-scroll-request">
				<i class="fa fa-spinner fa-spin"></i>Loading...
			</p>
			<p class="infinite-scroll-last">End of content</p>
			<p class="infinite-scroll-error">No more pages to load</p>
		</div>
		<div class="navigation">
			<?php echo $this->Paginator->next('次のページ'); ?>
			<?php // echo $this->Paginator->numbers(); ?>
		</div>
		<div hidden id="total_number_of_pages">
			<?= $this->Paginator->params()['pageCount']; ?>
		</div>
-->
	</body>
</html>
