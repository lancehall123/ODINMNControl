<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <title>Odin explorer</title>
    <link rel="stylesheet" href="themes/Slate/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/jqplot/jquery.jqplot.css">
    <link rel="stylesheet" href="stylesheets/style.css">
    <script>
        var auto_refresh;
        auto_refresh = setInterval(
            (function () {
                $("#randomtext").load("index.php" + '#randomtext"'); //Load the content into the div
            }), 1000);
    </script>
</head>
<body>
<div role="navigation" class="nav navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header"><button type="button" data-toggle="collapse" data-target="#navbar-collapse" class="navbar-toggle"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a href="file:///C|/My Web Sites/New folder/explore.odinblockchain.org/index.html" class="navbar-brand">Odin MasterNode Monitor</a></div>
        <div id="navbar-collapse" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li id="home"></li>
            </ul>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="row text-center">
        <div class="col-md-2 col-sm-12"><img src="images/odin.jpg" style="margin-top:-15px;height:128px;"></div>
        <div class="col-md-2 col-md-offset-1">
            <div class="panel panel-default hidden-sm hidden-xs">
                <div class="panel-heading"><strong>Block Count</strong></div>
                <div id="randomtext" class="panel-body">
                        <?php
                        require_once('wallet_func.php');
                        $control_wallet = array();
                        $control_wallet[] = array("name" => "mn01", "user" => "user", "password" => "password", "host" => "127.0.0.1", "port" => "1988");
                        $masternodes = array();
                        $masternodes[] = array("name" => "mn01", "user" => "user", "password" => "password", "host" => "127.0.0.1", "port" => "1988");
                        foreach($control_wallet as $wallet) {
                            $ODIN = new ODIN($wallet["user"], $wallet["password"], $wallet["host"], $wallet["port"]);
                            $WalletInfo = $ODIN->getInfo();

                            $MNStatus = $ODIN->getMasternodeStatus();
                            $ListMasterNodes = $ODIN->listMasterNodes($MNStatus['addr']);
                            $MNCount = $ODIN->getMasternodeCount();
                        }
                        ?>
                        <lab><?php echo $WalletInfo['blocks']; ?></lab>
                    </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="panel panel-default hidden-sm hidden-xs">
                <div class="panel-heading"><strong>MasterNode Count</strong></div>
                <div class="panel-body">
                    <?php
                    require_once('wallet_func.php');
                    $control_wallet = array();
                    $control_wallet[] = array("name" => "mn01", "user" => "user", "password" => "password", "host" => "127.0.0.1", "port" => "1988");
                    $masternodes = array();
                    $masternodes[] = array("name" => "mn01", "user" => "user", "password" => "password", "host" => "127.0.0.1", "port" => "1988");
                    foreach($control_wallet as $wallet) {
                        $ODIN = new ODIN($wallet["user"], $wallet["password"], $wallet["host"], $wallet["port"]);
                        $WalletInfo = $ODIN->getInfo();
                        $MNStatus = $ODIN->getMasternodeStatus();
                    }
                    ?>
                    <lab><?php echo $MNCount['total'];
                        echo $ListMasterNodes['lastseen'];
                    ?></lab>

                </div>
            </div>
        </div>


        <div class="col-md-2">
            <div class="panel panel-default hidden-sm hidden-xs">
                <div class="panel-heading"><strong>Difficulty</strong></div>
                <div class="panel-body">

                        <lab><?php echo $WalletInfo['difficulty']; ?></lab>

                    </div>
            </div>
        </div>


        <div class="col-md-2">
            <div class="panel panel-default hidden-sm hidden-xs">
                <div class="panel-heading"><strong>Coin Supply (ODIN)</strong></div>
                <div class="panel-body">
                    <label id="supply">
                        <?php
                        if (null==$WalletInfo['moneysupply'])
                        {
                        $WalletInfo['moneysupply'] = "Something has gone wrong";
                        }

                        echo $WalletInfo['moneysupply']?>

                    </label></div>


            </div>

        </div>
         <div class="col-md-2 col-md-offset-1">
            <div class="panel panel-default hidden-sm hidden-xs">
                <div class="panel-heading"><strong>MasterNode Status</strong></div>
                <div class="panel-body"><label><?php
                        if (null==$MNStatus['message'])
                        {
                            $MNStatus['message'] = "This is not a masternode";
                        }

                        echo $MNStatus['message']?> </label></div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="panel panel-default hidden-sm hidden-xs">
                <div class="panel-heading"><strong>Staking</strong></div>
                <div class="panel-body"><label id="lastPrice"><?php
                        if (null==$WalletInfo['staking status'])
                        {
                            $WalletInfo['staking status'] = "Something has gone wrong";
                        }

                        echo $WalletInfo['staking status']?> </label></div>
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
            <tr>
            <th class="text-center">Block Time</th>
                <th class="hidden-xs text-center">Transaction ID</th>
                <th class="text-center">Amount (ODIN)</th>

            </tr>
            <tr>
                <?php
                foreach($control_wallet as $wallet) {

                    $ODIN = new ODIN($wallet["user"], $wallet["password"], $wallet["host"], $wallet["port"]);

                    $transactions = $ODIN->getLastTransactions();
                    $txs_processed = array();
                    foreach ($transactions as $tx) {


                        if (!isset($txs_processed[$tx["txid"]]["amount"])) {
                    $txs_processed[$tx["txid"]]["amount"] = 0;
                }

                $txs_processed[$tx["txid"]]["txid"] = $tx["txid"];
                $txs_processed[$tx["txid"]]["category"] = $tx["category"];
                $txs_processed[$tx["txid"]]["amount"] += $tx["amount"];
                $txs_processed[$tx["txid"]]["confirmations"] = $tx["confirmations"];
                $txs_processed[$tx["txid"]]["txid"] = $tx["txid"];
                $txs_processed[$tx["txid"]]["blocktime"] = $tx["blocktime"];

                if (isset($tx["generated"])) {
                    $txs_processed[$tx["txid"]]["category"] = "generated";
                    if ($tx["category"] == "receive") {
                        $txs_processed[$tx["txid"]]["address"] = $tx["address"];
                    }
                } else {
                    $txs_processed[$tx["txid"]]["address"] = $tx["address"];
                }
                if (isset($tx["fee"])) {
                    $txs_processed[$tx["txid"]]["fee"] = $tx["fee"];
                } else {
                    $txs_processed[$tx["txid"]]["fee"] = 0;
                }


                ?>
            <tr>
                    <td>
                    <lab><?php echo date('Y-m-d H:i:s', $tx["blocktime"]); ?></lab>
                </td>
                <td>
                    <lab><?php echo $tx["txid"]; ?></lab>
                </td>
                <td>
                    <lab><?php echo $tx["amount"]; ?></lab>
                </td>

                <?php
                }
                }
                ?>
            </tr>
            </thead>
            </th>
            <tbody class="text-center">
            </tbody>
        </table>
    </div>
    <div class="footer-padding">
            <div>
            </div>
    </div>

    </div>
</div>
<div class="navbar navbar-default navbar-fixed-bottom hidden-xs">
</div>
<span class="connections"><label id="lblBlockcount" class="label label-default">-</label><label id="lblConnections" class="label label-default">-</label></span>
</body>

</html>