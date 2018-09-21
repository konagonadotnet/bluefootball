<?php
    namespace App\View\Helper;

    use Cake\View\Helper;
    use Cake\View\View;

    class MatchScheduleHelper extends Helper {

        // シーズンを返すヘルパーメソッド
        function getSeasonYear($season) {

            if ($season == null || $season == 2018) {
                // 2018シーズン選択時
                return 2018;
            } else if ($season == 2017) {
                // 2017シーズン選択時
                return 2017;
            } else if ($season == 2016) {
                // 2016シーズン選択時
                return 2016;
            } else if ($season  == 2015) {
                // 2016シーズン選択時
                return 2015;
            }
        }
    }