<?php

header('Content-type:application/json;charset=utf-8');

include("../config.php");

$res = [];

if (!empty($_POST['sname']) || !empty($_POST['snationality']) || !empty($_POST['sdob']) || !empty($_POST['smobile'])) {

    $sname = $_POST['sname'];
    $snationality = $_POST['snationality'];
    $sdate = strtotime(str_replace('/', '-', $_POST['sdob']));
    $date = date("Y-m-d", $sdate);
    $smobile = $_POST['smobile'];

    $sql = "INSERT into student(sname, snationality, sdob, smobile) VALUES ('$sname', '$snationality', '$date','$smobile')";

    if ($conn->query($sql) === TRUE) {
        $res = ['response' => 'success', 'message' => 'Successfully added'];
    } else {
        $res = ['response' => 'failed', 'message' => 'Failed to add. Error'];
    }

    $conn->close();
} else {
    $res = ['response' => 'failed', 'message' => 'Failed to add. Error'];
}

echo json_encode($res, JSON_PRETTY_PRINT);
