$(function(){
	$.getJSON(
		"./MatchResultsGraph",
		function(json){
			console.log(json.results_data);
			console.log(json.results_data.NextMatchNum);
			// console.log(json.results_data[0].Matchday1ResultPoint);
			// console.log(json.results_data[0].Matchday1ResultSumPoint);

			// tooltip表示内容設定
			var formatter = function() {
				var tooltipMessage = this.series.name + '<br>第' + this.x + '節：' + this.y +'Pint';
		        return tooltipMessage;
		    }

			Highcharts.chart('container', {
				// スクロールバーの表示設定
				// scrollbar: true,
				// ナビゲート表示設定
				// navigator: true,

				chart: {
					renderTo: 'container',
					// Zoom機能:x軸に対してZoom
					zoomType: 'x',
				},
				// グラフタイトル名設定
				title: {
					text: '2017-2018 J1リーグ'
				},

				// サブタイトル名設定
				subtitle: {
					text: '勝ち点推移グラフ'
				},

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
						text: '勝ち点'
					}
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

				series: [
					{
						// グラフのライン名(クラブの略称)
						name: json.results_data[0].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[0].Matchday1ResultSumPoint, json.results_data[0].Matchday2ResultSumPoint, json.results_data[0].Matchday3ResultSumPoint, json.results_data[0].Matchday4ResultSumPoint, json.results_data[0].Matchday5ResultSumPoint,
							json.results_data[0].Matchday6ResultSumPoint, json.results_data[0].Matchday7ResultSumPoint, json.results_data[0].Matchday8ResultSumPoint, json.results_data[0].Matchday9ResultSumPoint, json.results_data[0].Matchday10ResultSumPoint,
							json.results_data[0].Matchday11ResultSumPoint, json.results_data[0].Matchday12ResultSumPoint, json.results_data[0].Matchday13ResultSumPoint, json.results_data[0].Matchday14ResultSumPoint, json.results_data[0].Matchday15ResultSumPoint,
							json.results_data[0].Matchday16ResultSumPoint, json.results_data[0].Matchday17ResultSumPoint, json.results_data[0].Matchday18ResultSumPoint, json.results_data[0].Matchday19ResultSumPoint, json.results_data[0].Matchday20ResultSumPoint,
							json.results_data[0].Matchday21ResultSumPoint, json.results_data[0].Matchday22ResultSumPoint, json.results_data[0].Matchday23ResultSumPoint, json.results_data[0].Matchday24ResultSumPoint, json.results_data[0].Matchday25ResultSumPoint,
							json.results_data[0].Matchday26ResultSumPoint, json.results_data[0].Matchday27ResultSumPoint, json.results_data[0].Matchday28ResultSumPoint, json.results_data[0].Matchday29ResultSumPoint, json.results_data[0].Matchday30ResultSumPoint,
							json.results_data[0].Matchday31ResultSumPoint, json.results_data[0].Matchday32ResultSumPoint, json.results_data[0].Matchday33ResultSumPoint, json.results_data[0].Matchday34ResultSumPoint
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[1].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[1].Matchday1ResultSumPoint, json.results_data[1].Matchday2ResultSumPoint, json.results_data[1].Matchday3ResultSumPoint, json.results_data[1].Matchday4ResultSumPoint, json.results_data[1].Matchday5ResultSumPoint,
							json.results_data[1].Matchday6ResultSumPoint, json.results_data[1].Matchday7ResultSumPoint, json.results_data[1].Matchday8ResultSumPoint, json.results_data[1].Matchday9ResultSumPoint, json.results_data[1].Matchday10ResultSumPoint,
							json.results_data[1].Matchday11ResultSumPoint, json.results_data[1].Matchday12ResultSumPoint, json.results_data[1].Matchday13ResultSumPoint, json.results_data[1].Matchday14ResultSumPoint, json.results_data[1].Matchday15ResultSumPoint,
							json.results_data[1].Matchday16ResultSumPoint, json.results_data[1].Matchday17ResultSumPoint, json.results_data[1].Matchday18ResultSumPoint, json.results_data[1].Matchday19ResultSumPoint, json.results_data[1].Matchday20ResultSumPoint,
							json.results_data[1].Matchday21ResultSumPoint, json.results_data[1].Matchday22ResultSumPoint, json.results_data[1].Matchday23ResultSumPoint, json.results_data[1].Matchday24ResultSumPoint, json.results_data[1].Matchday25ResultSumPoint,
							json.results_data[1].Matchday26ResultSumPoint, json.results_data[1].Matchday27ResultSumPoint, json.results_data[1].Matchday28ResultSumPoint, json.results_data[1].Matchday29ResultSumPoint, json.results_data[1].Matchday30ResultSumPoint,
							json.results_data[1].Matchday31ResultSumPoint, json.results_data[1].Matchday32ResultSumPoint, json.results_data[1].Matchday33ResultSumPoint, json.results_data[1].Matchday34ResultSumPoint
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[2].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[2].Matchday1ResultSumPoint, json.results_data[2].Matchday2ResultSumPoint, json.results_data[2].Matchday3ResultSumPoint, json.results_data[2].Matchday4ResultSumPoint, json.results_data[2].Matchday5ResultSumPoint,
							json.results_data[2].Matchday6ResultSumPoint, json.results_data[2].Matchday7ResultSumPoint, json.results_data[2].Matchday8ResultSumPoint, json.results_data[2].Matchday9ResultSumPoint, json.results_data[2].Matchday10ResultSumPoint,
							json.results_data[2].Matchday11ResultSumPoint, json.results_data[2].Matchday12ResultSumPoint, json.results_data[2].Matchday13ResultSumPoint, json.results_data[2].Matchday14ResultSumPoint, json.results_data[2].Matchday15ResultSumPoint,
							json.results_data[2].Matchday16ResultSumPoint, json.results_data[2].Matchday17ResultSumPoint, json.results_data[2].Matchday18ResultSumPoint, json.results_data[2].Matchday19ResultSumPoint, json.results_data[2].Matchday20ResultSumPoint,
							json.results_data[2].Matchday21ResultSumPoint, json.results_data[2].Matchday22ResultSumPoint, json.results_data[2].Matchday23ResultSumPoint, json.results_data[2].Matchday24ResultSumPoint, json.results_data[2].Matchday25ResultSumPoint,
							json.results_data[2].Matchday26ResultSumPoint, json.results_data[2].Matchday27ResultSumPoint, json.results_data[2].Matchday28ResultSumPoint, json.results_data[2].Matchday29ResultSumPoint, json.results_data[2].Matchday30ResultSumPoint,
							json.results_data[2].Matchday31ResultSumPoint, json.results_data[2].Matchday32ResultSumPoint, json.results_data[2].Matchday33ResultSumPoint, json.results_data[2].Matchday34ResultSumPoint
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[3].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[3].Matchday1ResultSumPoint, json.results_data[3].Matchday2ResultSumPoint, json.results_data[3].Matchday3ResultSumPoint, json.results_data[3].Matchday4ResultSumPoint, json.results_data[3].Matchday5ResultSumPoint,
							json.results_data[3].Matchday6ResultSumPoint, json.results_data[3].Matchday7ResultSumPoint, json.results_data[3].Matchday8ResultSumPoint, json.results_data[3].Matchday9ResultSumPoint, json.results_data[3].Matchday10ResultSumPoint,
							json.results_data[3].Matchday11ResultSumPoint, json.results_data[3].Matchday12ResultSumPoint, json.results_data[3].Matchday13ResultSumPoint, json.results_data[3].Matchday14ResultSumPoint, json.results_data[3].Matchday15ResultSumPoint,
							json.results_data[3].Matchday16ResultSumPoint, json.results_data[3].Matchday17ResultSumPoint, json.results_data[3].Matchday18ResultSumPoint, json.results_data[3].Matchday19ResultSumPoint, json.results_data[3].Matchday20ResultSumPoint,
							json.results_data[3].Matchday21ResultSumPoint, json.results_data[3].Matchday22ResultSumPoint, json.results_data[3].Matchday23ResultSumPoint, json.results_data[3].Matchday24ResultSumPoint, json.results_data[3].Matchday25ResultSumPoint,
							json.results_data[3].Matchday26ResultSumPoint, json.results_data[3].Matchday27ResultSumPoint, json.results_data[3].Matchday28ResultSumPoint, json.results_data[3].Matchday29ResultSumPoint, json.results_data[3].Matchday30ResultSumPoint,
							json.results_data[3].Matchday31ResultSumPoint, json.results_data[3].Matchday32ResultSumPoint, json.results_data[3].Matchday33ResultSumPoint, json.results_data[3].Matchday34ResultSumPoint
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[4].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[4].Matchday1ResultSumPoint, json.results_data[4].Matchday2ResultSumPoint, json.results_data[4].Matchday3ResultSumPoint, json.results_data[4].Matchday4ResultSumPoint, json.results_data[4].Matchday5ResultSumPoint,
							json.results_data[4].Matchday6ResultSumPoint, json.results_data[4].Matchday7ResultSumPoint, json.results_data[4].Matchday8ResultSumPoint, json.results_data[4].Matchday9ResultSumPoint, json.results_data[4].Matchday10ResultSumPoint,
							json.results_data[4].Matchday11ResultSumPoint, json.results_data[4].Matchday12ResultSumPoint, json.results_data[4].Matchday13ResultSumPoint, json.results_data[4].Matchday14ResultSumPoint, json.results_data[4].Matchday15ResultSumPoint,
							json.results_data[4].Matchday16ResultSumPoint, json.results_data[4].Matchday17ResultSumPoint, json.results_data[4].Matchday18ResultSumPoint, json.results_data[4].Matchday19ResultSumPoint, json.results_data[4].Matchday20ResultSumPoint,
							json.results_data[4].Matchday21ResultSumPoint, json.results_data[4].Matchday22ResultSumPoint, json.results_data[4].Matchday23ResultSumPoint, json.results_data[4].Matchday24ResultSumPoint, json.results_data[4].Matchday25ResultSumPoint,
							json.results_data[4].Matchday26ResultSumPoint, json.results_data[4].Matchday27ResultSumPoint, json.results_data[4].Matchday28ResultSumPoint, json.results_data[4].Matchday29ResultSumPoint, json.results_data[4].Matchday30ResultSumPoint,
							json.results_data[4].Matchday31ResultSumPoint, json.results_data[4].Matchday32ResultSumPoint, json.results_data[4].Matchday33ResultSumPoint, json.results_data[4].Matchday34ResultSumPoint
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[5].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[5].Matchday1ResultSumPoint, json.results_data[5].Matchday2ResultSumPoint, json.results_data[5].Matchday3ResultSumPoint, json.results_data[5].Matchday4ResultSumPoint, json.results_data[5].Matchday5ResultSumPoint,
							json.results_data[5].Matchday6ResultSumPoint, json.results_data[5].Matchday7ResultSumPoint, json.results_data[5].Matchday8ResultSumPoint, json.results_data[5].Matchday9ResultSumPoint, json.results_data[5].Matchday10ResultSumPoint,
							json.results_data[5].Matchday11ResultSumPoint, json.results_data[5].Matchday12ResultSumPoint, json.results_data[5].Matchday13ResultSumPoint, json.results_data[5].Matchday14ResultSumPoint, json.results_data[5].Matchday15ResultSumPoint,
							json.results_data[5].Matchday16ResultSumPoint, json.results_data[5].Matchday17ResultSumPoint, json.results_data[5].Matchday18ResultSumPoint, json.results_data[5].Matchday19ResultSumPoint, json.results_data[5].Matchday20ResultSumPoint,
							json.results_data[5].Matchday21ResultSumPoint, json.results_data[5].Matchday22ResultSumPoint, json.results_data[5].Matchday23ResultSumPoint, json.results_data[5].Matchday24ResultSumPoint, json.results_data[5].Matchday25ResultSumPoint,
							json.results_data[5].Matchday26ResultSumPoint, json.results_data[5].Matchday27ResultSumPoint, json.results_data[5].Matchday28ResultSumPoint, json.results_data[5].Matchday29ResultSumPoint, json.results_data[5].Matchday30ResultSumPoint,
							json.results_data[5].Matchday31ResultSumPoint, json.results_data[5].Matchday32ResultSumPoint, json.results_data[5].Matchday33ResultSumPoint, json.results_data[5].Matchday34ResultSumPoint
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[6].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[6].Matchday1ResultSumPoint, json.results_data[6].Matchday2ResultSumPoint, json.results_data[6].Matchday3ResultSumPoint, json.results_data[6].Matchday4ResultSumPoint, json.results_data[6].Matchday5ResultSumPoint,
							json.results_data[6].Matchday6ResultSumPoint, json.results_data[6].Matchday7ResultSumPoint, json.results_data[6].Matchday8ResultSumPoint, json.results_data[6].Matchday9ResultSumPoint, json.results_data[6].Matchday10ResultSumPoint,
							json.results_data[6].Matchday11ResultSumPoint, json.results_data[6].Matchday12ResultSumPoint, json.results_data[6].Matchday13ResultSumPoint, json.results_data[6].Matchday14ResultSumPoint, json.results_data[6].Matchday15ResultSumPoint,
							json.results_data[6].Matchday16ResultSumPoint, json.results_data[6].Matchday17ResultSumPoint, json.results_data[6].Matchday18ResultSumPoint, json.results_data[6].Matchday19ResultSumPoint, json.results_data[6].Matchday20ResultSumPoint,
							json.results_data[6].Matchday21ResultSumPoint, json.results_data[6].Matchday22ResultSumPoint, json.results_data[6].Matchday23ResultSumPoint, json.results_data[6].Matchday24ResultSumPoint, json.results_data[6].Matchday25ResultSumPoint,
							json.results_data[6].Matchday26ResultSumPoint, json.results_data[6].Matchday27ResultSumPoint, json.results_data[6].Matchday28ResultSumPoint, json.results_data[6].Matchday29ResultSumPoint, json.results_data[6].Matchday30ResultSumPoint,
							json.results_data[6].Matchday31ResultSumPoint, json.results_data[6].Matchday32ResultSumPoint, json.results_data[6].Matchday33ResultSumPoint, json.results_data[6].Matchday34ResultSumPoint
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[7].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[7].Matchday1ResultSumPoint, json.results_data[7].Matchday2ResultSumPoint, json.results_data[7].Matchday3ResultSumPoint, json.results_data[7].Matchday4ResultSumPoint, json.results_data[7].Matchday5ResultSumPoint,
							json.results_data[7].Matchday6ResultSumPoint, json.results_data[7].Matchday7ResultSumPoint, json.results_data[7].Matchday8ResultSumPoint, json.results_data[7].Matchday9ResultSumPoint, json.results_data[7].Matchday10ResultSumPoint,
							json.results_data[7].Matchday11ResultSumPoint, json.results_data[7].Matchday12ResultSumPoint, json.results_data[7].Matchday13ResultSumPoint, json.results_data[7].Matchday14ResultSumPoint, json.results_data[7].Matchday15ResultSumPoint,
							json.results_data[7].Matchday16ResultSumPoint, json.results_data[7].Matchday17ResultSumPoint, json.results_data[7].Matchday18ResultSumPoint, json.results_data[7].Matchday19ResultSumPoint, json.results_data[7].Matchday20ResultSumPoint,
							json.results_data[7].Matchday21ResultSumPoint, json.results_data[7].Matchday22ResultSumPoint, json.results_data[7].Matchday23ResultSumPoint, json.results_data[7].Matchday24ResultSumPoint, json.results_data[7].Matchday25ResultSumPoint,
							json.results_data[7].Matchday26ResultSumPoint, json.results_data[7].Matchday27ResultSumPoint, json.results_data[7].Matchday28ResultSumPoint, json.results_data[7].Matchday29ResultSumPoint, json.results_data[7].Matchday30ResultSumPoint,
							json.results_data[7].Matchday31ResultSumPoint, json.results_data[7].Matchday32ResultSumPoint, json.results_data[7].Matchday33ResultSumPoint, json.results_data[7].Matchday34ResultSumPoint
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[8].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[8].Matchday1ResultSumPoint, json.results_data[8].Matchday2ResultSumPoint, json.results_data[8].Matchday3ResultSumPoint, json.results_data[8].Matchday4ResultSumPoint, json.results_data[8].Matchday5ResultSumPoint,
							json.results_data[8].Matchday6ResultSumPoint, json.results_data[8].Matchday7ResultSumPoint, json.results_data[8].Matchday8ResultSumPoint, json.results_data[8].Matchday9ResultSumPoint, json.results_data[8].Matchday10ResultSumPoint,
							json.results_data[8].Matchday11ResultSumPoint, json.results_data[8].Matchday12ResultSumPoint, json.results_data[8].Matchday13ResultSumPoint, json.results_data[8].Matchday14ResultSumPoint, json.results_data[8].Matchday15ResultSumPoint,
							json.results_data[8].Matchday16ResultSumPoint, json.results_data[8].Matchday17ResultSumPoint, json.results_data[8].Matchday18ResultSumPoint, json.results_data[8].Matchday19ResultSumPoint, json.results_data[8].Matchday20ResultSumPoint,
							json.results_data[8].Matchday21ResultSumPoint, json.results_data[8].Matchday22ResultSumPoint, json.results_data[8].Matchday23ResultSumPoint, json.results_data[8].Matchday24ResultSumPoint, json.results_data[8].Matchday25ResultSumPoint,
							json.results_data[8].Matchday26ResultSumPoint, json.results_data[8].Matchday27ResultSumPoint, json.results_data[8].Matchday28ResultSumPoint, json.results_data[8].Matchday29ResultSumPoint, json.results_data[8].Matchday30ResultSumPoint,
							json.results_data[8].Matchday31ResultSumPoint, json.results_data[8].Matchday32ResultSumPoint, json.results_data[8].Matchday33ResultSumPoint, json.results_data[8].Matchday34ResultSumPoint
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[9].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[9].Matchday1ResultSumPoint, json.results_data[9].Matchday2ResultSumPoint, json.results_data[9].Matchday3ResultSumPoint, json.results_data[9].Matchday4ResultSumPoint, json.results_data[9].Matchday5ResultSumPoint,
							json.results_data[9].Matchday6ResultSumPoint, json.results_data[9].Matchday7ResultSumPoint, json.results_data[9].Matchday8ResultSumPoint, json.results_data[9].Matchday9ResultSumPoint, json.results_data[9].Matchday10ResultSumPoint,
							json.results_data[9].Matchday11ResultSumPoint, json.results_data[9].Matchday12ResultSumPoint, json.results_data[9].Matchday13ResultSumPoint, json.results_data[9].Matchday14ResultSumPoint, json.results_data[9].Matchday15ResultSumPoint,
							json.results_data[9].Matchday16ResultSumPoint, json.results_data[9].Matchday17ResultSumPoint, json.results_data[9].Matchday18ResultSumPoint, json.results_data[9].Matchday19ResultSumPoint, json.results_data[9].Matchday20ResultSumPoint,
							json.results_data[9].Matchday21ResultSumPoint, json.results_data[9].Matchday22ResultSumPoint, json.results_data[9].Matchday23ResultSumPoint, json.results_data[9].Matchday24ResultSumPoint, json.results_data[9].Matchday25ResultSumPoint,
							json.results_data[9].Matchday26ResultSumPoint, json.results_data[9].Matchday27ResultSumPoint, json.results_data[9].Matchday28ResultSumPoint, json.results_data[9].Matchday29ResultSumPoint, json.results_data[9].Matchday30ResultSumPoint,
							json.results_data[9].Matchday31ResultSumPoint, json.results_data[9].Matchday32ResultSumPoint, json.results_data[9].Matchday33ResultSumPoint, json.results_data[9].Matchday34ResultSumPoint
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[10].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[10].Matchday1ResultSumPoint, json.results_data[10].Matchday2ResultSumPoint, json.results_data[10].Matchday3ResultSumPoint, json.results_data[10].Matchday4ResultSumPoint, json.results_data[10].Matchday5ResultSumPoint,
							json.results_data[10].Matchday6ResultSumPoint, json.results_data[10].Matchday7ResultSumPoint, json.results_data[10].Matchday8ResultSumPoint, json.results_data[10].Matchday9ResultSumPoint, json.results_data[10].Matchday10ResultSumPoint,
							json.results_data[10].Matchday11ResultSumPoint, json.results_data[10].Matchday12ResultSumPoint, json.results_data[10].Matchday13ResultSumPoint, json.results_data[10].Matchday14ResultSumPoint, json.results_data[10].Matchday15ResultSumPoint,
							json.results_data[10].Matchday16ResultSumPoint, json.results_data[10].Matchday17ResultSumPoint, json.results_data[10].Matchday18ResultSumPoint, json.results_data[10].Matchday19ResultSumPoint, json.results_data[10].Matchday20ResultSumPoint,
							json.results_data[10].Matchday21ResultSumPoint, json.results_data[10].Matchday22ResultSumPoint, json.results_data[10].Matchday23ResultSumPoint, json.results_data[10].Matchday24ResultSumPoint, json.results_data[10].Matchday25ResultSumPoint,
							json.results_data[10].Matchday26ResultSumPoint, json.results_data[10].Matchday27ResultSumPoint, json.results_data[10].Matchday28ResultSumPoint, json.results_data[10].Matchday29ResultSumPoint, json.results_data[10].Matchday30ResultSumPoint,
							json.results_data[10].Matchday31ResultSumPoint, json.results_data[10].Matchday32ResultSumPoint, json.results_data[10].Matchday33ResultSumPoint, json.results_data[10].Matchday34ResultSumPoint
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[11].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[11].Matchday1ResultSumPoint, json.results_data[11].Matchday2ResultSumPoint, json.results_data[11].Matchday3ResultSumPoint, json.results_data[11].Matchday4ResultSumPoint, json.results_data[11].Matchday5ResultSumPoint,
							json.results_data[11].Matchday6ResultSumPoint, json.results_data[11].Matchday7ResultSumPoint, json.results_data[11].Matchday8ResultSumPoint, json.results_data[11].Matchday9ResultSumPoint, json.results_data[11].Matchday10ResultSumPoint,
							json.results_data[11].Matchday11ResultSumPoint, json.results_data[11].Matchday12ResultSumPoint, json.results_data[11].Matchday13ResultSumPoint, json.results_data[11].Matchday14ResultSumPoint, json.results_data[11].Matchday15ResultSumPoint,
							json.results_data[11].Matchday16ResultSumPoint, json.results_data[11].Matchday17ResultSumPoint, json.results_data[11].Matchday18ResultSumPoint, json.results_data[11].Matchday19ResultSumPoint, json.results_data[11].Matchday20ResultSumPoint,
							json.results_data[11].Matchday21ResultSumPoint, json.results_data[11].Matchday22ResultSumPoint, json.results_data[11].Matchday23ResultSumPoint, json.results_data[11].Matchday24ResultSumPoint, json.results_data[11].Matchday25ResultSumPoint,
							json.results_data[11].Matchday26ResultSumPoint, json.results_data[11].Matchday27ResultSumPoint, json.results_data[11].Matchday28ResultSumPoint, json.results_data[11].Matchday29ResultSumPoint, json.results_data[11].Matchday30ResultSumPoint,
							json.results_data[11].Matchday31ResultSumPoint, json.results_data[11].Matchday32ResultSumPoint, json.results_data[11].Matchday33ResultSumPoint, json.results_data[11].Matchday34ResultSumPoint
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[12].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[12].Matchday1ResultSumPoint, json.results_data[12].Matchday2ResultSumPoint, json.results_data[12].Matchday3ResultSumPoint, json.results_data[12].Matchday4ResultSumPoint, json.results_data[12].Matchday5ResultSumPoint,
							json.results_data[12].Matchday6ResultSumPoint, json.results_data[12].Matchday7ResultSumPoint, json.results_data[12].Matchday8ResultSumPoint, json.results_data[12].Matchday9ResultSumPoint, json.results_data[12].Matchday10ResultSumPoint,
							json.results_data[12].Matchday11ResultSumPoint, json.results_data[12].Matchday12ResultSumPoint, json.results_data[12].Matchday13ResultSumPoint, json.results_data[12].Matchday14ResultSumPoint, json.results_data[12].Matchday15ResultSumPoint,
							json.results_data[12].Matchday16ResultSumPoint, json.results_data[12].Matchday17ResultSumPoint, json.results_data[12].Matchday18ResultSumPoint, json.results_data[12].Matchday19ResultSumPoint, json.results_data[12].Matchday20ResultSumPoint,
							json.results_data[12].Matchday21ResultSumPoint, json.results_data[12].Matchday22ResultSumPoint, json.results_data[12].Matchday23ResultSumPoint, json.results_data[12].Matchday24ResultSumPoint, json.results_data[12].Matchday25ResultSumPoint,
							json.results_data[12].Matchday26ResultSumPoint, json.results_data[12].Matchday27ResultSumPoint, json.results_data[12].Matchday28ResultSumPoint, json.results_data[12].Matchday29ResultSumPoint, json.results_data[12].Matchday30ResultSumPoint,
							json.results_data[12].Matchday31ResultSumPoint, json.results_data[12].Matchday32ResultSumPoint, json.results_data[12].Matchday33ResultSumPoint, json.results_data[12].Matchday34ResultSumPoint
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[13].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[13].Matchday1ResultSumPoint, json.results_data[13].Matchday2ResultSumPoint, json.results_data[13].Matchday3ResultSumPoint, json.results_data[13].Matchday4ResultSumPoint, json.results_data[13].Matchday5ResultSumPoint,
							json.results_data[13].Matchday6ResultSumPoint, json.results_data[13].Matchday7ResultSumPoint, json.results_data[13].Matchday8ResultSumPoint, json.results_data[13].Matchday9ResultSumPoint, json.results_data[13].Matchday10ResultSumPoint,
							json.results_data[13].Matchday11ResultSumPoint, json.results_data[13].Matchday12ResultSumPoint, json.results_data[13].Matchday13ResultSumPoint, json.results_data[13].Matchday14ResultSumPoint, json.results_data[13].Matchday15ResultSumPoint,
							json.results_data[13].Matchday16ResultSumPoint, json.results_data[13].Matchday17ResultSumPoint, json.results_data[13].Matchday18ResultSumPoint, json.results_data[13].Matchday19ResultSumPoint, json.results_data[13].Matchday20ResultSumPoint,
							json.results_data[13].Matchday21ResultSumPoint, json.results_data[13].Matchday22ResultSumPoint, json.results_data[13].Matchday23ResultSumPoint, json.results_data[13].Matchday24ResultSumPoint, json.results_data[13].Matchday25ResultSumPoint,
							json.results_data[13].Matchday26ResultSumPoint, json.results_data[13].Matchday27ResultSumPoint, json.results_data[13].Matchday28ResultSumPoint, json.results_data[13].Matchday29ResultSumPoint, json.results_data[13].Matchday30ResultSumPoint,
							json.results_data[13].Matchday31ResultSumPoint, json.results_data[13].Matchday32ResultSumPoint, json.results_data[13].Matchday33ResultSumPoint, json.results_data[13].Matchday34ResultSumPoint
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[14].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[14].Matchday1ResultSumPoint, json.results_data[14].Matchday2ResultSumPoint, json.results_data[14].Matchday3ResultSumPoint, json.results_data[14].Matchday4ResultSumPoint, json.results_data[14].Matchday5ResultSumPoint,
							json.results_data[14].Matchday6ResultSumPoint, json.results_data[14].Matchday7ResultSumPoint, json.results_data[14].Matchday8ResultSumPoint, json.results_data[14].Matchday9ResultSumPoint, json.results_data[14].Matchday10ResultSumPoint,
							json.results_data[14].Matchday11ResultSumPoint, json.results_data[14].Matchday12ResultSumPoint, json.results_data[14].Matchday13ResultSumPoint, json.results_data[14].Matchday14ResultSumPoint, json.results_data[14].Matchday15ResultSumPoint,
							json.results_data[14].Matchday16ResultSumPoint, json.results_data[14].Matchday17ResultSumPoint, json.results_data[14].Matchday18ResultSumPoint, json.results_data[14].Matchday19ResultSumPoint, json.results_data[14].Matchday20ResultSumPoint,
							json.results_data[14].Matchday21ResultSumPoint, json.results_data[14].Matchday22ResultSumPoint, json.results_data[14].Matchday23ResultSumPoint, json.results_data[14].Matchday24ResultSumPoint, json.results_data[14].Matchday25ResultSumPoint,
							json.results_data[14].Matchday26ResultSumPoint, json.results_data[14].Matchday27ResultSumPoint, json.results_data[14].Matchday28ResultSumPoint, json.results_data[14].Matchday29ResultSumPoint, json.results_data[14].Matchday30ResultSumPoint,
							json.results_data[14].Matchday31ResultSumPoint, json.results_data[14].Matchday32ResultSumPoint, json.results_data[14].Matchday33ResultSumPoint, json.results_data[14].Matchday34ResultSumPoint
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[15].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[15].Matchday1ResultSumPoint, json.results_data[15].Matchday2ResultSumPoint, json.results_data[15].Matchday3ResultSumPoint, json.results_data[15].Matchday4ResultSumPoint, json.results_data[15].Matchday5ResultSumPoint,
							json.results_data[15].Matchday6ResultSumPoint, json.results_data[15].Matchday7ResultSumPoint, json.results_data[15].Matchday8ResultSumPoint, json.results_data[15].Matchday9ResultSumPoint, json.results_data[15].Matchday10ResultSumPoint,
							json.results_data[15].Matchday11ResultSumPoint, json.results_data[15].Matchday12ResultSumPoint, json.results_data[15].Matchday13ResultSumPoint, json.results_data[15].Matchday14ResultSumPoint, json.results_data[15].Matchday15ResultSumPoint,
							json.results_data[15].Matchday16ResultSumPoint, json.results_data[15].Matchday17ResultSumPoint, json.results_data[15].Matchday18ResultSumPoint, json.results_data[15].Matchday19ResultSumPoint, json.results_data[15].Matchday20ResultSumPoint,
							json.results_data[15].Matchday21ResultSumPoint, json.results_data[15].Matchday22ResultSumPoint, json.results_data[15].Matchday23ResultSumPoint, json.results_data[15].Matchday24ResultSumPoint, json.results_data[15].Matchday25ResultSumPoint,
							json.results_data[15].Matchday26ResultSumPoint, json.results_data[15].Matchday27ResultSumPoint, json.results_data[15].Matchday28ResultSumPoint, json.results_data[15].Matchday29ResultSumPoint, json.results_data[15].Matchday30ResultSumPoint,
							json.results_data[15].Matchday31ResultSumPoint, json.results_data[15].Matchday32ResultSumPoint, json.results_data[15].Matchday33ResultSumPoint, json.results_data[15].Matchday34ResultSumPoint
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[16].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[16].Matchday1ResultSumPoint, json.results_data[16].Matchday2ResultSumPoint, json.results_data[16].Matchday3ResultSumPoint, json.results_data[16].Matchday4ResultSumPoint, json.results_data[16].Matchday5ResultSumPoint,
							json.results_data[16].Matchday6ResultSumPoint, json.results_data[16].Matchday7ResultSumPoint, json.results_data[16].Matchday8ResultSumPoint, json.results_data[16].Matchday9ResultSumPoint, json.results_data[16].Matchday10ResultSumPoint,
							json.results_data[16].Matchday11ResultSumPoint, json.results_data[16].Matchday12ResultSumPoint, json.results_data[16].Matchday13ResultSumPoint, json.results_data[16].Matchday14ResultSumPoint, json.results_data[16].Matchday15ResultSumPoint,
							json.results_data[16].Matchday16ResultSumPoint, json.results_data[16].Matchday17ResultSumPoint, json.results_data[16].Matchday18ResultSumPoint, json.results_data[16].Matchday19ResultSumPoint, json.results_data[16].Matchday20ResultSumPoint,
							json.results_data[16].Matchday21ResultSumPoint, json.results_data[16].Matchday22ResultSumPoint, json.results_data[16].Matchday23ResultSumPoint, json.results_data[16].Matchday24ResultSumPoint, json.results_data[16].Matchday25ResultSumPoint,
							json.results_data[16].Matchday26ResultSumPoint, json.results_data[16].Matchday27ResultSumPoint, json.results_data[16].Matchday28ResultSumPoint, json.results_data[16].Matchday29ResultSumPoint, json.results_data[16].Matchday30ResultSumPoint,
							json.results_data[16].Matchday31ResultSumPoint, json.results_data[16].Matchday32ResultSumPoint, json.results_data[16].Matchday33ResultSumPoint, json.results_data[16].Matchday34ResultSumPoint
						]
					}, {
						// グラフのライン名(クラブの略称)
						name: json.results_data[17].ShortTeamName,
						// プロットする値(勝ち点)
						data: [json.results_data[17].Matchday1ResultSumPoint, json.results_data[17].Matchday2ResultSumPoint, json.results_data[17].Matchday3ResultSumPoint, json.results_data[17].Matchday4ResultSumPoint, json.results_data[17].Matchday5ResultSumPoint,
							json.results_data[17].Matchday6ResultSumPoint, json.results_data[17].Matchday7ResultSumPoint, json.results_data[17].Matchday8ResultSumPoint, json.results_data[17].Matchday9ResultSumPoint, json.results_data[17].Matchday10ResultSumPoint,
							json.results_data[17].Matchday11ResultSumPoint, json.results_data[17].Matchday12ResultSumPoint, json.results_data[17].Matchday13ResultSumPoint, json.results_data[17].Matchday14ResultSumPoint, json.results_data[17].Matchday15ResultSumPoint,
							json.results_data[17].Matchday16ResultSumPoint, json.results_data[17].Matchday17ResultSumPoint, json.results_data[17].Matchday18ResultSumPoint, json.results_data[17].Matchday19ResultSumPoint, json.results_data[17].Matchday20ResultSumPoint,
							json.results_data[17].Matchday21ResultSumPoint, json.results_data[17].Matchday22ResultSumPoint, json.results_data[17].Matchday23ResultSumPoint, json.results_data[17].Matchday24ResultSumPoint, json.results_data[17].Matchday25ResultSumPoint,
							json.results_data[17].Matchday26ResultSumPoint, json.results_data[17].Matchday27ResultSumPoint, json.results_data[17].Matchday28ResultSumPoint, json.results_data[17].Matchday29ResultSumPoint, json.results_data[17].Matchday30ResultSumPoint,
							json.results_data[17].Matchday31ResultSumPoint, json.results_data[17].Matchday32ResultSumPoint, json.results_data[17].Matchday33ResultSumPoint, json.results_data[17].Matchday34ResultSumPoint
						]
					}],

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