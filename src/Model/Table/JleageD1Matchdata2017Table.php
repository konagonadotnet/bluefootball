<?php
    namespace App\Model\Table;

    use Cake\ORM\Table;
    // logファイル出力のため
    use Cake\Log\Log;

    class JleageD1Matchdata2017Table extends Table {
        public function initialize(array $config)
        {
            $this->setTable('jleage_d1_matchdata2017');
            $this->setPrimaryKey('id');
        }

        public function registerData($jleaguehistoricalmatchdata) {
            // ログへ登録処理開始メッセージ保存
            Log::info('2017シーズンJ1試合日程データ登録処理::::Start', 'jleague_historical_matchdata');

            // 1件ずつデータをチェックし保存
            foreach($jleaguehistoricalmatchdata as $historical_data) {
                // データチェック
                $check_id = $this->checkDeta($historical_data); // $check_id:0(新規)、0以外(既に存在)
                if ($check_id == 0) { // 新規登録
                    // ログへメッセージ保存
                    Log::info('DBのデータ存在チェック::::データが存在しません。新規登録開始 $check_id = '.$check_id, 'jleague_historical_matchdata');

                    // データの検証 (バリデーション)
                    $save_data = $this->newEntity($historical_data);
                    // データが存在しない場合、DBへ保存
                    $this->save($save_data);

                    if ($this->save($save_data)) {
                        // 正常に保存された場合、登録結果をログへ保存
                        Log::info('DBのデータ存在チェック::::新規登録成功', 'jleague_historical_matchdata');
                    } else {
                        // 保存に失敗した場合、結果をログへ保存
                        Log::info('DBのデータ存在チェック::::新規登録失敗', 'jleague_historical_matchdata');
                    }
                } else { // データ比較、更新
                    // ログへメッセージ保存
                    Log::info('DBのデータ存在チェック::::データはすでに存在します。DBとの比較開始 $check_id = '.$check_id, 'jleague_historical_matchdata');

                    // データが存在する場合、$idからエンティティーを取得
                    $query_matchdata = $this->get($check_id); // データがなければ、NotFoundExceptionが投げられる
                    // objectから配列へ変換
                    $query_matchdata_array = $query_matchdata->toArray();

                    // DBから取得した試合日格納用変数
                    $matchday_data = null;
                    // DBから取得した試合日を変数へ格納
                    if (!empty($query_matchdata_array['MatchDay'])) {
                        // 試合日時が取得できている場合、試合日時を変数へ格納
                        $matchday_data = $query_matchdata_array['MatchDay']->format('Y-m-d');
                    } else {
                        // 試合日時が取得できていない場合、カラを変数へ格納
                        $matchday_data = $query_matchdata_array['MatchDay'];
                    }

                    // DBから取得した試合日時格納用変数
                    $matchday_time_data = null;
                    // DBから取得した試合日時を変数へ格納
                    if (!empty($query_matchdata_array['MatchDayTime'])) {
                        // 試合日時が取得できている場合、試合日時を変数へ格納
                        $matchday_time_data = $query_matchdata_array['MatchDayTime']->format('Y-m-d H:i:s');
                    } else {
                        // 試合日時が取得できていない場合、カラを変数へ格納
                        $matchday_time_data = $query_matchdata_array['MatchDayTime'];
                    }

                    // 公式ページから取得したデータとDBから取得した値を比較
                    if ($historical_data['MatchDay'] != $matchday_data
                        || $historical_data['MatchDayTime'] != $matchday_time_data
                        || $historical_data['ShortStadiumName'] != $query_matchdata_array['ShortStadiumName']
                        || $historical_data['HomeTeam'] != $query_matchdata_array['HomeTeam']
                        || $historical_data['AwayTeam'] != $query_matchdata_array['AwayTeam']
                        || $historical_data['HomeGetPoint'] != $query_matchdata_array['HomeGetPoint']
                        || $historical_data['AwayGetPoint'] != $query_matchdata_array['AwayGetPoint']
                        || $historical_data['Division'] != $query_matchdata_array['Division']
                        || $historical_data['MatchNum'] != $query_matchdata_array['MatchNum']
                        ) {
                            // 値が1つでも異なる場合、比較結果をログへ保存
                            Log::info('Webページから取得したデータとDBから取得した値を比較::::データが異なります', 'jleague_historical_matchdata');

                            // POSTされたリクエストデータを、バリデーションして、エンティティーにマージ
                            $this->patchEntity($query_matchdata, $historical_data);
                            // 値が1つでも異なる場合、DBへ上書き保存
                            if ($this->save($query_matchdata)) {
                                // 保存成功した場合、メッセージをログへ保存
                                Log::info('Webページから取得したデータとDBから取得した値を比較::::データの上書き成功 $check_id = '.$check_id, 'jleague_historical_matchdata');
                            } else {
                                // 保存失敗した場合、メッセージをログへ保存
                                Log::info('Webページから取得したデータとDBから取得した値を比較::::データの上書き失敗 $check_id = '.$check_id, 'jleague_historical_matchdata');
                            }
                    } else {
                        // 比較結果をログへ保存
                        Log::info('Webページから取得したデータとDBから取得した値を比較::::データは同じため登録処理をスキップ $check_id = '.$check_id, 'jleague_historical_matchdata');
                    }
                    // ログへメッセージ保存
                    Log::info('DBのデータ存在チェック::::データはすでに存在します。DBとの比較終了 $check_id = '.$check_id, 'jleague_historical_matchdata');
                }
            }
            // ログへメッセージ保存
            Log::info('2017シーズンJ1試合日程データ登録処理::::End', 'jleague_historical_matchdata');
        }

        public function getMatchSchedule() {
            // Select文実行
            $query_data = $this->find();
            $query_data_all = $query_data->all();
            if($query_data_all->count() == 0) {
                // データが取得できなかった場合
                return false;
            }

            return $query_data_all->toArray();
        }

        public function getMatchScheduleNoArray() {
            // Select文実行
            $query_data = $this->find();
            if($query_data->count() == 0) {
                // データが取得できなかった場合
                return false;
            }

            return $query_data;
        }

        public function getMatchScheduleSetToday($today) {
            // Select文実行
            $query_data = $this->find()
                ->where(['MatchDay >=' => $today])
                ->where(['MatchDayTime IS NOT NULL'])
                ->order(['MatchDayTime' => 'ASC']);
            if($query_data->count() == 0) {
                // データが取得できなかった場合
                return false;
            }

            return $query_data;
        }

        // 現在日時に対して試合が実施されていない節を取得
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

        // 現在日時以降で節数に対する未実施の試合数を取得
        public function getUnexecutedGameNum($matchnum, $today) {
            // Select文実行
            $query_data = $this->find()
                ->where(['MatchNum' => $matchnum])
                ->where(['MatchDay IS NOT NULL'])
                ->where(['MatchDayTime + interval 90 minute >=' => $today]) // 試合中のデータは取得しない
                ->count();
            if(empty($query_data)) {
                // データが取得できなかった場合
                return false;
            }

            return $query_data;
        }

        private function checkDeta($data) {
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
    }
