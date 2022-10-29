<?

//include('connection.php');
//include('functions.php');

//echo dirname(__FILE__).'\vendor\autoload.php';
//die();
require_once '../vendor/firebase/php-jwt/src/JWT.php';
require_once '../vendor/firebase/php-jwt/src/JWK.php';
//require_once '../vendor/firebase/php-jwt/src/JWT.php';

use Firebase\JWT\JWT;

/**
 * 
 */
class jwt_token
{
    
    function jwt_token()
    {
        // code...
    }

    function getToken($privateKey, $publicKey, $unix, $kid){

        //echo $unix;
        //die();

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
        //$jwt = JWT::encode($payload, $privateKey, $header);
        //echo = "Encode:\n" . print_r($jwt, true) . "\n";

        //$decoded = JWT::decode($jwt, $publicKey, array('RS256'));

        /*
         NOTE: This will now be an object instead of an associative array. To get
         an associative array, you will need to cast it as such:
        */

        //$decoded_array = (array) $decoded;
        //echo "Decode:\n" . print_r($decoded_array, true) . "\n";


        
        //echo $jwt;

    }

}


?>