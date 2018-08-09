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
                            debug('id = '.$query_data['id'].'::::$matchday_num = '.$matchday_num.'::::カラムが存在しません');
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
                            debug('id = '.$query_data['id'].'::::$matchday_num = '.$matchday_num.'::::試合結果データupdateに失敗しました');
                            // データ更新に失敗した場合、スキップ
                            continue;
                        } else {
                            // データ更新した場合
                            debug('id = '.$query_data['id'].'::::$matchday_num = '.$matchday_num.'::::試合結果データupdate完了');
                        }
                    } else {
                        // updateクエリ実行しない場合(DBと更新対象データが同じ場合)
                        debug('id = '.$query_data['id'].'::::$matchday_num = '.$matchday_num.'::::試合結果データは同じ');
                    }
                }
            }
        }
    }

    public function registerRankData($rank_data) {
        // debug($rank_data);
        // exit();

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
                debug('JleageD1MatchResults2018Table :::: MatchNum = '.$target_match_num.' :::: Matchday'.$target_match_num.'Rankカラムが存在しないためupdateクエリ実行スキップ');
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
                    // ->where(['Matchday'.$target_match_num.'Rank' => $data[$num]['Matchday'.$target_match_num.'Rank']])
                    // ->where(['Matchday'.$target_match_num.'Played IS NOT NULL'])
                    ->all();
                if (empty($db_team_result_data)) {
                    // データ取得に失敗した場合、スキップ
                    continue;
                }
                // 取得した登録・更新対象のRankデータを配列へ変換
                $db_team_result_data_array = $db_team_result_data->toArray();
                if (empty($db_team_result_data_array)) {
                    debug('id = '.$data[$num]['id'].'::::MatchNum = '.$data[$num]['MatchNum'].'::::Rankデータnullのためupdateなし');
                    // Rankデータがnullの場合、スキップ
                    continue;
                }

                // テーブルのRankカラム値と更新予定のRankデータが同じかどうかチェック
                if ($db_team_result_data_array[0]['id'] == $data[$num]['id']
                 && $db_team_result_data_array[0]['Matchday'.$target_match_num.'Rank'] == $data[$num]['Matchday'.$target_match_num.'Rank']) {
                     debug('id = '.$data[$num]['id'].'::::MatchNum = '.$data[$num]['MatchNum'].'::::Rankデータ同値のためupdateなし');
                    // テーブルのRankカラム値と更新予定のRankデータが同じ場合、updateは行わずスキップ
                    continue;
                }

                // チームidを指定しupdateクエリ実行を実行
                $update_query_result = $this->query()->update()
                    ->set(['Matchday'.$target_match_num.'Rank' => $data[$num]['Matchday'.$target_match_num.'Rank']])
                    ->where(['id' => $data[$num]['id']])
                    ->execute();
                if (empty($update_query_result)) {
                    debug('id = '.$data[$num]['id'].'::::MatchNum = '.$data[$num]['MatchNum'].'::::Rankデータupdateに失敗しました');
                    // データ更新に失敗した場合、スキップ
                    continue;
                } else {
                    // データ更新した場合
                    debug('id = '.$data[$num]['id'].'::::MatchNum = '.$data[$num]['MatchNum'].'::::Rankデータupdate完了');
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
