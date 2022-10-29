<?

//include('connection.php');
//include('functions.php');

//echo dirname(__FILE__).'\vendor\autoload.php';
//die();
require_once '../vendor/firebase/php-jwt/src/JWT.php';
require_once '../vendor/firebase/php-jwt/src/JWK.php';
//require_once '../vendor/firebase/php-jwt/src/JWT.php';

use Firebase\JWT\JWT;

function jwt_token($privateKey, $publicKey, $unix, $kid){

    $header = array(
        "alg" => "RS256",
        "typ" => "JWT",
        "kid" => "tucelular2979"
    );

    $payload = array(
        "exp" => $unix,
        "client_id" => "tucelular",
        "scope" => [
            "devices:getpin",
            "devices:register",
            "devices:status",
            "devices:update"
        ]
    );

    $jwt = JWT::encode($payload, $privateKey, 'RS256', $kid);

    return $jwt;
    
}

?>