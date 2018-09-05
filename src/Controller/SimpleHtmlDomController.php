<?php
    namespace App\Controller;

    // configファイル使用のため
    use Cake\Core\Configure;
    // model使用のため
    use Cake\ORM\TableRegistry;
    // DateTimeクラス使用のため
    use \DateTime;
    use \DateTimeZone;
    // HtmlDomParserクラス使用のため
    use Sunra\PhpSimple\HtmlDomParser;
    // logファイル出力のため
    use Cake\Log\Log;

    class SimpleHtmlDomController extends AppController {

        public function index() {
            // ログへデータ取得開始メッセージ保存
            Log::info('2018シーズンJ1試合日程データ取得開始', 'simple_html_dom');

            // URL設定
            $url_config = Configure::read('jleagueURL', 'data'); // メモ : '配列名', 'ファイル名'
            foreach ($url_config as $url) {
                // 対象URLをログへ保存
                Log::info('$url = '.$url, 'simple_html_dom');

                // 試合データ格納用配列初期化
                $data = array();
                // URL先からhtml取得
                $html = @file_get_contents($url);
                if (empty($html)) {
                    // ログへメッセージ保存
                    Log::info('URL先からhtml取得::::FALSE ($html)', 'simple_html_dom');

                    // 該当のURLが存在しない場合、該当のデータはスキップ
                    continue;
                }

                // 取得したhtmlをHtmlDomParserへ設定
                $dom = HtmlDomParser::str_get_html($html);
                if (empty($dom)) {
                    // ログへメッセージ保存
                    Log::info('取得したhtmlをHtmlDomParserへ設定::::FALSE $dom', 'simple_html_dom');
                }

                // URLからディビジョン取得
                preg_match('/j[0-9]/', $url, $division_num); // 'j1'、'j2' or 'j3'の文字列取得
                // 文字列'j'削除し数字のみ取得
                $division = str_replace('j', '', $division_num[0]);
                if (empty($division)) {
                    // ログへメッセージ保存
                    Log::info('URLからディビジョン取得::::FALSE $division', 'simple_html_dom');
                }

                // 節数取得
                $result_match_num_data = $dom->find('#breadcrumbList h1', 0);
                // 数字以外は削除
                $result_match_num = preg_replace('/[^0-9]/', '', $result_match_num_data->plaintext);
                if (empty($result_match_num_data) || empty($result_match_num)) {
                    // ログへメッセージ保存
                    Log::info('節数取得::::FALSE $result_match_num', 'simple_html_dom');
                }

                // リーグ名&節数取得
                $result_title = $dom->find('.ttlLink h3', 0);
                if (empty($result_title)) {
                    // ログへメッセージ保存
                    Log::info('リーグ名&節数取得::::FALSE $result_title->plaintext', 'simple_html_dom');
                }

                // 詳細データ取得
                $result = $dom->find('.matchlistWrap');
                if (empty($result)) {
                    // ログへメッセージ保存
                    Log::info('各データ取得::::FALSE $dom->find(.matchlistWrap)', 'simple_html_dom');
                }
                // ログへメッセージ保存
                Log::info('詳細データ抽出::::Start', 'simple_html_dom');
                // 詳細データ抽出
                foreach($result as $element){
                    // 試合日の取得
                    $result_matchday = $element->find('.leftRedTit', 0);
                    if (empty($result_matchday)) {
                        // ログへメッセージ保存
                        Log::info('試合日の取得::::FALSE $result_matchday', 'simple_html_dom');
                    }

                    // 試合日から曜日削除
                    $cut = 3; // カットしたい文字数設定 : 「(月)」を削除するため
                    // 後ろ3文字削除
                    $matchday_replace = mb_substr( $result_matchday->plaintext , 0 , mb_strlen($result_matchday->plaintext) - $cut); // 2018年2月23日(金) → 2018年2月23日を抽出
                    // DateTime型へ変換
                    $matchday_date = DateTime::createFromFormat('Y年m月d日', $matchday_replace);
                    if (empty($matchday_date)){
                        // ログへメッセージ保存
                        Log::info('試合日から曜日削除、該当データの登録処理をスキップ::::FALSE $matchday_date', 'simple_html_dom');

                        // 日付データが存在しない場合、詳細データ抽出を途中で終了し該当データの登録は行わない(スキップ)
                        break;
                    }

                    // DateTime型のフォーマットをYmdへ変換
                    $matchday_key = $matchday_date->format('Ymd');
                    // 試合開始時刻とスタジアム名(略称)の取得
                    $result_time_stadium = $element->find('.stadium');
                    if (!empty($result_time_stadium)) {
                        // 試合開始時刻とスタジアム名(略称)格納用配列初期化
                        $time_stadium = array();
                        // id設定用変数を初期化
                        $time_stadium_key_id = 1;
                        foreach($result_time_stadium as $element_time_stadium){
                            // 一意となるようid作成
                            $key = $matchday_key.$time_stadium_key_id;
                            // 試合開始時刻と略称のスタジアム名を改行コードで分割し配列へ格納
                            $time_stadium_explode = explode("\r\n",$element_time_stadium->plaintext); // [0]:試合開始時刻、[1]:略称のスタジアム名
                            // 分割した試合開始時刻と略称のスタジアム名を成形
                            $time_stadium[$key] = array(
                                // 試合開始時刻を設定
                                'time' => $time_stadium_explode[0],
                                // 略称のスタジアム名を設定(全角英数字を半角へ変換)
                                'stadium_short_name' => mb_convert_kana($time_stadium_explode[1], 'rn'),
                            );
                            // idに+1を行い更新
                            $time_stadium_key_id = $time_stadium_key_id + 1;
                        }

                        // id設定用変数を初期化
                        $matchdaytime_key_id = 1;
                        // 試合開始日時の抽出
                        foreach ($time_stadium as $element_time) {
                            // 一意となるようid作成
                            $key = $matchday_key.$matchdaytime_key_id;

                            $date = new DateTime();
                            $date->setTimezone(new DateTimeZone('Asia/Tokyo'));

                            // 試合開始日格納用変数初期化
                            $matchday_format = null;
                            // 試合開始日時格納用変数初期化
                            $matchdaytime_format = null;
                            // Y年m月d日 H:i表記からY-m-d H:i:s表記へ変更
                            $matchdaytime = $date->createFromFormat('Y年m月d日 H:i', $matchday_replace." ".$element_time['time']);
                            if (!empty($matchdaytime)) {
                                $matchday_format = $matchdaytime->format('Y-m-d');
                                $matchdaytime_format = $matchdaytime->format('Y-m-d H:i:s');
                            }

                            // 試合データ格納用配列へ格納
                            $data[$key] = array(
                                'MatchDay' => $matchday_format,
                                'MatchDayTime' => $matchdaytime_format,
                                'ShortStadiumName' => $element_time['stadium_short_name']
                            );
                            // idに+1を行い更新
                            $matchdaytime_key_id = $matchdaytime_key_id + 1;
                        }
                    } else {
                        // ログへメッセージ保存
                        Log::info('試合開始時刻とスタジアム名(略称)の取得::::FALSE $result_time_stadium', 'simple_html_dom');
                    }

                    // HOMEとAWAYのチーム名取得
                    $result_team_names = $element->find('.clubName'); // clubName leftside
                    if (!empty($result_team_names)) {
                        // チーム名格納用配列初期化
                        $team_names = array();
                        foreach($result_team_names as $element_team_names){
                            $tmp_team_names = '';
                            // チーム名の先頭、末尾にあるスペースを削除
                            $tmp_team_names = trim($element_team_names->plaintext);
                            // チーム名の全角英数字を半角へ変換
                            $tmp_team_names = mb_convert_kana($tmp_team_names, 'rn');
                            // チーム名格納用配列へ格納
                            $team_names['team_names'][] = $tmp_team_names;
                        }

                        // id設定用変数を初期化
                        $home_team_key = 1;
                        $away_team_key = 1;
                        $home_key = 0;
                        $away_key = 0;
                        for ($key_num = 0; $key_num < count($team_names['team_names']); $key_num++) {
                            if($key_num % 2 == 0) {
                                // 一意となるようid作成
                                $key = $matchday_key.$home_team_key;
                                // 試合データ格納用配列へHomeチーム名を設定(偶数の場合)
                                $data[$key] = array_merge($data[$key], array('HomeTeam' => $team_names['team_names'][$key_num]));
                                // idに+1を行い更新
                                $home_team_key = $home_team_key + 1;
                            }else{
                                // 一意となるようid作成
                                $key = $matchday_key.$away_team_key;
                                // 試合データ格納用配列へAwayチーム名を設定(奇数の場合)
                                $data[$key] = array_merge($data[$key], array('AwayTeam' => $team_names['team_names'][$key_num]));
                                // idに+1を行い更新
                                $away_team_key = $away_team_key + 1;
                            }
                        }
                    } else {
                        // ログへメッセージ保存
                        Log::info('HOMEとAWAYのチーム名取得::::FALSE $result_time_stadium', 'simple_html_dom');
                    }

                    // HOMEとAWAYの得点取得
                    $result_point = $element->find('.point'); // point leftside
                    if (!empty($result_point)) {
                        // 得点格納用配列初期化
                        $point = array();
                        foreach($result_point as $element_point){
                            $point[] = trim($element_point->plaintext);
                        }

                        // id設定用変数を初期化
                        $point_home_team_key = 1;
                        $point_away_team_key = 1;
                        $point_home_key = 0;
                        $point_away_key = 0;
                        for ($key_num = 0; $key_num < count($point); $key_num++) {
                            if ($key_num % 2 == 0) {
                                // 一意となるようid作成
                                $key = $matchday_key.$point_home_team_key;
                                // 試合データ格納用配列へHomeチームのゴール数を設定(偶数の場合)
                                $data[$key] = array_merge($data[$key], array('HomeGetPoint' => $point[$key_num]));
                                // idに+1を行い更新
                                $point_home_team_key = $point_home_team_key + 1;
                            } else {
                                // 一意となるようid作成
                                $key = $matchday_key.$point_away_team_key;
                                // 試合データ格納用配列へAwayチームのゴール数を設定(偶数の場合)
                                $data[$key] = array_merge($data[$key], array('AwayGetPoint' => $point[$key_num]));
                                // idに+1を行い更新
                                $point_away_team_key = $point_away_team_key + 1;
                            }
                        }
                    } else {
                        // ログへメッセージ保存
                        Log::info('HOMEとAWAYの得点取得::::FALSE $result_point', 'simple_html_dom');
                    }
                }
                // ログへメッセージ保存
                Log::info('詳細データ抽出::::End', 'simple_html_dom');

                // 試合データ格納用配列のキー値を取得
                $data_key = array_keys($data);
                // ディビジョンと節数を設定
                foreach ($data_key as $element_key) {
                    $data[$element_key] = array_merge($data[$element_key], array('Division' => $division));
                    $data[$element_key] = array_merge($data[$element_key], array('MatchNum' => $result_match_num));
                }

                // HtmlDomParserを開放
                $dom->clear();
                unset($dom);

                // Model呼び出しのため
                $jleaged1matchdata2018 = TableRegistry::get('JleageD1Matchdata2018'); // 2017-2018シーズン用テーブル
                // データ保存
                $jleaged1matchdata2018->registerData($data);
                // 1秒待機
                sleep(1);
            }

            // ログへデータ取得終了メッセージ保存
            Log::info('2018シーズンJ1試合日程データ取得終了','simple_html_dom');

            return true;
        }

        public function view()
        {
            //something...
        }

}