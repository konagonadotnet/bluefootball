<?php
    namespace App\Model\Table;

    use Cake\ORM\Table;
    // logファイル出力のため
    use Cake\Log\Log;

    class JleageD1MatchResults2015Table extends Table {

        public function initialize(array $config) {
            $this->setTable('jleage_d1_match_results2015');
            $this->setPrimaryKey('id');
        }

        /*
         * チームid指定によるデータ取得メソッド
         */
        public function getTeamResutls($id) {
            // Select文実行
            $query_data = $this->find()->where(['id' => $id])->all();
            if($query_data->count() == 0) {
                // データが取得できなかった場合
                return false;
            }

            // 配列で返す
            return $query_data->toArray();
        }

        /*
         * チームid指定によるデータ登録メソッド
         */
        public function saveResultsData($save_data, $id) {
            // DBのデータを上書き保存
            $query_data = $this->query()->update()
                ->set($save_data)
                ->where(['id' => $id])
                ->execute();
            if ($query_data == false) {
                // DBへの保存が失敗した場合
                return false;
            } else {
                // DBへの保存に成功した場合
                return true;
            }
        }

        /*
         * 節数指定によるデータ取得メソッド
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

        /*
         * 節数、チームID指定による順位データ取得メソッド
         */
        public function getMatchRank($target_match_num, $id) {
            $query_data = $this->find()
                ->select([
                    'id', // チームID
                    'Matchday'.$target_match_num.'Rank', // 順位
                ])
                ->where(['id' => $id])
                ->all();
            if($query_data->count() == 0) {
                // データが取得できなかった場合
                return false;
            }

            // 配列で返す
            return $query_data;
        }

        /*
         * チームid指定による順位データ登録メソッド
         */
        public function saveRankData($target_match_num, $rank, $id) {
            // DBのデータを上書き保存
            $query_data = $this->query()->update()
                ->set(['Matchday'.$target_match_num.'Rank' => $rank])
                ->where(['id' => $id])
                ->execute();
            if ($query_data == false) {
                // DBへの保存が失敗した場合
                return false;
            } else {
                // DBへの保存に成功した場合
                return $query_data;
            }
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
