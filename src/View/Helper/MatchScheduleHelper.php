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
                // 2015シーズン選択時
                return 2015;
            } else if ($season  == 2014) {
                // 2014シーズン選択時
                return 2014;
            } else if ($season  == 2013) {
                // 2013シーズン選択時
                return 2013;
            } else if ($season  == 2012) {
                // 2012シーズン選択時
                return 2012;
            } else if ($season  == 2011) {
                // 2011シーズン選択時
                return 2011;
            } else if ($season  == 2010) {
                // 2010シーズン選択時
                return 2010;
            } else if ($season  == 2009) {
                // 2009シーズン選択時
                return 2009;
            } else if ($season  == 2008) {
                // 2008シーズン選択時
                return 2008;
            } else if ($season  == 2007) {
                // 2007シーズン選択時
                return 2007;
            } else if ($season  == 2006) {
                // 2006シーズン選択時
                return 2006;
            } else if ($season  == 2005) {
                // 2005シーズン選択時
                return 2005;
            }
        }
    }