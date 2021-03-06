<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class JleageTeamsTable extends Table {
    public function initialize(array $config) {
        $this->setTable('jleague_teams');
        $this->setPrimaryKey('id');
    }

    /**** Select文発行 start ****/
    /*
     * 全データ取得メソッド
     */
    public function getJleageTeams() {
        // Select文実行
        $query_data = $this->find();
        $query_data_all = $query_data->all();

        if($query_data_all->count() == 0) {
            // データが取得できなかった場合
            return false;
        }

        return $query_data_all->toArray();
    }

    /*
     * 略称チーム名指定によるチームデータ取得メソッド
     */
    public function getTeamDataSetShortTeamName($short_team_name) {
        // $short_team_name = array('G大阪');

        // Select文実行
        $query_data = $this->find()
            ->where(['ShortTeamName IN' => $short_team_name])
            ->all();
        if($query_data->count() == 0) {
            // データが取得できなかった場合
            return false;
        }

        return $query_data;
    }
}
