<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\AttendoEmail;
use Mail;
use App\User;

class ApiController extends Controller {

    public function __construct() {

        DB::enableQueryLog();
    }

    public function social_media_login(Request $request) {

        if (empty($request->input('name'))) {
            return response()->json(array('status' => false, 'message' => 'You must enter username.'));
        } elseif (empty($request->input('device_token'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
        }

        $user = new User();
        $response = $user->where(['name' => $request->input('name')])->orwhere(['email' => $request->input('email')])->first();

        if (empty($response)) {
            return response()->json(array('status' => false, 'message' => 'Invalid name or email. Record not found in db.'));
        } elseif ($response->status != 'active') {
            return response()->json(array('status' => false, 'message' => 'Your account needs admin approval, after that you can login to your account'));
        }
        $data = array(
            'device_token' => $request->input('device_token'),
            'last_login_date' => date("Y-m-d H:i:s")
        );
        $update_user = $user->where('user_id', $response->user_id)->update($data);

        $result = $user->where('user_id', $response->user_id)->first();
        if ($result == true) {
            return response()->json(array('status' => true, 'alert' => true, 'message' => 'User Login Successfully!!', 'response' => $result));
        } else {
            return response()->json(array('status' => false, 'message' => 'Somer Server Error Occurred'));
        }
    }

    public function login(Request $request) {

        if (empty($request->input('name'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your name'));
        } elseif (empty($request->input('password'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your password'));
        } elseif (empty($request->input('device_token'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
        }

        $user = new User();
        $response = $user->where(['name' => $request->input('name'), 'password' => md5($request->input('password'))])->first();

        if (empty($response)) {
            return response()->json(array('status' => false, 'message' => 'Invalid Username or Password'));
        } elseif ($response->status != 'active') {
            return response()->json(array('status' => false, 'message' => 'Your account needs admin approval, after that you can login to your account'));
        }
        $data = array(
            'device_token' => $request->input('device_token'),
            'last_login_date' => date("Y-m-d H:i:s")
        );
        $update_user = $user->where('user_id', $response->user_id)->update($data);

        $result = $user->where('user_id', $response->user_id)->first();

        if ($response == true) {
            return response()->json(array('status' => true, 'alert' => true, 'message' => 'User Login Successfully!!', 'response' => $result));
        } else {
            return response()->json(array('status' => false, 'message' => 'Somer Server Error Occurred'));
        }
    }

    public function register(Request $request) {
        if (empty($request->input('name')) || is_numeric($request->input('name'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Valid Name'));
        } elseif (empty($request->input('email'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your Email'));
        } elseif (empty($request->input('password'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your Password'));
        } elseif (strlen($request->input('password')) < 6) {
            return response()->json(array('status' => false, 'message' => 'Your password must consist of 6 characters'));
        } elseif (empty($request->input('device_token'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter Your Device token'));
        }

        $user = new User();
        $check_email = $user->where('email', $request->input('email'))->first();

        if ((!empty($check_email))) {
            return response()->json(array('status' => false, 'message' => 'Email is already Exists'));
        }

        $check_username = $user->where('name', $request->input('name'))->first();

        if ((!empty($check_username))) {
            return response()->json(array('status' => false, 'message' => 'Username already Exists'));
        }

        $data = array(
            'name' => htmlspecialchars($request->input('name')),
            'email' => $request->input('email'),
            'password' => md5($request->input('password')),
            'device_token' => strtoupper($request->input('device_token')),
            'registeration_date' => date("Y-m-d H:i:s"),
            'last_login_date' => date("Y-m-d H:i:s"),
            'status' => 'active'
        );

        $response = DB::table('users')->insertGetId($data);

        $get_user = $user->where('user_id', $response)->first();

        if ($response == true) {
            return response()->json(array('status' => true, 'message' => 'User Registration Successfully!!', 'response' => $get_user));
        } else {
            return response()->json(array('status' => false, 'message' => 'Somer Server Error Occurred'));
        }
    }

    public function forgot_password(Request $request) {
        if (empty($request->input('email'))) {
            return response()->json(array('status' => false, 'message' => 'Please Enter your email address'));
        }

        $user = new User();
        $check = $user->where(['email' => $request->input('email'), 'status' => 'active'])->first();
        if (empty($check)) {
            return response()->json(array('status' => false, 'message' => "The email address '" . $request->input("email") . "' is not registered with My App. Please Try again."));
        }
        $updated_password = $this->getToken(8);
        $user->where(['user_id' => $check->user_id])->update(['password' => md5($updated_password)]);
        $check->for = 'forgot_password';
        $check->subject = 'Forgot Password';
        $check->link = $updated_password;
        //Mail::to($check->email)->send(new AttendoEmail($check));
        return response()->json(array('status' => true, 'message' => 'Please Check Your Email. We sent you an email with instructions to reset your password.'));
    }

    function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 1)
            return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }

    function getToken($length) {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max - 1)];
        }

        return $token;
    }

}
