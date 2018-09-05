<?php
    namespace App\Shell;

    use Cake\Console\ConsoleOptionParser;
    use Cake\Console\Shell;
    use Cake\Log\Log;

    use Cake\ORM\TableRegistry;
    use App\Controller\SimpleHtmlDomController;
    use App\Controller\JleagueHistoricalMatchDataController;
    use App\Controller\MatchResultsGraphController;

    class UpdateMatchdataShell extends Shell {

        public function initialize()
        {
            parent::initialize();
            $this->SimpleHtmlDom = new SimpleHtmlDomController();
            $this->JleagueHistoricalMatchData = new JleagueHistoricalMatchDataController();
            $this->MatchResultsGraph = new MatchResultsGraphController();
        }

        public function main() {
            // ログへShell実行開始メッセージ保存
            Log::info('Shell::::UpdateMatchdataShell.php:::main()::::Start', 'simple_html_dom');

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

            // ログへShell実行終了メッセージ保存
            Log::info('Shell::::UpdateMatchdataShell.php::::main()::::End', 'simple_html_dom');
        }
    }