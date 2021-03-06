<?php
    namespace App\Controller;

    // model使用のため
    use Cake\ORM\TableRegistry;
    // DateTimeクラス使用のため
    use \DateTime;
    use \DateTimeZone;

    use Cake\Datasource\ConnectionManager;

    use Cake\View\Helper\UrlHelper;
    use Cake\Routing\Router;

    class MatchScheduleController extends AppController {

        public function initialize() {
            parent::initialize();

            // Seasonフィルターの設定値
            $this->season_filter = array(
                "2018" => [
                    'season' => 2018,
                    'value' => './',
                    'text' => '2018',
                    'selected' => false,
                ],
                "2017" => [
                    'season' => 2017,
                    'value' => './?season=2017',
                    'text' => '2017',
                    'selected' => false,
                ],
                "2016" => [
                    'season' => 2016,
                    'value' => './?season=2016',
                    'text' => '2016',
                    'selected' => false,
                ],
                "2015" => [
                    'season' => 2015,
                    'value' => './?season=2015',
                    'text' => '2015',
                    'selected' => false,
                ],
                "2014" => [
                    'season' => 2014,
                    'value' => './?season=2014',
                    'text' => '2014',
                    'selected' => false,
                ],
                "2013" => [
                    'season' => 2013,
                    'value' => './?season=2013',
                    'text' => '2013',
                    'selected' => false,
                ],
                "2012" => [
                    'season' => 2012,
                    'value' => './?season=2012',
                    'text' => '2012',
                    'selected' => false,
                ],
                "2011" => [
                    'season' => 2011,
                    'value' => './?season=2011',
                    'text' => '2011',
                    'selected' => false,
                ],
                "2010" => [
                    'season' => 2010,
                    'value' => './?season=2010',
                    'text' => '2010',
                    'selected' => false,
                ],
                "2009" => [
                    'season' => 2009,
                    'value' => './?season=2009',
                    'text' => '2009',
                    'selected' => false,
                ],
                "2008" => [
                    'season' => 2008,
                    'value' => './?season=2008',
                    'text' => '2008',
                    'selected' => false,
                ],
                "2007" => [
                    'season' => 2007,
                    'value' => './?season=2007',
                    'text' => '2007',
                    'selected' => false,
                ],
                "2006" => [
                    'season' => 2006,
                    'value' => './?season=2006',
                    'text' => '2006',
                    'selected' => false,
                ],
                "2005" => [
                    'season' => 2005,
                    'value' => './?season=2005',
                    'text' => '2005',
                    'selected' => false,
                ],
            );

            // Model呼び出しをインスタンス化
            if ($this->request->getQuery('season') == null || $this->request->getQuery('season') == 2018) { // get取得
                // JleageD1Matchdata2018テーブルを呼び出しインスタンス化
                $this->JleageMatchdata = TableRegistry::get('JleageD1Matchdata2018');

                // Seasonフィルターの選択済み設定値をtrueへ変更
                $this->season_filter[2018]['selected'] = true;
            } else if ($this->request->getQuery('season') == 2017) { // get取得
                // JleageD1Matchdata2017テーブルを呼び出しインスタンス化
                $this->JleageMatchdata = TableRegistry::get('JleageD1Matchdata2017');

                // Seasonフィルターの選択済み設定値をtrueへ変更
                $this->season_filter[2017]['selected'] = true;
            } else if ($this->request->getQuery('season') == 2016) { // get取得
                // JleageD1Matchdata2016テーブルを呼び出しインスタンス化
                $this->JleageMatchdata = TableRegistry::get('JleageD1Matchdata2016');

                // Seasonフィルターの選択済み設定値をtrueへ変更
                $this->season_filter[2016]['selected'] = true;
            } else if ($this->request->getQuery('season') == 2015) { // get取得
                // JleageD1Matchdata2015テーブルを呼び出しインスタンス化
                $this->JleageMatchdata = TableRegistry::get('JleageD1Matchdata2015');

                // Seasonフィルターの選択済み設定値をtrueへ変更
                $this->season_filter[2015]['selected'] = true;
            } else if ($this->request->getQuery('season') == 2014) { // get取得
                // JleageD1Matchdata2014テーブルを呼び出しインスタンス化
                $this->JleageMatchdata = TableRegistry::get('JleageD1Matchdata2014');

                // Seasonフィルターの選択済み設定値をtrueへ変更
                $this->season_filter[2014]['selected'] = true;
            } else if ($this->request->getQuery('season') == 2013) { // get取得
                // JleageD1Matchdata2013テーブルを呼び出しインスタンス化
                $this->JleageMatchdata = TableRegistry::get('JleageD1Matchdata2013');

                // Seasonフィルターの選択済み設定値をtrueへ変更
                $this->season_filter[2013]['selected'] = true;
            } else if ($this->request->getQuery('season') == 2012) { // get取得
                // JleageD1Matchdata2012テーブルを呼び出しインスタンス化
                $this->JleageMatchdata = TableRegistry::get('JleageD1Matchdata2012');

                // Seasonフィルターの選択済み設定値をtrueへ変更
                $this->season_filter[2012]['selected'] = true;
            } else if ($this->request->getQuery('season') == 2011) { // get取得
                // JleageD1Matchdata2011テーブルを呼び出しインスタンス化
                $this->JleageMatchdata = TableRegistry::get('JleageD1Matchdata2011');

                // Seasonフィルターの選択済み設定値をtrueへ変更
                $this->season_filter[2011]['selected'] = true;
            } else if ($this->request->getQuery('season') == 2010) { // get取得
                // JleageD1Matchdata2010テーブルを呼び出しインスタンス化
                $this->JleageMatchdata = TableRegistry::get('JleageD1Matchdata2010');

                // Seasonフィルターの選択済み設定値をtrueへ変更
                $this->season_filter[2010]['selected'] = true;
            } else if ($this->request->getQuery('season') == 2009) { // get取得
                // JleageD1Matchdata2009テーブルを呼び出しインスタンス化
                $this->JleageMatchdata = TableRegistry::get('JleageD1Matchdata2009');

                // Seasonフィルターの選択済み設定値をtrueへ変更
                $this->season_filter[2009]['selected'] = true;
            } else if ($this->request->getQuery('season') == 2008) { // get取得
                // JleageD1Matchdata2008テーブルを呼び出しインスタンス化
                $this->JleageMatchdata = TableRegistry::get('JleageD1Matchdata2008');

                // Seasonフィルターの選択済み設定値をtrueへ変更
                $this->season_filter[2008]['selected'] = true;
            } else if ($this->request->getQuery('season') == 2007) { // get取得
                // JleageD1Matchdata2007テーブルを呼び出しインスタンス化
                $this->JleageMatchdata = TableRegistry::get('JleageD1Matchdata2007');

                // Seasonフィルターの選択済み設定値をtrueへ変更
                $this->season_filter[2007]['selected'] = true;
            } else if ($this->request->getQuery('season') == 2006) { // get取得
                // JleageD1Matchdata2006テーブルを呼び出しインスタンス化
                $this->JleageMatchdata = TableRegistry::get('JleageD1Matchdata2006');

                // Seasonフィルターの選択済み設定値をtrueへ変更
                $this->season_filter[2006]['selected'] = true;
            } else if ($this->request->getQuery('season') == 2005) { // get取得
                // JleageD1Matchdata2005テーブルを呼び出しインスタンス化
                $this->JleageMatchdata = TableRegistry::get('JleageD1Matchdata2005');

                // Seasonフィルターの選択済み設定値をtrueへ変更
                $this->season_filter[2005]['selected'] = true;
            }
        }

        public function index() {
            // 現在日時を取得
            $today = date("Y/m/d H:i");

            // Seasonフィルターの設定値を格納
            $season_filter = $this->season_filter;

            // 試合日時データ取得
            $match_schedule_paginate_data = $this->JleageMatchdata->getMatchScheduleNoArray();
            if (empty($match_schedule_paginate_data)) {
                // 試合日時データが取得できなかった場合、メッセージ表示
                $this->Flash->error(__('日程データが取得できませんでした'));
            }
            // 取得した試合日時データをオブジェクトから連想配列に変換
            $match_schedule_data = $match_schedule_paginate_data->toArray();

            // Model呼び出し
            $jleageteams = TableRegistry::get('JleageTeams');
            // チーム名データ取得
            $teams_data = $jleageteams->getJleageTeams();
            if (empty($teams_data)) {
                // チーム名データが取得できなかった場合、メッセージ表示
                $this->Flash->error(__('チーム名が取得できませんでした'));
            }

            // 試合日時データ取得
            if ($this->request->getQuery('season') == null || $this->request->getQuery('season') == 2018) {
                $match_schedule_settoday_data = $this->JleageMatchdata->getMatchScheduleSetToday($today);
                // 次の試合が開催される節数取得
                $target_anker_id = $match_schedule_settoday_data->first()->get('MatchNum');
                // 次節の試合日を取得
                $match_schedule_nextmatchday = $this->getNextMatchDays($target_anker_id);
            } else {
                $target_anker_id = '';
                $match_schedule_nextmatchday = '';
            }

            $count_num = 0;
            foreach ($match_schedule_data as $match_data) {
                // HomeTeam名を取得
                $home_team_name = $this->getTeamName($teams_data, $match_data->HomeTeam);
                $match_schedule_data[$count_num]['HomeTeamFullName'] = $home_team_name;

                // AwayTeam名を取得
                $away_team_name = $this->getTeamName($teams_data, $match_data->AwayTeam);
                $match_schedule_data[$count_num]['AwayTeamFullName'] = $away_team_name;

                // 試合状態&アンカーidを設定
                if (empty($match_data->MatchDayTime)) {
                    $match_schedule_data[$count_num]['MatchState'] = '';
                    $match_schedule_data[$count_num]['AnkerId'] = '';
                } else {
                    $target_day = date('Y/m/d H:i', strtotime($match_data->MatchDayTime));
                    if((strtotime($target_day) <= strtotime($today)) && (strtotime($today) <= strtotime($match_data->MatchDayTime) + 60 * 110)) {
                        // 現在の時刻が試合開始時刻  + 110分以内の場合
                        $match_schedule_data[$count_num]['MatchState'] = '試合中';
                    } else if (strtotime($target_day) < strtotime($today)){
                        // 試合が終了している場合
                        $match_schedule_data[$count_num]['MatchState'] = '試合終了';
                    } else {
                        // これから試合が開始される場合
                        $match_schedule_data[$count_num]['MatchState'] = '';
                    }
                    // アンカーidを設定
                    $match_schedule_data[$count_num]['AnkerId'] = $match_schedule_data[$count_num]['MatchNum'];
                }

                // スタジアム名を取得
                $stadium_name = $this->getStadiumName($teams_data, $match_data->ShortStadiumName);
                $match_schedule_data[$count_num]['StadiumName'] = $stadium_name;

                $count_num = $count_num + 1;
            }

            // viewへデータを渡す
            $this->set(compact('season_filter')); // シーズン選択ドロップダウン設定値を渡す
            $this->set(compact('match_schedule_data')); // 試合日程データを渡す
            $this->set(compact('target_anker_id')); // 次節数を渡す
            $this->set(compact('match_schedule_nextmatchday')); // 次節の試合日を渡す
        }

        public function getTeamName($teams_data, $teamname) {
            // 全角英数字を半角へ変換
            $team_name_convert = mb_convert_kana($teamname, 'rn');

            // 略称チーム名比較
            foreach ($teams_data as $data) {
                if ($data['ShortTeamName'] == $team_name_convert) {
                    // チーム名を返す
                    return $data['TeamName'];
                }
            }

            return false;
        }

        public function getStadiumName($teams_data, $short_stadium_name) {
            // 全角英数字を半角へ変換
            $short_stadium_name_convert = mb_convert_kana($short_stadium_name, 'rn');

            // 略称チーム名比較
            foreach ($teams_data as $data) {
                if ($data['ShortStadiumName1'] == $short_stadium_name_convert) {
                    // スタジアム名を返す
                    return $data['StadiumName1'];
                } else if ($data['ShortStadiumName2'] == $short_stadium_name_convert) {
                    // スタジアム名を返す
                    return $data['StadiumName2'];
                } else if ($data['ShortStadiumName3'] == $short_stadium_name_convert) {
                    // スタジアム名を返す
                    return $data['StadiumName3'];
                } else if ($data['ShortStadiumName4'] == $short_stadium_name_convert) {
                    // スタジアム名を返す
                    return $data['StadiumName4'];
                } else if ($data['ShortStadiumName5'] == $short_stadium_name_convert) {
                    // スタジアム名を返す
                    return $data['StadiumName5'];
                }
            }

            return false;
        }

        // 次節の試合日を取得
        public function getNextMatchDays($target_anker_id) {
            // 次節指定による試合日時を取得
            $data_nextmatchday = $this->JleageMatchdata->getNextMatchday($target_anker_id);
            if (empty($data_nextmatchday)) {
                // データ取得に失敗した場合、カラを設定
                return '';
            }
            // 取得した試合日時をオブジェクトから連想配列に変換
            $data_nextmatchday_array = $data_nextmatchday->toArray();

            return $data_nextmatchday_array;
        }

    }