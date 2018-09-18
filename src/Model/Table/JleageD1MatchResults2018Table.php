<?php
    namespace App\Model\Table;

    use Cake\ORM\Table;
    // logファイル出力のため
    use Cake\Log\Log;

    class JleageD1MatchResults2018Table extends Table {

        public function initialize(array $config) {
            $this->setTable('jleage_d1_match_results2018');
            $this->setPrimaryKey('id');
        }

        public function registerResutlsData($data) {
            // 1件ずつデータをチェックし保存
            foreach ($data as $query_data){
                // チームごとの試合結果データ取得
                $team_results_data = $this->getTeamResutls($query_data['id']);
                if (empty($team_results_data)) {
                    // ログへ試合結果データ取得失敗メッセージ保存
                    Log::info('2018シーズンJ1試合結果データ取得処理::::False::::: id = '.$query_data['id'], 'simple_html_dom');

                    // データ取得に失敗した場合、スキップ
                    continue;
                }

                // keyを指定して要素を削除
                $target_data = array();
                $target_data = $query_data;
                unset($target_data['id'], $target_data['TeamName'], $target_data['ShortTeamName'], $target_data['Division']);

                // update対象データ格納用変数初期化
                $update_data = array();
                foreach ($team_results_data as $db_data) {
                    foreach ($target_data as $query_match_results_data) {
                        // 節数を格納
                        $matchday_num = $query_match_results_data['MatchNum'];

                        // updateクエリ実行フラグ初期化
                        $update_query_flg = false;
                        // dbから取得した値がnullかどうか判定、登録対象のデータ値がnullかどうか判定
                        if ((is_null($db_data['Matchday'.$matchday_num.'Played']) // 試合数カラムのチェック
                         || is_null($db_data['Matchday'.$matchday_num.'Result']) // 試合結果カラムのチェック
                         || is_null($db_data['Matchday'.$matchday_num.'ResultPoint']) // 勝ち点カラムのチェック
                         || is_null($db_data['Matchday'.$matchday_num.'ResultSumPoint']) // 勝ち点合計カラムのチェック
                         || is_null($db_data['Matchday'.$matchday_num.'TotalGoalScore']) // 総得点カラムのチェック
                         || is_null($db_data['Matchday'.$matchday_num.'TotalLostGoalScore']) // 総失点カラムのチェック
                         || is_null($db_data['Matchday'.$matchday_num.'GoalDifference']) // 得失点差カラムのチェック
                         || is_null($db_data['Matchday'.$matchday_num.'HomeAndAway'])) // ホームorアウェイ判定カラムのチェック
                         && (!is_null($query_match_results_data['Matchday'.$matchday_num.'Result']) // 新データの試合結果チェック
                         && !is_null($query_match_results_data['Matchday'.$matchday_num.'ResultPoint']) // 新データの勝ち点チェック
                         && !is_null($query_match_results_data['Matchday'.$matchday_num.'ResultSumPoint']) // 新データの勝ち点合計チェック
                         && !is_null($query_match_results_data['Matchday'.$matchday_num.'TotalGoalScore']) // 新データの総得点チェック
                         && !is_null($query_match_results_data['Matchday'.$matchday_num.'TotalLostGoalScore']) // 新データの総失点チェック
                         && !is_null($query_match_results_data['Matchday'.$matchday_num.'GoalDifference']) // 新データの得失点差チェック
                         && !is_null($query_match_results_data['Matchday'.$matchday_num.'HomeAndAway']))) { // 新データのホームorアウェイ判定チェック
                             // dbから取得した値がどれか1つでもnull、かつ、登録対象データがnullでない場合、updateクエリ実行(新規登録)
                            $update_query_flg = true;
                        } else if ($query_match_results_data['Matchday'.$matchday_num.'Played'] != $db_data['Matchday'.$matchday_num.'Played']
                         || $query_match_results_data['Matchday'.$matchday_num.'Result'] != $db_data['Matchday'.$matchday_num.'Result']
                         || $query_match_results_data['Matchday'.$matchday_num.'ResultPoint'] != $db_data['Matchday'.$matchday_num.'ResultPoint']
                         || $query_match_results_data['Matchday'.$matchday_num.'ResultSumPoint'] != $db_data['Matchday'.$matchday_num.'ResultSumPoint']
                         || $query_match_results_data['Matchday'.$matchday_num.'TotalGoalScore'] != $db_data['Matchday'.$matchday_num.'TotalGoalScore']
                         || $query_match_results_data['Matchday'.$matchday_num.'TotalLostGoalScore'] != $db_data['Matchday'.$matchday_num.'TotalLostGoalScore']
                         || $query_match_results_data['Matchday'.$matchday_num.'GoalDifference'] != $db_data['Matchday'.$matchday_num.'GoalDifference']
                         || $query_match_results_data['Matchday'.$matchday_num.'HomeAndAway']!= $db_data['Matchday'.$matchday_num.'HomeAndAway']) {
                             // dbから取得した値がnullでない、かつ、登録対象データと異なる場合、updateクエリ実行
                            $update_query_flg = true;
                        }

                        // DBのカラム存在チェック
                        if ($update_query_flg === true) {
                            // DBのカラムが存在しているかチェック
                            if ($this->hasField('Matchday'.$matchday_num.'Played') == false
                             || $this->hasField('Matchday'.$matchday_num.'Result') == false
                             || $this->hasField('Matchday'.$matchday_num.'ResultPoint') == false
                             || $this->hasField('Matchday'.$matchday_num.'ResultSumPoint') == false
                             || $this->hasField('Matchday'.$matchday_num.'TotalGoalScore') == false
                             || $this->hasField('Matchday'.$matchday_num.'TotalLostGoalScore') == false
                             || $this->hasField('Matchday'.$matchday_num.'GoalDifference') == false
                             || $this->hasField('Matchday'.$matchday_num.'HomeAndAway') == false ) {
                                 // データ更新に失敗した場合、ログへカラムが存在しないメッセージ保存
                                 Log::info('2018シーズンJ1試合結果データ更新処理::::FALSE::::カラムが存在しません::::: id = '.$query_data['id'].', $matchday_num = '.$matchday_num, 'simple_html_dom');

                                // DBのカラムが存在しない場合、updateクエリ実行スキップ
                                continue;
                            }

                            // update対象データ設定
                            $update_data = [
                                'Matchday'.$matchday_num.'Played' => $query_match_results_data['Matchday'.$matchday_num.'Played'],
                                'Matchday'.$matchday_num.'Result' => $query_match_results_data['Matchday'.$matchday_num.'Result'],
                                'Matchday'.$matchday_num.'ResultPoint' => $query_match_results_data['Matchday'.$matchday_num.'ResultPoint'],
                                'Matchday'.$matchday_num.'ResultSumPoint' => $query_match_results_data['Matchday'.$matchday_num.'ResultSumPoint'],
                                'Matchday'.$matchday_num.'TotalGoalScore' => $query_match_results_data['Matchday'.$matchday_num.'TotalGoalScore'],
                                'Matchday'.$matchday_num.'TotalLostGoalScore' => $query_match_results_data['Matchday'.$matchday_num.'TotalLostGoalScore'],
                                'Matchday'.$matchday_num.'GoalDifference' => $query_match_results_data['Matchday'.$matchday_num.'GoalDifference'],
                                'Matchday'.$matchday_num.'HomeAndAway' => $query_match_results_data['Matchday'.$matchday_num.'HomeAndAway'],
                            ];

                            // updateクエリ実行
                            $update_query_result = $this->query()->update()
                                ->set($update_data)
                                ->where(['id' => $query_data['id']])
                                ->execute();
                            if (empty($update_query_result)) {
                                // データ更新に失敗した場合、ログへ試合結果データ更新失敗メッセージ保存
                                Log::info('2018シーズンJ1試合結果データ更新処理::::FALSE::::: id = '.$query_data['id'].', $matchday_num = '.$matchday_num, 'simple_html_dom');

                                // データ更新に失敗した場合、スキップ
                                continue;
                            } else {
                                // データ更新した場合、ログへ試合結果データ更新失敗メッセージ保存
                                Log::info('2018シーズンJ1試合結果データ更新完了::::: id = '.$query_data['id'].', $matchday_num = '.$matchday_num, 'simple_html_dom');
                            }
                        } else {
                            // updateクエリ実行しない場合(DBと更新対象データが同じ場合)
                            Log::info('2018シーズンJ1試合結果データ更新処理:::::試合結果データは同じため登録・更新処理スキップ:::::id = '.$query_data['id'].', $matchday_num = '.$matchday_num, 'simple_html_dom');
                        }
                    }
                }
            }
        }

        public function registerRankData($rank_data) {
//            debug($rank_data);
//            exit();

            // ログへ順位データ登録・更新処理開始メッセージ保存
            Log::info('2018シーズンJ1順位データ登録・更新処理:::::Start', 'simple_html_dom');

            // 順位データが設定されているかチェック
            if (empty($rank_data)) {
                // 順位データがカラの場合、falseを返す
                return false;
            }

            // 登録・更新対象の節数格納用変数初期化
            $target_match_num = 0;
            // 各節ごとに順位を登録・更新
            foreach ($rank_data as $data) {
                // 登録・更新対象の節数更新
                $target_match_num = $target_match_num + 1;

                // DBのカラムが存在しているかチェック
                if ($this->hasField('Matchday'.$target_match_num.'Rank') == false) {
                    // ログへ順位データ登録・更新処理開始メッセージ保存
                    Log::info('2018シーズンJ1順位データ登録・更新処理:::::FALSE::::Rankカラムが存在しないためupdateクエリ実行スキップ::::MatchNum = '.$target_match_num.' Matchday'.$target_match_num, 'simple_html_dom');

                    // DBのカラムが存在しない場合、updateクエリ実行スキップ
                    continue;
                }

                // 1件ずつデータをチェックし保存
                for ($num = 0; $num < J1LEAGE_ALL_TEAM_NUM; $num++) { // 18チーム分更新
                    // 登録・更新対象のRankデータ取得
                    $db_team_result_data = $this->find()
                        ->select([
                            'id', // チームID
                            'Matchday'.$target_match_num.'Rank', // 順位
                        ])
                        ->where(['id' => $data[$num]['id']])
                        ->all();
                    if (empty($db_team_result_data)) {
                        // データ取得に失敗した場合、スキップ
                        continue;
                    }
                    // 取得した登録・更新対象のRankデータを配列へ変換
                    $db_team_result_data_array = $db_team_result_data->toArray();
                    if (empty($db_team_result_data_array)) {
                        // ログへ登録・更新対象のRankデータ取得に失敗したメッセージ保存
                        Log::info('2018シーズンJ1順位データ登録・更新処理:::::Rankデータnullのためスキップ::::id = '.$data[$num]['id'].'MatchNum = '.$data[$num]['MatchNum'], 'simple_html_dom');

                        // Rankデータがnullの場合、スキップ
                        continue;
                    }

                    // テーブルのRankカラム値と更新予定のRankデータが同じかどうかチェック
                    if ($db_team_result_data_array[0]['id'] == $data[$num]['id']
                     && $db_team_result_data_array[0]['Matchday'.$target_match_num.'Rank'] == $data[$num]['Matchday'.$target_match_num.'Rank']) {
                         // ログへ登録・更新対象のRankデータが同値のメッセージ保存
                         Log::info('2018シーズンJ1順位データ登録・更新処理:::::Rankデータ同値のためupdateなし::::MatchNum = '.$data[$num]['MatchNum'].' id = '.$data[$num]['id'], 'simple_html_dom');

                        // テーブルのRankカラム値と更新予定のRankデータが同じ場合、updateは行わずスキップ
                        continue;
                    }

                    // チームidを指定しupdateクエリ実行を実行
                    $update_query_result = $this->query()->update()
                        ->set(['Matchday'.$target_match_num.'Rank' => $data[$num]['Matchday'.$target_match_num.'Rank']])
                        ->where(['id' => $data[$num]['id']])
                        ->execute();
                    if (empty($update_query_result)) {
                        // ログへ登録・更新対象のRankデータのupdateクエリ実行失敗メッセージ保存
                        Log::info('2018シーズンJ1順位データ登録・更新処理:::::Rankデータupdateに失敗しました::::MatchNum = '.$data[$num]['MatchNum'].' id = '.$data[$num]['id'], 'simple_html_dom');

                        // データ更新に失敗した場合、スキップ
                        continue;
                    } else {
                        // ログへ登録・更新対象のRankデータのupdateクエリが正常に実行された場合のメッセージ保存
                        Log::info('2018シーズンJ1順位データ登録・更新処理:::::Rankデータupdate完了::::MatchNum = '.$data[$num]['MatchNum'].' id = '.$data[$num]['id'], 'simple_html_dom');
                    }
                }
            }

            // update処理が終了した場合、tureを返す
            return true;
        }

        public function getTeamResutls($id) {
            // Select文実行
            $query_data = $this->find()->where(['id' => $id])->all();
            if($query_data->count() == 0) {
                // データが取得できなかった場合
                return false;
            }

            return $query_data->toArray();
        }

        public function getAllResutlsData() {
            // Select文実行
            $query_data = $this->find()->all();
            if($query_data->count() == 0) {
                // データが取得できなかった場合
                return false;
            }

            return $query_data;
        }

        /*
         * 節数指定による順位データ取得メソッド
         */
        public function getMatchResultsTargetMatchNum($target_match_num) {
            // 各節ごとの試合結果データを取得
            $query_data = $this->find()
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
            if($query_data->count() == 0) {
                // データが取得できなかった場合
                return false;
            }

            // 配列で返す
            return $query_data;
        }
    }
