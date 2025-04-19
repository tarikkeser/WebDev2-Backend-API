<?

namespace App\Services;

class ResponseService
{
    // send a JSON response with appropriate header and status
    static function Send($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit();
    }

    // send JSON error response with status
    static function Error($error = "An error occurred", $status = 500)
    {
        self::Send(["error" => $error], $status);
    }

    static function SetCorsHeaders()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

        // Handle preflight OPTIONS request
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
    }
}
