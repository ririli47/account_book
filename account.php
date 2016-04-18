<html>
<head>
<title>家計簿</title>
<link rel="stylesheet" type="text/css" href="account.css">
</head>
<body>
<div class = "parent">
<h1>簡単家計簿</h1>

<form method = "POST" action = "<?php print($_SERVER['PHP_SELF']) ?>">
<input type = "radio" name = "pm" value = "plus" checked = "cehcked">収入
<input type = "radio" name = "pm" value = "minus">支出<br><br>

収入の場合はこちら<br>
<input type = "radio" name = "kind_plus" value = "kyu">給与
<input type = "radio" name = "kind_plus" value = "rin">臨時収入
<input type = "radio" name = "kind_plus" value = "other_p" checked = "checked">その他<br><br>

支出の場合はこちら<br>
<input type = "radio" name = "kind_minus" value = "food">食費
<input type = "radio" name = "kind_minus" value = "kou">交際費
<input type = "radio" name = "kind_minus" value = "other_m" checked = "checked">その他<br>


<p>金額<input type = "text" name = "money"></p>
<p>詳細<input type = "text" name = "contents"></p>
<input type = "submit" name = "btn1" value = "投稿する">
<input type = "submit" name = "btn2" value = "リセットする">
</form>

<?php

//リセットボタン処理
if(isset($_POST["btn2"]))
{
	$fp = fopen("pm.txt", 'w');
	$r = ("0 0");
	fseek($fp, 0);
	fputs($fp, $r);
	fclose($fp);
	
	$fp = fopen("money.txt", 'w');
	$r = ("0");
	fseek($fp, 0);
	fputs($fp, $r);
	fclose($fp);
	
	$fp = fopen("account.txt", 'w');
	$r = ("");
	fseek($fp, 0);
	fputs($fp, $r);
	fclose($fp);
	
	$fp = fopen("kind.txt", 'w');
	$r = ("0 0 0 0 0 0");
	fseek($fp, 0);
	fputs($fp, $r);
	fclose($fp);
}

$pm = $_POST["pm"];


if($_SERVER["REQUEST_METHOD"] == "POST")
{
	if(isset($_POST["money"]) && $_POST["money"] != '' && preg_match("/^[0-9]+$/", $_POST["money"]) )
	{
		if(isset($_POST["contents"]))
		{
			writeData();
		}
	}
}

calcMoney();

$money = $_POST["money"];

$kind_plus = $_POST["kind_plus"];
$kind_minus = $_POST["kind_minus"];

$fp = fopen("kind.txt", 'r+');
fscanf($fp, "%d %d %d %d %d %d", $kind[0], $kind[1], $kind[2], $kind[3], $kind[4], $kind[5]);

if($pm == "plus")
{
	if($kind_plus == "kyu")
	{
		$kind[0] += $money;
	}
	else if($kind_plus == "rin")
	{
		$kind[1] += $money;
	}
	else
	{
		$kind[2] += $money;
	}
}
else if( $pm == "minus")
{
	if($kind_minus == "food")
	{
		$kind[3] += $money;
	}
	else if($kind_minus == "kou")
	{
		$kind[4] += $money;
	}
	else
	{
		$kind[5] += $money;
	}
}

$r = sprintf("%d %d %d %d %d %d", $kind[0], $kind[1], $kind[2], $kind[3], $kind[4], $kind[5]);
fseek($fp, 0);
fputs($fp, $r);
fclose($fp);

$fp = fopen("pm.txt", 'r+');
fscanf($fp, "%d %d", $count[0], $count[1]);
if(isset($_POST["money"]))
{
	if($pm == "plus")
	{
		$count[0] += $money;
	}
	else if($pm == "minus")
	{
		$count[1] += $money;
	}
}
$r = sprintf("%d %d", $count[0], $count[1]);
fseek($fp, 0);
fputs($fp, $r);
fclose($fp);

if($count[0] < 100000 && $count[1] < 100000)
	$m = 150;
else if($count[0] >= 100000 || $count[1] >= 100000)
	$m = 300;
else if($count[0] >= 500000 || $count[1] >= 500000)
	$m = 600;
	
for ($i = 0; $i < 2; $i++) $width[$i] = (int)($count[$i] / $m);

for ($i = 0; $i < 6; $i++) $width_k[$i] = (int)($kind[$i] / $m);


?>

<table>
  <tr>
    <td><p>収入</p></td>
    <td align="right"><?php echo $count[0] ?>円</td>
    <td><div style="width:<?php echo $width[0] ?>px; height:10px; background-color:blue"></div></td>
  </tr>
  <tr>
    <td>給与</td>
    <td align="right"><?php echo $kind[0] ?>円</td>
    <td><div style="width:<?php echo $width_k[0] ?>px; height:10px; background-color:#638702"></div></td>
  </tr>
  <tr>
    <td>臨時収入</td>
    <td align="right"><?php echo $kind[1] ?>円</td>
    <td><div style="width:<?php echo $width_k[1] ?>px; height:10px; background-color:#cc9944"></div></td>
  </tr>
  <tr>
    <td>その他</td>
    <td align="right"><?php echo $kind[2] ?>円</td>
    <td><div style="width:<?php echo $width_k[2] ?>px; height:10px; background-color:#bb3855"></div></td>
  </tr>
  <tr>
    <td><p>支出</p></td>
    <td align="right"><?php echo $count[1] ?>円</td>
    <td><div style="width:<?php echo $width[1] ?>px; height:10px; background-color:red"></div>
      </tr>
  <tr>
    <td>食費</td>
    <td align="right"><?php echo $kind[3] ?>円</td>
    <td><div style="width:<?php echo $width_k[3] ?>px; height:10px; background-color:#654321"></div></td>
  </tr>
  <tr>
    <td>交際費</td>
    <td align="right"><?php echo $kind[4] ?>円</td>
    <td><div style="width:<?php echo $width_k[4] ?>px; height:10px; background-color:#123456"></div></td>
  </tr>
  <tr>
    <td>その他</td>
    <td align="right"><?php echo $kind[5] ?>円</td>
    <td><div style="width:<?php echo $width_k[5] ?>px; height:10px; background-color:#dd1234"></div></td>
  </tr>
</table>

</div>

<?php
readData();


function calcMoney()
{
	$money = $_POST["money"];
	$pm = $_POST["pm"];


	//収支合計を取得
	$fp = fopen("money.txt", 'r');
	if($fp)
	{
		if(flock($fp, LOCK_SH))
		{
			$buffer = fgets($fp);
			//print($buffer);
		}
		flock($fp, LOCK_UN);
	}
	else
	{
		print("ファイルロックに失敗しました。");
	}
	fclose($fp);

	if($pm == "plus")
		$sum = intval($buffer) + intval($money);
	else if($pm == "minus")
		$sum = intval($buffer) - intval($money);
	else
		$sum = intval($buffer);
		
	print("収支合計：" . $sum . "円<br>");

	//収支合計を記録
	$fp = fopen("money.txt", 'w');
	if($fp)
	{
		if(flock($fp, LOCK_EX))
		{
			if(fwrite($fp, $sum) == FALSE)
			{
				print("ファイル書き込みに失敗しました。");
			}
		}
		flock($fp, LOCK_UN);
	}
	else
	{
		print("ファイルロックに失敗しました。");
	}
	
	fclose($fp);
}




function readData()
{
	$fp = fopen("account.txt", 'r');
	if($fp)
	{
		if(flock($fp, LOCK_SH))
		{
			print("<div class = \"rireki\">");

			while(!feof($fp))
			{
				$buffer = fgets($fp);
				print("<br>" . $buffer . "<br>");
			}
			print("</div>");

		}
		flock($fp, LOCK_UN);
	}
	else
	{
		print("ファイルロックに失敗しました。");
	}

	fclose($fp);
}

function writeData()
{
	$money = $_POST["money"];
	$contents = $_POST["contents"];
	$pm = $_POST["pm"];
	
	if($pm == "plus")
		$money = "+" . $money;
	else if($pm == "minus")
		$money = "-" . $money;

	$contents = htmlspecialchars($contents, ENT_COMPAT, 'UTF-8');

	$data = $money . "  " . $contents . "\r\n";

	//収支履歴を記録
	$fp = fopen("account.txt", 'a');
	if($fp)
	{
		if(flock($fp, LOCK_EX))
		{
			if(fwrite($fp, $data) == FALSE)
			{
				print("ファイル書き込みに失敗しました。");
			}
		}
		flock($fp, LOCK_UN);
	}
	else
	{
		print("ファイルロックに失敗しました。");
	}

	fclose($fp);

}

?>
</body>
</html>