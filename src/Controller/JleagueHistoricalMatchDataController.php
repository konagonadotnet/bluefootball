<?php
    namespace App\Controller;

    // logファイル出力のため
    use Cake\Log\Log;
    // configファイル使用のため
    use Cake\Core\Configure;
    // model使用のため
    use Cake\ORM\TableRegistry;
    // DateTimeクラス使用のため
    use \DateTime;
    use \DateTimeZone;
    // HtmlDomParserクラス使用のため
    use Sunra\PhpSimple\HtmlDomParser;

    class JleagueHistoricalMatchDataController extends AppController {
        public function initialize(){
            // シーズン選択
            $this->season_year = 2017;
            // $this->season_year = 2016;

            // configファイル、Table呼び出し用設定値を設定
            if ($this->season_year == 2017) {
                $this->conf_val = array(
                    'season' => 2017,
                    'category' => 'j1',
                    'url' => 'j1league2017URL',
                    'TableMatchdata' => 'JleageD1Matchdata2017',
                    'TableMatchResults' => 'JleageD1MatchResults2017',
                    'DataMaxNum' => 60, // htmlからのデータ取得箇所数の設定値
                );
            }
            if ($this->season_year == 2016) {
                $this->conf_val = array(
                    'season' => 2016,
                    'category' => 'j1',
                    'url' => 'j1league2016URL',
                    'TableMatchdata' => 'JleageD1Matchdata2016',
                    'TableMatchResults' => 'JleageD1MatchResults2016',
                    'DataMaxNum' => 46, // htmlからのデータ取得箇所数の設定値
                );
            }
        }

        /*
         * 試合日程データ取得成形・新規登録、更新処理
         */
        public function index() {
            // ログへデータ取得開始メッセージ保存
            Log::info($this->conf_val['season'].'シーズン'.$this->conf_val['category'].'試合日程データ取得開始', 'jleague_historical_matchdata');

            // configファイルから対象のURLデータを取得
            $url_config = Configure::read($this->season_year.'.'.$this->conf_val['url'], 'data'); // メモ : '配列名', 'ファイル名'

            // 対象URLの設定
            $url = $url_config[0];
            // ログへ対象URLを保存
            Log::info('$url = '.$url, 'jleague_historical_matchdata');

            // 試合データ格納用配列初期化
            $data = array();
            // URL先からhtml取得
            $html = @file_get_contents($url);
            if (empty($html)) {
                // ログへメッセージ保存
                Log::info('URL先からhtml取得::::FALSE ($html)', 'jleague_historical_matchdata');

                // 該当のURLが存在しない場合、該当のデータはスキップ
                continue;
            }

            // 取得したhtmlをHtmlDomParserへ設定
            $dom = HtmlDomParser::str_get_html($html);
            if (empty($dom)) {
                // ログへメッセージ保存
                Log::info('取得したhtmlをHtmlDomParserへ設定::::FALSE $dom', 'jleague_historical_matchdata');
            }

            // URLからディビジョン取得
            preg_match('/j[0-9]/', $url, $division_num); // 'j1'、'j2' or 'j3'以外の文字列取得
            // 文字列'j'削除し数字のみ取得
            $division = str_replace('j', '', $division_num[0]);
            if (empty($division)) {
                // ログへメッセージ保存
                Log::info('URLからディビジョン取得::::FALSE $division', 'jleague_historical_matchdata');
            }

            // リーグ名&節数取得
            $result_title = $dom->find('.ttlLink h3', 0);
            if (empty($result_title)) {
                // ログへメッセージ保存
                Log::info('リーグ名&節数取得::::FALSE $result_title->plaintext', 'jleague_historical_matchdata');
            }

            // DateTimeクラスの設定
            $date = new DateTime();
            $date->setTimezone(new DateTimeZone('Asia/Tokyo'));
            // 全試合データ格納用配列を初期化
            $jleaguehistoricalmatchdata = array();
            // 全試合データ抽出成形&格納
            for ($data_num = 0; $data_num <= $this->conf_val['DataMaxNum']; $data_num++) {
                // 試合日の取得
                $result_matchday = $dom->find('.leftRedTit', $data_num);
                if (empty($result_matchday)) {
                    // ログへメッセージ保存
                    Log::info('試合日の取得::::FALSE $result_matchday', 'jleague_historical_matchdata');
                }

                // 試合日から曜日削除
                $cut = 3; // カットしたい文字数設定 : 「(月)」を削除するため
                // 後ろ3文字削除
                $matchday_replace = mb_substr($result_matchday->plaintext , 0 , mb_strlen($result_matchday->plaintext) - $cut); // 2018年2月23日(金) → 2018年2月23日を抽出
                // DateTime型へ変換
                $matchday_date = DateTime::createFromFormat('Y年m月d日', $matchday_replace);
                if (empty($matchday_date)){
                    // ログへメッセージ保存
                    Log::info('試合日から曜日削除、該当データの登録処理をスキップ::::FALSE $matchday_date', 'jleague_historical_matchdata');

                    // 日付データが存在しない場合、詳細データ抽出を途中で終了し該当データの登録は行わない(スキップ)
                    break;
                }
                // DateTime型のフォーマットをYmdへ変換
                $matchday_key = $matchday_date->format('Ymd');

                // 節数取得
                $result_match_num_data = $dom->find('.leagAccTit h5', $data_num);
                if (empty($result_match_num_data)) {
                    // ログへメッセージ保存
                    Log::info('節数取得::::FALSE $result_match_num', 'jleague_historical_matchdata');
                    // 節数取得失敗の場合、スキップ
                    break;
                }

                $result_match_num = null;
                // 半角数字以外は削除
                if ($this->season_year == 2017) { // 2017シーズンの場合、半角数字以外は削除し節数を格納
                    $result_match_num = preg_replace('/[^0-9]/', '', $result_match_num_data->plaintext);
                } else if ($this->season_year == 2016) { // 2016シーズンの場合、ステージ数(1st or 2nd)を判定し節数を格納
                    // 区切り文字をスペースとして配列に変換する変数初期化
                    $table_stage_num_array = array();

                    // 全角英数字を半角英数字へ変換
                    $table_stage_num_plaintext = mb_convert_kana($result_match_num_data->plaintext,'a');
                    // 全角スペースを半角スペースへ変換
                    $table_stage_num_plaintext = preg_replace('/　/', ' ', $table_stage_num_plaintext);
                    // 区切り文字をスペースとして配列へ変換
                    $table_stage_num_array = explode(" ", $table_stage_num_plaintext);

                    // ステージ数をを判定
                    if ($table_stage_num_array[1] == '1stステージ') {
                        // 半角数字以外は削除し節数を設定
                        $result_match_num = preg_replace('/[^0-9]/', '', $table_stage_num_array[2]);
                    } else if ($table_stage_num_array[1] == '2ndステージ'){
                        // 半角数字以外は削除し節数を設定
                        $result_match_num = 17;
                        $result_match_num = $result_match_num + preg_replace('/[^0-9]/', '', $table_stage_num_array[2]);
                    } else {
                        // ログへメッセージ保存
                        Log::info('ステージ数取得::::FALSE $table_stage_num_array', 'jleague_historical_matchdata');
                    }
                }
                // 節数取得の確認
                if (empty($result_match_num)) {
                    // ログへメッセージ保存
                    Log::info('節数取得::::FALSE $result_match_num', 'jleague_historical_matchdata');
                    // 節数取得失敗の場合、スキップ
                    break;
                }

                // 試合詳細データ取得
                $result = $dom->find('.matchTable', $data_num);
                if (empty($result)) {
                    // ログへメッセージ保存
                    Log::info('各データ取得::::FALSE $dom->find(.matchlistWrap)', 'jleague_historical_matchdata');
                }
                // 要素からタグを除外したテキストを取得
                $table_data_plaintext = $result->plaintext;

                // 特定文字列を削除
                $table_data_plaintext = str_replace(array("スタジアム", "対戦カード", "チケット / データ", "試合終了", "試合詳細", "選手コメント", "監督コメント", "トラッキング", "フォト", "レポート・動画"), '', $table_data_plaintext);
                // 全角スペースを半角スペースへ変換
                $table_data_plaintext = preg_replace('/　/', ' ', $table_data_plaintext);
                // 連続する半角スペースを1つの半角スペースへ変換
                $table_data_plaintext = preg_replace('/\s+/', ' ', $table_data_plaintext);
                // 先頭の半角・全角スペース削除
                $table_data_plaintext = preg_replace('/^[ 　]+/u', '', $table_data_plaintext);
                // 末尾の半角・全角スペース削除
                $table_data_plaintext = preg_replace('/[ 　]+$/u', '', $table_data_plaintext);

                // 区切り文字をスペースとして配列に変換する変数初期化
                $table_data_array = array();
                // 区切り文字をスペースとして配列へ変換
                $table_data_array = explode(" ", $table_data_plaintext);

                // 成形データ格納用配列を初期化
                $element = array();
                foreach ($table_data_array as $data) {
                    // 'の'を含んだ配列要素を削除する
                    if (!preg_match( '/の/', $data)) { // '横浜FMvs浦和の'を含んだ要素を削除
                        $element[] = $data;
                    }
                }
                // ログへメッセージ保存
                Log::info($this->conf_val['season'].'シーズン'.$this->conf_val['category'].'試合日程データ成形完了::::$matchday_key = '.$matchday_key, 'jleague_historical_matchdata');

                // 成形したデータ数を設定
                $element_max = count($element);
                // 試合日格納用キー値を初期化
                $matchday_num = 0;
                // スタジアム名格納用キー値を初期化
                $short_stadium_name_num = 1;
                // ホームチーム名格納用キー値を初期化
                $home_team_num = 2;
                // ホームチームのゴール数格納用キー値を初期化
                $home_get_point_num = 3;
                // アウェイチームのゴール数格納用キー値を初期化
                $away_get_point_num = 4;
                // アウェイチーム名格納用キー値を初期化
                $away_team_num = 5;
                // 全試合データ格納用要素キー作成用ID変数を初期化
                $matchdaytime_key_id = 1;
                // 節ごとのデータ格納用配列を初期化
                $jleaguehistorical_data = array();
                for ($num = 0; $num < $element_max; $num++) {
                    // 全試合データ格納用要素キーの作成
                    $data_key = $matchday_key.$matchdaytime_key_id;

                    if ($num == $matchday_num) {
                        // DateTime型へ変換
                        $matchday_time_date = $date->createFromFormat('Y-m-d H:i', $matchday_date->format('Y-m-d')." ".$element[$num]);
                        if (!empty($matchday_time_date)) {
                            // 試合日時を格納
                            $jleaguehistorical_data[$data_key]['MatchDayTime'] = $matchday_time_date->format('Y-m-d H:i:s');
                            // 試合日を格納
                            $jleaguehistorical_data[$data_key]['MatchDay'] = $matchday_time_date->format('Y-m-d');
                        }
                    } else if ($num == $short_stadium_name_num) {
                        // スタジアム名の略称を格納(全角英数字を半角へ変換)
                        $jleaguehistorical_data[$data_key]['ShortStadiumName'] = mb_convert_kana($element[$num], 'rn');
                    } else if ($num == $home_team_num) {
                        // ホームチーム名を格納(全角英数字を半角へ変換)
                        $jleaguehistorical_data[$data_key]['HomeTeam'] = mb_convert_kana($element[$num], 'rn');
                    } else if ($num == $home_get_point_num) {
                        // ホームチームのゴール数を格納
                        $jleaguehistorical_data[$data_key]['HomeGetPoint'] = $element[$num];
                    } else if ($num == $away_get_point_num) {
                        // アウェイチームのゴール数を格納
                        $jleaguehistorical_data[$data_key]['AwayGetPoint'] = $element[$num];
                    } else if ($num == $away_team_num) {
                        // アウェイチーム名を格納(全角英数字を半角へ変換)
                        $jleaguehistorical_data[$data_key]['AwayTeam'] = mb_convert_kana($element[$num], 'rn');
                        // ディビジョンを格納
                        $jleaguehistorical_data[$data_key]['Division'] = $division;
                        // 節数を格納
                        $jleaguehistorical_data[$data_key]['MatchNum'] = $result_match_num;

                        // 全試合データ格納用要素キー作成用ID変数を更新
                        $matchdaytime_key_id = $matchdaytime_key_id + 1;
                        // 試合日格納用キー値を更新
                        $matchday_num = $num + 1;
                        // スタジアム名格納用キー値を更新
                        $short_stadium_name_num = $num + 2;
                        // ホームチーム名格納用キー値を更新
                        $home_team_num = $num + 3;
                        // ホームチームのゴール数格納用キー値を更新
                        $home_get_point_num = $num + 4;
                        // アウェイチームのゴール数格納用キー値を更新
                        $away_get_point_num = $num + 5;
                        // アウェイチーム名格納用キー値を更新
                        $away_team_num = $num + 6;
                    }
                }
                // 全試合データ格納用配列へ結合
                $jleaguehistoricalmatchdata = $jleaguehistoricalmatchdata + $jleaguehistorical_data;
            }
            // HtmlDomParserを開放
            $dom->clear();
            unset($dom);

            // ログへメッセージ保存
            Log::info($this->conf_val['season'].'シーズン'.$this->conf_val['category'].'試合日程データ登録・更新準備完了', 'jleague_historical_matchdata');

            // 登録・更新処理開始
            if ($this->season_year == 2016 || $this->season_year == 2017) {
                // ログへ登録処理開始メッセージ保存
                Log::info($this->conf_val['season'].'シーズン'.$this->conf_val['category'].'試合日程データ登録処理::::Start', 'jleague_historical_matchdata');

                // Model呼び出し
                $jleagedmatchdata = TableRegistry::get($this->conf_val['TableMatchdata']);

                // 1件ずつデータをチェックし保存
                foreach($jleaguehistoricalmatchdata as $historical_data) {
                    // データチェック
                    $check_id = $jleagedmatchdata->checkDeta($historical_data); // $check_id:0(新規)、0以外(既に存在)

                    // 新規登録 or 更新の判定
                    if ($check_id == 0) { // 新規登録
                        // ログへメッセージ保存
                        Log::info('DBのデータ存在チェック::::データが存在しません。新規登録開始 $check_id = '.$check_id, 'jleague_historical_matchdata');

                        $table_save_flg = false; // 初期化
                        // 登録を実行
                        $table_save_flg = $jleagedmatchdata->saveNewData($historical_data);
                        if ($table_save_flg == false) {
                            // 保存に失敗した場合、結果をログへ保存
                            Log::info('DBのデータ存在チェック::::新規登録失敗::::$historical_data = '.print_r($historical_data, true), 'jleague_historical_matchdata');
                        } else {
                            // 正常に保存された場合、登録結果をログへ保存
                            Log::info('DBのデータ存在チェック::::新規登録成功::::$check_id = '.$table_save_flg, 'jleague_historical_matchdata');
                        }
                    } else { // データ比較、更新
                        // ログへメッセージ保存
                        Log::info('DBのデータ存在チェック::::データはすでに存在します。DBとの比較開始 $check_id = '.$check_id, 'jleague_historical_matchdata');

                        $table_matchdata_array = array(); // 初期化

                        // データが存在する場合、$check_idからテーブルデータ取得
                        $table_matchdata = $jleagedmatchdata->getTableData($check_id);
                        // objectから配列へ変換
                        $table_matchdata_array = $table_matchdata->toArray();

                        // DBから取得した試合日格納用変数の初期化
                        $matchday_data = null;
                        // DBから取得した試合日を変数へ格納
                        if (!empty($table_matchdata_array['MatchDay'])) {
                            // 試合日時が取得できている場合、試合日時を変数へ格納
                            $matchday_data = $table_matchdata_array['MatchDay']->format('Y-m-d');
                        } else {
                            // 試合日時が取得できていない場合、カラを変数へ格納
                            $matchday_data = $table_matchdata_array['MatchDay'];
                        }

                        // DBから取得した試合日時格納用変数
                        $matchday_time_data = null;
                        // DBから取得した試合日時を変数へ格納
                        if (!empty($table_matchdata_array['MatchDayTime'])) {
                            // 試合日時が取得できている場合、試合日時を変数へ格納
                            $matchday_time_data = $table_matchdata_array['MatchDayTime']->format('Y-m-d H:i:s');
                        } else {
                            // 試合日時が取得できていない場合、カラを変数へ格納
                            $matchday_time_data = $table_matchdata_array['MatchDayTime'];
                        }

                        // 公式ページから取得したデータとDBから取得した値を比較
                        if ($historical_data['MatchDay'] != $matchday_data
                         || $historical_data['MatchDayTime'] != $matchday_time_data
                         || $historical_data['ShortStadiumName'] != $table_matchdata_array['ShortStadiumName']
                         || $historical_data['HomeTeam'] != $table_matchdata_array['HomeTeam']
                         || $historical_data['AwayTeam'] != $table_matchdata_array['AwayTeam']
                         || $historical_data['HomeGetPoint'] != $table_matchdata_array['HomeGetPoint']
                         || $historical_data['AwayGetPoint'] != $table_matchdata_array['AwayGetPoint']
                         || $historical_data['Division'] != $table_matchdata_array['Division']
                         || $historical_data['MatchNum'] != $table_matchdata_array['MatchNum']) {
                            // 値が1つでも異なる場合、比較結果をログへ保存
                            Log::info('Webページから取得したデータとDBから取得した値を比較::::データが異なります', 'jleague_historical_matchdata');

                            $table_update_flg = false; // 初期化

                            $table_update_flg = $jleagedmatchdata->updateData($table_matchdata, $historical_data);
                            if ($table_update_flg == false) {
                                // 保存に失敗した場合、結果をログへ保存
                                Log::info('Webページから取得したデータとDBから取得した値を比較::::データの上書き失敗 $check_id = '.$check_id.' $historical_data = '.print_r($historical_data, true), 'jleague_historical_matchdata');
                            } else {
                                // 正常に保存された場合、登録結果をログへ保存
                                Log::info('Webページから取得したデータとDBから取得した値を比較::::データの上書き成功 $check_id = '.$table_update_flg, 'jleague_historical_matchdata');
                            }
                        } else {
                            // 比較結果をログへ保存
                            Log::info('Webページから取得したデータとDBから取得した値を比較::::データは同じため登録処理をスキップ $check_id = '.$check_id, 'jleague_historical_matchdata');
                        }
                    }
                }
                // ログへメッセージ保存
                Log::info($this->conf_val['season'].'シーズン'.$this->conf_val['category'].'試合日程データ登録処理::::End', 'jleague_historical_matchdata');
            } else {
                // Model呼び出し
                $jleagedmatchdata = TableRegistry::get($this->conf_val['TableMatchdata']);
                // データ保存
                $jleagedmatchdata->registerData($jleaguehistoricalmatchdata);
            }

            // ログへデータ取得終了メッセージ保存
            Log::info($this->conf_val['season'].'シーズン'.$this->conf_val['category'].'試合日程データ取得終了', 'jleague_historical_matchdata');

            // 処理終了
            exit();
        }

        /*
         * 試合結果データ取得成形、新規登録・更新処理
         */
        public function RegistrationMatchResults() {
            /**** 試合結果登録・更新処理 start ****/
            // ログへ試合結果登録・更新処理開始メッセージ保存
            Log::info($this->season_year.'シーズン'.$this->conf_val['category'].'試合結果登録・更新処理::::Start', 'jleague_historical_matchdata');

            // 現在日時を取得
            $today = date("Y/m/d H:i");

            // ログへ試合結果登録・更新準備完了メッセージ保存
            Log::info($this->season_year.'シーズン'.$this->conf_val['category'].'試合結果データ準備::::開始', 'jleague_historical_matchdata');

            // J1チームデータModel呼び出し
            $jleageteams = TableRegistry::get('JleageTeams');
            // チーム名データ取得
            $teams_data = $jleageteams->getJleageTeams();
            if (empty($teams_data)) {
                // ログへ試合結果登録・更新処理開始メッセージ保存
                Log::info($this->season_year.'シーズン'.$this->conf_val['category'].'試合結果DB登録・更新処理::::チーム名が取得できませんでした', 'jleague_historical_matchdata');
            }

            // J1試合日程データのModel呼び出し
            $jleagedmatchdata = TableRegistry::get($this->conf_val['TableMatchdata']);

            // 試合結果集計データのDB登録用変数初期化
            $match_results_data = array();
            // 各チームごとの試合データ抽出結果格納用変数初期化
            $match_results_teams_data = array();
            // 試合結果登録用データ収集
            foreach ($teams_data as $team) {
                // 各チームの試合データ抽出
                $match_results_teams_data[$team['id']] = $jleagedmatchdata->getConfigTeamMatchData($team['ShortTeamName']);
                if ($match_results_teams_data[$team['id']] != false) {
                    // idをキーに設定しidを格納
                    $match_results_data[$team['id']]['id'] = $team['id'];
                    // idをキーに設定しTeamNameを格納
                    $match_results_data[$team['id']]['TeamName'] = $team['TeamName'];
                    // TeamNameを格納
                    $match_results_data[$team['id']]['ShortTeamName'] = $team['ShortTeamName'];

                    // 登録・更新対象チームのIDを格納
                    $target_team_id[] = $team['id'];
                }
            }
            // カラの要素を削除
            $match_results_teams_data = array_filter($match_results_teams_data);

            // チームごとに試合結果を登録・更新
            foreach ($target_team_id as $team_id) {
                // 勝ち点の合計格納用変数初期化
                $result_sum_point = 0;
                // 総得点数集計用変数初期化
                $result_total_goal_score = 0;
                // 総失点数集計用変数初期化
                $result_total_lost_score = 0;
                // 試合数集計用変数初期化
                $result_sum_play = 0;
                foreach ($match_results_teams_data[$team_id] as $results_data) {
                    // 試合結果格納用変数初期化
                    $result_status = null;
                    $result_point = null;
                    $result_home_and_away = null;
                    // Home or Awayを判定し試合結果を設定(勝ち:W(3点)、引き分け:D(1点)、負け(0点):L)
                    if ($match_results_data[$team_id]['ShortTeamName'] == $results_data['HomeTeam'] // ホームかどうかチェック
                     && !is_null($results_data['HomeGetPoint']) // ホームチームのゴール数データ存在チェック
                     && !is_null($results_data['AwayGetPoint'])) { // アウェイチームのゴール数データ存在チェック
                        // ホームの場合の試合結果を設定
                        if ($results_data['HomeGetPoint'] > $results_data['AwayGetPoint']) { // 勝ちの場合
                            // Homeで勝った場合、'W'を設定
                            $result_status = 'W';
                            // Homeで勝った場合、勝ち点3を設定
                            $result_point = 3;
                        } else if ($results_data['HomeGetPoint'] == $results_data['AwayGetPoint']) { // 引き分けの場合
                            // Homeで引き分けの場合、'D'を設定
                            $result_status = 'D';
                            // Homeで引き分けの場合、勝ち点1を設定
                            $result_point = 1;
                        } else if ($results_data['HomeGetPoint'] < $results_data['AwayGetPoint']) { // 負けの場合
                            // Homeで負けた場合、'L'を設定
                            $result_status = 'L';
                            // Homeで負けた場合、勝ち点0を設定
                            $result_point = 0;
                        }

                        // Homeの場合、文字'H'を格納
                        $result_home_and_away = 'H';

                        // 勝ち点の合計を格納
                        $result_sum_point = $result_sum_point + $result_point;

                        // 試合数の合計を格納
                        $result_sum_play = $result_sum_play + 1;
                    } else if ($match_results_data[$team_id]['ShortTeamName'] == $results_data['AwayTeam'] // アウェイかどうかチェック
                     && !is_null($results_data['HomeGetPoint']) // ホームチームのゴール数データ存在チェック
                     && !is_null($results_data['AwayGetPoint'])) { // アウェイチームのゴール数データ存在チェック
                        // アウェイの場合の試合結果を設定
                        if ($results_data['AwayGetPoint'] > $results_data['HomeGetPoint']) {
                            // Awayで勝った場合、'W'を設定
                            $result_status = 'W';
                            // Awayで勝った場合、勝ち点3を設定
                            $result_point = 3;
                        } elseif ($results_data['AwayGetPoint'] == $results_data['HomeGetPoint']) {
                            // Awayで引き分けの場合、'D'を設定
                            $result_status = 'D';
                            // Awayで引き分けの場合、勝ち点1を設定
                            $result_point = 1;
                        } elseif ($results_data['AwayGetPoint'] < $results_data['HomeGetPoint']) {
                            // Awayで負けの場合、'L'を設定
                            $result_status = 'L';
                            // Awayで負けの場合、勝ち点0を設定
                            $result_point = 0;
                        }

                        // Awayの場合、文字'H'を格納
                        $result_home_and_away = 'A';

                        // 勝ち点の合計を格納
                        $result_sum_point = $result_sum_point + $result_point;

                        // 試合数の合計を格納
                        $result_sum_play = $result_sum_play + 1;
                    }

                    // 総得点のDB保存用変数初期化
                    $result_sum_score = null;
                    // 総失点のDB保存用変数初期化
                    $result_sum_lost_score = null;
                    // 得失点差のDB保存用変数初期化
                    $result_sum_goal_difference = null;
                    // 総得点数(TotalGoalScore)、総失点数(TotalLostGoalScore)の集計
                    if (!empty($result_home_and_away) && $result_home_and_away == 'H') {
                        // 総得点を集計
                        $result_total_goal_score = $result_total_goal_score + $results_data['HomeGetPoint'];
                        // 総失点数を集計
                        $result_total_lost_score = $result_total_lost_score + $results_data['AwayGetPoint'];

                        // 総得点をDB保存用変数へ設定
                        $result_sum_score = $result_total_goal_score;
                        // 総失点をDB保存用変数へ設定
                        $result_sum_lost_score = $result_total_lost_score;
                    } else if (!empty($result_home_and_away) && $result_home_and_away == 'A') {
                        // 総得点を集計
                        $result_total_goal_score = $result_total_goal_score + $results_data['AwayGetPoint'];
                        // 総失点数を集計
                        $result_total_lost_score = $result_total_lost_score + $results_data['HomeGetPoint'];

                        // 総得点をDB保存用変数へ設定
                        $result_sum_score = $result_total_goal_score;
                        // 総失点をDB保存用変数へ設定
                        $result_sum_lost_score = $result_total_lost_score;
                    }

                    // 得失点差を集計の集計しDB保存用変数へ設定
                    $result_sum_goal_difference = $result_sum_score - $result_sum_lost_score;

                    // 節数による試合結果集計データのDB登録用変数へ格納
                    $match_results_data[$team_id][$results_data['MatchNum']] = [
                        // 節数を格納
                        'MatchNum' => $results_data['MatchNum'],
                        // ホームチーム名を格納
                        'HomeTeam' => $results_data['HomeTeam'],
                        // アウェイチーム名を格納
                        'AwayTeam' => $results_data['AwayTeam'],
                        // 試合数を格納
                        'Matchday'.$results_data['MatchNum'].'Played' => $result_sum_play,
                        // 試合結果を格納
                        'Matchday'.$results_data['MatchNum'].'Result'  => $result_status,
                        // 試合結果(勝ち点)を格納
                        'Matchday'.$results_data['MatchNum'].'ResultPoint'  => $result_point,
                        // 試合結果(勝ち点の合計)を格納
                        'Matchday'.$results_data['MatchNum'].'ResultSumPoint'  => $result_sum_point,
                        // 総得点数を格納
                        'Matchday'.$results_data['MatchNum'].'TotalGoalScore' => $result_sum_score,
                        // 総失点数を格納
                        'Matchday'.$results_data['MatchNum'].'TotalLostGoalScore' => $result_sum_lost_score,
                        // 得失点差を格納
                        'Matchday'.$results_data['MatchNum'].'GoalDifference' => $result_sum_goal_difference,
                        // 試合結果(Home or Away)を格納
                        'Matchday'.$results_data['MatchNum'].'HomeAndAway'  => $result_home_and_away,
                        // ホームチームの得点数を格納
                        'HomeGetPoint' => $results_data['HomeGetPoint'],
                        // アウェイチームの得点数を格納
                        'AwayGetPoint' => $results_data['AwayGetPoint'],
                    ];
                }
            }
            // ログへ試合結果登録・更新準備完了メッセージ保存
            Log::info($this->season_year.'シーズン'.$this->conf_val['category'].'試合結果データ準備::::完了', 'jleague_historical_matchdata');

            // ログへ試合結果登録・更新処理開始メッセージ保存
            Log::info($this->season_year.'シーズン'.$this->conf_val['category'].'試合結果DB登録・更新処理::::開始', 'jleague_historical_matchdata');

            // J1試合結果登録用テーブルのModel呼び出し
            $jleagedmatchresults = TableRegistry::get($this->conf_val['TableMatchResults']);

            // 1件ずつデータをチェックし保存
            foreach ($match_results_data as $query_data){
                // チームごとに試合結果データ取得
                $team_results_data = $jleagedmatchresults->getTeamResutls($query_data['id']);
                if (empty($team_results_data)) {
                    // ログへ試合結果データ取得失敗メッセージ保存
                    Log::info($this->season_year.'シーズン'.$this->conf_val['category'].'試合結果データ取得処理::::False::::: id = '.$query_data['id'], 'jleague_historical_matchdata');

                    // データ取得に失敗した場合、スキップ
                    continue;
                }

                // 登録・更新予定のデータとテーブルのデータ比較
                foreach ($team_results_data as $table_data) {
                    // 節数ごとにチェックし新規登録、更新
                    for ($match_num = 1; $match_num <= 34; $match_num++) {
                        // 登録・更新予定データ格納用配列の初期化
                        $update_data = array();

                        // Playedカラムの登録対象データチェック
                        if ($table_data['Matchday'.$match_num.'Played'] !== $query_data[$match_num]['Matchday'.$match_num.'Played']) {
                            // update対象データ設定
                            $update_data = array_merge($update_data, array('Matchday'.$match_num.'Played' => $query_data[$match_num]['Matchday'.$match_num.'Played']));
                        }

                        // Resultカラムの登録対象データチェック
                        if ($table_data['Matchday'.$match_num.'Result'] != $query_data[$match_num]['Matchday'.$match_num.'Result']) {
                            // update対象データ設定
                            $update_data = array_merge($update_data, array('Matchday'.$match_num.'Result' => $query_data[$match_num]['Matchday'.$match_num.'Result']));
                        }

                        // ResultPointカラムの登録対象データチェック
                        if ($table_data['Matchday'.$match_num.'ResultPoint'] !== $query_data[$match_num]['Matchday'.$match_num.'ResultPoint']) {
                            // update対象データ設定
                            $update_data = array_merge($update_data, array('Matchday'.$match_num.'ResultPoint' => $query_data[$match_num]['Matchday'.$match_num.'ResultPoint']));
                        }

                        // ResultSumPointカラムの登録対象データチェック
                        if ($table_data['Matchday'.$match_num.'ResultSumPoint'] !== $query_data[$match_num]['Matchday'.$match_num.'ResultSumPoint']) {
                            // update対象データ設定
                            $update_data = array_merge($update_data, array('Matchday'.$match_num.'ResultSumPoint' => $query_data[$match_num]['Matchday'.$match_num.'ResultSumPoint']));
                        }

                        // TotalGoalScoreカラムの登録対象データチェック
                        if ($table_data['Matchday'.$match_num.'TotalGoalScore'] !== $query_data[$match_num]['Matchday'.$match_num.'TotalGoalScore']) {
                            // update対象データ設定
                            $update_data = array_merge($update_data, array('Matchday'.$match_num.'TotalGoalScore' => $query_data[$match_num]['Matchday'.$match_num.'TotalGoalScore']));
                        }

                        // TotalLostGoalScoreカラムの登録対象データチェック
                        if ($table_data['Matchday'.$match_num.'TotalLostGoalScore'] !== $query_data[$match_num]['Matchday'.$match_num.'TotalLostGoalScore']) {
                            // update対象データ設定
                            $update_data = array_merge($update_data, array('Matchday'.$match_num.'TotalLostGoalScore' => $query_data[$match_num]['Matchday'.$match_num.'TotalLostGoalScore']));
                        }

                        // GoalDifferenceカラムの登録対象データチェック
                        if ($table_data['Matchday'.$match_num.'GoalDifference'] !== $query_data[$match_num]['Matchday'.$match_num.'GoalDifference']) {
                            // update対象データ設定
                            $update_data = array_merge($update_data, array('Matchday'.$match_num.'GoalDifference' => $query_data[$match_num]['Matchday'.$match_num.'GoalDifference']));
                        }

                        // HomeAndAwayカラムの登録対象データチェック
                        if ($table_data['Matchday'.$match_num.'HomeAndAway'] != $query_data[$match_num]['Matchday'.$match_num.'HomeAndAway']) {
                            // update対象データ設定
                            $update_data = array_merge($update_data, array('Matchday'.$match_num.'HomeAndAway' => $query_data[$match_num]['Matchday'.$match_num.'HomeAndAway']));
                        }

                        // アップデート対象データが存在するかチェック
                        if (!empty($update_data)) {
                            // J1試合結果登録用テーブル更新チェックフラグの初期化
                            $chk_table_registry_flg = false;

                            // アップデート対象データが存在する場合、updateクエリ実行
                            $chk_table_registry_flg = $jleagedmatchresults->saveResultsData($update_data, $query_data['id']);
                            if ($chk_table_registry_flg == false) {
                                // データ更新に失敗した場合、ログへ試合結果データ更新失敗メッセージ保存
                                Log::info($this->season_year.'シーズン'.$this->conf_val['category'].'試合結果データ更新処理::::FALSE::::: id = '.$query_data['id'].', $match_num = '.$match_num, 'jleague_historical_matchdata');

                                // データ更新に失敗した場合、スキップ
                                continue;
                            } else {
                                // ログへ試合結果登録・更新完了メッセージ保存
                                Log::info($this->season_year.'シーズン'.$this->conf_val['category'].'試合結果データ更新完了::::: id = '.$query_data['id'].', $match_num = '.$match_num, 'jleague_historical_matchdata');
                            }
                        } else { // DBと更新対象データが同じ場合
                            // updateクエリ実行しない場合、メッセージ保存
                            Log::info($this->season_year.'シーズン'.$this->conf_val['category'].'試合結果データ更新処理:::::試合結果データは同じため登録・更新処理スキップ:::::id = '.$query_data['id'].', $match_num = '.$match_num, 'jleague_historical_matchdata');
                        }
                    }
                }
            }
            // ログへ試合結果登録・更新処理終了メッセージ保存
            Log::info($this->season_year.'シーズン'.$this->conf_val['category'].'試合結果DB登録・更新処理::::終了', 'jleague_historical_matchdata');

            // ログへ試合結果登録・更新処理開始メッセージ保存
            Log::info($this->season_year.'シーズン'.$this->conf_val['category'].'試合結果登録・更新処理::::End', 'jleague_historical_matchdata');

            // 処理終了
            exit();
        }

        /*
         * 試合順位データ集計、新規登録・更新処理
         */
        public function RegistrationMatchRank() {
            /**** 順位データの集計処理 start ****/
            // ログへ試合結果登録・更新処理開始メッセージ保存
            Log::info($this->season_year.'シーズン'.$this->conf_val['category'].'順位データの登録・更新処理::::Start', 'jleague_historical_matchdata');

            // Jリーグ試合結果登録用テーブルのModel呼び出し
            $jleagedmatchresults = TableRegistry::get($this->conf_val['TableMatchResults']);

            // DB保存用順位を含めたチームデータ格納用配列初期化
            $rank_data = array();
            // 節数指定変数初期化
            $target_match_num = 0;
            // 各節ごとに順位を登録・更新
            for ($target_match_num = 1; $target_match_num <= 34; $target_match_num++) {
                // 各節ごとの試合結果データを取得
                $target_results_data = $jleagedmatchresults->getMatchResultsTargetMatchNum($target_match_num);
                // 参照用として配列へ変換
                $target_results_data_array = $target_results_data->toArray();

                // 順位格納用変数初期化
                $rank_num = 1;
                // 順位チェック
                foreach ($target_results_data_array as $results_data) {
                    // 順位決定条件1に当てはまるチームデータ格納用変数初期化
                    $results_data_condition1 = array();
                    // 順位決定条件2に当てはまるチームデータ格納用変数初期化
                    $results_data_condition2 = array();
                    // 順位決定条件3に当てはまるチームデータ格納用変数初期化
                    $results_data_condition3 = array();

                    // 順位決定条件1:勝ち点をチェック
                    if (!is_null($results_data['Matchday'.$target_match_num.'ResultSumPoint'])) {
                        // 順位決定条件1:勝ち点をチェック
                        for ($num = 0; $num <= 17; $num++) {
                            // 対象のチームと他チームの勝ち点を比較
                            if ($results_data['Matchday'.$target_match_num.'ResultSumPoint'] == $target_results_data_array[$num]['Matchday'.$target_match_num.'ResultSumPoint']
                             && $results_data['id'] != $target_results_data_array[$num]['id'] ) {
                                // 勝ち点が同値のチームを抽出
                                $results_data_condition1[] = $target_results_data_array[$num];
                            }
                        }
                    } else {
                        // 順位決定条件1:勝ち点をチェック失敗メッセージ保存
                        Log::info($this->season_year.'シーズン'.$this->conf_val['category'].'順位データの登録・更新処理::::Rankデータ決定条件1:勝ち点をチェックのデータ異常::::TeamID = '.$results_data['id'].'::::MatchNum = '.$target_match_num, 'jleague_historical_matchdata');
                        // スキップ
                        break;
                    }

                    // 順位決定条件2:得失点差をチェック
                    if (!empty($results_data_condition1)) {
                        for ($num = 0; $num < count($results_data_condition1); $num++) {
                            // 対象のチームと他チームの得失点差を比較
                            if ($results_data['Matchday'.$target_match_num.'GoalDifference'] == $results_data_condition1[$num]['Matchday'.$target_match_num.'GoalDifference']
                                && $results_data['id'] != $results_data_condition1[$num]['id'] ) {
                                    // 勝ち点が同じ、かつ、得失点差も同じチームデータを抽出
                                    $results_data_condition2[] = $results_data_condition1[$num];
                                }
                        }
                    }

                    // 順位決定条件3:総得点をチェック
                    if (!empty($results_data_condition2)) {
                        // debug($results_data_condition2);
                        for ($num = 0; $num < count($results_data_condition2); $num++) {
                            if ($results_data['Matchday'.$target_match_num.'TotalGoalScore'] == $results_data_condition2[$num]['Matchday'.$target_match_num.'TotalGoalScore']
                                && $results_data['id'] != $results_data_condition2[$num]['id'] ) {
                                    // 勝ち点が同じ、得失点差も同じ、かつ、総得点も同じチームデータを抽出
                                    $results_data_condition3[] = $results_data_condition2[$num];
                                }
                        }
                    }

                    // 順位決定条件4:当該チーム間の対戦成績（イ：勝点、ロ：得失点差、ハ：総得点数）をチェック(未実装:現在は同率順位で設定)
                    if (!empty($results_data_condition3)) {

                        if (!empty($rank_data['Matchday'.$target_match_num])) {
                            foreach ($rank_data['Matchday'.$target_match_num] as $check_rank_data) {
                                for ($num = 0; $num < count($results_data_condition3); $num++) {
                                    if ($check_rank_data['id'] == $results_data_condition3[$num]['id']) {
                                        // 同率順位がすでに設定されているチームが存在している場合、同率順位を設定
                                        $rank_num = $check_rank_data['Matchday'.$target_match_num.'Rank'];
                                    }
                                }
                            }
                        }

                        // 順位を設定
                        $rank_data['Matchday'.$target_match_num][] = [
                            'id' => $results_data->id,
                            'Matchday'.$target_match_num.'Rank' => $rank_num,
                            'MatchNum' => $target_match_num,
                        ];

                        // 同率順位のチーム数だけ順位数を進める
                        $rank_num = $rank_num + count($results_data_condition3) + 1;
                    } else {
                        // 同率順位のチームが存在しない場合ｌ、順位を設定
                        $rank_data['Matchday'.$target_match_num][] = [
                            'id' => $results_data->id,
                            'Matchday'.$target_match_num.'Rank' => $rank_num,
                            'MatchNum' => $target_match_num,
                        ];

                        $rank_num = $rank_num + 1;
                    }
                }
            }
            // ログへ順位データ登録・更新処理完了メッセージ保存
            Log::info($this->season_year.'シーズン'.$this->conf_val['category'].'順位データの集計準備完了', 'jleague_historical_matchdata');
            /**** 順位データの集計処理 end ****/

            /**** 順位データの登録・更新処理 start ****/
            // ログへ順位データ登録・更新処理完了メッセージ保存
            Log::info($this->season_year.'シーズン'.$this->conf_val['category'].'順位データの登録・更新開始', 'jleague_historical_matchdata');

            // 登録・更新対象の節数格納用変数初期化
            $target_match_num = 0;
            // 各節ごとに順位を登録・更新
            foreach ($rank_data as $data) {
                // 登録・更新対象の節数更新
                $target_match_num = $target_match_num + 1;

                // 1件ずつデータをチェックし保存
                for ($num = 0; $num < J1LEAGE_ALL_TEAM_NUM; $num++) { // 18チーム分更新
                    // 登録・更新対象のRankデータ取得
                    $db_team_result_data = $jleagedmatchresults->getMatchRank($target_match_num, $data[$num]['id']);
                    if (empty($db_team_result_data)) {
                        // ログへ登録・更新対象のRankデータ取得に失敗したメッセージ保存
                        Log::info($this->season_year.'シーズン'.$this->conf_val['category'].'順位データ登録・更新処理:::::Rankデータnullのためスキップ::::id = '.$data[$num]['id'].'MatchNum = '.$data[$num]['MatchNum'], 'jleague_historical_matchdata');

                        // データ取得に失敗した場合、スキップ
                        continue;
                    }
                    // 取得した登録・更新対象のRankデータを配列へ変換
                    $db_team_result_data_array = $db_team_result_data->toArray();

                    // テーブルのRankカラム値と更新予定のRankデータが同じかどうかチェック
                    if ($db_team_result_data_array[0]['id'] == $data[$num]['id']
                     && $db_team_result_data_array[0]['Matchday'.$target_match_num.'Rank'] == $data[$num]['Matchday'.$target_match_num.'Rank']) {
                         // テーブルのRankカラム値と更新予定のRankデータが同じ場合、同値のメッセージ保存
                        Log::info($this->season_year.'シーズン'.$this->conf_val['category'].'順位データ登録・更新処理:::::Rankデータ同値のためupdateなし::::MatchNum = '.$data[$num]['MatchNum'].' id = '.$data[$num]['id'], 'jleague_historical_matchdata');

                        // テーブルのRankカラム値と更新予定のRankデータが同じ場合、updateは行わずスキップ
                        continue;
                    }

                    // チームidを指定しupdateクエリ実行を実行
                    $update_rank_data_flg = $jleagedmatchresults->saveRankData($target_match_num, $data[$num]['Matchday'.$target_match_num.'Rank'], $data[$num]['id']);
                    if (empty($update_rank_data_flg)) {
                        // ログへ登録・更新対象のRankデータのupdateクエリ実行失敗メッセージ保存
                        Log::info($this->season_year.'シーズン'.$this->conf_val['category'].'順位データ登録・更新処理:::::Rankデータupdateに失敗しました::::MatchNum = '.$data[$num]['MatchNum'].' id = '.$data[$num]['id'].' rank = '.$data[$num]['Matchday'.$target_match_num.'Rank'], 'jleague_historical_matchdata');

                        // データ更新に失敗した場合、スキップ
                        continue;
                    } else {
                        // ログへ登録・更新対象のRankデータのupdateクエリが正常に実行された場合のメッセージ保存
                        Log::info($this->season_year.'シーズン'.$this->conf_val['category'].'順位データ登録・更新処理:::::Rankデータupdate完了::::MatchNum = '.$data[$num]['MatchNum'].' id = '.$data[$num]['id'].' rank = '.$data[$num]['Matchday'.$target_match_num.'Rank'], 'jleague_historical_matchdata');
                    }
                }
            }
            /**** 順位データの登録・更新処理 end ****/

            // ログへ順位データ登録・更新処理完了メッセージ保存
            Log::info($this->season_year.'シーズン'.$this->conf_val['category'].'順位データの登録・更新処理::::End', 'jleague_historical_matchdata');

            // 処理終了
            exit();
        }

        public function view() {
            //something...
        }
}