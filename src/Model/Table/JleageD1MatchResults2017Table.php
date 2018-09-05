<?php
    namespace App\Model\Table;

    use Cake\ORM\Table;
    // logファイル出力のため
    use Cake\Log\Log;

    class JleageD1MatchResults2017Table extends Table {

        public function initialize(array $config) {
            $this->setTable('jleage_d1_match_results2017');
            $this->setPrimaryKey('id');
        }

        public function registerResutlsData($match_results_data) {
            // 1件ずつデータをチェックし保存
            foreach ($match_results_data as $query_data){
                // チームごとに試合結果データ取得
                $team_results_data = $this->getTeamResutls($query_data['id']);
                if (empty($team_results_data)) {
                    // ログへ試合結果データ取得失敗メッセージ保存
                    Log::info('2018シーズンJ1試合結果データ取得処理::::False::::: id = '.$query_data['id'], 'jleague_historical_matchdata');

                    // データ取得に失敗した場合、スキップ
                    continue;
                }

                // 登録・更新予定のデータとテーブルのデータ比較
                foreach ($team_results_data as $table_data) {
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
                            // アップデート対象データが存在する場合、updateクエリ実行
                            $update_query_result = $this->query()->update()
                                ->set($update_data)
                                ->where(['id' => $query_data['id']])
                                ->execute();
                            if (empty($update_query_result)) {
                                // データ更新に失敗した場合、ログへ試合結果データ更新失敗メッセージ保存
                                Log::info('2017シーズンJ1試合結果データ更新処理::::FALSE::::: id = '.$query_data['id'].', $match_num = '.$match_num, 'jleague_historical_matchdata');

                                // データ更新に失敗した場合、スキップ
                                continue;
                            } else {
                                // データ更新した場合、ログへ試合結果データ更新失敗メッセージ保存
                                Log::info('2017シーズンJ1試合結果データ更新完了::::: id = '.$query_data['id'].', $match_num = '.$match_num, 'jleague_historical_matchdata');
                            }
                        } else { // DBと更新対象データが同じ場合
                            // updateクエリ実行しない場合、メッセージ保存
                            Log::info('2017シーズンJ1試合結果データ更新処理:::::試合結果データは同じため登録・更新処理スキップ:::::id = '.$query_data['id'].', $match_num = '.$match_num, 'jleague_historical_matchdata');
                        }
                    }
                }
            }

            return true;
        }

        public function registerRankData($rank_data) {
            // ログへ順位データ登録・更新処理開始メッセージ保存
            Log::info('2017シーズンJ1順位データ登録・更新処理:::::Start', 'jleague_historical_matchdata');

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
                    Log::info('2018シーズンJ1順位データ登録・更新処理:::::FALSE::::Rankカラムが存在しないためupdateクエリ実行スキップ::::MatchNum = '.$target_match_num.' Matchday'.$target_match_num, 'jleague_historical_matchdata');

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
                        Log::info('2017シーズンJ1順位データ登録・更新処理:::::Rankデータnullのためスキップ::::id = '.$data[$num]['id'].'MatchNum = '.$data[$num]['MatchNum'], 'jleague_historical_matchdata');

                        // Rankデータがnullの場合、スキップ
                        continue;
                    }

                    // テーブルのRankカラム値と更新予定のRankデータが同じかどうかチェック
                    if ($db_team_result_data_array[0]['id'] == $data[$num]['id']
                     && $db_team_result_data_array[0]['Matchday'.$target_match_num.'Rank'] == $data[$num]['Matchday'.$target_match_num.'Rank']) {
                         // ログへ登録・更新対象のRankデータが同値のメッセージ保存
                         Log::info('2017シーズンJ1順位データ登録・更新処理:::::Rankデータ同値のためupdateなし::::MatchNum = '.$data[$num]['MatchNum'].' id = '.$data[$num]['id'], 'jleague_historical_matchdata');

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
                        Log::info('2017シーズンJ1順位データ登録・更新処理:::::Rankデータupdateに失敗しました::::MatchNum = '.$data[$num]['MatchNum'].' id = '.$data[$num]['id'], 'jleague_historical_matchdata');

                        // データ更新に失敗した場合、スキップ
                        continue;
                    } else {
                        // ログへ登録・更新対象のRankデータのupdateクエリが正常に実行された場合のメッセージ保存
                        Log::info('2017シーズンJ1順位データ登録・更新処理:::::Rankデータupdate完了::::MatchNum = '.$data[$num]['MatchNum'].' id = '.$data[$num]['id'], 'jleague_historical_matchdata');
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
    }
