<?php

header("Access-Control-Allow-Origin: *");
require_once "tool.php";

//header("Content-Type: text/html;charset=utf-8");
//添加新的比赛

$data = file_get_contents('php://input');
$message = json_decode($data,true);


/*
$message['m_title'] = "标题";
$message['title'] = "辩题";
$message['view1'] = "正方观点";
$message['view2'] = "反方观点";
$message['content1'] = '正方文案';
$message['content2'] = '反方文案';
$message['times'] = time();
$message['root_pwd'] = "root_pwd";
*/

if($message['root_pwd']=="root_pwd"){
	echo 1;
	//前一场比赛结束
	$db = conn();
	$sql = 'update team set flag = 0 where flag = 1';
	$stmt = $db->prepare($sql);
	$stmt->execute();

	//添加新的队伍
	$sql = 'insert into team(view,content,piao,flag) values(:view,:content,0,1)';
	$stmt = $db->prepare($sql);
	$stmt->execute([':view'=>$message['view1'],':content'=>$message['content1']]);
	$stmt->execute([':view'=>$message['view2'],':content'=>$message['content2']]);

	//获取新的队伍的id并把它存入比赛列表
	$sql = 'select max(id) from team';
	$stmt = $db->prepare($sql);
	$stmt->execute();
	$res = $stmt->fetch();
	$id_2 = $res[0];
	$id_1 = $id_2 - 1;

	//
	$sql = 'insert into match_result(team1,team2,m_title,title,times) values(:id_1,:id_2,:m_title,:title,:times)';
	$stmt = $db->prepare($sql);
	$stmt->execute([':id_1'=>$id_1,':id_2'=>$id_2,':m_title'=>$message['m_title'],':title'=>$message['title'],':times'=>$message['times']]);

}
else{
	echo 0;
}
