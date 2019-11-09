<?php
 header("Access-Control-Allow-Origin: *");
require_once "tool.php";

$data = file_get_contents('php://input');
$message = json_decode($data,true);

//$message['ip'] = '1234';
//$message['vote'] = 25	;

$flag = 1;
//判断该ip是否已经投过票了
if($message['vote']==null){
	echo 0;
}
else{
	$db = conn();
	$sql = 'select vote from matchs where ip = :ip';
	$stmt = $db->prepare($sql);
	$stmt->execute([':ip'=>$message['ip']]);
	$res = $stmt->fetch();
	if($res[0]!=NULL){
		if($res[0]==$message['vote']){
			$flag = 0;
		}
		//进行减票操作
		/*
		$da_vote = $res[0];
		
		$sql = 'delete from matchs where ip = :ip';
		$stmt = $db->prepare($sql);
		$stmt->execute([':ip'=>$message['ip']]);
		
		$sql = 'update team set piao = piao - 1 where id = :vote';
		$stmt = $db->prepare($sql);
		$stmt->execute([':vote'=>$da_vote]);
		*/
	}


	//没有投过票，进行投票
	$sql = 'insert into matchs(ip,vote) values(:ip,:vote)';
	$stmt = $db->prepare($sql);
	$stmt->execute([':ip'=>$message['ip'],':vote'=>$message['vote']]);
		
	//对投票的队伍票数加1
	$sql = 'update team set piao = piao + 1 where id = :vote';
	$stmt = $db->prepare($sql);
	$stmt->execute([':vote'=>$message['vote']]);

	echo $flag;
}

