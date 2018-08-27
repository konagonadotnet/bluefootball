<?php
    namespace App\Shell;

    use Cake\Console\ConsoleOptionParser;
    use Cake\Console\Shell;
    use Cake\Log\Log;

    use Cake\ORM\TableRegistry;
    use App\Controller\SimpleHtmlDomController;
    use App\Controller\MatchResultsGraphController;

    class UpdateMatchdataShell extends Shell {

        public function initialize()
        {
            parent::initialize();
            $this->SimpleHtmlDom = new SimpleHtmlDomController();
            $this->MatchResultsGraph = new MatchResultsGraphController();
        }

        public function main() {
            debug('Shell::::UpdateMatchdataShell.php:::main()::::Start');

            // 更新チェックフラグをFALSEで初期化
            $check_flg = false;

            // 試合日程データ登録処理の実行
            $check_flg = $this->SimpleHtmlDom->index();
            if ($check_flg == false) {
                debug('Shell::::試合日程データ登録処理の実行に失敗しました::::処理を終了します。');
                exit;
            }

            // 更新チェックフラグをFALSEで初期化
            $check_flg = false;
            // 試合結果データ登録・更新処理の実行
            $check_flg = $this->MatchResultsGraph->RegistrationMatchResults();
            if ($check_flg == false) {
                debug('Shell::::試合結果データ登録・更新処理の実行に失敗しました::::処理を終了します。');
                exit;
            }

            debug('Shell::::UpdateMatchdataShell.php::::main()::::End');
        }
    }