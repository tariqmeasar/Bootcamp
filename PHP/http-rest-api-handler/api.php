<?php

header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];
$headers = function_exists('getallheaders') ? getallheaders() : [];
$authHeader = $headers['Authorization'] ?? ($headers['authorization'] ?? null);

$rawInput = file_get_contents("php://input");
$data = json_decode($rawInput, true);

if (!empty($rawInput) && json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Invalid JSON payload"
    ], JSON_PRETTY_PRINT);
    exit;
}

switch ($method) {
    case 'GET':
        http_response_code(200);
        $response = [
            "status" => "success",
            "method" => "GET",
            "message" => "Resource retrieved successfully",
            "authorization_header" => $authHeader,
            "query_params" => $_GET
        ];
        break;

    case 'POST':
        http_response_code(201);
        $response = [
            "status" => "success",
            "method" => "POST",
            "message" => "Resource created successfully",
            "authorization_header" => $authHeader,
            "received_data" => $data
        ];
        break;

    case 'PUT':
        http_response_code(200);
        $response = [
            "status" => "success",
            "method" => "PUT",
            "message" => "Resource updated successfully",
            "authorization_header" => $authHeader,
            "updated_data" => $data
        ];
        break;

    case 'DELETE':
        http_response_code(200);
        $response = [
            "status" => "success",
            "method" => "DELETE",
            "message" => "Resource deleted successfully",
            "authorization_header" => $authHeader,
            "deleted_target" => $data ?? $_GET
        ];
        break;

    default:
        http_response_code(405);
        $response = [
            "status" => "error",
            "method" => $method,
            "message" => "Method Not Allowed"
        ];
        break;
}

echo json_encode($response, JSON_PRETTY_PRINT);
