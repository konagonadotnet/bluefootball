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

    use Cake\Datasource\ConnectionManager;

    use Cake\View\Helper\UrlHelper;
    use Cake\Routing\Router;

    class MatchResultsGraphController extends AppController {
        /*
         public $paginate = [
            'limit' => 18,
            'order' => [
                'MatchSchedule.id' => 'desc'
            ]
         ];
         */

        public function initialize() {
            parent::initialize();
            // コンポーネントの呼び出し
            // $this->loadComponent('Paginator');
            $this->loadComponent('RequestHandler');

            // Seasonフィルターの設定値
            $this->season_filter = array(
                "2018" => [
                    'season' => 2018,
                    'value' => './match-results-graph',
                    'text' => '2018',
                    'selected' => false,
                ],
                "2017" => [
                    'season' => 2017,
                    'value' => './match-results-graph?season=2017',
                    'text' => '2017',
                    'selected' => false,
                ],
                "2016" => [
                    'season' => 2016,
                    'value' => './match-results-graph?season=2016',
                    'text' => '2016',
                    'selected' => false,
                ],
            );

            // Model呼び出しをインスタンス化
            if ($this->request->getQuery('season') == null || $this->request->getQuery('season') == 2018) { // get取得
                // JleageD1Matchdata2018テーブルを呼び出しインスタンス化
                $this->JleageMatchdata = TableRegistry::get('JleageD1Matchdata2018');
                $this->JleageMatchResults = TableRegistry::get('JleageD1MatchResults2018');

                // Seasonフィルターの選択済み設定値をtrueへ変更
                $this->season_filter[2018]['selected'] = true;
            } else if ($this->request->getQuery('season') == 2017) { // get取得
                // JleageD1Matchdata2017テーブルを呼び出しインスタンス化
                $this->JleageMatchdata = TableRegistry::get('JleageD1Matchdata2017');
                $this->JleageMatchResults = TableRegistry::get('JleageD1MatchResults2017');

                // Seasonフィルターの選択済み設定値をtrueへ変更
                $this->season_filter[2017]['selected'] = true;
            } else if ($this->request->getQuery('season') == 2016) { // get取得
                // JleageD1Matchdata2016テーブルを呼び出しインスタンス化
                $this->JleageMatchdata = TableRegistry::get('JleageD1Matchdata2016');
                $this->JleageMatchResults = TableRegistry::get('JleageD1MatchResults2016');

                // Seasonフィルターの選択済み設定値をtrueへ変更
                $this->season_filter[2016]['selected'] = true;
            }
        }

        /*
         * シーズン指定による順位グラフと順位表を表示
         */
        public function index() {
            // 現在日時を取得
            $today = date("Y/m/d H:i");

            // Seasonフィルターの設定値を格納
            $season_filter = $this->season_filter;

            // データ取得
            $results_data = $this->JleageMatchResults->getAllResutlsData();
            // 取得したデータを配列へ変換
            $graph_data =  $results_data->toArray();

            // 各節数に対して未実施の試合が存在する節数を取得
            $target_match_num = $this->JleageMatchdata->getMatchNumNotPlayGame($today);
            if (!empty($target_match_num)) {
                // 次の試合が開催される節数格納用変数初期化
                $target_anker_id = 0;
                // 次の試合が開催される節数を取得
                foreach ($target_match_num as $num) {
                    // 未実施の試合が存在する節に対して未実施の試合数を取得
                    $count_not_play_game_num = $this->JleageMatchdata->getUnexecutedGameNum($num->MatchNum, $today);
                    if ($count_not_play_game_num == J1LEAGE_MATCHNUM_NUMBER_OF_GAME) {
                        // 未実施の試合が存在する節に対して全ての試合が未実施の場合、次の試合が開催される最新の節数として設定
                        $target_anker_id = $num->MatchNum;
                        // ループを中断し抜ける
                        break;
                    }
                }
                // 次の試合が開催される節数を設定
                $graph_data['NextMatchNum'] = $target_anker_id;

                // 試合が終了している最新の節数を設定
                $target_match_num = $target_anker_id - 1;
            } else {
                // 次の試合が開催される節数を設定
                $graph_data['NextMatchNum'] = J1LEAGE_2018_MAX_MATCHNUM_NUMBER_OF_GAME;

                // 試合が終了している最新の節数を設定
                $target_match_num = J1LEAGE_2018_MAX_MATCHNUM_NUMBER_OF_GAME;
            }

            // J1試合結果データをグラフ表示用データをviewへ渡す
            $this->set('results_data', $graph_data);
            // JSONへ変換
            $this->set('_serialize', ['results_data']);

            // 試合が終了している最新の節数に対する順位データを取得
            $table_data = $this->JleageMatchResults->getMatchResultsTargetMatchNum($target_match_num);

            // $table_dataのオブジェクト要素名を各変数へ格納
            $table_data_key_name_played = 'Matchday'.$target_match_num.'Played';
            $table_data_key_name_result = 'Matchday'.$target_match_num.'Result';
            $table_data_key_name_resultpoint = 'Matchday'.$target_match_num.'ResultPoint';
            $table_data_key_name_resultsumpoint = 'Matchday'.$target_match_num.'ResultSumPoint';
            $table_data_key_name_totalgoalscore = 'Matchday'.$target_match_num.'TotalGoalScore';
            $table_data_key_name_totallostgoalscore = 'Matchday'.$target_match_num.'TotalLostGoalScore';
            $table_data_key_name_goaldifference = 'Matchday'.$target_match_num.'GoalDifference';
            $table_data_key_name_homeandaway = 'Matchday'.$target_match_num.'HomeAndAway';

            // $table_dataのオブジェクト要素名をviewへ渡す
            $this->set(compact(
                'table_data_key_name_played',
                'table_data_key_name_result',
                'table_data_key_name_resultpoint',
                'table_data_key_name_resultsumpoint',
                'table_data_key_name_totalgoalscore',
                'table_data_key_name_totallostgoalscore',
                'table_data_key_name_goaldifference',
                'table_data_key_name_homeandaway'
            ));
            // 順位表表示時点の節数をviewへ渡す
            $this->set('target_match_num', $target_match_num);
            // 順位表のデータをviewへ渡す
            $this->set('table_data', $table_data);
            // シーズン選択ドロップダウン設定値をviewへ渡す
            $this->set(compact('season_filter'));
        }

        // 試合結果DB登録・更新用メソッド
        public function RegistrationMatchResults() {
            // 現在日時を取得
            $today = date("Y/m/d H:i");

            /**** 試合結果登録・更新処理 start ****/
            // ログへ試合結果登録・更新処理開始メッセージ保存
            Log::info('2018シーズンJ1試合結果登録・更新処理::::Start', 'simple_html_dom');

            // J1試合日程データのModel呼び出し
            $jleaged1matchdata2018 = TableRegistry::get('JleageD1Matchdata2018'); // 2017-2018シーズン用テーブル
            // 試合日時データ取得
            $match_schedule_paginate_data = $jleaged1matchdata2018->getMatchScheduleNoArray();
            if (empty($match_schedule_paginate_data)) {
                // ログへ試合結果登録・更新処理開始メッセージ保存
                Log::info('2018シーズンJ1試合結果DB登録・更新処理::::日程データが取得できませんでした', 'simple_html_dom');
            }
            // 取得した試合日時データをオブジェクトから連想配列に変換
            $match_schedule_data = $match_schedule_paginate_data->toArray();

            // 試合開催予定の節数を全て取得
            $target_match_num = $jleaged1matchdata2018->getMatchNumNotPlayGame($today);
            // 次の試合が開催される節数格納用変数初期化
            $target_anker_id = 0;
            // 次の試合が開催される節数を取得
            foreach ($target_match_num as $num) {
                // 節数に対する未実施の試合数を取得
                $count_not_play_game_num = $jleaged1matchdata2018->getUnexecutedGameNum($num->MatchNum, $today);
                if ($count_not_play_game_num == J1LEAGE_MATCHNUM_NUMBER_OF_GAME) {
                    // 節数に対して全ての試合が未実施の場合、次の試合が開催される最新の節数として設定
                    $target_anker_id = $num->MatchNum;
                    // ループを中断し抜ける
                    break;
                }
            }
            // 試合が終了済みの節数を設定
            $target_end_match_num = $target_anker_id - 1;

            // J1チームデータModel呼び出し
            $jleageteams = TableRegistry::get('JleageTeams');
            // チーム名データ取得
            $teams_data = $jleageteams->getJleageTeams();
            if (empty($teams_data)) {
                // ログへ試合結果登録・更新処理開始メッセージ保存
                Log::info('2018シーズンJ1試合結果DB登録・更新処理::::チーム名が取得できませんでした', 'simple_html_dom');
            }

            // 試合結果集計データのDB登録用変数初期化
            $match_results_data = array();
            // 各チームごとの試合データ抽出結果格納用変数初期化
            $match_results_teams_data = array();
            // 試合結果登録用データ収集
            foreach ($teams_data as $team) {
                // idをキーに設定しidを格納
                $match_results_data[$team['id']]['id'] = $team['id'];
                // idをキーに設定しTeamNameを格納
                $match_results_data[$team['id']]['TeamName'] = $team['TeamName'];
                // TeamNameを格納
                $match_results_data[$team['id']]['ShortTeamName'] = $team['ShortTeamName'];

                // 各チームの試合データ抽出
                $match_results_teams_data[$team['id']] = $jleaged1matchdata2018->find()
                                                            ->where(['HomeTeam' => $team['ShortTeamName']])
                                                            ->orWhere(['AwayTeam' => $team['ShortTeamName']])
                                                            ->all(); // HomeTeam = '鳥栖' or AwayTeam = '鳥栖'
            }

            foreach ($teams_data as $team) {
                // 勝ち点の合計格納用変数初期化
                $result_sum_point = 0;
                // 総得点数集計用変数初期化
                $result_total_goal_score = 0;
                // 総失点数集計用変数初期化
                $result_total_lost_score = 0;
                // 試合数集計用変数初期化
                $result_sum_play = 0;
                foreach ($match_results_teams_data[$team['id']] as $results_data) {
                    // ディビジョンを設定
                    $match_results_data[$team['id']]['Division'] = $results_data['Division'];

                    // 試合結果格納用変数初期化
                    $result_status = null;
                    $result_point = null;
                    $result_home_and_away = null;
                    // Home or Awayを判定し試合結果を取得(勝ち:W(3点)、引き分け:D(1点)、負け(0点):L)
                    if ($match_results_data[$team['id']]['ShortTeamName'] == $results_data['HomeTeam'] && !is_null($results_data['HomeGetPoint']) && !is_null($results_data['AwayGetPoint'])) {
                        // Homeの場合
                        if ($results_data['HomeGetPoint'] > $results_data['AwayGetPoint']) {
                            // Homeで勝った場合、'W'を設定
                            $result_status = 'W';
                            // Homeで勝った場合、勝ち点3を設定
                            $result_point = 3;
                        } elseif ($results_data['HomeGetPoint'] == $results_data['AwayGetPoint']) {
                            // Homeで引き分けの場合、'D'を設定
                            $result_status = 'D';
                            // Homeで引き分けの場合、勝ち点1を設定
                            $result_point = 1;
                        } elseif ($results_data['HomeGetPoint'] < $results_data['AwayGetPoint']) {
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
                    } else if ($match_results_data[$team['id']]['ShortTeamName'] == $results_data['AwayTeam'] && !is_null($results_data['HomeGetPoint']) && !is_null($results_data['AwayGetPoint'])) {
                        // Awayの場合
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

                    // 勝ち点の合計値をnullで設定するかどうか判定
                    if (is_null($result_point) && $target_end_match_num < $results_data['MatchNum']) {
                        // 勝ち点がnull(試合結果がない)の場合、勝ち点の合計値もnullで設定
                        $result_sum_val = null;
                        // 試合がまだの場合、nullを設定
                        $result_sum_play_val = null;
                    } else {
                        // 試合が実施済みの場合、勝ち点の合計値を設定(悪天候などにより試合が実施されなかった場合は前節の値)
                        $result_sum_val = $result_sum_point;
                        // 試合数を設定
                        $result_sum_play_val = $result_sum_play;
                    }

                    // 総得点のDB保存用変数初期化
                    $result_sum_score = null;
                    // 総失点のDB保存用変数初期化
                    $result_sum_lost_score = null;
                    // 得失点差のDB保存用変数初期化
                    $result_sum_goal_difference = null;
                    // 総得点数(TotalGoalScore)、総失点数(TotalLostGoalScore)、得失点差の集計
                    if (!empty($result_home_and_away)) {
                        if ($result_home_and_away == 'H') {
                            // 総得点を集計
                            $result_total_goal_score = $result_total_goal_score + $results_data['HomeGetPoint'];
                            // 総失点数を集計
                            $result_total_lost_score = $result_total_lost_score + $results_data['AwayGetPoint'];

                            // 総得点をDB保存用変数へ設定
                            $result_sum_score = $result_total_goal_score;
                            // 総失点をDB保存用変数へ設定
                            $result_sum_lost_score = $result_total_lost_score;
                        } else if ($result_home_and_away == 'A') {
                            // 総得点を集計
                            $result_total_goal_score = $result_total_goal_score + $results_data['AwayGetPoint'];
                            // 総失点数を集計
                            $result_total_lost_score = $result_total_lost_score + $results_data['HomeGetPoint'];

                            // 総得点をDB保存用変数へ設定
                            $result_sum_score = $result_total_goal_score;
                            // 総失点をDB保存用変数へ設定
                            $result_sum_lost_score = $result_total_lost_score;
                        }
                        // 得失点差を集計しDB保存用変数へ設定
                        $result_sum_goal_difference = $result_sum_score - $result_sum_lost_score;
                    } else {
                        // 悪天候などにより試合が実施されなかった場合、前節までの総得点数、総失点数を設定
                        if ($results_data['MatchNum'] <= $target_end_match_num) {
                            // 前節の総得点をDB保存用変数へ設定
                            $result_sum_score = $result_total_goal_score;
                            // 前節の総失点をDB保存用変数へ設定
                            $result_sum_lost_score = $result_total_lost_score;
                            // 得失点差を集計しDB保存用変数へ設定
                            $result_sum_goal_difference = $result_sum_score - $result_sum_lost_score;
                        }
                    }

                    // 節数による試合結果集計データのDB登録用変数へ格納
                    $match_results_data[$team['id']][$results_data['MatchNum']] = [
                        // 節数を格納
                        'MatchNum' => $results_data['MatchNum'],
                        // ホームチーム名を格納
                        'HomeTeam' => $results_data['HomeTeam'],
                        // アウェイチーム名を格納
                        'AwayTeam' => $results_data['AwayTeam'],
                        // 試合数を格納
                        'Matchday'.$results_data['MatchNum'].'Played' => $result_sum_play_val,
                        // 試合結果を格納
                        'Matchday'.$results_data['MatchNum'].'Result'  => $result_status,
                        // 試合結果(勝ち点)を格納
                        'Matchday'.$results_data['MatchNum'].'ResultPoint'  => $result_point,
                        // 試合結果(勝ち点の合計)を格納
                        'Matchday'.$results_data['MatchNum'].'ResultSumPoint'  => $result_sum_val,
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

            // J1試合結果登録用テーブルのModel呼び出し
            $jleaged1matchresults2018 = TableRegistry::get('JleageD1MatchResults2018');
            // データ登録
            $jleaged1matchresults2018->registerResutlsData($match_results_data);

            // ログへ試合結果登録・更新処理開始メッセージ保存
            Log::info('2018シーズンJ1試合結果登録・更新処理::::End', 'simple_html_dom');
            /**** 試合結果登録・更新処理 end ****/

            /**** 順位データの登録・更新処理 start ****/
            // ログへ試合結果登録・更新処理開始メッセージ保存
            Log::info('2018シーズンJ1順位データの登録・更新処理::::Start', 'simple_html_dom');

            // DB保存用順位を含めたチームデータ格納用配列初期化
            $rank_data = array();
            // 節数指定変数初期化
            $target_match_num = 0;
            // 各節ごとに順位を登録・更新
            for ($target_match_num = 1; $target_match_num <= $target_end_match_num; $target_match_num++) {
                // 各節ごとの試合結果データを取得
                $target_results_data = $jleaged1matchresults2018->find()
                    ->select([
                        'id', // チームID
                        'TeamName', // チーム名
                        'Matchday'.$target_match_num.'Rank', // 順位
                        'Matchday'.$target_match_num.'Played', // 試合数
                        'Matchday'.$target_match_num.'Result', // 試合結果
                        'Matchday'.$target_match_num.'ResultPoint', // 勝ち点
                        'Matchday'.$target_match_num.'ResultSumPoint', // 総勝ち点数
                        'Matchday'.$target_match_num.'TotalGoalScore', // 総ゴール数
                        'Matchday'.$target_match_num.'TotalLostGoalScore', //総失点数
                        'Matchday'.$target_match_num.'GoalDifference', // 得失点差
                        'Matchday'.$target_match_num.'HomeAndAway', // 節数に対するHome or Away
                    ])
                    // 順位決定条件1:勝ち点を降順でソート
                    ->order(['Matchday'.$target_match_num.'ResultSumPoint' => 'DESC'])
                    // 順位決定条件2:得失点差を降順でソート
                    ->order(['Matchday'.$target_match_num.'GoalDifference' => 'DESC'])
                    // 順位決定条件3:総得点を降順でソート
                    ->order(['Matchday'.$target_match_num.'TotalGoalScore' => 'DESC'])
                    // データ取得実行
                    ->all();
                // 参照用として配列へ変換
                $target_results_data_array = $target_results_data->toArray();

                // 試合が実施済みかどうかチェック
                foreach ($target_results_data_array as $data) {
                    // 試合結果カラムに値が設定されているかどうかチェック
                    if ($target_match_num != 1 && empty($data['Matchday'.$target_match_num.'Result'])) {
                        // 試合結果カラムに値がない場合、未実施として前節の結果データを取得
                        for ($target_end_game_match_num = $target_match_num; $target_end_game_match_num >= 1; $target_end_game_match_num--) {
                            $target_end_game_match_data = $jleaged1matchresults2018->find()
                                ->select([
                                    'id', // チームID
                                    'TeamName', // チーム名
                                    'Matchday'.$target_end_game_match_num.'Rank', // 順位
                                    'Matchday'.$target_end_game_match_num.'Played', // 試合数
                                    'Matchday'.$target_end_game_match_num.'Result', // 試合結果
                                    'Matchday'.$target_end_game_match_num.'ResultPoint', // 勝ち点
                                    'Matchday'.$target_end_game_match_num.'ResultSumPoint', // 総勝ち点数
                                    'Matchday'.$target_end_game_match_num.'TotalGoalScore', // 総ゴール数
                                    'Matchday'.$target_end_game_match_num.'TotalLostGoalScore', //総失点数
                                    'Matchday'.$target_end_game_match_num.'GoalDifference', // 得失点差
                                    'Matchday'.$target_end_game_match_num.'HomeAndAway', // 節数に対するHome or Away
                                ])
                                ->where(['id' => $data['id']])
                                // データ取得実行
                                ->all();
                            // 参照用として配列へ変換
                            $target_end_game_match_data_array = $target_end_game_match_data->toArray();

                            // 試合結果カラムに値が設定されているかどうかチェック
                            if (!empty($target_end_game_match_data_array[0]['Matchday'.$target_end_game_match_num.'Result'])) {
                                // 前節の結果データをDB登録用変数へ設定
                                for ($num = 0; $num < 18; $num++) {
                                    if ($target_results_data_array[$num]['id'] == $target_end_game_match_data_array[0]['id']) {
                                        // 試合数
                                        $target_results_data_array[$num]['Matchday'.$target_match_num.'Played'] = $target_end_game_match_data_array[0]['Matchday'.$target_end_game_match_num.'Played'];
                                        // ゴール数
                                        $target_results_data_array[$num]['Matchday'.$target_match_num.'TotalGoalScore'] = $target_end_game_match_data_array[0]['Matchday'.$target_end_game_match_num.'TotalGoalScore'];
                                        // 失点数
                                        $target_results_data_array[$num]['Matchday'.$target_match_num.'TotalLostGoalScore'] = $target_end_game_match_data_array[0]['Matchday'.$target_end_game_match_num.'TotalLostGoalScore'];
                                        // 得失点差
                                        $target_results_data_array[$num]['Matchday'.$target_match_num.'GoalDifference'] = $target_end_game_match_data_array[0]['Matchday'.$target_end_game_match_num.'GoalDifference'];
                                        // 勝ち点
                                        $target_results_data_array[$num]['Matchday'.$target_match_num.'ResultSumPoint'] = $target_end_game_match_data_array[0]['Matchday'.$target_end_game_match_num.'ResultSumPoint'];

                                        break;
                                    }
                                }
                                break;
                            }
                        }
                    }
                }

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
                            if ($results_data['Matchday'.$target_match_num.'ResultSumPoint'] == $target_results_data_array[$num]['Matchday'.$target_match_num.'ResultSumPoint']
                                && $results_data['id'] != $target_results_data_array[$num]['id'] ) {
                                    // 勝ち点が同じチームデータを抽出
                                    $results_data_condition1[] = $target_results_data_array[$num];
                                }
                        }
                    } else {
                        // 順位決定条件1:勝ち点をチェック失敗メッセージ保存
                        Log::info('2018シーズンJ1順位データの登録・更新処理::::Rankデータ決定条件1:勝ち点をチェックのデータ異常::::TeamID = '.$results_data['id'].'::::MatchNum = '.$target_match_num, 'simple_html_dom');
                    }

                    // 順位決定条件2:得失点差をチェック
                    if (!empty($results_data_condition1)) {
                        for ($num = 0; $num < count($results_data_condition1); $num++) {
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

            // 順位データ登録
            $rank_data_update_result = $jleaged1matchresults2018->registerRankData($rank_data);
            if ($rank_data_update_result == true) {
                // ログへ順位データ登録・更新処理完了メッセージ保存
                Log::info('2018シーズンJ1順位データの登録・更新処理::::End', 'simple_html_dom');
            } else {
                // ログへ順位データ登録・更新処理失敗メッセージ保存
                Log::info('2018シーズンJ1順位データの登録・更新処理::::FALSE::::End', 'simple_html_dom');
            }
            /**** 順位データの登録・更新処理 end ****/

            return true;
        }
    }