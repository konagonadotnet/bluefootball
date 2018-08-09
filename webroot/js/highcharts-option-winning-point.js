$(function(){
	$.getJSON(
		"./MatchResultsGraph",
		function(json){
			// tooltip表示内容設定
			var formatter = function() {
				var tooltipMessage = this.series.name + '<br>第' + this.x + '節：' + this.y +'位';
		        return tooltipMessage;
		    }

			Highcharts.chart('container-winning-point', {
				// スクロールバーの表示設定
				// scrollbar: true,
				// ナビゲート表示設定
				// navigator: true,

				chart: {
					renderTo: 'container-winning-point',
					// Zoom機能:x軸に対してZoom
					zoomType: 'x',
				},
				// グラフタイトル名設定
				title: {
					text: '2018 J1リーグ 順位推移グラフ'
				},

				// サブタイトル名設定
				/*
				subtitle: {
					text: '順位推移グラフ'
				},
				*/

				// x軸設定
				xAxis: {
					// x軸の小数点以下の表示設定
					allowDecimals: false, // falseで小数点以下は非表示
					// x軸名
					title: {
						text: '節数'
					},
					// x軸最大表示数
					max: json.results_data.NextMatchNum
				},
				// y軸設定
				yAxis: {
					// y軸の小数点以下の表示設定
					allowDecimals: false, // falseで小数点以下は非表示
					// y軸名
					title: {
						text: '順位'
					},
					// 軸の最大値、最小値を逆に設定
					reversed: true,
				},
				legend: {
					layout: 'vertical',
					align: 'right',
					verticalAlign: 'middle'
				},

				// 点の設定
				plotOptions: {
					series: {
						label: {
							connectorAllowed: false
						},
						// x軸開始点設定
						pointStart: 1 // 第一節として1を設定
					}
				},

				// クレジットリンクの表示/非表示の設定
				credits: {
					enabled: false,
				},

				series: [
					{
						// グラフのライン名(クラブの略称)
						name: json.results_data[0].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[0].Matchday1Rank, json.results_data[0].Matchday2Rank, json.results_data[0].Matchday3Rank, json.results_data[0].Matchday4Rank, json.results_data[0].Matchday5Rank,
							json.results_data[0].Matchday6Rank, json.results_data[0].Matchday7Rank, json.results_data[0].Matchday8Rank, json.results_data[0].Matchday9Rank, json.results_data[0].Matchday10Rank,
							json.results_data[0].Matchday11Rank, json.results_data[0].Matchday12Rank, json.results_data[0].Matchday13Rank, json.results_data[0].Matchday14Rank, json.results_data[0].Matchday15Rank,
							json.results_data[0].Matchday16Rank, json.results_data[0].Matchday17Rank, json.results_data[0].Matchday18Rank, json.results_data[0].Matchday19Rank, json.results_data[0].Matchday20Rank,
							json.results_data[0].Matchday21Rank, json.results_data[0].Matchday22Rank, json.results_data[0].Matchday23Rank, json.results_data[0].Matchday24Rank, json.results_data[0].Matchday25Rank,
							json.results_data[0].Matchday26Rank, json.results_data[0].Matchday27Rank, json.results_data[0].Matchday28Rank, json.results_data[0].Matchday29Rank, json.results_data[0].Matchday30Rank,
							json.results_data[0].Matchday31Rank, json.results_data[0].Matchday32Rank, json.results_data[0].Matchday33Rank, json.results_data[0].Matchday34Rank
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[1].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[1].Matchday1Rank, json.results_data[1].Matchday2Rank, json.results_data[1].Matchday3Rank, json.results_data[1].Matchday4Rank, json.results_data[1].Matchday5Rank,
							json.results_data[1].Matchday6Rank, json.results_data[1].Matchday7Rank, json.results_data[1].Matchday8Rank, json.results_data[1].Matchday9Rank, json.results_data[1].Matchday10Rank,
							json.results_data[1].Matchday11Rank, json.results_data[1].Matchday12Rank, json.results_data[1].Matchday13Rank, json.results_data[1].Matchday14Rank, json.results_data[1].Matchday15Rank,
							json.results_data[1].Matchday16Rank, json.results_data[1].Matchday17Rank, json.results_data[1].Matchday18Rank, json.results_data[1].Matchday19Rank, json.results_data[1].Matchday20Rank,
							json.results_data[1].Matchday21Rank, json.results_data[1].Matchday22Rank, json.results_data[1].Matchday23Rank, json.results_data[1].Matchday24Rank, json.results_data[1].Matchday25Rank,
							json.results_data[1].Matchday26Rank, json.results_data[1].Matchday27Rank, json.results_data[1].Matchday28Rank, json.results_data[1].Matchday29Rank, json.results_data[1].Matchday30Rank,
							json.results_data[1].Matchday31Rank, json.results_data[1].Matchday32Rank, json.results_data[1].Matchday33Rank, json.results_data[1].Matchday34Rank
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[2].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[2].Matchday1Rank, json.results_data[2].Matchday2Rank, json.results_data[2].Matchday3Rank, json.results_data[2].Matchday4Rank, json.results_data[2].Matchday5Rank,
							json.results_data[2].Matchday6Rank, json.results_data[2].Matchday7Rank, json.results_data[2].Matchday8Rank, json.results_data[2].Matchday9Rank, json.results_data[2].Matchday10Rank,
							json.results_data[2].Matchday11Rank, json.results_data[2].Matchday12Rank, json.results_data[2].Matchday13Rank, json.results_data[2].Matchday14Rank, json.results_data[2].Matchday15Rank,
							json.results_data[2].Matchday16Rank, json.results_data[2].Matchday17Rank, json.results_data[2].Matchday18Rank, json.results_data[2].Matchday19Rank, json.results_data[2].Matchday20Rank,
							json.results_data[2].Matchday21Rank, json.results_data[2].Matchday22Rank, json.results_data[2].Matchday23Rank, json.results_data[2].Matchday24Rank, json.results_data[2].Matchday25Rank,
							json.results_data[2].Matchday26Rank, json.results_data[2].Matchday27Rank, json.results_data[2].Matchday28Rank, json.results_data[2].Matchday29Rank, json.results_data[2].Matchday30Rank,
							json.results_data[2].Matchday31Rank, json.results_data[2].Matchday32Rank, json.results_data[2].Matchday33Rank, json.results_data[2].Matchday34Rank
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[3].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[3].Matchday1Rank, json.results_data[3].Matchday2Rank, json.results_data[3].Matchday3Rank, json.results_data[3].Matchday4Rank, json.results_data[3].Matchday5Rank,
							json.results_data[3].Matchday6Rank, json.results_data[3].Matchday7Rank, json.results_data[3].Matchday8Rank, json.results_data[3].Matchday9Rank, json.results_data[3].Matchday10Rank,
							json.results_data[3].Matchday11Rank, json.results_data[3].Matchday12Rank, json.results_data[3].Matchday13Rank, json.results_data[3].Matchday14Rank, json.results_data[3].Matchday15Rank,
							json.results_data[3].Matchday16Rank, json.results_data[3].Matchday17Rank, json.results_data[3].Matchday18Rank, json.results_data[3].Matchday19Rank, json.results_data[3].Matchday20Rank,
							json.results_data[3].Matchday21Rank, json.results_data[3].Matchday22Rank, json.results_data[3].Matchday23Rank, json.results_data[3].Matchday24Rank, json.results_data[3].Matchday25Rank,
							json.results_data[3].Matchday26Rank, json.results_data[3].Matchday27Rank, json.results_data[3].Matchday28Rank, json.results_data[3].Matchday29Rank, json.results_data[3].Matchday30Rank,
							json.results_data[3].Matchday31Rank, json.results_data[3].Matchday32Rank, json.results_data[3].Matchday33Rank, json.results_data[3].Matchday34Rank
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[4].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[4].Matchday1Rank, json.results_data[4].Matchday2Rank, json.results_data[4].Matchday3Rank, json.results_data[4].Matchday4Rank, json.results_data[4].Matchday5Rank,
							json.results_data[4].Matchday6Rank, json.results_data[4].Matchday7Rank, json.results_data[4].Matchday8Rank, json.results_data[4].Matchday9Rank, json.results_data[4].Matchday10Rank,
							json.results_data[4].Matchday11Rank, json.results_data[4].Matchday12Rank, json.results_data[4].Matchday13Rank, json.results_data[4].Matchday14Rank, json.results_data[4].Matchday15Rank,
							json.results_data[4].Matchday16Rank, json.results_data[4].Matchday17Rank, json.results_data[4].Matchday18Rank, json.results_data[4].Matchday19Rank, json.results_data[4].Matchday20Rank,
							json.results_data[4].Matchday21Rank, json.results_data[4].Matchday22Rank, json.results_data[4].Matchday23Rank, json.results_data[4].Matchday24Rank, json.results_data[4].Matchday25Rank,
							json.results_data[4].Matchday26Rank, json.results_data[4].Matchday27Rank, json.results_data[4].Matchday28Rank, json.results_data[4].Matchday29Rank, json.results_data[4].Matchday30Rank,
							json.results_data[4].Matchday31Rank, json.results_data[4].Matchday32Rank, json.results_data[4].Matchday33Rank, json.results_data[4].Matchday34Rank
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[5].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[5].Matchday1Rank, json.results_data[5].Matchday2Rank, json.results_data[5].Matchday3Rank, json.results_data[5].Matchday4Rank, json.results_data[5].Matchday5Rank,
							json.results_data[5].Matchday6Rank, json.results_data[5].Matchday7Rank, json.results_data[5].Matchday8Rank, json.results_data[5].Matchday9Rank, json.results_data[5].Matchday10Rank,
							json.results_data[5].Matchday11Rank, json.results_data[5].Matchday12Rank, json.results_data[5].Matchday13Rank, json.results_data[5].Matchday14Rank, json.results_data[5].Matchday15Rank,
							json.results_data[5].Matchday16Rank, json.results_data[5].Matchday17Rank, json.results_data[5].Matchday18Rank, json.results_data[5].Matchday19Rank, json.results_data[5].Matchday20Rank,
							json.results_data[5].Matchday21Rank, json.results_data[5].Matchday22Rank, json.results_data[5].Matchday23Rank, json.results_data[5].Matchday24Rank, json.results_data[5].Matchday25Rank,
							json.results_data[5].Matchday26Rank, json.results_data[5].Matchday27Rank, json.results_data[5].Matchday28Rank, json.results_data[5].Matchday29Rank, json.results_data[5].Matchday30Rank,
							json.results_data[5].Matchday31Rank, json.results_data[5].Matchday32Rank, json.results_data[5].Matchday33Rank, json.results_data[5].Matchday34Rank
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[6].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[6].Matchday1Rank, json.results_data[6].Matchday2Rank, json.results_data[6].Matchday3Rank, json.results_data[6].Matchday4Rank, json.results_data[6].Matchday5Rank,
							json.results_data[6].Matchday6Rank, json.results_data[6].Matchday7Rank, json.results_data[6].Matchday8Rank, json.results_data[6].Matchday9Rank, json.results_data[6].Matchday10Rank,
							json.results_data[6].Matchday11Rank, json.results_data[6].Matchday12Rank, json.results_data[6].Matchday13Rank, json.results_data[6].Matchday14Rank, json.results_data[6].Matchday15Rank,
							json.results_data[6].Matchday16Rank, json.results_data[6].Matchday17Rank, json.results_data[6].Matchday18Rank, json.results_data[6].Matchday19Rank, json.results_data[6].Matchday20Rank,
							json.results_data[6].Matchday21Rank, json.results_data[6].Matchday22Rank, json.results_data[6].Matchday23Rank, json.results_data[6].Matchday24Rank, json.results_data[6].Matchday25Rank,
							json.results_data[6].Matchday26Rank, json.results_data[6].Matchday27Rank, json.results_data[6].Matchday28Rank, json.results_data[6].Matchday29Rank, json.results_data[6].Matchday30Rank,
							json.results_data[6].Matchday31Rank, json.results_data[6].Matchday32Rank, json.results_data[6].Matchday33Rank, json.results_data[6].Matchday34Rank
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[7].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[7].Matchday1Rank, json.results_data[7].Matchday2Rank, json.results_data[7].Matchday3Rank, json.results_data[7].Matchday4Rank, json.results_data[7].Matchday5Rank,
							json.results_data[7].Matchday6Rank, json.results_data[7].Matchday7Rank, json.results_data[7].Matchday8Rank, json.results_data[7].Matchday9Rank, json.results_data[7].Matchday10Rank,
							json.results_data[7].Matchday11Rank, json.results_data[7].Matchday12Rank, json.results_data[7].Matchday13Rank, json.results_data[7].Matchday14Rank, json.results_data[7].Matchday15Rank,
							json.results_data[7].Matchday16Rank, json.results_data[7].Matchday17Rank, json.results_data[7].Matchday18Rank, json.results_data[7].Matchday19Rank, json.results_data[7].Matchday20Rank,
							json.results_data[7].Matchday21Rank, json.results_data[7].Matchday22Rank, json.results_data[7].Matchday23Rank, json.results_data[7].Matchday24Rank, json.results_data[7].Matchday25Rank,
							json.results_data[7].Matchday26Rank, json.results_data[7].Matchday27Rank, json.results_data[7].Matchday28Rank, json.results_data[7].Matchday29Rank, json.results_data[7].Matchday30Rank,
							json.results_data[7].Matchday31Rank, json.results_data[7].Matchday32Rank, json.results_data[7].Matchday33Rank, json.results_data[7].Matchday34Rank
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[8].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[8].Matchday1Rank, json.results_data[8].Matchday2Rank, json.results_data[8].Matchday3Rank, json.results_data[8].Matchday4Rank, json.results_data[8].Matchday5Rank,
							json.results_data[8].Matchday6Rank, json.results_data[8].Matchday7Rank, json.results_data[8].Matchday8Rank, json.results_data[8].Matchday9Rank, json.results_data[8].Matchday10Rank,
							json.results_data[8].Matchday11Rank, json.results_data[8].Matchday12Rank, json.results_data[8].Matchday13Rank, json.results_data[8].Matchday14Rank, json.results_data[8].Matchday15Rank,
							json.results_data[8].Matchday16Rank, json.results_data[8].Matchday17Rank, json.results_data[8].Matchday18Rank, json.results_data[8].Matchday19Rank, json.results_data[8].Matchday20Rank,
							json.results_data[8].Matchday21Rank, json.results_data[8].Matchday22Rank, json.results_data[8].Matchday23Rank, json.results_data[8].Matchday24Rank, json.results_data[8].Matchday25Rank,
							json.results_data[8].Matchday26Rank, json.results_data[8].Matchday27Rank, json.results_data[8].Matchday28Rank, json.results_data[8].Matchday29Rank, json.results_data[8].Matchday30Rank,
							json.results_data[8].Matchday31Rank, json.results_data[8].Matchday32Rank, json.results_data[8].Matchday33Rank, json.results_data[8].Matchday34Rank
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[9].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[9].Matchday1Rank, json.results_data[9].Matchday2Rank, json.results_data[9].Matchday3Rank, json.results_data[9].Matchday4Rank, json.results_data[9].Matchday5Rank,
							json.results_data[9].Matchday6Rank, json.results_data[9].Matchday7Rank, json.results_data[9].Matchday8Rank, json.results_data[9].Matchday9Rank, json.results_data[9].Matchday10Rank,
							json.results_data[9].Matchday11Rank, json.results_data[9].Matchday12Rank, json.results_data[9].Matchday13Rank, json.results_data[9].Matchday14Rank, json.results_data[9].Matchday15Rank,
							json.results_data[9].Matchday16Rank, json.results_data[9].Matchday17Rank, json.results_data[9].Matchday18Rank, json.results_data[9].Matchday19Rank, json.results_data[9].Matchday20Rank,
							json.results_data[9].Matchday21Rank, json.results_data[9].Matchday22Rank, json.results_data[9].Matchday23Rank, json.results_data[9].Matchday24Rank, json.results_data[9].Matchday25Rank,
							json.results_data[9].Matchday26Rank, json.results_data[9].Matchday27Rank, json.results_data[9].Matchday28Rank, json.results_data[9].Matchday29Rank, json.results_data[9].Matchday30Rank,
							json.results_data[9].Matchday31Rank, json.results_data[9].Matchday32Rank, json.results_data[9].Matchday33Rank, json.results_data[9].Matchday34Rank
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[10].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[10].Matchday1Rank, json.results_data[10].Matchday2Rank, json.results_data[10].Matchday3Rank, json.results_data[10].Matchday4Rank, json.results_data[10].Matchday5Rank,
							json.results_data[10].Matchday6Rank, json.results_data[10].Matchday7Rank, json.results_data[10].Matchday8Rank, json.results_data[10].Matchday9Rank, json.results_data[10].Matchday10Rank,
							json.results_data[10].Matchday11Rank, json.results_data[10].Matchday12Rank, json.results_data[10].Matchday13Rank, json.results_data[10].Matchday14Rank, json.results_data[10].Matchday15Rank,
							json.results_data[10].Matchday16Rank, json.results_data[10].Matchday17Rank, json.results_data[10].Matchday18Rank, json.results_data[10].Matchday19Rank, json.results_data[10].Matchday20Rank,
							json.results_data[10].Matchday21Rank, json.results_data[10].Matchday22Rank, json.results_data[10].Matchday23Rank, json.results_data[10].Matchday24Rank, json.results_data[10].Matchday25Rank,
							json.results_data[10].Matchday26Rank, json.results_data[10].Matchday27Rank, json.results_data[10].Matchday28Rank, json.results_data[10].Matchday29Rank, json.results_data[10].Matchday30Rank,
							json.results_data[10].Matchday31Rank, json.results_data[10].Matchday32Rank, json.results_data[10].Matchday33Rank, json.results_data[10].Matchday34Rank
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[11].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[11].Matchday1Rank, json.results_data[11].Matchday2Rank, json.results_data[11].Matchday3Rank, json.results_data[11].Matchday4Rank, json.results_data[11].Matchday5Rank,
							json.results_data[11].Matchday6Rank, json.results_data[11].Matchday7Rank, json.results_data[11].Matchday8Rank, json.results_data[11].Matchday9Rank, json.results_data[11].Matchday10Rank,
							json.results_data[11].Matchday11Rank, json.results_data[11].Matchday12Rank, json.results_data[11].Matchday13Rank, json.results_data[11].Matchday14Rank, json.results_data[11].Matchday15Rank,
							json.results_data[11].Matchday16Rank, json.results_data[11].Matchday17Rank, json.results_data[11].Matchday18Rank, json.results_data[11].Matchday19Rank, json.results_data[11].Matchday20Rank,
							json.results_data[11].Matchday21Rank, json.results_data[11].Matchday22Rank, json.results_data[11].Matchday23Rank, json.results_data[11].Matchday24Rank, json.results_data[11].Matchday25Rank,
							json.results_data[11].Matchday26Rank, json.results_data[11].Matchday27Rank, json.results_data[11].Matchday28Rank, json.results_data[11].Matchday29Rank, json.results_data[11].Matchday30Rank,
							json.results_data[11].Matchday31Rank, json.results_data[11].Matchday32Rank, json.results_data[11].Matchday33Rank, json.results_data[11].Matchday34Rank
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[12].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[12].Matchday1Rank, json.results_data[12].Matchday2Rank, json.results_data[12].Matchday3Rank, json.results_data[12].Matchday4Rank, json.results_data[12].Matchday5Rank,
							json.results_data[12].Matchday6Rank, json.results_data[12].Matchday7Rank, json.results_data[12].Matchday8Rank, json.results_data[12].Matchday9Rank, json.results_data[12].Matchday10Rank,
							json.results_data[12].Matchday11Rank, json.results_data[12].Matchday12Rank, json.results_data[12].Matchday13Rank, json.results_data[12].Matchday14Rank, json.results_data[12].Matchday15Rank,
							json.results_data[12].Matchday16Rank, json.results_data[12].Matchday17Rank, json.results_data[12].Matchday18Rank, json.results_data[12].Matchday19Rank, json.results_data[12].Matchday20Rank,
							json.results_data[12].Matchday21Rank, json.results_data[12].Matchday22Rank, json.results_data[12].Matchday23Rank, json.results_data[12].Matchday24Rank, json.results_data[12].Matchday25Rank,
							json.results_data[12].Matchday26Rank, json.results_data[12].Matchday27Rank, json.results_data[12].Matchday28Rank, json.results_data[12].Matchday29Rank, json.results_data[12].Matchday30Rank,
							json.results_data[12].Matchday31Rank, json.results_data[12].Matchday32Rank, json.results_data[12].Matchday33Rank, json.results_data[12].Matchday34Rank
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[13].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[13].Matchday1Rank, json.results_data[13].Matchday2Rank, json.results_data[13].Matchday3Rank, json.results_data[13].Matchday4Rank, json.results_data[13].Matchday5Rank,
							json.results_data[13].Matchday6Rank, json.results_data[13].Matchday7Rank, json.results_data[13].Matchday8Rank, json.results_data[13].Matchday9Rank, json.results_data[13].Matchday10Rank,
							json.results_data[13].Matchday11Rank, json.results_data[13].Matchday12Rank, json.results_data[13].Matchday13Rank, json.results_data[13].Matchday14Rank, json.results_data[13].Matchday15Rank,
							json.results_data[13].Matchday16Rank, json.results_data[13].Matchday17Rank, json.results_data[13].Matchday18Rank, json.results_data[13].Matchday19Rank, json.results_data[13].Matchday20Rank,
							json.results_data[13].Matchday21Rank, json.results_data[13].Matchday22Rank, json.results_data[13].Matchday23Rank, json.results_data[13].Matchday24Rank, json.results_data[13].Matchday25Rank,
							json.results_data[13].Matchday26Rank, json.results_data[13].Matchday27Rank, json.results_data[13].Matchday28Rank, json.results_data[13].Matchday29Rank, json.results_data[13].Matchday30Rank,
							json.results_data[13].Matchday31Rank, json.results_data[13].Matchday32Rank, json.results_data[13].Matchday33Rank, json.results_data[13].Matchday34Rank
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[14].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[14].Matchday1Rank, json.results_data[14].Matchday2Rank, json.results_data[14].Matchday3Rank, json.results_data[14].Matchday4Rank, json.results_data[14].Matchday5Rank,
							json.results_data[14].Matchday6Rank, json.results_data[14].Matchday7Rank, json.results_data[14].Matchday8Rank, json.results_data[14].Matchday9Rank, json.results_data[14].Matchday10Rank,
							json.results_data[14].Matchday11Rank, json.results_data[14].Matchday12Rank, json.results_data[14].Matchday13Rank, json.results_data[14].Matchday14Rank, json.results_data[14].Matchday15Rank,
							json.results_data[14].Matchday16Rank, json.results_data[14].Matchday17Rank, json.results_data[14].Matchday18Rank, json.results_data[14].Matchday19Rank, json.results_data[14].Matchday20Rank,
							json.results_data[14].Matchday21Rank, json.results_data[14].Matchday22Rank, json.results_data[14].Matchday23Rank, json.results_data[14].Matchday24Rank, json.results_data[14].Matchday25Rank,
							json.results_data[14].Matchday26Rank, json.results_data[14].Matchday27Rank, json.results_data[14].Matchday28Rank, json.results_data[14].Matchday29Rank, json.results_data[14].Matchday30Rank,
							json.results_data[14].Matchday31Rank, json.results_data[14].Matchday32Rank, json.results_data[14].Matchday33Rank, json.results_data[14].Matchday34Rank
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[15].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[15].Matchday1Rank, json.results_data[15].Matchday2Rank, json.results_data[15].Matchday3Rank, json.results_data[15].Matchday4Rank, json.results_data[15].Matchday5Rank,
							json.results_data[15].Matchday6Rank, json.results_data[15].Matchday7Rank, json.results_data[15].Matchday8Rank, json.results_data[15].Matchday9Rank, json.results_data[15].Matchday10Rank,
							json.results_data[15].Matchday11Rank, json.results_data[15].Matchday12Rank, json.results_data[15].Matchday13Rank, json.results_data[15].Matchday14Rank, json.results_data[15].Matchday15Rank,
							json.results_data[15].Matchday16Rank, json.results_data[15].Matchday17Rank, json.results_data[15].Matchday18Rank, json.results_data[15].Matchday19Rank, json.results_data[15].Matchday20Rank,
							json.results_data[15].Matchday21Rank, json.results_data[15].Matchday22Rank, json.results_data[15].Matchday23Rank, json.results_data[15].Matchday24Rank, json.results_data[15].Matchday25Rank,
							json.results_data[15].Matchday26Rank, json.results_data[15].Matchday27Rank, json.results_data[15].Matchday28Rank, json.results_data[15].Matchday29Rank, json.results_data[15].Matchday30Rank,
							json.results_data[15].Matchday31Rank, json.results_data[15].Matchday32Rank, json.results_data[15].Matchday33Rank, json.results_data[15].Matchday34Rank
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[16].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[16].Matchday1Rank, json.results_data[16].Matchday2Rank, json.results_data[16].Matchday3Rank, json.results_data[16].Matchday4Rank, json.results_data[16].Matchday5Rank,
							json.results_data[16].Matchday6Rank, json.results_data[16].Matchday7Rank, json.results_data[16].Matchday8Rank, json.results_data[16].Matchday9Rank, json.results_data[16].Matchday10Rank,
							json.results_data[16].Matchday11Rank, json.results_data[16].Matchday12Rank, json.results_data[16].Matchday13Rank, json.results_data[16].Matchday14Rank, json.results_data[16].Matchday15Rank,
							json.results_data[16].Matchday16Rank, json.results_data[16].Matchday17Rank, json.results_data[16].Matchday18Rank, json.results_data[16].Matchday19Rank, json.results_data[16].Matchday20Rank,
							json.results_data[16].Matchday21Rank, json.results_data[16].Matchday22Rank, json.results_data[16].Matchday23Rank, json.results_data[16].Matchday24Rank, json.results_data[16].Matchday25Rank,
							json.results_data[16].Matchday26Rank, json.results_data[16].Matchday27Rank, json.results_data[16].Matchday28Rank, json.results_data[16].Matchday29Rank, json.results_data[16].Matchday30Rank,
							json.results_data[16].Matchday31Rank, json.results_data[16].Matchday32Rank, json.results_data[16].Matchday33Rank, json.results_data[16].Matchday34Rank
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[17].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[17].Matchday1Rank, json.results_data[17].Matchday2Rank, json.results_data[17].Matchday3Rank, json.results_data[17].Matchday4Rank, json.results_data[17].Matchday5Rank,
							json.results_data[17].Matchday6Rank, json.results_data[17].Matchday7Rank, json.results_data[17].Matchday8Rank, json.results_data[17].Matchday9Rank, json.results_data[17].Matchday10Rank,
							json.results_data[17].Matchday11Rank, json.results_data[17].Matchday12Rank, json.results_data[17].Matchday13Rank, json.results_data[17].Matchday14Rank, json.results_data[17].Matchday15Rank,
							json.results_data[17].Matchday16Rank, json.results_data[17].Matchday17Rank, json.results_data[17].Matchday18Rank, json.results_data[17].Matchday19Rank, json.results_data[17].Matchday20Rank,
							json.results_data[17].Matchday21Rank, json.results_data[17].Matchday22Rank, json.results_data[17].Matchday23Rank, json.results_data[17].Matchday24Rank, json.results_data[17].Matchday25Rank,
							json.results_data[17].Matchday26Rank, json.results_data[17].Matchday27Rank, json.results_data[17].Matchday28Rank, json.results_data[17].Matchday29Rank, json.results_data[17].Matchday30Rank,
							json.results_data[17].Matchday31Rank, json.results_data[17].Matchday32Rank, json.results_data[17].Matchday33Rank, json.results_data[17].Matchday34Rank
						]
					}
				],

				responsive: {
					rules: [{
						condition: {
							// グラフ表示レイアウト幅
							maxWidth: 750
						},
						chartOptions: {
							legend: {
								layout: 'horizontal',
								align: 'center',
								verticalAlign: 'bottom'
							}
						}
					}]
				},

				// tooltip設定
				tooltip: {
					// tooltip表示内容設定メソッド呼び出し
					formatter: formatter,
				}
			});
		}
	);
});
