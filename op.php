<?php

$rifconn = include_once('connection.php');

header('Content-Type: application/json');

switch($_SERVER['REQUEST_METHOD'])
{
    case 'GET':
        getOp($_GET,$rifconn);
        break;

    case 'POST':
        //inserimento op (noleggio)
        postNol($_POST,$rifconn);
        break;
    case 'PUT':
        //aggiornamento op (restituzione)
        putNol($_POST,$rifconn);//$_PUT????
        break;

}


/*function getOp($conn)//3a
{
    $stmt = $conn->prepare("SELECT id_bike_fk,id_ut_fk,id_staz_fk FROM op WHERE tipo_op='noleggio' AND DATE(data_start)=DATE(NOW());");
    $stmt->execute();
    $result = $stmt->get_result();

    $msmError = (object) array('Error' => ''. $conn.' '. mysqli_error($conn));
    $msmAlert =  (object) array('Messaggio' => 'No results');
    if(!$result) echo json_encode($msmError);
    $return = array();
    while($row = mysqli_fetch_assoc($result)){
        $return[] = $row;
    }if ($return == null){
    echo json_encode($msmAlert);
}else{
    $object = (object) ($return);
    echo json_encode($object);
}
}*/


function getOp($g,$conn)
{
    if(isset($g['id_ut']))//id_ut_fk
    {
        //4
        //SELECT id_bike_fk,id_staz_fk,datediff(data_end,data_start) AS Durata, FROM `op` WHERE id_ut_fk=2
        //il costo va calcolato a parte
        $id_ut = $g['id_ut'];
        $stmt = $conn->prepare("SELECT id_bike_fk,id_staz_fk,datediff(data_end,data_start) AS Durata, FROM `op` WHERE id_ut_fk=?");
        $stmt->bindparam("i",$id_ut);
        $stmt->execute();
        $result = $stmt->get_result();

        $msmError = (object) array('Error' => ''. $conn.' '. mysqli_error($conn));
        $msmAlert =  (object) array('Messaggio' => 'No results');
        if(!$result) echo json_encode($msmError);
        $return = array();
        while($row = mysqli_fetch_assoc($result)){
            $return[] = $row;
        }if ($return == null){
        echo json_encode($msmAlert);
    }else{
        $object = (object) ($return);
        echo json_encode($object);
    }

    }else if(isset($g['id_bike']))//id_bike_fk
    {
        $id_bike=$g['id_bike'];
        //5a
        //SELECT id_ut_fk from op where id_bike_fk=2 and MONTH(data_start) = MONTH(CURRENT_DATE()) AND YEAR(columnName) = YEAR(CURRENT_DATE())
        $stmt = $conn->prepare("SELECT nome,cogn from utenti inner join op on id_ut = id_ut_fk where id_bike_fk=? and MONTH(data_start) = MONTH(CURRENT_DATE()) AND YEAR(columnName) = YEAR(CURRENT_DATE());");
        $stmt->bindparam("i",$id_bike);
        $stmt->execute();
        $result = $stmt->get_result();

        $msmError = (object) array('Error' => ''. $conn.' '. mysqli_error($conn));
        $msmAlert =  (object) array('Messaggio' => 'No results');
        if(!$result) echo json_encode($msmError);
        $return = array();
        while($row = mysqli_fetch_assoc($result)){
            $return[] = $row;
        }if ($return == null){
        echo json_encode($msmAlert);
    }else{
        $object = (object) ($return);
        echo json_encode($object);
    }

    }else if(isset($g['data1'])&&isset($g['data2']))
    {
        $data1=$g['data1'];
        $data2=$g['data2'];
        //5b
        //SELECT id_staz_fk, COUNT(*) AS FREQ FROM op WHERE tipo_op LIKE 'noleggio' AND data_start between '2019-05-06 16:00:01' and '2019-05-06 18:00:01'
        // GROUP BY id_staz_fk ORDER BY FREQ DESC LIMIT 1
        $stmt = $conn->prepare("SELECT ind_staz, COUNT(*) AS FREQ FROM staz inner join op on id_staz = id_staz_fk WHERE tipo_op LIKE 'noleggio' AND data_start between ? and ? GROUP BY id_staz_fk ORDER BY FREQ DESC LIMIT 1;");
        $stmt->bindparam("ss",$data1,$data2);
        $stmt->execute();
        $result = $stmt->get_result();

        $msmError = (object) array('Error' => ''. $conn.' '. mysqli_error($conn));
        $msmAlert =  (object) array('Messaggio' => 'No results');
        if(!$result) echo json_encode($msmError);
        $return = array();
        while($row = mysqli_fetch_assoc($result)){
            $return[] = $row;
        }if ($return == null){
        echo json_encode($msmAlert);
    }else{
        $object = (object) ($return);
        echo json_encode($object);
    }

    }else
    {   //3b
        $stmt = $conn->prepare("SELECT id_bike_fk,id_ut_fk,id_staz_fk FROM op WHERE tipo_op='noleggio' AND DATE(data_start)=DATE(NOW());");
        $stmt->execute();
        $result = $stmt->get_result();

        $msmError = (object) array('Error');
        $msmAlert =  (object) array('No results');
        if(!$result) echo json_encode($msmError);
        $return = array();
        while($row = mysqli_fetch_assoc($result)){
            $return[] = $row;
        }if ($return == null){
        echo json_encode($msmAlert);
        }else{
        $object = (object) ($return);
        echo json_encode($object);
        }
    }//main if

}//end getOp



