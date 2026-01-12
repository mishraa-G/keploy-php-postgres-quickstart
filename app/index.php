<?php

header("Content-Type: application/json");
require "db.php";

$method = $_SERVER["REQUEST_METHOD"];
$path = trim($_SERVER["REQUEST_URI"], "/");
$parts = explode("/", $path);

// Health check
if ($path === "health") {
    echo json_encode(["status" => "ok"]);
    exit;
}

// /users routes
if ($parts[0] === "users") {

    // POST /users
    if ($method === "POST" && count($parts) === 1) {
        $data = json_decode(file_get_contents("php://input"), true);

        pg_query_params(
            $conn,
            "INSERT INTO users(name, email) VALUES($1, $2)",
            [$data["name"], $data["email"]]
        );

        echo json_encode(["message" => "User created"]);
        exit;
    }

    // GET /users
    if ($method === "GET" && count($parts) === 1) {
        $result = pg_query($conn, "SELECT * FROM users");
        echo json_encode(pg_fetch_all($result));
        exit;
    }

    // GET /users/{id}
    if ($method === "GET" && count($parts) === 2) {
        $result = pg_query_params(
            $conn,
            "SELECT * FROM users WHERE id=$1",
            [$parts[1]]
        );
        echo json_encode(pg_fetch_assoc($result));
        exit;
    }

    // DELETE /users/{id}
    if ($method === "DELETE" && count($parts) === 2) {
        pg_query_params(
            $conn,
            "DELETE FROM users WHERE id=$1",
            [$parts[1]]
        );
        echo json_encode(["message" => "User deleted"]);
        exit;
    }
}

// Fallback
http_response_code(404);
echo json_encode(["error" => "Not found"]);
