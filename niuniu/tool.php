<?php



function  conn(){
	$dns = "mysql:host=localhost;dbname=niuniu";
	return new PDO($dns, "root","root");
}