<?php
header("Access-Control-Allow-Origin: *");
//双方的票数，名字，辩题，id
//总的辩题，截止时间，访问量
//header("Content-Type: text/html;charset=utf-8");

require_once "tool.php";

$db = conn();

//获取正在参与投票的两支队伍

$sql = 'select id from team where flag = 1';
$stmt = $db->prepare($sql);
$stmt->execute();
$res = $stmt->fetchAll();

$teams = array();
foreach($res as $data){
	//$data[0] 代表队伍的id
	$team_id = $data[0];
	$team = array();
	$sql = 'select * from team where id = :id';
	$stmt = $db->prepare($sql);
	$stmt->execute([':id'=>$data[0]]);
	$team_data = $stmt->fetch();
	$team['id'] = $team_data['id'];
	$team['name'] = $team_data['name'];
	$team['slogan'] = $team_data['slogan'];
	$team['piao'] = $team_data['piao'];
	$teams[] = $team;
}



$sql = 'select main_s,times,intro from match_result where team1 = :team or team2 = :team';
$stmt = $db->prepare($sql);
$stmt->execute([':team'=>$team_id]);
$res = $stmt->fetch();


$teams['main_s'] = $res['main_s'];
$teams['time'] = $res['times'];
$teams['intro'] = $res['intro'];

echo json_encode($teams);







