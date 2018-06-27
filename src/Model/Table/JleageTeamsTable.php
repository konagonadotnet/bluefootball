<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class JleageTeamsTable extends Table
{
    public function initialize(array $config)
    {
        $this->setTable('jleague_teams');
        $this->setPrimaryKey('id');
    }

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
}
