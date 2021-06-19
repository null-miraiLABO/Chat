<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>chat10</title>
</head>
<body>
<form method='post' enctype="multipart/form-data">
		なまえ;<input type="text" name="nm">
		めっせー;<textarea name="msg"></textarea>
		画像<input type="file" name="upfile" size="50" /><br />
		<input type="submit" value="メッセージ送信">
	</form>

<?php
	//書き込みファイル名
	$savefile = "log/chat10.txt";
	//カウントする変数初期化

	$cnt = array();//n
	//既にファイルがあれば読み込む
	if(file_exists($savefile))
	{
		$cnt = file($savefile);//n
	}

	//コメント消去
	if(isset($_GET["del"]) && is_numeric($_GET["del"])){
		$index = $_GET["del"];
		if(isset($cnt[$index])){
			$cnt[$index] ="削除\n";
			file_put_contents($savefile, $cnt);
		}
	}

	$trans=str_replace(array("¥r¥n","¥r","¥n"),'<br>', htmlspecialchars($trans));
	$cnt[]=htmlspecialchars($_POST[$trans])."<>".htmlspecialchars($_POST[$trans])."<>".date('Y/m/d H:i:s')."\n";

	//$chktext = "aab.addad.ddd.ccc.jpg.exe.";

	//入力
	if(isset($_POST["nm"]) && isset($_POST["msg"]))
	{
		if($_POST["nm"]=='' || $_POST["msg"]==''){
			echo "error";
		}
		else if(preg_match("/う[ 　\/／]*ん[ 　\/／]*(こ|ち)/", $_POST["nm"]."<>".$_POST["msg"])){ 
			echo "NG!!!";//
		}
		else{

			var_dump($_FILES);

			$NameTemp="";
			$OnceFile=$_FILES['upfile'];

			if($OnceFile['name']!='')
			{
				$NameTemp=md5($OnceFile['name'].microtime());
				var_dump($NameTemp);
				move_uploaded_file($OnceFile['tmp_name'],'img/'.$NameTemp);
			}

			$msg=str_replace(array("\r\n","\r","\n"),'<br>', htmlspecialchars($_POST["msg"]));
			//かきこ
			$cnt[]=htmlspecialchars($_POST["nm"])."<>".$msg."<>".date('Y/m/d H:i:s')."<>".$NameTemp."\n";
			//ファイルへ保存
			file_put_contents($savefile, $cnt);
		}
	}
	
	//カウンターの値を表示
	//echo $cnt;

	foreach($cnt as $k=>$v)
	{
		if($v == "削除\n"){
			continue;
		}

		$newArray = explode("<>",$v);

		if(isset($_GET['selname']) && $_GET['selname']!=$newArray[0]){
			continue;
		}

		$ImgTag="";
		$ImgName=trim($newArray[3]);//
		if($ImgName!="")
		{
			$ImgTag='<img src="img/'.$ImgName.'">';
		}
		
		echo '<p><span style="font-weight:bold"><a href="?selname='.urlencode($newArray[0]).'">'.$newArray[0].'</a></span> <span style="font-size:15px;">'.$newArray[2].'</span> <a href="?del='.$k.'">削除</a><br><span style="margin-left:1em;">'.$newArray[1].'</div>'.$ImgTag.'</p>';
	}
	
?>
</body>
</html>
	
	