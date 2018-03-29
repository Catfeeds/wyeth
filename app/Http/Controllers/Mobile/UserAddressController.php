<?php

namespace App\Http\Controllers\Mobile;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAddressController extends Controller {
    public function index(Request $request) {
        $user = Auth::user();

        $address = UserAddress::where('uid', $user->id)->first();

        $price_list = [5123065, 7753630, 7382055, 5051244];

        $access = in_array($user->id, $price_list);

        return view('mobile.user_address', ['user' => $user, 'address' => $address, 'access' => $access]);
    }

    public function save(Request $request) {
        $user = Auth::user();
        $name = $request->input('name');
        $phone = $request->input('phone');
        $city = $request->input('city');
        $address = $request->input('address');

        $user_address = UserAddress::where('uid', $user->id)->first();

        if (!$user_address) {
            $user_address = new UserAddress();
        }

        $user_address->uid = $user->id;
        $user_address->name = $name;
        $user_address->phone = $phone;
        $user_address->city = $city;
        $user_address->address = $address;
        $user_address->save();
        if ($user_address) {
            $result = ['status' => 200, 'message' => 'Update Success!'];
        } else {
            $result = ['status' => 500, 'message' => 'Update Fild!'];
        }
        echo json_encode($result);
    }
}