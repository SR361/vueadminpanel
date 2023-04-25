<?php

namespace App\Traits;

trait ApiResponse {
    public function sendResponse( $message, $code, $data = NULL) {
        $response = [
            'code' => $code,
            'message' => $message
        ];
        if($code == 422){
            $response = [
                'code' => $code,
                'message' => $message
            ];
        }
        if( isset($data) ){
            $response['data'] = $data;
        }
        return response()->json( $response, $code );
    }

    // FOR API
    // 422 = validation
    // 401 = unathorise user
    // 200 = success

    // FOR WEB
    // 422 = validation
    // 404 = common error
}
