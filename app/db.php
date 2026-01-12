<?php

$host = getenv("DB_HOST") ?: "127.0.0.1";
$db   = getenv("DB_NAME") ?: "keploy";
$user = getenv("DB_USER") ?: "postgres";
$pass = getenv("DB_PASS") ?: "postgres";

$conn = pg_connect(
    "host=$host dbname=$db user=$user password=$pass"
);

if (!$conn) {
    http_response_code(500);
    echo json_encode([
        "error" => "Failed to connect to PostgreSQL"
    ]);
    exit;
}
