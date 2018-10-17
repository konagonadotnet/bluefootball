<?php
    namespace App\Model\Table;

    use Cake\ORM\Table;
    // logファイル出力のため
    use Cake\Log\Log;

    class JleageD1Matchdata2009Table extends Table {

        public function initialize(array $config) {
            $this->setTable('jleage_d1_matchdata2009');
            $this->setPrimaryKey('id');
        }

        /**** Select文発行 start ****/
        /*
         * 全データ取得メソッド
         */
        public function getMatchScheduleNoArray() {
            // Select文実行
            $query_data = $this->find();
            if($query_data->count() == 0) {
                // データが取得できなかった場合
                return false;
            }

            return $query_data;
        }

        /*
         * idカラム値指定によるデータ取得クエリ実行メソッド
         */
        public function getTableData($id) {
            // idによるデータ取得
            $query_data = $this->get($id);

            // 取得したデータを返す
            return $query_data;
        }

        /*
         * 略称チーム名指定による全データ取得メソッド
         */
        public function getConfigTeamMatchData($short_team_name) {
            // Select文実行
            $query_data = $this->find()
                ->where(['HomeTeam' => $short_team_name])
                ->orWhere(['AwayTeam' => $short_team_name])
                ->all();
            if($query_data->count() == 0) {
                // データが取得できなかった場合
                return false;
            }

            return $query_data;
        }

        /*
         * ホームチーム名、アウェイチーム名取得メソッド
         */
        public function getTeamNameData($match_num) {
            // Select文実行
            $query_data = $this->find()
                ->select([
                    'HomeTeam', // ホームチーム名
                    'AwayTeam', // アウェイチーム名
                ])
                ->where(['MatchNum' => $match_num])
                ->all();
            if($query_data->count() == 0) {
                // データが取得できなかった場合
                return false;
            }

            return $query_data;
        }

        /*
         * Divisionカラム、MatchNumカラム、HomeTeamカラム、AwayTeamカラム値指定によるidカラム値取得クエリ実行メソッド
         */
        public function checkDeta($data) {
            // データ有無チェックフラグを初期化
            $check_id = 0;

            // Select文実行
            $query_data = $this->find()
                ->select(['id'])
                ->where(['Division' => $data['Division']])
                ->where(['MatchNum' => $data['MatchNum']])
                ->where(['HomeTeam' => $data['HomeTeam']])
                ->where(['AwayTeam' => $data['AwayTeam']])
                ->all();

            // 取得したデータ件数により有無を判定
            if($query_data->count() != 0) {
                // データが存在する場合、objectから配列へ変換しidを取得
                $query_data_array = $query_data->toArray();
                $check_id = $query_data_array[0]['id'];
            }

            return $check_id;
        }

        /*
         * 現在日時に対して試合が実施されていない節を取得
         */
        public function getMatchNumNotPlayGame($today) {
            // Select文実行
            $query_data = $this->find()
                ->select(['MatchNum'])
                ->where(['MatchDay >=' => $today])
                ->where(['MatchDayTime IS NOT NULL'])
                ->where(['HomeGetPoint IS NULL'])
                ->where(['AwayGetPoint IS NULL'])
                ->group(['MatchNum'])
                ->all();
            if($query_data->count() == 0) {
                // データが取得できなかった場合
                return false;
            }

            return $query_data;
        }
        /**** Select文発行 end ****/

        /**** update文、insert文発行 start ****/
        /*
         * 新規データ登録メソッド
         */
        public function saveNewData($data) {
            // Entityオブジェクトに変換
            $save_data_article = $this->newEntity($data);

            // データが存在しない場合、DBへ保存
            if($this->save($save_data_article) == false) {
                // DBへの保存が失敗した場合
                return false;
            } else {
                // DBへの保存に成功した場合、idを返す
                return $save_data_article->id;
            }
        }

        /*
         * 既存データ更新メソッド
         */
        public function updateData($query_data, $data) {
            // Entityオブジェクトにマージして変換
            $update_data_article = $this->patchEntity($query_data, $data);
            // DBのデータを上書き保存
            if ($this->save($update_data_article) == false) {
                // DBへの保存が失敗した場合
                return false;
            } else {
                // DBへの保存に成功した場合、idを返す
                return $update_data_article->id;
            }
        }
        /**** update文、insert文発行 end ****/
    }
