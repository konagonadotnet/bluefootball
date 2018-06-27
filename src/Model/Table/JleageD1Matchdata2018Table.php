<?php
    namespace App\Model\Table;

    use Cake\ORM\Table;
    // logファイル出力のため
    use Cake\Log\Log;

    class JleageD1Matchdata2018Table extends Table {
        public function initialize(array $config)
        {
            $this->setTable('jleage_d1_matchdata2018');
            $this->setPrimaryKey('id');
        }

        public function registerData($key, $data) {
            Log::info('J1試合日程データ登録処理開始', 'simple_html_dom');

            // 1件ずつデータをチェックし保存
            foreach($data as $element_data) {
                // データチェック
                $check_id = $this->checkDeta($element_data);
// debug($check_id);
                if ($check_id == 0) {
                    Log::info('DBのデータ存在チェック::::データが存在しません。新規登録開始 $check_id = '.$check_id, 'simple_html_dom');
// debug($element_data);
                    // データの検証 (バリデーション)
                    $save_data = $this->newEntity($element_data);
                    // データが存在しない場合、DBへ保存
                    $this->save($save_data);

                    if ($this->save($save_data)) {
                        // 登録結果をログへ保存
                        Log::info('DBのデータ存在チェック::::新規登録成功', 'simple_html_dom');
                    } else {
                        // 登録結果をログへ保存
                        Log::info('DBのデータ存在チェック::::新規登録失敗', 'simple_html_dom');
                    }
                } else {
                    Log::info('DBのデータ存在チェック::::データはすでに存在します。DBとの比較開始 $check_id = '.$check_id, 'simple_html_dom');

                    // データが存在する場合、$idからエンティティーを取得
                    $query_matchdata = $this->get($check_id); // データがなければ、NotFoundExceptionが投げられる。
// debug($query_matchdata);
                    // objectから配列へ変換
                    $query_matchdata_array = $query_matchdata->toArray();
// debug($query_matchdata_array);

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
// debug($matchday_time_data);

                    // Webページから取得したデータとDBから取得した値を比較
                    if ($element_data['MatchDay'] != $matchday_data
                        || $element_data['MatchDayTime'] != $matchday_time_data
                        || $element_data['ShortStadiumName'] != $query_matchdata_array['ShortStadiumName']
                        || $element_data['HomeTeam'] != $query_matchdata_array['HomeTeam']
                        || $element_data['AwayTeam'] != $query_matchdata_array['AwayTeam']
                        || $element_data['HomeGetPoint'] != $query_matchdata_array['HomeGetPoint']
                        || $element_data['AwayGetPoint'] != $query_matchdata_array['AwayGetPoint']
                        || $element_data['Division'] != $query_matchdata_array['Division']
                        || $element_data['MatchNum'] != $query_matchdata_array['MatchNum']
                        ) {
// debug('データが異なります');
                            // 比較結果をログへ保存
                            Log::info('Webページから取得したデータとDBから取得した値を比較::::データが異なります', 'simple_html_dom');

                            // POSTされたリクエストデータを、バリデーションして、エンティティーにマージ
                            $this->patchEntity($query_matchdata, $element_data);
                            // DBへ上書き保存
                            if ($this->save($query_matchdata)) {
// debug('保存成功');
                                // 保存成功した場合、メッセージをログへ保存
                                Log::info('Webページから取得したデータとDBから取得した値を比較::::データの上書き成功 $check_id = '.$check_id, 'simple_html_dom');
                            } else {
                                // 保存失敗した場合、メッセージをログへ保存
                                Log::info('Webページから取得したデータとDBから取得した値を比較::::データの上書き失敗 $check_id = '.$check_id, 'simple_html_dom');
                            }
                    } else {
// debug('DBと同じデータです');
                        // 比較結果をログへ保存
                        Log::info('Webページから取得したデータとDBから取得した値を比較::::データは同じため登録処理をスキップ $check_id = '.$check_id, 'simple_html_dom');
                    }
                    Log::info('DBのデータ存在チェック::::データはすでに存在します。DBとの比較終了 $check_id = '.$check_id, 'simple_html_dom');
                }
                Log::info('J1試合日程データ登録処理終了', 'simple_html_dom');
            }
        }

        public function getMatchSchedule() {
            debug('Model:getMatchSchedule');

            // Select文実行
            $query_data = $this->find();
            $query_data_all = $query_data->all();
            if($query_data_all->count() == 0) {
                // データが取得できなかった場合
                return false;
            }
            debug($query_data_all->toArray());
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

        private function checkDeta($data) {
            // データ有無チェックフラグ
            $check_id = 0;

            // Select文実行
            $query_data = $this->find()
                ->select(['id'])
                ->where(['Division' => $data['Division']])
                ->where(['MatchNum' => $data['MatchNum']])
                ->where(['HomeTeam' => $data['HomeTeam']])
                ->where(['AwayTeam' => $data['AwayTeam']])
                ->all();
// debug($query_data);
// debug($query_data->toArray());

            // 取得したデータ件数により有無を判定
            if($query_data->count() != 0) {
                // データが存在する場合、objectから配列へ変換しidを取得
                $query_data_array = $query_data->toArray();
                $check_id = $query_data_array[0]['id'];
            }

            return $check_id;
        }
    }