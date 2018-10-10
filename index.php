<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>ODIN Masternode control</title>
		<style>
			strong {width: 12em; display: inline-block;}
		</style>
	</head>
	<body>
<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
require_once('wallet_func.php');
$control_wallet = array();
$control_wallet[] = array("name" => "[own name]", "user" => "[RPCUser]", "password" => "[RPCPassword]", "host" => "[RPCHost]", "port" => "[RPCPort]");
$masternodes = array();
$masternodes[] = array("name" => "[own name]", "user" => "[RPCUser]", "password" => "[RPCPassword]", "host" => "[RPCHost]", "port" => "[RPCPort]");
$masternodes[] = array("name" => "[own name]", "user" => "[RPCUser]", "password" => "[RPCPassword]", "host" => "[RPCHost]", "port" => "[RPCPort]");
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
		
		function custom_sort($a,$b) {
			return $a['blocktime']<$b['blocktime'];
		}
		
		usort($txs_processed, "custom_sort");
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
					<td style="text-align: right;"><?php echo date('Y-m-d H:i:s',$tx["blocktime"] ); ?></td>
					<td style="text-align: right;"><?php echo $tx["category"]; ?></td>
					<td style="text-align: right;"><?php echo number_format($tx["amount"], 8, '.', ',') . " ODIN";?></td>
					<td style="text-align: right;"><?php echo $tx["confirmations"];?></td>
					<td style="text-align: right;"><?php echo $tx["address"]; ?></td>
					<td style="text-align: right;"><a href="https://explore.odinblockchain.org/tx/<?php echo $tx["txid"];?>" target='_blank' alt ='<?php echo $tx["txid"]; ?>'>Tx</a></td>
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
}
?>
	</body>
</html>
