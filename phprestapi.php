<?php
require_once "koneksi.php";

// Periksa apakah fungsi ada sebelum dipanggil
if (isset($_GET["function"]) && function_exists($_GET["function"])) {
    $_GET["function"]();
} else {
    echo json_encode(["status" => 0, "message" => "Function Not Found"]);
}

// Fungsi untuk mendapatkan semua data karyawan
function get_karyawan()
{
    global $connect;
    $query = $connect->query("SELECT * FROM karyawan");
    
    $data = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }

    $response = [
        "status" => 1,
        "message" => "Success",
        "data" => $data
    ];
    header("Content-Type: application/json");
    echo json_encode($response);
}

// Fungsi untuk mendapatkan data karyawan berdasarkan ID
function get_karyawan_id()
{
    global $connect;
    
    if (!isset($_GET["id"])) {
        echo json_encode(["status" => 0, "message" => "ID is required"]);
        return;
    }

    $id = mysqli_real_escape_string($connect, $_GET["id"]);
    $query = "SELECT * FROM karyawan WHERE id = '$id'";
    $result = $connect->query($query);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    if ($data) {
        $response = ["status" => 1, "message" => "Success", "data" => $data];
    } else {
        $response = ["status" => 0, "message" => "No Data Found"];
    }

    header("Content-Type: application/json");
    echo json_encode($response);
}

// Fungsi untuk menambahkan data karyawan
function insert_karyawan()
{
    global $connect;

    // Ambil data JSON dari request body
    $post_data = json_decode(file_get_contents("php://input"), true);

    if (!isset($post_data["nama"], $post_data["jenis_kelamin"], $post_data["alamat"])) {
        echo json_encode(["status" => 0, "message" => "Wrong Parameter"]);
        return;
    }

    // Escape input untuk mencegah SQL Injection
    $nama = mysqli_real_escape_string($connect, $post_data["nama"]);
    $jenis_kelamin = mysqli_real_escape_string($connect, $post_data["jenis_kelamin"]);
    $alamat = mysqli_real_escape_string($connect, $post_data["alamat"]);

    $query = "INSERT INTO karyawan (nama, jenis_kelamin, alamat) VALUES ('$nama', '$jenis_kelamin', '$alamat')";
    $result = mysqli_query($connect, $query);

    if ($result) {
        echo json_encode(["status" => 1, "message" => "Insert Success"]);
    } else {
        echo json_encode(["status" => 0, "message" => "Insert Failed: " . mysqli_error($connect)]);
    }
}

// Fungsi untuk mengupdate data karyawan
function update_karyawan()
{
    global $connect;

    if (!isset($_GET["id"])) {
        echo json_encode(["status" => 0, "message" => "ID is required"]);
        return;
    }

    // Ambil ID dan escape
    $id = mysqli_real_escape_string($connect, $_GET["id"]);
    $post_data = json_decode(file_get_contents("php://input"), true);

    if (!isset($post_data["nama"], $post_data["jenis_kelamin"], $post_data["alamat"])) {
        echo json_encode(["status" => 0, "message" => "Wrong Parameter"]);
        return;
    }

    // Escape input
    $nama = mysqli_real_escape_string($connect, $post_data["nama"]);
    $jenis_kelamin = mysqli_real_escape_string($connect, $post_data["jenis_kelamin"]);
    $alamat = mysqli_real_escape_string($connect, $post_data["alamat"]);

    $query = "UPDATE karyawan SET nama='$nama', jenis_kelamin='$jenis_kelamin', alamat='$alamat' WHERE id='$id'";
    $result = mysqli_query($connect, $query);

    if ($result) {
        echo json_encode(["status" => 1, "message" => "Update Success"]);
    } else {
        echo json_encode(["status" => 0, "message" => "Update Failed: " . mysqli_error($connect)]);
    }
}

// Fungsi untuk menghapus karyawan berdasarkan ID
function delete_karyawan()
{
    global $connect;

    if (!isset($_GET["id"])) {
        echo json_encode(["status" => 0, "message" => "ID is required"]);
        return;
    }

    $id = mysqli_real_escape_string($connect, $_GET["id"]);
    $query = "DELETE FROM karyawan WHERE id = '$id'";

    if (mysqli_query($connect, $query)) {
        echo json_encode(["status" => 1, "message" => "Delete Success"]);
    } else {
        echo json_encode(["status" => 0, "message" => "Delete Failed: " . mysqli_error($connect)]);
    }
}
?>
