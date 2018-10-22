<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <title>Odin explorer</title>
    <link rel="stylesheet" href="themes/Slate/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/jqplot/jquery.jqplot.css">
    <link rel="stylesheet" href="stylesheets/style.css">
</head>
<body>
<div role="navigation" class="nav navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header"><button type="button" data-toggle="collapse" data-target="#navbar-collapse" class="navbar-toggle"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a href="file:///C|/My Web Sites/New folder/explore.odinblockchain.org/index.html" class="navbar-brand">Odin explorer</a></div>
        <div id="navbar-collapse" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li id="home"></li>
            </ul>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="row text-center">
        <div class="col-md-2 col-md-offset-1">
            <div class="panel panel-default hidden-sm hidden-xs">
                <div class="panel-heading"><strong>Network (GH/s)</strong></div>
                <div class="panel-body"><label id="hashrate">- </label></div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="panel panel-default hidden-sm hidden-xs">
                <div class="panel-heading"><strong>Difficulty</strong></div>
                <div class="panel-body"><label id="difficulty">-</label></div>
            </div>
        </div>
        <div class="col-md-2 col-sm-12"><img src="images/odin.jpg" style="margin-top:-15px;height:128px;"></div>
        <div class="col-md-2">
            <div class="panel panel-default hidden-sm hidden-xs">
                <div class="panel-heading"><strong>Coin Supply (ODIN)</strong></div>
                <div class="panel-body"><label id="supply">-  </label></div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="panel panel-default hidden-sm hidden-xs">
                <div class="panel-heading"><strong>BTC Price</strong></div>
                <div class="panel-body"><label id="lastPrice">- </label></div>
            </div>
        </div>
    </div>
    <div style="margin-top:10px;margin-bottom:20px;" class="row text-center">
        <form method="post" action="https://explore.odinblockchain.org/search" class="form-inline">
            <div id="index-search" class="form-group"></div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-md-12"></div>
</div>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Latest Transactions</strong></div>
        <table id="recent-table" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th class="text-center">Block</th>
                <th class="hidden-xs text-center">Hash</th>
                <th class="hidden-xs text-center">Recipients</th>
                <th class="text-center">Amount (ODIN)</th>
                <th class="text-center">Timestamp</th>
            </tr>
            </thead>
            <tbody class="text-center"></tbody>
        </table>
    </div>
    <div class="footer-padding">
        <?php
        ini_set('display_startup_errors', 1);
        ini_set('display_errors', 1);
        error_reporting(-1);
        require_once('wallet_func.php');
        $control_wallet = array();
        $control_wallet[] = array("name" => "mn01", "user" => "user", "password" => "password", "host" => "127.0.0.1", "port" => "1988");
        $masternodes = array();
        $masternodes[] = array("name" => "mn01", "user" => "user", "password" => "password", "host" => "127.0.0.1", "port" => "1988");
        //$masternodes[] = array("name" => "[own name]", "user" => "[RPCUser]", "password" => "[RPCPassword]", "host" => "[RPCHost]", "port" => "[RPCPort]");
        foreach($control_wallet as $wallet) {
            $ODIN = new ODIN($wallet["user"], $wallet["password"], $wallet["host"], $wallet["port"]);
            $WalletInfo = $ODIN->getInfo();
            echo "<h1>" . $wallet['name'] . "</h1>";
            echo "<p><strong>Balance</strong>" . $WalletInfo['balance'] . "</p>";
            echo "<p><strong>Connections</strong>" . $WalletInfo['connections'] . "</p>";
            echo "<p><strong>Staking status</strong>" . $WalletInfo['staking status'] . "</p>";
            ?>
            <div>
                <h2>Last transactions</h2>
              <?php
               $transactions = $ODIN->getLastTransactions();
                $txs_processed = array();
                 foreach($transactions as $tx) {
                    if(!isset($txs_processed[$tx["txid"]]["amount"])) {
                        $txs_processed[$tx["txid"]]["amount"] = 0;
                    }
                    $txs_processed[$tx["txid"]]["txid"] = $tx["txid"];
                    $txs_processed[$tx["txid"]]["category"] = $tx["category"];
                    $txs_processed[$tx["txid"]]["amount"] += $tx["amount"];
                    $txs_processed[$tx["txid"]]["confirmations"] = $tx["confirmations"];
                    $txs_processed[$tx["txid"]]["txid"] = $tx["txid"];
                    $txs_processed[$tx["txid"]]["blocktime"] = $tx["blocktime"];

                    if(isset($tx["generated"])) {
                        $txs_processed[$tx["txid"]]["category"] = "generated";
                        if($tx["category"] == "receive") {
                            $txs_processed[$tx["txid"]]["address"] = $tx["address"];
                        }
                    } else {
                        $txs_processed[$tx["txid"]]["address"] = $tx["address"];
                    }
                    if(isset($tx["fee"])) {
                        $txs_processed[$tx["txid"]]["fee"] = $tx["fee"];
                    } else {
                        $txs_processed[$tx["txid"]]["fee"] = 0;
                    }
                }

                //function custom_sort($a,$b) {
                //	return $a['blocktime']<$b['blocktime'];
                //}

                //usort($txs_processed, "custom_sort");
                ?>
                <table style="margin: auto; min-width: 80%;">
                    <thead>
                    <tr>
                        <th>Date Time</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Confs</th>
                        <th>Address</th>
                        <th>Tx</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                 foreach($txs_processed as $tx) {
                        $tx["amount"] = $tx["amount"] + $tx["fee"];
                        ?>
                        <tr>
                            <td style="text-align: right;"><?php /*echo date('Y-m-d H:i:s',$tx["blocktime"] ); */?></td>
                            <td style="text-align: right;"><?php /*echo $tx["category"]; */?></td>
                            <td style="text-align: right;"><?php /*echo number_format($tx["amount"], 8, '.', ',') . " ODIN";*/?></td>
                            <td style="text-align: right;"><?php /*echo $tx["confirmations"];*/?></td>
                            <td style="text-align: right;"><?php /*echo $tx["address"]; */?></td>
                            <td style="text-align: right;"><a href="https://explore.odinblockchain.org/tx/<?php /*echo $tx["txid"];*/?>" target='_blank' alt ='<?php /*echo $tx["txid"]; */?>'>Tx</a></td>
                        </tr>
                        <?php
          }
                    ?>
                    </tbody>
                </table>
            </div>
            <?php
        }
        foreach($masternodes as $wallet) {
            $ODIN = new ODIN($wallet["user"], $wallet["password"], $wallet["host"], $wallet["port"]);
            $MNStatus = $ODIN->getMasternodeStatus();
            echo "<h1>" . $wallet['name'] . "</h1>";
            echo "<p><strong>Status</strong>" . $MNStatus['message'] . "</p>";
            echo "<p><strong>Address</strong>" . $MNStatus['addr'] . "</p>";
            echo "<p><strong>Public ip-address</strong>" . $MNStatus['netaddr'] . "</p>";
            $MNCount = $ODIN->getMasternodeCount();
            echo "<h1>" . $wallet['name'] . "</h1>";
            echo "<p><strong>Count</strong>" . $MNCount['total'] . "</p>";
        }
        ?>
    </div>
</div>
<div class="navbar navbar-default navbar-fixed-bottom hidden-xs">
    <div class="col-md-4">
        <ul class="nav navbar-nav">
            <li class="pull-left"></li>
        </ul>
    </div>
</div>
<div class="col-md-4">
    <ul class="nav">
        <li style="margin-left:80px;margin-right:80px;" class="text-center">
            <p style="margin-top:15px;"><a href="https://github.com/iquidus/explorer" target="_blank" class="navbar-link">Powered by Iquidus Explorer </a></p>
        </li>
    </ul>
</div>
<span class="connections"><label id="lblBlockcount" class="label label-default">-</label><label id="lblConnections" class="label label-default">-</label></span>
</body>

</html>