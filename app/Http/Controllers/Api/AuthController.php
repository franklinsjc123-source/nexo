<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Support;
use App\Models\UserVedio;
use App\Models\Service;
use App\Models\Country;
use App\Models\Advertisement;
use App\Models\PersonalDetails;
use App\Models\ServiceTypeModel;
use App\Models\HairModel;
use App\Models\EyeModel;
use App\Models\LanguageModel;
use App\Models\OrientationModel;
use App\Models\BodyTypeModel;
use App\Models\ManageRequestModel;
use App\Models\WebRequestModel;
use App\Models\AllRequestModel;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{


    public function login(Request $request)
    {

        $email_or_mobile = $request->input('email_or_mobile');
        $password = $request->input('password');
        $token    = $request->input('token');


        if ($email_or_mobile && $password) {

            $user = User::where('email', $email_or_mobile)
                ->orWhere('mobile', $email_or_mobile)
                ->first();



            if (!$user) {
                return response()->json(['status' => 'User not found'], 400);
            }

            /** Store FCM Token  */
            $device_id = isset($user->device_id) ? $user->device_id : '';
            if ($device_id == '') {
                User::Where('id', $user->id)->update(array('device_id' => $token));
            }

            if (Hash::check($password, $user->password)) {

                $success_array = array('status' => 'success', 'message' => 'login Successfully', 'data' => $user);
                return response()->json(array($success_array), 200);
            } else {

                $error_array = array('status' => 'error', 'message' => 'Password is incorrect');
                return response()->json(array($error_array), 400);
            }
        } else {

            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }


    public function triggerNotification()
    {
        return sendRequestNotification('findflciker', 'test notification from findflicker');
    }


    public function register(Request $request)
    {

        $name = $request->input('name');
        $email = $request->input('email');
        $mobile = $request->input('mobile');
        $password = $request->input('password');
        $device_id = $request->input('device_id');


        if ($name != '' &&  $email != '' &&  $mobile != '' && $password != '') {

            $user = User::where('email', $email)
                ->orWhere('mobile', $mobile)
                ->first();

            if ($user) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Email or mobile number already exists'
                ], 409);
            }

            $insertArray = array(
                'name'          => $name,
                'email'         => $email,
                'mobile'        => $mobile,
                'device_id'     => $device_id,
                'auth_level'    => 2,
                'password'      => Hash::make($password),
                'created_at'    => now()

            );

            $user = User::create($insertArray);

            $success_array = array('status' => 'success', 'message' => 'Register Successfully', 'user_id' => $user->id);
            return response()->json(array($success_array), 200);
        } else {

            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }


    public function support_request(Request $request)
    {

        $email = $request->input('email');
        $mobile = $request->input('mobile');
        $issue = $request->input('issue');
        $photos = $request->file('photo');

        if ($email != '' &&  $mobile != '' && $issue != '') {

            $imageUrls = [];

            if ($request->hasFile('photo')) {

                foreach ($photos as $photo) {

                    $imageName = 'support' . time() . '_' . str_replace(' ', '_', $photo->getClientOriginalName());
                    $photo->move(public_path('/uploads/support/'), $imageName);
                    $imageUrl = URL::to('/') . '/uploads/support/' . $imageName;
                    $imageUrls[] = $imageUrl;
                }
            }

            $commaSeparatedImages = implode(',', $imageUrls);


            $insertArray = array(
                'email'         => $email,
                'mobile'        => $mobile,
                'issue'         => $issue,
                'photo'         => $commaSeparatedImages,
                'created_at'    => now()

            );

            Support::insert($insertArray);

            $success_array = array('status' => 'success', 'message' => 'Request send Successfully');
            return response()->json(array($success_array), 200);
        } else {

            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }



    public function addCountry(Request $request)
    {

        $id = $request->input('id');
        $photo = $request->file('photo');

        if ($id != '') {

            if ($photo) {
                $imageName = 'country' . time() . '_' . str_replace(' ', '_', $photo->getClientOriginalName());
                $photo->move(public_path('/uploads/country/'), $imageName);
                $imageUrl = URL::to('/') . '/uploads/country/' . $imageName;
            }

            $updateArray = array(
                'image'         => $imageUrl,
            );

            Country::where('id', $id)->update($updateArray);

            $success_array = array('status' => 'success', 'message' => 'Updated  Successfully');
            return response()->json(array($success_array), 200);
        } else {

            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }


    public function forgetPasswordOTP(Request $request)
    {

        $mobile = $request->input('mobile');

        if ($mobile != '') {

            $user_id = User::where('mobile', $mobile)->value('id');
            if (!$user_id) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'user not found'
                ], 409);
            }
            $otp = rand(100000, 999999);

            $udpateArray =  array(
                'otp' => $otp
            );

            User::where('id', $user_id)->update($udpateArray);

            $url = "http://reseller.alphasoftz.info/api/sendsms.php?user=ALPHAS&apikey=2SQpniQWa8ZGG4meLjgJ&mobile=$mobile&message=Dear%20Sir%20/%20Madam,%20Your%20OTP%20for%20Find%20Flicker%20is%20$otp%20powdered%20by%20Alphasoftz&senderid=ASOFTZ&type=txt&tid=1607100000000333922";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $otp_send  = curl_exec($curl);

            if ($otp_send) {
                $success_array = array('status' => 'success', 'message' => 'Otp sent successfully');
                return response()->json(array($success_array), 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'Otp not sent');
                return response()->json(array($error_array), 400);
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }



    public function checkOTP(Request $request)
    {

        $mobile = $request->input('mobile');
        $otp = $request->input('otp');

        if ($mobile != '' && $otp != '') {

            $user_id = User::where('mobile', $mobile)->value('id');

            $stored_otp = User::where('id', $user_id)->value('otp');

            if ($stored_otp ==  $otp) {
                $success_array = array('status' => 'success', 'message' => 'Otp verified successfully');
                return response()->json(array($success_array), 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'Otp not matched');
                return response()->json(array($error_array), 400);
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }


    public function createUserVedio(Request $request)
    {
        $vedio = $request->file('vedio');

        if ($vedio) {


            $imageName = 'user_guide_vedio_' . time() . '_' . str_replace(' ', '_', $vedio->getClientOriginalName());
            $vedio->move(public_path('/uploads/user_vedio/'), $imageName);

            $imageUrl = URL::to('/') . '/uploads/user_vedio/' . $imageName;

            $insert = UserVedio::create([
                'vedio' => $imageUrl,
                'created_at' => now()
            ]);
            if ($insert) {
                $success_array = array('status' => 'success', 'message' => 'Inserted Successfully');
                return response()->json(array($success_array), 200);
            } else {
                $error_array = array('status' => 'success', 'message' => 'Not inserted');
                return response()->json(array($error_array), 400);
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }



    public function getUserVedio()
    {

        $vedio = UserVedio::get();

        if ($vedio) {
            $success_array = array('status' => 'success', 'message' => 'Data received successfully', 'data' => $vedio);
            return response()->json(array($success_array), 200);
        } else {
            $error_array = array('status' => 'success', 'message' => 'Something went wrong');
            return response()->json(array($error_array), 400);
        }
    }


    public function getUserDetails(Request $request)
    {

        $user_id = $request->input('user_id');

        if ($user_id) {

            $user = User::from('users as u')
                ->leftJoin('countries as c', 'c.id', '=', 'u.country')
                ->where('u.id', $user_id)
                ->select('u.*', 'c.id as country_id', 'c.name as country_name', 'c.image as country_flag', 'c.currency_name')
                ->first();

            if ($user) {
                $success_array = array('status' => 'success', 'message' => 'Data received successfully', 'data' =>  $user);
                return response()->json(array($success_array), 200);
            } else {
                $error_array = array('status' => 'success', 'message' => 'Something went wrong');
                return response()->json(array($error_array), 400);
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }


    public function updateProfile(Request $request)
    {

        $user_id = $request->input('user_id');
        $name = $request->input('name');
        $email = $request->input('email');
        $mobile = $request->input('mobile');

        if ($name != '' &&  $email != '' &&  $mobile != '') {


            $updateArray = array(
                'name'          => $name,
                'email'         => $email,
                'mobile'        => $mobile,
            );

            $update = User::where('id', $user_id)->update($updateArray);
            if ($update) {
                $success_array = array('status' => 'success', 'message' => 'Updated Successfully');
                return response()->json(array($success_array), 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'Not updated');
                return response()->json(array($error_array), 400);
            }
        } else {

            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }


    public function updateCountry(Request $request)
    {

        $user_id = $request->input('user_id');
        $country = $request->input('country');

        if ($user_id != '' &&  $country != '') {

            $updateArray = array(
                'country'         => $country,
            );

            $update = User::where('id', $user_id)->update($updateArray);

            if ($update) {
                $success_array = array('status' => 'success', 'message' => 'Updated Successfully');
                return response()->json(array($success_array), 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'Not updated');
                return response()->json(array($error_array), 400);
            }
        } else {

            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }


    public function updatePassword(Request $request)
    {

        $mobile = $request->input('mobile');
        $password = $request->input('password');


        if ($mobile != '' &&  $password != '') {

            $user = User::where('mobile', $mobile)->first();

            if (!$user) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'user not found'
                ], 409);
            }

            $updateArray = array(
                'password'      => Hash::make($password),

            );

            $update = User::where('id', $user->id)->update($updateArray);

            $success_array = array('status' => 'success', 'message' => 'Updated successfully');
            return response()->json(array($success_array), 200);
        } else {

            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }




    public function changePassword(Request $request)
    {

        $user_id = $request->input('user_id');
        $old_password = $request->input('old_password');
        $password = $request->input('password');


        if ($user_id != '' &&  $old_password != ''  &&  $password != '') {

            $user = User::where('id', $user_id)->first();

            if (!$user) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'user not found'
                ], 409);
            }

            if (Hash::check($old_password, $user->password)) {

                $updateArray = array(
                    'password'      => Hash::make($password),

                );

                User::where('id', $user_id)->update($updateArray);

                $success_array = array('status' => 'success', 'message' => 'Updated successfully');
                return response()->json(array($success_array), 200);
            } else {

                $error_array = array('status' => 'error', 'message' => 'Password Not matched');
                return response()->json(array($error_array), 400);
            }
        } else {

            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }


    public function addService(Request $request)
    {

        $user_id = $request->input('user_id');
        $service_type = $request->input('service_type');
        $title = $request->input('title');
        $description = $request->input('description');
        $photos = $request->file('photo');
        $day_rate_min = $request->input('day_rate_min');
        $day_rate_max = $request->input('day_rate_max');
        $night_rate_min = $request->input('night_rate_min');
        $night_rate_max = $request->input('night_rate_max');
        $availability = $request->input('availability');
        $language = $request->input('language');




        if ($title != '' &&  $description != '' &&  !empty($photos)) {

            $imageUrls = [];

            if ($request->hasFile('photo')) {

                foreach ($photos as $photo) {

                    $imageName = 'service_' . time() . '_' . str_replace(' ', '_', $photo->getClientOriginalName());
                    $photo->move(public_path('/uploads/service/'), $imageName);
                    $imageUrl = url('/uploads/service/' . $imageName);
                    $imageUrls[] = $imageUrl;
                }
            }

            $commaSeparatedImages = implode(',', $imageUrls);


            // $imageName = 'service_' . time() . '_' . str_replace(' ', '_', $photo->getClientOriginalName());
            // $photo->move(public_path('/uploads/service/'), $imageName);
            // $imageUrl = URL::to('/') . '/uploads/service/' . $imageName;


            $insertArray = array(
                'user_id'           => $user_id,
                'service_type'      => $service_type,
                'title'             => $title,
                'description'       => $description,
                'day_rate_min'      => $day_rate_min,
                'day_rate_max'      => $day_rate_max,
                'night_rate_min'    => $night_rate_min,
                'night_rate_max'    => $night_rate_max,
                'availability'      => $availability,
                'language'          => $language,
                'photo'             => $commaSeparatedImages,
                'created_at'        => now()

            );

            $serice =   Service::create($insertArray);

            $success_array = array('status' => 'success', 'message' => 'Service added successfully', 'data' => $serice);
            return response()->json(array($success_array), 200);
        } else {

            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }


    public function addAdvertisement(Request $request)
    {

        $service_id = $request->input('service_id');
        $name = $request->input('name');
        $phone = $request->input('phone');
        $whatsapp = $request->input('whatsapp');
        $telegram = $request->input('telegram');
        $viber = $request->input('viber');
        $title = $request->input('title');
        $location = $request->input('location');
        $logitude = $request->input('logitude');
        $latitude = $request->input('latitude');


        if ($name != '' &&  $name != '' &&  $service_id != ''  && $whatsapp != '') {

            $insertArray = array(
                'service_id'    => $service_id,
                'name'          => $name,
                'phone'         => $phone,
                'whatsapp'      => $whatsapp,
                'telegram'      => $telegram,
                'viber'         => $viber,
                'title'         => $title,
                'location'      => $location,
                'latitiude'     => $latitude,
                'logitude'      => $logitude,
                'created_at'    => now()

            );

            $data =   Advertisement::create($insertArray);

            $success_array = array('status' => 'success', 'message' => 'Advertisement added successfully', 'data' => $data);
            return response()->json(array($success_array), 200);
        } else {

            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }


    public function addPersonalDetails(Request $request)
    {

        $service_id = $request->input('service_id');
        $age = $request->input('age');
        $height = $request->input('height');
        $weight = $request->input('weight');
        $nationality = $request->input('nationality');
        $hair_color = $request->input('hair_color');
        $orientation = $request->input('orientation');
        $bust_size = $request->input('bust_size');
        $body_type = $request->input('body_type');
        $breast_type = $request->input('breast_type');
        $public_hair = $request->input('public_hair');
        $eye_color = $request->input('eye_color');
        $smoker = $request->input('smoker');
        $liquor = $request->input('liquor');


        if ($age != '' &&  $height != '' &&  $service_id != ''  && $weight != '' && $nationality != '' && $hair_color != '' && $orientation != '' && $body_type != '' && $breast_type != '' && $smoker != '' && $liquor != '') {

            $insertArray = array(
                'service_id'        => $service_id,
                'age'               => $age,
                'height'            => $height,
                'weight'            => $weight,
                'nationality'       => $nationality,
                'hair_color'        => $hair_color,
                'orientation'       => $orientation,
                'bust_size'         => $bust_size,
                'body_type'         => $body_type,
                'eye_color'         => $eye_color,
                'breast_type'       => $breast_type,
                'public_hair'       => $public_hair,
                'smoker'            => $smoker,
                'liquor'            => $liquor,
                'created_at'        => now()

            );

            $data =   PersonalDetails::create($insertArray);

            $success_array = array('status' => 'success', 'message' => 'Advertisement added successfully', 'data' => $data);
            return response()->json(array($success_array), 200);
        } else {

            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }


    public function editService(Request $request)
    {

        $id = $request->input('service_id');
        $user_id = $request->input('user_id');
        $service_type = $request->input('service_type');
        $title = $request->input('title');
        $description = $request->input('description');
        $photos = $request->file('photo');
        $old_photo = $request->input('old_photo');
        $day_rate_min = $request->input('day_rate_min');
        $day_rate_max = $request->input('day_rate_max');
        $night_rate_min = $request->input('night_rate_min');
        $night_rate_max = $request->input('night_rate_max');
        $availability = $request->input('availability');
        $language = $request->input('language');



        if ($title != '' &&  $description != '') {

            $updateArray = array(
                'user_id'           => $user_id,
                'service_type'      => $service_type,
                'title'             => $title,
                'description'       => $description,
                'day_rate_min'      => $day_rate_min,
                'day_rate_max'      => $day_rate_max,
                'night_rate_min'    => $night_rate_min,
                'night_rate_max'    => $night_rate_max,
                'availability'      => $availability,
                'language'          => $language,
                //'photo'             => $commaSeparatedImages,
            );

            $imageUrls = [];
            if ($request->hasFile('photo')) {

                foreach ($photos as $photo) {

                    $imageName = 'service_' . time() . '_' . str_replace(' ', '_', $photo->getClientOriginalName());
                    $photo->move(public_path('/uploads/service/'), $imageName);
                    $imageUrl = url('/uploads/service/' . $imageName);
                    $imageUrls[] = $imageUrl;
                }
                $commaSeparatedImages = implode(',', $imageUrls);
                $updateArray['photo'] = $commaSeparatedImages;
            }



            $update =   Service::where('id', $id)->update($updateArray);

            $success_array = array('status' => 'success', 'message' => 'Service updated successfully', 'service id' => $update);
            return response()->json(array($success_array), 200);
        } else {

            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }


    public function editAdvertisement(Request $request)
    {
        $service_id = $request->input('service_id');
        $name = $request->input('name');
        $phone = $request->input('phone');
        $whatsapp = $request->input('whatsapp');
        $telegram = $request->input('telegram');
        $viber = $request->input('viber');
        $title = $request->input('title');
        $location = $request->input('location');
        $latitiude = $request->input('latitiude');
        $logitude = $request->input('logitude');


        if ($name != '' &&  $name != '' &&  $service_id != ''  && $whatsapp != '') {

            $updateArray = array(
                'service_id'    => $service_id,
                'name'          => $name,
                'phone'         => $phone,
                'whatsapp'      => $whatsapp,
                'telegram'      => $telegram,
                'viber'         => $viber,
                'title'         => $title,
                'location'      => $location,
                'latitiude'     => $latitiude,
                'logitude'      => $logitude,
            );

            $data =   Advertisement::where('service_id', $service_id)->update($updateArray);

            $success_array = array('status' => 'success', 'message' => 'Advertisement updated successfully', 'data' => $data);
            return response()->json(array($success_array), 200);
        } else {

            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }


    public function editPersonalDetails(Request $request)
    {


        $service_id = $request->input('service_id');
        $age = $request->input('age');
        $height = $request->input('height');
        $weight = $request->input('weight');
        $nationality = $request->input('nationality');
        $hair_color = $request->input('hair_color');
        $orientation = $request->input('orientation');
        $bust_size = $request->input('bust_size');
        $body_type = $request->input('body_type');
        $breast_type = $request->input('breast_type');
        $public_hair = $request->input('public_hair');
        $eye_color = $request->input('eye_color');
        $smoker = $request->input('smoker');
        $liquor = $request->input('liquor');


        if ($age != '' &&  $height != '' &&  $service_id != ''  && $weight != '' && $nationality != '' && $hair_color != '' && $orientation != '' && $body_type != '' && $breast_type != '' && $smoker != '' && $liquor != '') {

            $updateArray = array(
                'service_id'        => $service_id,
                'age'               => $age,
                'height'            => $height,
                'weight'            => $weight,
                'nationality'       => $nationality,
                'hair_color'        => $hair_color,
                'orientation'       => $orientation,
                'bust_size'         => $bust_size,
                'body_type'         => $body_type,
                'eye_color'         => $eye_color,
                'breast_type'       => $breast_type,
                'public_hair'       => $public_hair,
                'smoker'            => $smoker,
                'liquor'            => $liquor,
            );

            $data =   PersonalDetails::where('service_id', $service_id)->update($updateArray);

            $success_array = array('status' => 'success', 'message' => 'Personal details updated successfully', 'data' => $data);
            return response()->json(array($success_array), 200);
        } else {

            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            return response()->json(array($error_array), 400);
        }
    }


    public function getServiceList()
    {

        $service = Service::get();

        if ($service) {
            $success_array = array('status' => 'success', 'message' => 'Data received successfully', 'data' => $service);
            return response()->json(array($success_array), 200);
        } else {
            $error_array = array('status' => 'success', 'message' => 'Something went wrong');
            return response()->json(array($error_array), 400);
        }
    }


    public function getAdsList(Request $request)
    {

        $user_id = $request->input('user_id');

        $services = DB::table('service as s')
            ->leftJoin('personal_details as pd', 'pd.service_id', '=', 's.id')
            ->leftJoin('advertisement as a', 'a.service_id', '=', 's.id')
            ->select(
                's.id',
                's.service_type',
                's.title as service_title',
                's.description',
                's.photo',
                's.day_rate_min',
                's.day_rate_min',
                's.day_rate_max',
                's.night_rate_min',
                's.night_rate_max',
                's.availability',
                's.language',
                'pd.age',
                'pd.height',
                'pd.weight',
                'pd.nationality',
                'pd.hair_color',
                'pd.orientation',
                'pd.bust_size',
                'pd.body_type',
                'pd.breast_type',
                'pd.public_hair',
                'pd.eye_color',
                'pd.smoker',
                'pd.liquor',
                'pd.is_verified',
                'a.name as ad_name',
                'a.phone as ad_phone',
                'a.whatsapp',
                'a.title',
                'a.telegram',
                'a.viber',
                'a.location',
                'a.latitiude',
                'a.logitude',
                'a.status',
                DB::raw('0 as user_matches'),
                DB::raw('0 as user_views')
            )
            ->where('s.user_id', $user_id)
            ->whereNotNull('a.id')
            ->orderBy('s.id', 'desc')
            ->get();


        $services->transform(function ($service) {
            if ($service->language) {
                $languageIds = explode(',', $service->language);
                $languageNames = DB::table('language')
                    ->whereIn('id', $languageIds)
                    ->pluck('name')
                    ->toArray();

                $service->language_names = implode(', ', $languageNames);
            } else {
                $service->language_names = null;
            }
            return $service;
        });



        if ($services) {
            $success_array = array('status' => 'success', 'message' => 'Data received successfully', 'data' => $services);
            return response()->json(array($success_array), 200);
        } else {
            $error_array = array('status' => 'success', 'message' => 'Something went wrong');
            return response()->json(array($error_array), 400);
        }
    }


    public function getAdsDetails(Request $request)
    {

        $service_id = $request->input('service_id');

        $services = DB::table('service as s')
            ->leftJoin('personal_details as pd', 'pd.service_id', '=', 's.id')
            ->leftJoin('advertisement as a', 'a.service_id', '=', 's.id')
            ->leftJoin('hair as h', 'h.id', '=', 'pd.hair_color')
            ->leftJoin('eye as e', 'e.id', '=', 'pd.eye_color')
            ->leftJoin('orientation as o', 'o.id', '=', 'pd.orientation')
            ->leftJoin('body_type as bt', 'bt.id', '=', 'pd.body_type')
            ->select(
                's.id',
                's.service_type',
                's.title as service_title',
                's.description',
                's.photo',
                's.day_rate_min',
                's.day_rate_min',
                's.day_rate_max',
                's.night_rate_min',
                's.night_rate_max',
                's.availability',
                's.language',
                'pd.age',
                'pd.height',
                'pd.weight',
                'pd.nationality',
                'h.hair_color',
                'o.orientation_name as orientation',
                'pd.bust_size',
                'bt.body_type',
                'pd.breast_type',
                'pd.public_hair',
                'e.eye_color',
                'pd.smoker',
                'pd.liquor',
                'a.name as ad_name',
                'a.phone as ad_phone',
                'a.title',
                'a.whatsapp',
                'a.telegram',
                'a.viber',
                'a.location',
                'a.latitiude',
                'a.logitude',
                'a.status',

            )
            ->where('s.id', $service_id)
            ->get();

        $services->transform(function ($service) {
            if ($service->language) {
                $languageIds = explode(',', $service->language);
                $languageNames = DB::table('language')
                    ->whereIn('id', $languageIds)
                    ->pluck('name')
                    ->toArray();

                $service->language_names = implode(', ', $languageNames);
            } else {
                $service->language_names = null;
            }
            return $service;
        });


        if ($services) {
            $success_array = array('status' => 'success', 'message' => 'Data received successfully', 'data' => $services);
            return response()->json(array($success_array), 200);
        } else {
            $error_array = array('status' => 'success', 'message' => 'Something went wrong');
            return response()->json(array($error_array), 400);
        }
    }


    public function deleteAds(Request $request)
    {

        $service_id = $request->input('service_id');

        $delete = Service::where('id', $service_id)->delete();
        PersonalDetails::where('service_id', $service_id)->delete();
        Advertisement::where('service_id', $service_id)->delete();

        if ($delete) {
            $success_array = array('status' => 'success', 'message' => ' Deleted successfully');
            return response()->json(array($success_array), 200);
        } else {
            $error_array = array('status' => 'success', 'message' => 'Something went wrong');
            return response()->json(array($error_array), 400);
        }
    }


    public function adsChangeStatus(Request $request)
    {

        $ads_id = $request->input('ads_id');
        $status = $request->input('status');

        $updatArray = array(
            'status' => $status
        );

        $update = Advertisement::where('service_id', $ads_id)->update($updatArray);

        if ($update) {
            $success_array = array('status' => 'success', 'message' => ' Updated successfully');
            return response()->json(array($success_array), 200);
        } else {
            $error_array = array('status' => 'success', 'message' => 'Something went wrong');
            return response()->json(array($error_array), 400);
        }
    }

    public function getAllCountries()
    {
        $data = Country::from('countries as c')
            ->leftJoin('currency as cur', 'cur.code', '=', 'c.currency_code')
            ->select('c.id', 'c.name', 'c.country_flag', 'c.currency_name', 'c.currency_code', 'cur.symbol')
            ->get();

        if ($data) {
            $success_array = array('status' => 'success', 'message' => 'Data received successfully', 'data' => $data);
            return response()->json(array($success_array), 200);
        } else {
            $error_array = array('status' => 'success', 'message' => 'Something went wrong');
            return response()->json(array($error_array), 400);
        }
    }




    public function getServiceTypeList()
    {

        $data = ServiceTypeModel::where('status', 1)->get();

        if ($data) {
            $success_array = array('status' => 'success', 'message' => 'Data received successfully', 'data' => $data);
            return response()->json(array($success_array), 200);
        } else {
            $error_array = array('status' => 'success', 'message' => 'Something went wrong');
            return response()->json(array($error_array), 400);
        }
    }


    public function getAllLanguage()
    {
        $data = LanguageModel::where('status', 1)
            ->select('id', 'name')
            ->get();

        if ($data) {
            $success_array = array('status' => 'success', 'message' => 'Data received successfully', 'data' => $data);
            return response()->json(array($success_array), 200);
        } else {
            $error_array = array('status' => 'success', 'message' => 'Something went wrong');
            return response()->json(array($error_array), 400);
        }
    }






    public function getAllMasterDatas(Request $request)
    {

        $hair = HairModel::where('status', 1)
            ->select('id', 'hair_color as name')
            ->get();

        $eye = EyeModel::where('status', 1)
            ->select('id', 'eye_color as name')
            ->get();

        $orientation = OrientationModel::where('status', 1)
            ->select('id', 'orientation_name as name')
            ->get();

        $body_type = BodyTypeModel::where('status', 1)
            ->select('id', 'body_type as name')
            ->get();

        $data = [
            'hair'          => $hair,
            'eye'           => $eye,
            'orientation'   => $orientation,
            'body_type'     => $body_type,
        ];


        if ($data) {
            $success_array = array('status' => 'success', 'message' => 'Data received successfully', 'data' => $data);
            return response()->json(array($success_array), 200);
        } else {
            $error_array = array('status' => 'success', 'message' => 'Something went wrong');
            return response()->json(array($error_array), 400);
        }
    }

    // public function updateImageurl()
    // {
    //     $country  = Country::get();

    //     foreach ($country  as $c) {

    //         $flag_name =  strtolower($c->iso2);

    //         $updateArray = array(
    //             'country_flag'  => ,
    //         );

    //            $country  = Country::where('id', $c->id)->update($updateArray);
    //     }

    //     exit('ok');
    // }


    public function manageRequest(Request $request)
    {
        $input         = $request->all();
        $service_id    = $input['service_id'];
        $request_id    = $input['request_id'];
        $user_id       = $input['user_id'];
        $type          = $input['type'];

        $start_time = now()->timezone('Asia/Kolkata');
        $end_time   = now()->timezone('Asia/Kolkata')->addMinutes(5);

        $insertArray = array(
            'service_id'    => $service_id,
            'request_id'    => $request_id,
            'user_id'       => $user_id,
            'type'          => $type,
            'start_time'    => $start_time,
            'end_time'      => $end_time,
        );

        $id  = ManageRequestModel::Where('service_id', $service_id)
            ->Where('request_id', $request_id)
            ->Where('user_id', $user_id)->value('id');

        if ((isset($id) && $id > 0)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'already exist your action',
            ], 400);
        }

        $insert =  ManageRequestModel::create($insertArray);

        $id = AllRequestModel::where('service_id', $service_id)
            ->where('request_id', $request_id)
            ->where('user_id', $user_id)
            ->pluck('id');

        if ($id) {
            $updateArray = array(
                'type' =>  $type
            );
            AllRequestModel::where('id', $id)->update($updateArray);
        }



        if ($insert['id'] > 0) {
            return response()->json([
                'status'  => 'success',
                'message' => ($type == 1) ? 'Request Accepted' : 'Request Declined',
            ], 200);
        }
    }

    public function serviceList(Request $request)
    {
        $user_id = $request->user_id;
        $type    = $request->type;
        $date    = $request->date;

        $currentTime = now()->timezone('Asia/Kolkata');

        $query = DB::table('all_request as ar')
            ->leftJoin('service as s', 'ar.service_id', '=', 's.id')
            ->leftJoin('personal_details as pd', 'pd.service_id', '=', 's.id')
            ->leftJoin('service_type as st', 'st.id', '=', 's.service_type')
            ->leftJoin('web_request as wr', 'wr.request_id', '=', 'ar.request_id')
            ->select(
                's.id',
                'st.service_type as service_type_name',
                's.service_type',
                's.title as service_title',
                's.description',
                's.photo',
                's.day_rate_min',
                's.day_rate_max',
                's.night_rate_min',
                's.night_rate_max',
                's.availability',
                'wr.language',
                'wr.request_id',
                'wr.location',
                'ar.type as status',
                'ar.start_time as accepted_time',
                'ar.created_at',
                'ar.start_time',
                'ar.end_time',


            );

        $query->where(function ($q) use ($currentTime) {
            $q->where(function ($q1) use ($currentTime) {
                $q1->where('ar.type', '!=', 3)
                    ->orWhere(function ($q2) use ($currentTime) {
                        $q2->where('ar.type', 3)
                            ->where(function ($q3) use ($currentTime) {
                                $q3->where('ar.start_time', '>', $currentTime)
                                    ->orWhere('ar.end_time', '<', $currentTime);
                            });
                    });
            });
        });

        if (isset($user_id) && $user_id != '') {
            $query = $query->Where('ar.user_id', $user_id);
        }

        if (isset($type) && $type != '') {
            if ($type == 1) {
                $query = $query->Where('ar.type', 1);
            } elseif ($type == 2) {
                $query = $query->Where('ar.type', 2);
            } elseif ($type == 3) {
                $query = $query->where('ar.type', 3);
            }
        }


        if ($date != '' && $date > 0) {
            $today = now()->startOfDay();
            switch ($date) {
                case 1:
                    $query = $query->whereDate('ar.created_at', $today);
                    break;
                case 2:
                    $query = $query->whereBetween('ar.created_at', [now()->subDays(7)->startOfDay(), now()]);
                    break;
                case 3:
                    $query = $query->whereMonth('ar.created_at', now()->month)
                        ->whereYear('ar.created_at', now()->year);
                    break;
                case 4:
                    $query = $query->whereBetween('ar.created_at', [now()->subDays(30)->startOfDay(), now()]);
                    break;
                case 5:
                    $query = $query->whereBetween('ar.created_at', [[now()->subMonths(90)->startOfDay(), now()]]);
                    break;
            }
        }

        $matchedServices = $query->orderBy('s.id', 'desc')->get();

        $matchedServices->transform(function ($service) {
            if (!empty($service->language)) {
                $ids = explode(',', $service->language);
                $service->language_names = DB::table('language')
                    ->whereIn('id', $ids)
                    ->pluck('name')
                    ->implode(', ');
            } else {
                $service->language_names = null;
            }
            return $service;
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'records found',
            'data'    => $matchedServices
        ], 200);
    }





    public function getdashboardList(Request $request)
    {
        $user_id = $request->input('user_id');
        // $current_time = now()->timezone('Asia/Kolkata');
        $start_time = now()->timezone('Asia/Kolkata');
        $end_time   = now()->timezone('Asia/Kolkata')->addMinutes(2);
        DB::enableQueryLog();
        $request_data = WebRequestModel::where(function ($q) use ($start_time, $end_time) {
            $q->whereBetween('start_time', [
                $start_time,
                $end_time
            ])->orWhereBetween('end_time', [
                $start_time,
                $end_time
            ]);
        })->get();
        $d = DB::getRawQueryLog();
        if ($request_data->isEmpty()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No active requests found',
                'data'    => []
            ], 400);
        }
        $results =  [
            'new_request' => []
        ];
        $matched = [];
        foreach ($request_data as $req) {
            $query = DB::table('service as s')
                ->leftJoin('personal_details as pd', 'pd.service_id', '=', 's.id')
                ->leftJoin('service_type as st', 'st.id', '=', 's.service_type')
                ->leftJoin('advertisement as a', 'a.service_id', '=', 's.id')
                ->leftJoin('manage_request as mr', function ($join) use ($req) {
                    $join->on('mr.service_id', '=', 's.id')
                        ->where('mr.request_id', '=', $req->request_id);
                })
                ->select(
                    's.id',
                    'st.service_type as service_type_name',
                    's.service_type',
                    's.title as service_title',
                    's.description',
                    's.photo',
                    's.day_rate_min',
                    's.day_rate_max',
                    's.night_rate_min',
                    's.night_rate_max',
                    's.availability',
                    's.language',
                    'pd.age',
                    'pd.height',
                    'pd.weight',
                    'pd.nationality',
                    'pd.hair_color',
                    'pd.orientation',
                    'pd.bust_size',
                    'pd.body_type',
                    'pd.breast_type',
                    'pd.public_hair',
                    'pd.eye_color',
                    'pd.smoker',
                    'pd.liquor',
                    'a.name as ad_name',
                    'a.phone as ad_phone',
                    'a.whatsapp',
                    'a.title',
                    'a.telegram',
                    'a.viber',
                    'a.location',
                    'a.latitiude',
                    'a.logitude',
                    'a.status',
                )
                ->where('s.user_id', $user_id)
                ->WhereNull('mr.type');
            if (!empty($req->service_type)) {
                $query->where('s.service_type', $req->service_type);
            }
            if (!empty($req->language)) {
                $requestedLang = explode(',', $req->language);
                $query->where(function ($q) use ($requestedLang) {
                    foreach ($requestedLang as $lang) {
                        $q->orWhereRaw("FIND_IN_SET(?, s.language)", [$lang]);
                    }
                });
            }
            $matchedServices = $query->orderBy('s.id', 'desc')->get();
            $matchedServices->transform(function ($item) use ($req) {
                $item->start_time = $req->start_time;
                $item->end_time   = $req->end_time;
                return $item;
            });
            $matchedServices->transform(function ($service) {
                if ($service->language) {
                    $ids = explode(',', $service->language);
                    $names = DB::table('language')
                        ->whereIn('id', $ids)
                        ->pluck('name')
                        ->toArray();
                    $service->language_names = implode(', ', $names);
                } else {
                    $service->language_names = null;
                }
                return $service;
            });
            foreach ($matchedServices as $val) {
                $val->request_id = $req->request_id ?? '';
                $matched[] = $val;

                $insertArray = array(
                    'service_id'    => $val->id,
                    'request_id'    => $val->request_id,
                    'user_id'       => $user_id,
                    'type'          => 3,
                    'start_time'    => $req->start_time,
                    'end_time'      => $req->end_time
                );

                $exists = AllRequestModel::where('service_id', $val->id)
                    ->where('request_id', $val->request_id)
                    ->where('user_id', $user_id)
                    ->where('type', 3)
                    ->exists();

                if (!$exists) {
                    AllRequestModel::create($insertArray);
                }
            }
            //$results['request_id'] = $req->request_id ?? '';
        }
        $results['new_request'] = $matched;
        $cont = (isset($results) && count($results) > 0) ? count($results) : 0;
        if (empty($results) && $cont == 0) {
            // if (empty($results) || (isset($results[0]['new_request']) && $results[0]['new_request']->isEmpty())) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No matching services found',
                'data'    => []
            ], 400);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Data received successfully',
            'count' => (isset($matched) && count($matched) > 0) ? count($matched) : 0,
            'data' => $results
        ]);
    }



    public function getAccepetedList(Request $request)
    {
        $user_id = $request->input('user_id');

        if ($user_id  != '') {

            $start_time = now()->timezone('Asia/Kolkata');
            $end_time   = now()->timezone('Asia/Kolkata')->addMinutes(5);


            $query =  DB::table('manage_request as mr')
                ->leftJoin('service as s', 's.id', '=', 'mr.service_id')
                ->leftJoin('personal_details as pd', 'pd.service_id', '=', 's.id')
                ->leftJoin('service_type as st', 'st.id', '=', 's.service_type')
                ->leftJoin('advertisement as a', 'a.service_id', '=', 's.id')
                ->leftJoin('service_requests as sr', 'sr.request_id', '=', 'mr.request_id')
                ->select(
                    's.id',
                    'st.service_type as service_type_name',
                    's.service_type',
                    's.title as service_title',
                    's.description',
                    's.photo',
                    's.day_rate_min',
                    's.day_rate_max',
                    's.night_rate_min',
                    's.night_rate_max',
                    's.availability',
                    's.language',
                    'pd.age',
                    'pd.height',
                    'pd.weight',
                    'pd.nationality',
                    'pd.hair_color',
                    'pd.orientation',
                    'pd.bust_size',
                    'pd.body_type',
                    'pd.breast_type',
                    'pd.public_hair',
                    'pd.eye_color',
                    'pd.smoker',
                    'pd.liquor',
                    'a.name as ad_name',
                    'a.phone as ad_phone',
                    'a.whatsapp',
                    'a.title',
                    'a.telegram',
                    'a.viber',
                    'a.location',
                    'a.latitiude',
                    'a.logitude',
                    'a.status',
                    'mr.start_time',
                    'mr.end_time',
                    'mr.request_id',
                    DB::raw('COALESCE(sr.service_request_type_id, 0) as contact_type'),
                    'sr.number',
                    DB::raw("CASE WHEN sr.id IS NULL THEN 0 ELSE 1 END AS is_mobile")
                )
                ->where(function ($q) use ($start_time, $end_time) {
                    $q->whereBetween('mr.start_time', [$start_time, $end_time])
                        ->orWhereBetween('mr.end_time', [$start_time, $end_time]);
                })
                ->where('s.user_id', $user_id)
                ->Where('mr.type', 1);

            $acceptedServices = $query->orderBy('s.id', 'desc')->get();

            $acceptedServices->transform(function ($service) {
                if (!empty($service->language)) {

                    $ids = explode(',', $service->language);

                    $names = DB::table('language')
                        ->whereIn('id', $ids)
                        ->pluck('name')
                        ->toArray();

                    $service->language_names = implode(', ', $names);
                } else {
                    $service->language_names = null;
                }
                return $service;
            });


            $results['accepted_request'] = $acceptedServices;
            $cont = (isset($results) && count($results) > 0) ? count($results) : 0;

            if (empty($results) && $cont == 0) {

                return response()->json([
                    'status'  => 'error',
                    'message' => 'No matching services found',
                    'data'    => []
                ], 400);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Data received successfully',
                'count' => (isset($matched) && count($matched) > 0) ? count($matched) : 0,
                'data' => $results
            ]);
        } else {

            return response()->json([
                'status' => 'error',
                'message' => 'Parameter Missing',

            ], 400);
        }
    }



    public function uploadVedio(Request $request)
    {

        $service_id = $request->input('service_id');
        $vedio = $request->file('vedio');

        if ($service_id != '' &&  $vedio != '') {

            if ($request->hasFile('vedio')) {

                $vedioName = 'vedio' . time() . '_' . str_replace(' ', '_', $vedio->getClientOriginalName());
                $vedio->move(public_path('/uploads/vedio/'), $vedioName);
                $vedioUrl = URL::to('/') . '/uploads/vedio/' . $vedioName;
            }

            if ($vedioUrl) {
                $updateArray = array(
                    'vedio' => $vedioUrl,
                    'is_verified' => 1,
                );
            }

            $update =  PersonalDetails::where('service_id', $service_id)->update($updateArray);

            if ($update) {

                $success_array = array('status' => 'success', 'message' => 'Upload Successfully');
                return response()->json(array($success_array), 200);
            } else {

                $error_array = array('status' => 'error', 'message' => 'Something went wrong');
                return response()->json(array($error_array), 400);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter Missing',

            ], 400);
        }
    }




    public function vedioVerify(Request $request)
    {

        $service_id = $request->input('service_id');

        if ($service_id != '') {

            $updateArray = array(
                'is_verified' => 2,
            );

            $update =  PersonalDetails::where('service_id', $service_id)->update($updateArray);

            if ($update) {

                $success_array = array('status' => 'success', 'message' => 'Verifed Successfully');
                return response()->json(array($success_array), 200);
            } else {

                $error_array = array('status' => 'error', 'message' => 'Something went wrong');
                return response()->json(array($error_array), 400);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter Missing',

            ], 400);
        }
    }


    //  public function serviceList(Request $request)
    // {
    //     $user_id = $request->user_id;
    //     $type    = $request->type;
    //     $date    = $request->date;

    //     $currentTime = now()->timezone('Asia/Kolkata');

    //     $query = DB::table('all_request as ar')
    //         ->leftJoin('service as s', 'ar.service_id', '=', 's.id')
    //         ->leftJoin('personal_details as pd', 'pd.service_id', '=', 's.id')
    //         ->leftJoin('service_type as st', 'st.id', '=', 's.service_type')
    //         ->leftJoin('web_request as wr', 'wr.request_id', '=', 'ar.request_id')
    //         ->select(
    //             's.id',
    //             'st.service_type as service_type_name',
    //             's.service_type',
    //             's.title as service_title',
    //             's.description',
    //             's.photo',
    //             's.day_rate_min',
    //             's.day_rate_max',
    //             's.night_rate_min',
    //             's.night_rate_max',
    //             's.availability',
    //             'wr.language',
    //             'wr.request_id',
    //             'wr.location',
    //             'ar.type as status',
    //             'ar.start_time as accepted_time',
    //             'ar.created_at',
    //             'ar.start_time',
    //             'ar.end_time',


    //         );

    //     $query->where(function ($q) use ($currentTime) {
    //         $q->where('ar.type', 3)
    //             ->where(function ($q2) use ($currentTime) {
    //                 $q2->where('ar.start_time', '>', $currentTime)
    //                     ->orWhere('ar.end_time', '<', $currentTime);
    //             });
    //     });

    //     if (isset($user_id) && $user_id != '') {
    //         $query = $query->Where('ar.user_id', $user_id);
    //     }

    //     if (isset($type) && $type != '') {
    //         if ($type == 1) {
    //             $query = $query->Where('ar.type', 1);
    //         } elseif ($type == 2) {
    //             $query = $query->Where('ar.type', 2);
    //         } elseif ($type == 3) {
    //             $query = $query->where('ar.type', 3);
    //         }
    //     }


    //     if ($date != '' && $date > 0) {
    //         $today = now()->startOfDay();
    //         switch ($date) {
    //             case 1:
    //                 $query = $query->whereDate('ar.created_at', $today);
    //                 break;
    //             case 2:
    //                 $query = $query->whereBetween('ar.created_at', [now()->subDays(7)->startOfDay(), now()]);
    //                 break;
    //             case 3:
    //                 $query = $query->whereMonth('ar.created_at', now()->month)
    //                     ->whereYear('ar.created_at', now()->year);
    //                 break;
    //             case 4:
    //                 $query = $query->whereBetween('ar.created_at', [now()->subDays(30)->startOfDay(), now()]);
    //                 break;
    //             case 5:
    //                 $query = $query->whereBetween('ar.created_at', [[now()->subMonths(90)->startOfDay(), now()]]);
    //                 break;
    //         }
    //     }

    //     $matchedServices = $query->orderBy('s.id', 'desc')->get();

    //     $matchedServices->transform(function ($service) {
    //         if (!empty($service->language)) {
    //             $ids = explode(',', $service->language);
    //             $service->language_names = DB::table('language')
    //                 ->whereIn('id', $ids)
    //                 ->pluck('name')
    //                 ->implode(', ');
    //         } else {
    //             $service->language_names = null;
    //         }
    //         return $service;
    //     });

    //     return response()->json([
    //         'status'  => 'success',
    //         'message' => 'records found',
    //         'data'    => $matchedServices
    //     ], 200);
    // }
}
