<?php
    // デフォルトのレイアウトを無効
    $this->layout = false;
?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<meta http-equiv="Content-Script-Type" content="text/javascript">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>4231.ch</title>
		<link rel="icon" type="image/x-icon" href="./Data/img/favicon.ico">
		<!-- css 読み込み -->
		<?php echo $this->Html->css('style'); ?>
		<?php echo $this->Html->css('base'); ?>
		<?php echo $this->Html->css('index'); ?>
		<style type="text/css">
            #container {
                min-width: 310px;
                max-width: 800px;
                height: 400px;
                margin: 0 auto
            }
		</style>
		<link href="https://fonts.googleapis.com/earlyaccess/notosansjapanese.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
		<!-- javascript 読み込み -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<!-- script src="http://code.highcharts.com/highcharts.js"></script -->
		<?php echo $this->Html->script('highcharts'); ?>
		<?php echo $this->Html->script('highcharts-option-winning-point'); ?>
		<?php echo $this->Html->script('match-schedule'); ?>
	</head>
	<body>
		<div id="header">
			<div id="header_logo">
				<a href="./" title="4231.ch">
    				<div id="logo_wrapper">
    					<!-- img id="header-img1" src="<?php echo $this->Url->build("/img/logo-icon-img.png"); ?>" alt="4231.chロゴ" width="34px" height="31px" -->
    					<span id="logo_name">4231.ch</span>
    				</div>
				</a>
			</div>
			<nav>
				<div id="navigation_bar">
					<ul id="nav">
						<a href="./"><li class="nav_left">J1リーグ 日程・結果</li></a>
						<a href="<?php echo $this->Url->build(['controller'=>'MatchResultsGraph', 'action'=>'index']); ?>"><li>J1リーグ 順位</li></a>
					</ul>
				</div>
			</nav>
		</div>