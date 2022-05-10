<?php

include 'bd/BD.php';

header('Access-Control-Allow-Origin: *');

$json =  file_get_contents('php://input'); //rescata datos del body del mensaje
$data = json_decode($json); //convierte esos daos en objeto

//ATRIBUTOS DEL PRODUCTO
$METHOD = $data->METHOD;
$id = $data->id;
$nombre = $data->nombre;
$lanzamiento = $data->lanzamiento;
$desarrollador = $data->desarrollador;

//GET
//ALL:http://localhost/apiPhpPrueba/
//ID: http://localhost/apiPhpPrueba/?id=4
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $query = "select * from frameworks where id=" . $_GET['id'];
        $resultado = metodoGet($query);
        echo json_encode($resultado->fetch(PDO::FETCH_ASSOC));
    } else {
        $query = "select * from frameworks";
        $resultado = metodoGet($query);
        echo json_encode($resultado->fetchAll());
    }
    header("HTTP/1.1 200 OK");
    exit();
}

//POST
if ($METHOD == 'POST') {
    unset($data->METHOD);
    $query = "insert into frameworks(nombre, lanzamiento, desarrollador) values ('$nombre', '$lanzamiento', '$desarrollador')";
    $queryAutoIncrement = "select MAX(id) as id from frameworks";
    $db_data = metodoPost($query, $queryAutoIncrement);
    if ($db_data) {
        echo json_encode($db_data);
        header("HTTP/1.1 200 OK");
    } else {
        echo '{"error":"no se agrego"}';
        header("HTTP/1.1 400 Bad Request");
    }
    exit();
}
//PUT
if ($METHOD == 'PUT') {
    unset($data->METHOD);
    $query = "UPDATE frameworks SET nombre='$nombre', lanzamiento='$lanzamiento', desarrollador='$desarrollador' WHERE id='$id'";
    $db_data = metodoPut($query, $id);
    if ($db_data) {
        echo json_encode($db_data);
        header("HTTP/1.1 200 OK");
    } else {
        echo '{"error":"no existe"}';
        header("HTTP/1.1 400 Bad Request");
    }
    exit();
}
//DELETE
if ($METHOD == 'DELETE') {
    unset($data->METHOD);
    $query = "DELETE FROM frameworks WHERE id='$id'";
    $resultado = metodoDelete($query, $id);
    echo '{"id":"' . $resultado . '"}';
    header("HTTP/1.1 200 OK");
    exit();
}

//FORM-DATA


//POST
// if($_POST['METHOD']=='POST'){
//     unset($_POST['METHOD']);
//     $nombre=$_POST['nombre'];
//     $lanzamiento=$_POST['lanzamiento'];
//     $desarrollador=$_POST['desarrollador'];
//     $query="insert into frameworks(nombre, lanzamiento, desarrollador) values ('$nombre', '$lanzamiento', '$desarrollador')";
//     $queryAutoIncrement="select MAX(id) as id from frameworks";
//     $resultado=metodoPost($query, $queryAutoIncrement);
//     echo json_encode($resultado);
// header("HTTP/1.1 200 OK");
//     exit();
// }

//PUT
// if ($_POST['METHOD'] == 'PUT') {
//     unset($_POST['METHOD']);
//     $id = $_GET['id'];
//     $nombre = $_POST['nombre'];
//     $lanzamiento = $_POST['lanzamiento'];
//     $desarrollador = $_POST['desarrollador'];
//     $query = "UPDATE frameworks SET nombre='$nombre', lanzamiento='$lanzamiento', desarrollador='$desarrollador' WHERE id='$id'";
//     $resultado = metodoPut($query);
//     echo json_encode($resultado);
//     header("HTTP/1.1 200 OK");
//     exit();
// }


//DELETE
// if ($_POST['METHOD'] == 'DELETE') {
//     unset($_POST['METHOD']);
//     $id = $_GET['id'];
//     $query = "DELETE FROM frameworks WHERE id='$id'";
//     $resultado = metodoDelete($query);
//     echo json_encode($resultado);
//     header("HTTP/1.1 200 OK");
//     exit();
// }

// if ($_POST['METHOD'] == 'DELETE') {
//     unset($_POST['METHOD']);
//     $id = $_GET['id'];
//     $query = "DELETE FROM frameworks WHERE id='$id'";
//     $resultado = metodoDelete($query);
//     echo json_encode($resultado);
//     header("HTTP/1.1 200 OK");
//     exit();
// }

header("HTTP/1.1 400 Bad Request");
