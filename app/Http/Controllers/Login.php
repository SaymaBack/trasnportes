<?php

namespace App\Http\Controllers;

use App\Http\Implementation\UserAuth;
use App\Http\Implementation\ValidationUser;

use App\Http\Implementation\UserAuthjwt;
use Illuminate\Http\Request;

class Login extends Controller
{
    public function Auth(Request $request)
    {
        $data = $request->all();
        $validation = new ValidationUser();

        $response = $validation->validateExistUsername($data);

        if (!$response["success"]) {
            return response()->json(['success' => false, 'message' => $response["message"]], 409);
        }

        $response = $validation->validateExistPassword($data);
        if (!$response["success"]) {
            return response()->json(['success' => false, 'message' => $response["message"]], 409);
        }

        $validation = new UserAuth();
        $response =  $validation->authenticate($data);
        if (!$response["success"]) {
            return response()->json(['success' => false, 'message' => $response["message"]], 409);
        }

        $validation = new UserAuthjwt();
        $response = $validation->generarToke($response["data"]->usuario, $response["data"]->id);


        if (!$response["success"]) {
            return response()->json(['success' => false, 'message' => $response["message"]], $response["status"]);
        }

        return response()->json(['success' => true, 'message' => $response["message"],"token"=>$response["token"]], $response["status"]);
    }
}
