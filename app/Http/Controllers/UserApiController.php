<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use DB;
use Log;
use Auth;
use Hash;
use Storage;
use Setting;
use Exception;
use Notification;
use Mail;
use Validator;

use Carbon\Carbon;
use App\Http\Controllers\SendPushNotification;
use App\Notifications\ResetPasswordOTP;
use App\Helpers\Helper;

use App\Card;
use App\User;
use App\ContactMessage;
use App\Provider;
use App\Settings;
use App\Promocode;
use App\ServiceType;
use App\UserRequests;
use App\RequestFilter;
use App\PromocodeUsage;
use App\ProviderService;
use App\UserRequestRating;
use App\Http\Controllers\ProviderResources\TripController;


class UserApiController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function testing(){
        print_r($_GET);
        die;
    }
    
    
    public function forgotpassword(Request $request){    
        $data=$request->all();
         $data['type']=(string) $data['type'];
        if(!in_array($data['type'],array(0,1))){
          return response()->json(['error' => 'Please select the user type.'], 200);
        }
     if(empty($data['type'])){//driver
          $validation=array('email' => 'required|email|exists:providers,email'); 
     }else{//passenger        
         $validation=array('email' => 'required|email|exists:users,email');
     }
        $validator=Validator::make($request->all(),$validation);
         if($validator->fails()){
             $errors=$validator->errors();
             $all=array();
             foreach($errors->all() as $k=>$v){
                 $all=$v;
             }
             return response()->json(['error' =>$all],200);
         }
        try{    
            if(empty($data['type'])){//driver
               $user = Provider::where('email' , $request->email)->first();
             }else{//passenger
               $user = User::where('email' , $request->email)->first();
             }           
            $password=$this->generateRandomString();
            $user->password = bcrypt($password);
            $user->save();
            $to = $request->email;
            $subject = "Forgot Password";  
            $logo='http://clientstagingdev.com/yourcab/public/uploads/logo.png';             
            $htmlContent='<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head> 
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
	<link href="http://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet" type="text/css">
	<title>Your Cab Email</title>
</head>

	<body marginheight="0" topmargin="0" marginwidth="0" style="bgcolor:blue ;margin: 0px; font:12px arial; color:#000;">	<table cellspacing="0" border="0" align="center" cellpadding="0" width="600" style="border:1px solid #ccc; margin-top:10px;">
			<tr>
				<td>
					<table cellspacing="0" border="0" align="center" cellpadding="20" width="100%">
						<!-- -->
						<tr align="center" >
							<td style="font-family:arial; padding-bottom:40px;"><strong>
                          <img src="'.$logo.'" alt="Your Cab">
							</strong></td>
						</tr><!-- -->
					</table>
					<table cellspacing="0" border="0" align="center" cellpadding="10" width="100%" style="border:0px solid #efefef; margin-top:0px; padding:40px;">
						<tr>
						<td><h2>Hello '.ucfirst($user->first_name).' '.ucfirst($user->last_name).',</h2></td>
						</tr>
						<tr>
						    <td>                            
                            <h4><p>Your password has been updated as you requested.<br><br>Your new password is : '.$password.'</p></h4></td>
						</tr>
						<tr>
							<td>
								<table cellspacing="0" border="0" cellpadding="0" width="100%">	
									<tr>
										<td><h3>Best Regards</h3>
											<h3>Your Cab Team</h3>
										</td>
									</tr>
								</table>
							</td>
							<td width="30"></td> 
						</tr>
					</table>
					<table cellspacing="0" border="0" align="center" cellpadding="0" width="100%" style="border:0px solid #efefef; margin-top:20px; padding:0px;">
						<tr>
							<td align="center" style="font-family:PT Sans,sans-serif; font-size:13px; padding:15px 0; border-top:1px solid #efefef;"> 
							<strong><b>Your Cab</b></strong></td> 
						</tr>
					</table>
				</td>   
			</tr>
		</table></body>
</html>';
        require 'phpmailer/PHPMailerAutoload.php';
        $mail = new \PHPMailer;
        $mail->IsSMTP();
        $mail->Mailer = "smtp";
        $mail->SMTPAuth = true;
        $mail->Host = "mail.smtp2go.com";
        $mail->SMTPSecure = 'ssl';
        $mail->Port =443;
        $mail->Username = "swaran.lata@imarkinfotech.com";
        $mail->Password = "bQvXa66Yetn6";
        $mail->setFrom('swaran.lata@imarkinfotech.com', 'Your Cab');
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->msgHTML($htmlContent);
        $mail->send();       
        return response()->json([
           'message' => 'Your password has been updated and sent to your email.'               
        ]);
        }catch(Exception $e){
                return response()->json(['error' => trans('api.something_went_wrong')], 200);
        }
    }

    public function signup(Request $request)
    {
        $this->validate($request, [
                'social_unique_id' => ['required_if:login_by,facebook,google','unique:users'],
                'device_type' => 'required|in:android,ios',
                'device_token' => 'required',
                'device_id' => 'required',
                'login_by' => 'required|in:manual,facebook,google',
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'mobile' => 'required',
                'password' => 'required|min:6',
            ]);

        try{
            
            $User = $request->all();

            $User['payment_mode'] = 'CASH';
            $User['password'] = bcrypt($request->password);
            $User = User::create($User);

            return $User;
        } catch (Exception $e) {
             return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function logout(Request $request)
    {
        try {
            User::where('id', $request->id)->update(['device_id'=> '', 'device_token' => '']);
            return response()->json(['message' => trans('api.logout_success')]);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function change_password(Request $request){
       
        $this->validate($request, [
                'password' => 'required|min:6',
                //'password' => 'required|confirmed|min:6',
                'old_password' => 'required',
            ]);
        $User = Auth::user();        
        if(Hash::check($request->old_password, $User->password))
        {
            $User->password = bcrypt($request->password);
            $User->save();
            if($request->ajax()) {
                return response()->json(['message' => trans('api.user.password_updated')]);
            }else{
                return response()->json(['message' =>  'Password Updated']);
                //return back()->with('flash_success', 'Password Updated');                
            }die('hhh');
        } else {
            return response()->json(['error' => trans('api.user.incorrect_password')], 200);
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function update_location(Request $request){

        $this->validate($request, [
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

        if($user = User::find(Auth::user()->id)){

            $user->latitude = $request->latitude;
            $user->longitude = $request->longitude;
            if(!empty($this->getCityName($request->latitude,$request->longitude))){
                $user->city = $this->getCityName($request->latitude,$request->longitude);
            }
            $user->save();

            return response()->json(['message' => trans('api.user.location_updated')]);

        }else{

            return response()->json(['error' => trans('api.user.user_not_found')], 500);

        }

    }
    public function getCityName($lat=null,$long=null){
        $get_API = "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyDAaHXZgvDNUGHQR09PDggnORlIXmuhuDM&latlng=";
        $get_API .= round($lat,2).",";
        $get_API .= round($long,2);         

        $jsonfile = file_get_contents($get_API.'&sensor=false');
        $jsonarray = json_decode($jsonfile);    
        if (isset($jsonarray->results[1]->address_components[1]->long_name)) {
            return($jsonarray->results[1]->address_components[1]->long_name);
        }
        
        
    }
    
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function details(Request $request){

        $this->validate($request, [
            'device_type' => 'in:android,ios',
        ]);

        try{

            if($user = User::find(Auth::user()->id)){

                if($request->has('device_token')){
                    $user->device_token = $request->device_token;
                }

                if($request->has('device_type')){
                    $user->device_type = $request->device_type;
                }

                if($request->has('device_id')){
                    $user->device_id = $request->device_id;
                }
                $user->save();
                $user->currency = Setting::get('currency');
                $user->sos = Setting::get('sos_number', '911');
                return $user;

            } else {
                return response()->json(['error' => trans('api.user.user_not_found')], 500);
            }
        }
        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function update_profile(Request $request)
    {

        $this->validate($request, [
                'first_name' => 'required|max:255',
                'last_name' => 'max:255',
                'email' => 'email|unique:users,email,'.Auth::user()->id,
                'mobile' => 'required',
                'picture' => 'mimes:jpeg,bmp,png',
            ]);

         try {

            $user = User::findOrFail(Auth::user()->id);

            if($request->has('first_name')){ 
                $user->first_name = $request->first_name;
            }
            
            if($request->has('last_name')){
                $user->last_name = $request->last_name;
            }
            
            if($request->has('email')){
                $user->email = $request->email;
            }
        
            if($request->has('mobile')){
                $user->mobile = $request->mobile;
            }

            if ($request->picture != "") {
                Storage::delete($user->picture);
                $user->picture = $request->picture->store('user/profile');
            }

            $user->save();

            if($request->ajax()) {
                return response()->json($user);
            }else{
                return back()->with('flash_success', trans('api.user.profile_updated'));
            }
        }

        catch (ModelNotFoundException $e) {
             return response()->json(['error' => trans('api.user.user_not_found')], 500);
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function services() {

        if($serviceList = ServiceType::all()) {
            return $serviceList;
        } else {
            return response()->json(['error' => trans('api.services_not_found')], 500);
        }

    }
    
     public function terms() {
         $setting= DB::table('settings')->where('key','terms')->first();
         if(!empty($setting)){
             $string = str_replace("\n", "", $setting->value);
             $string = str_replace("\r", "", $string);
             echo json_encode(array('status'=>'true','description'=>$string));
             die;
         }else{
             echo json_encode(array('status'=>'false','description'=>''));
             die;    
         }


    }
     public function privacy() {
         $setting= DB::table('settings')->where('key','page_privacy')->first();
         if(!empty($setting)){
             $string = str_replace("\n", "", $setting->value);
             $string = str_replace("\r", "", $string);
             echo json_encode(array('status'=>'true','description'=>$string));
             die;
         }else{
             echo json_encode(array('status'=>'false','description'=>''));
             die;    
         }
     }
    public function aboutus() {
         $setting= DB::table('settings')->where('key','aboutus')->first();
         if(!empty($setting)){
             $string = str_replace("\n", "", $setting->value);
             $string = str_replace("\r", "", $string);
             echo json_encode(array('status'=>'true','description'=>$string));
             die;
         }else{
             echo json_encode(array('status'=>'false','description'=>''));
             die;    
         }
     }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function send_request(Request $request) {

        $this->validate($request, [
                'd_address' => 'required',
                's_address' => 'required',
               // 's_latitude' => 'required|numeric',
               // 'd_latitude' => 'required|numeric',
                //'s_longitude' => 'required|numeric',
               // 'd_longitude' => 'required|numeric',
                'service_type' => 'required|numeric|exists:service_types,id',
                'promo_code' => 'exists:promocodes,promo_code',
                'distance' => 'required|numeric',
                'use_wallet' => 'numeric',
                'payment_mode' => 'required|in:CASH,CARD,PAYPAL',
                //'card_id' => ['required_if:payment_mode,CARD','exists:cards,card_id,user_id,'.Auth::user()->id],
            ], [
            's_address.required' => 'Please enter your pick up location.',
            'd_address.required' => 'Please enter your drop off location.'
        ]);

       /* Log::info('New Request from User: '.Auth::user()->id);
        Log::info('Request Details:', $request->all());*/
        
        $ActiveRequests = UserRequests::PendingRequest(Auth::user()->id)->count();
        if($ActiveRequests > 0) {
             return response()->json(['error' => 'Already request is in progress. Try again later'], 500);
        }

      /*  if($request->has('schedule_date') && $request->has('schedule_time')){
            $beforeschedule_time = (new Carbon("$request->schedule_date $request->schedule_time"))->subHour(1);
            $afterschedule_time = (new Carbon("$request->schedule_date $request->schedule_time"))->addHour(1);

            $CheckScheduling = UserRequests::where('status','SCHEDULED')
                            ->where('user_id', Auth::user()->id)
                            ->whereBetween('schedule_at',[$beforeschedule_time,$afterschedule_time])
                            ->count();


            if($CheckScheduling > 0){
                if($request->ajax()) {
                    return response()->json(['error' => trans('api.ride.request_scheduled')], 500);
                }else{
                    return redirect('dashboard')->with('flash_error', 'Already request is Scheduled on this time.');
                }
            }

        }*/

        $distance = Setting::get('provider_search_radius', '10'); 
        $latitude = $request->s_latitude;
        $longitude = $request->s_longitude;
        $service_type = $request->service_type;

        $Providers = Provider::with('service')
            ->select(DB::Raw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) AS distance"),'id')
            ->where('status', 'approved')
            ->whereRaw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
            ->whereHas('service', function($query) use ($service_type){
                        $query->where('status','active');
                        $query->where('service_type_id',$service_type);
                    })
            ->orderBy('distance')
            ->get();
        $details = "https://maps.googleapis.com/maps/api/directions/json?origin=".$request->s_latitude.",".$request->s_longitude."&destination=".$request->d_latitude.",".$request->d_longitude."&mode=driving&key=".env('GOOGLE_MAP_KEY');
            $json = curl($details);
            $details = json_decode($json, TRUE);
      
            if(isset($details['status']) and $details['status']=='ZERO_RESULTS'){
                return response()->json(['error' => 'No Route defined.'], 500);
            }
            $route_key = $details['routes'][0]['overview_polyline']['points']; 
            $map_icon = asset('asset/marker.png');
            $map_icon = '';
            $route = "https://maps.googleapis.com/maps/api/staticmap?".
                                "autoscale=1".
                                "&size=320x130".
                                "&maptype=terrian".
                                "&format=png".
                                "&visual_refresh=true".
                                "&markers=icon:".$map_icon."%7C".$request->s_latitude.",".$request->s_longitude.
                                "&markers=icon:".$map_icon."%7C".$request->d_latitude.",".$request->d_longitude.
                                "&path=color:0x191919|weight:3|enc:".$route_key.
                                "&key=".env('GOOGLE_MAP_KEY');
       
        // List Providers who are currently busy and add them to the filter list.

        if(count($Providers) == 0) {
            if($request->ajax()) {
                // Push Notification to User
                return response()->json(['message' => trans('api.ride.no_providers_found')]); 
            }else{
                 return response()->json(['message' =>'No Providers Found! Please try again.']); 
                return back()->with('flash_success', 'No Providers Found! Please try again.');
            }
        }
        try{

            /*$details = "https://maps.googleapis.com/maps/api/directions/json?origin=".$request->s_latitude.",".$request->s_longitude."&destination=".$request->d_latitude.",".$request->d_longitude."&mode=driving&key=".env('GOOGLE_MAP_KEY');
            $json = curl($details);
            $details = json_decode($json, TRUE);
            $route_key = $details['routes'][0]['overview_polyline']['points']; */
            $UserRequest = new UserRequests;
            $UserRequest->booking_id = Helper::generate_booking_id();
            $UserRequest->user_id = Auth::user()->id;
            $UserRequest->current_provider_id = $Providers[0]->id;
            $UserRequest->service_type_id = $request->service_type;
            $UserRequest->payment_mode = $request->payment_mode;
            $UserRequest->status = 'SEARCHING';
            $UserRequest->s_address = $request->s_address ? : "";
            $UserRequest->d_address = $request->d_address ? : "";
            $UserRequest->s_latitude = $request->s_latitude;
            $UserRequest->s_longitude = $request->s_longitude;
            $UserRequest->d_latitude = $request->d_latitude;
            $UserRequest->d_longitude = $request->d_longitude;
            $UserRequest->route_key = $route;
            $UserRequest->distance = $request->distance;
            if(Auth::user()->wallet_balance > 0){
                $UserRequest->use_wallet = $request->use_wallet ? : 0;
            }
            $UserRequest->assigned_at = Carbon::now();
            $UserRequest->route_key = $route;

            if($Providers->count() <= Setting::get('surge_trigger') && $Providers->count() > 0){
                $UserRequest->surge = 1;
            }

            if($request->has('schedule_date') && $request->has('schedule_time')){
                $UserRequest->schedule_at = date("Y-m-d H:i:s",strtotime("$request->schedule_date $request->schedule_time"));
            }
            $UserRequest->save();

           /* Log::info('New Request id : '. $UserRequest->id .' Assigned to provider : '. $UserRequest->current_provider_id);*/


            // update payment mode 

          /*  User::where('id',Auth::user()->id)->update(['payment_mode' => $request->payment_mode]);*/

           /* if($request->has('card_id')){

                Card::where('user_id',Auth::user()->id)->update(['is_default' => 0]);
                Card::where('card_id',$request->card_id)->update(['is_default' => 1]);
            }*/

            (new SendPushNotification)->IncomingRequest($Providers[0]->id);

            foreach ($Providers as $key => $Provider) {

                $Filter = new RequestFilter;
                // Send push notifications to the first provider
                // incoming request push to provider
                $Filter->request_id = $UserRequest->id;
                $Filter->provider_id = $Provider->id; 
                $Filter->save();
            }

            if($request->ajax()) {
                return response()->json([
                        'message' => 'New request Created!',
                        'request_id' => $UserRequest->id,
                        'current_provider' => $UserRequest->current_provider_id,
                    ]);
            }else{
                return response()->json([
                        'message' => 'New request Created!',
                        'request_id' => $UserRequest->id,
                        'current_provider' => $UserRequest->current_provider_id,
                    ]);
                return redirect('dashboard');
            }

        } catch (Exception $e) {
            if($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
            }else{
                return response()->json(['error' =>'Something went wrong while sending request. Please try again.'], 500);
                return back()->with('flash_error', 'Something went wrong while sending request. Please try again.');
            }
        }
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function cancel_request(Request $request) {

        $this->validate($request, [
            'request_id' => 'required|numeric|exists:user_requests,id,user_id,'.Auth::user()->id,
        ]);

        try{

            $UserRequest = UserRequests::findOrFail($request->request_id);

            if($UserRequest->status == 'CANCELLED')
            {
                if($request->ajax()) {
                    return response()->json(['error' => trans('api.ride.already_cancelled')], 500); 
                }else{
                    return back()->with('flash_error', 'Request is Already Cancelled!');
                }
            }

            if(in_array($UserRequest->status, ['SEARCHING','STARTED','ARRIVED','SCHEDULED'])) {

                if($UserRequest->status != 'SEARCHING'){
                    $this->validate($request, [
                        'cancel_reason'=> 'max:255',
                    ]);
                }

                $UserRequest->status = 'CANCELLED';
                $UserRequest->cancel_reason = $request->cancel_reason;
                $UserRequest->cancelled_by = 'USER';
                $UserRequest->save();

                RequestFilter::where('request_id', $UserRequest->id)->delete();

                if($UserRequest->status != 'SCHEDULED'){

                    if($UserRequest->provider_id != 0){

                        ProviderService::where('provider_id',$UserRequest->provider_id)->update(['status' => 'active']);

                    }
                }

                 // Send Push Notification to User
                (new SendPushNotification)->UserCancellRide($UserRequest);

                if($request->ajax()) {
                    return response()->json(['message' => trans('api.ride.ride_cancelled')]); 
                }else{
                    return redirect('dashboard')->with('flash_success','Request Cancelled Successfully');
                }

            } else {
                if($request->ajax()) {
                    return response()->json(['error' => trans('api.ride.already_onride')], 500); 
                }else{
                    return back()->with('flash_error', 'Service Already Started!');
                }
            }
        }

        catch (ModelNotFoundException $e) {
            if($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            }else{
                return back()->with('flash_error', 'No Request Found!');
            }
        }

    }

    /**
     * Show the request status check.
     *
     * @return \Illuminate\Http\Response
     */

    public function request_status_check() {

        try{
            $check_status = ['CANCELLED', 'SCHEDULED'];
            $UserRequests = UserRequests::UserRequestStatusCheck(Auth::user()->id, $check_status)
                                        ->get()
                                        ->toArray();
            $finalArray=array();
            if(!empty($UserRequests)){
                foreach($UserRequests as $k=>$v){
                    if($v['status']=='COMPLETED' and $v['user_rated']==1){
                        continue;
                    }
                    $finalArray[]=$v;
                }                
            }
            $search_status = ['SEARCHING','SCHEDULED'];
            $UserRequestsFilter = UserRequests::UserRequestAssignProvider(Auth::user()->id,$search_status)->get(); 
            $Timeout = Setting::get('provider_select_timeout', 180);
            if(!empty($UserRequestsFilter)){
                for ($i=0; $i < sizeof($UserRequestsFilter); $i++) {
                    $ExpiredTime = $Timeout - (time() - strtotime($UserRequestsFilter[$i]->assigned_at));
                    if($UserRequestsFilter[$i]->status == 'SEARCHING' && $ExpiredTime < 0) {
                        $Providertrip = new TripController();
                        $Providertrip->assign_next_provider($UserRequestsFilter[$i]->id);
                    }else if($UserRequestsFilter[$i]->status == 'SEARCHING' && $ExpiredTime > 0){
                        break;
                    }
                }
            }
            return response()->json(['data' => $finalArray]);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */


    public function rate_provider(Request $request) {
       

        $this->validate($request, [
                'request_id' => 'required|integer',
                //'request_id' => 'required|integer|exists:user_requests,id,user_id,'.Auth::user()->id,
                'rating' => 'required|integer|in:1,2,3,4,5',
                'comment' => 'max:255',
            ]);
       
        $UserRequests = UserRequests::where('id' ,$request->request_id)
                ->where('status' ,'COMPLETED')
                ->where('paid', 0)
                ->first();

        if ($UserRequests) {
            if($request->ajax()){
                return response()->json(['error' => trans('api.user.not_paid')], 500);
            } else {
                return response()->json(['error' => trans('api.user.not_paid')], 500);
                //return back()->with('flash_error', 'Service Already Started!');
            }
        }

        try{
            $UserRequest = UserRequests::findOrFail($request->request_id);      
            $rating=UserRequestRating::where('request_id',$request->request_id)->first();

        
            if($UserRequest->user_rated == 0) {
                UserRequestRating::create([
                        'provider_id' => $UserRequest->provider_id,
                        'user_id' => $UserRequest->user_id,
                        'request_id' => $UserRequest->id,
                        'user_rating' => $request->rating,
                        'user_comment' => $request->comment,
                    ]);
            } else {
                return response()->json(['error' =>'already rated.']); 
                $UserRequest->rating->update([
                        'user_rating' => $request->rating,
                        'user_comment' => $request->comment,
                    ]);
            }
            $UserRequest->user_rated = 1;
            $UserRequest->save();
            $average = UserRequestRating::where('provider_id', $UserRequest->provider_id)->avg('user_rating');
            Provider::where('id',$UserRequest->provider_id)->update(['rating' => $average]);
            // Send Push Notification to Provider 
            if($request->ajax()){
                return response()->json(['message' => trans('api.ride.provider_rated')]); 
            }else{
                return response()->json(['message' => trans('api.ride.provider_rated')]); 
              //  return redirect('dashboard')->with('flash_success', 'Driver Rated Successfully!');
            }
        } catch (Exception $e) {
            if($request->ajax()){
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
            }else{
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
                //return back()->with('flash_error', 'Something went wrong');
            }
        }

    } 


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function trips() {
    
        try{
            $getDeletedTrips=DB::table('delete_trips')->
               where('deleted_by','user')->
               where('user_id', Auth::user()->id)->get();
                $allTrips=array();
                if(!empty($getDeletedTrips)){
                    foreach($getDeletedTrips as $k=>$v){
                        $allTrips[]=$v->trip_id;
                    }
                }
            $UserRequests = UserRequests::UserTrips(Auth::user()->id)->get();            
            if(!empty($UserRequests)){
                $map_icon = asset('asset/img/marker-start.png');
                $map_icon = asset('asset/marker.png');
                foreach ($UserRequests as $key => $value) {                   
                    $UserRequests[$key]->static_map =$value->route_key; 
                    /*$UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?".
                            "autoscale=1".
                            "&size=320x130".
                            "&maptype=terrian".
                            "&format=png".
                            "&visual_refresh=true".
                            "&markers=icon:".$map_icon."%7C".$value->s_latitude.",".$value->s_longitude.
                            "&markers=icon:".$map_icon."%7C".$value->d_latitude.",".$value->d_longitude.
                            "&path=color:0x191919|weight:3|enc:".$value->route_key.
                            "&key=".env('GOOGLE_MAP_KEY');*/
                }
            }
            $dataFinal=array();
            if(!empty($UserRequests)){
                foreach($UserRequests as $k=>$v){
                  if(!in_array($v['id'],$allTrips)){
                     $dataFinal[]=$v; 
                  }  
                }
            }
            
            
            return $dataFinal;
        }

        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }
    
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function estimated_fare(Request $request){
       /* \Log::info('Estimate', $request->all());*/
       /* print_r($request->all());die;*/
        $this->validate($request,[
                's_latitude' => 'required|numeric',
                's_longitude' => 'required|numeric',
                'd_latitude' => 'required|numeric',
                'd_longitude' => 'required|numeric',
                'service_type' => 'required|numeric|exists:service_types,id',
            ]);

        try{

            $details = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$request->s_latitude.",".$request->s_longitude."&destinations=".$request->d_latitude.",".$request->d_longitude."&mode=driving&sensor=false&key=".env('GOOGLE_MAP_KEY');

            $json = curl($details);

            $details = json_decode($json, TRUE);

            $meter = $details['rows'][0]['elements'][0]['distance']['value'];
            $time = $details['rows'][0]['elements'][0]['duration']['text'];
            $seconds = $details['rows'][0]['elements'][0]['duration']['value'];

            $kilometer = round($meter/1000);
            $minutes = round($seconds/60);

            $tax_percentage = Setting::get('tax_percentage');
            $commission_percentage = Setting::get('commission_percentage');
            $service_type = ServiceType::findOrFail($request->service_type);
            
            $price = $service_type->fixed;

            if($service_type->calculator == 'MIN') {
                $price += $service_type->minute * $minutes;
            } else if($service_type->calculator == 'HOUR') {
                $price += $service_type->minute * 60;
            } else if($service_type->calculator == 'DISTANCE') {
                $price += ($kilometer * $service_type->price);
            } else if($service_type->calculator == 'DISTANCEMIN') {
                $price += ($kilometer * $service_type->price) + ($service_type->minute * $minutes);
            } else if($service_type->calculator == 'DISTANCEHOUR') {
                $price += ($kilometer * $service_type->price) + ($service_type->minute * $minutes * 60);
            } else {
                $price += ($kilometer * $service_type->price);
            }

            $tax_price = ( $tax_percentage/100 ) * $price;
            $total = $price + $tax_price;

            $ActiveProviders = ProviderService::AvailableServiceProvider($request->service_type)->get()->pluck('provider_id');

            $distance = Setting::get('provider_search_radius', '10');
            $latitude = $request->s_latitude;
            $longitude = $request->s_longitude;

            $Providers = Provider::whereIn('id', $ActiveProviders)
                ->where('status', 'approved')
                ->whereRaw("(1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                ->get();
            $surge = 0;            
            if($Providers->count() <= Setting::get('surge_trigger') && $Providers->count() > 0){
                $surge_price = (Setting::get('surge_percentage')/100) * $total;
                $total += $surge_price;
                $surge = 1;
            }
            return response()->json([
                    'estimated_fare' => round($total,2), 
                    'distance' => $kilometer,
                    'time' => $time,
                    'surge' => $surge,
                    'surge_value' => '1.4X',
                    'tax_price' => $tax_price,
                    'base_price' => $service_type->fixed,
                    'wallet_balance' => Auth::user()->wallet_balance
                ]);

        } catch(Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function trip_details(Request $request) {
         $this->validate($request, [
                'request_id' => 'required|integer|exists:user_requests,id',
            ]);    
         try{
            $UserRequests = UserRequests::UserTripDetails(Auth::user()->id,$request->request_id)->get();
            if(!empty($UserRequests)){
                $map_icon = asset('asset/img/marker-start.png');
                $map_icon = asset('asset/marker.png');
                foreach ($UserRequests as $key => $value) {
                    $UserRequests[$key]->static_map =$value->route_key; 
                   /* $UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?".
                            "autoscale=1".
                            "&size=320x130".
                            "&maptype=terrian".
                            "&format=png".
                            "&visual_refresh=true".
                            "&markers=icon:".$map_icon."%7C".$value->s_latitude.",".$value->s_longitude.
                            "&markers=icon:".$map_icon."%7C".$value->d_latitude.",".$value->d_longitude.
                            "&path=color:0x191919|weight:3|enc:".$value->route_key.
                            "&key=".env('GOOGLE_MAP_KEY');*/
                }
            }
            $createdTime=strtotime($UserRequests[0]['finished_at']);
            $after24hours=date('Y-m-d H:i:s', strtotime('+1 day', $createdTime));
            if(strtotime($after24hours)<time()){
             /* $UserRequests[0]['user']['mobile']='';*/
              $UserRequests[0]['provider']['mobile']='';
            }
           /*  print_r($UserRequests);
             die;*/
            return $UserRequests;
        }

        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }

    /**
     * get all promo code.
     *
     * @return \Illuminate\Http\Response
     */

    public function promocodes() {
        try{
            $this->check_expiry();

            return PromocodeUsage::Active()
                    ->where('user_id', Auth::user()->id)
                    ->with('promocode')
                    ->get();

        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    } 


    public function check_expiry(){
        try{
            $Promocode = Promocode::all();
            foreach ($Promocode as $index => $promo) {
                if(date("Y-m-d") > $promo->expiration){
                    $promo->status = 'EXPIRED';
                    $promo->save();
                    PromocodeUsage::where('promocode_id', $promo->id)->update(['status' => 'EXPIRED']);
                }else{
                    PromocodeUsage::where('promocode_id', $promo->id)->update(['status' => 'ADDED']);
                }
            }
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }


    /**
     * add promo code.
     *
     * @return \Illuminate\Http\Response
     */

    public function add_promocode(Request $request) {

        $this->validate($request, [
                'promocode' => 'required|exists:promocodes,promo_code',
            ]);

        try{

            $find_promo = Promocode::where('promo_code',$request->promocode)->first();

            if($find_promo->status == 'EXPIRED' || (date("Y-m-d") > $find_promo->expiration)){

                if($request->ajax()){

                    return response()->json([
                        'message' => trans('api.promocode_expired'), 
                        'code' => 'promocode_expired'
                    ]);

                }else{
                    return back()->with('flash_error', trans('api.promocode_expired'));
                }

            }elseif(PromocodeUsage::where('promocode_id',$find_promo->id)->where('user_id', Auth::user()->id)->where('status','ADDED')->count() > 0){

                if($request->ajax()){

                    return response()->json([
                        'message' => trans('api.promocode_already_in_use'), 
                        'code' => 'promocode_already_in_use'
                        ]);

                }else{
                    return back()->with('flash_error', 'Promocode Already in use');
                }

            }else{

                $promo = new PromocodeUsage;
                $promo->promocode_id = $find_promo->id;
                $promo->user_id = Auth::user()->id;
                $promo->status = 'ADDED';
                $promo->save();

                if($request->ajax()){

                    return response()->json([
                            'message' => trans('api.promocode_applied') ,
                            'code' => 'promocode_applied'
                         ]); 

                }else{
                    return back()->with('flash_success', trans('api.promocode_applied'));
                }
            }

        }

        catch (Exception $e) {
            if($request->ajax()){
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
            }else{
                return back()->with('flash_error', 'Something Went Wrong');
            }
        }

    } 

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function upcoming_trips() {
    
        try{
            $UserRequests = UserRequests::UserUpcomingTrips(Auth::user()->id)->get();
            if(!empty($UserRequests)){
                $map_icon = asset('asset/img/marker-start.png');
                foreach ($UserRequests as $key => $value) {
                     $UserRequests[$key]->static_map = $value->route_key;
                   /* $UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?".
                            "autoscale=1".
                            "&size=320x130".
                            "&maptype=terrian".
                            "&format=png".
                            "&visual_refresh=true".
                            "&markers=icon:".$map_icon."%7C".$value->s_latitude.",".$value->s_longitude.
                            "&markers=icon:".$map_icon."%7C".$value->d_latitude.",".$value->d_longitude.
                            "&path=color:0x000000|weight:3|enc:".$value->route_key.
                            "&key=".env('GOOGLE_MAP_KEY');*/
                }
            }
            return $UserRequests;
        }

        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function upcoming_trip_details(Request $request) {
         $this->validate($request, [
                'request_id' => 'required|integer|exists:user_requests,id',
            ]);    
         try{
            $UserRequests = UserRequests::UserUpcomingTripDetails(Auth::user()->id,$request->request_id)->get();
            if(!empty($UserRequests)){
                $map_icon = asset('asset/img/marker-start.png');
                foreach ($UserRequests as $key => $value) {
                    $UserRequests[$key]->static_map = $value->route_key;
                    /*$UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?".
                            "autoscale=1".
                            "&size=320x130".
                            "&maptype=terrian".
                            "&format=png".
                            "&visual_refresh=true".
                            "&markers=icon:".$map_icon."%7C".$value->s_latitude.",".$value->s_longitude.
                            "&markers=icon:".$map_icon."%7C".$value->d_latitude.",".$value->d_longitude.
                            "&path=color:0x000000|weight:3|enc:".$value->route_key.
                            "&key=".env('GOOGLE_MAP_KEY');*/
                }
            }
            return $UserRequests;
        }

        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }


    /**
     * Show the nearby providers.
     *
     * @return \Illuminate\Http\Response
     */

    public function show_providers(Request $request) {
        $request['service']=1;
        $this->validate($request, [
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'service' => 'numeric|exists:service_types,id',
            ]);

        try{

            $distance = Setting::get('provider_search_radius', '10');
            $latitude = $request->latitude;
            $longitude = $request->longitude;

            if($request->has('service')){
                $ActiveProviders = ProviderService::AvailableServiceProvider($request->service)->get()->pluck('provider_id');
                $Providers = Provider::whereIn('id', $ActiveProviders)
                    ->where('status', 'approved')
                    ->whereRaw("(1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                    ->get();
            } else {
                $Providers = Provider::where('status', 'approved')
                    ->whereRaw("(1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                    ->get();
            }

            if(count($Providers) == 0) {
                if($request->ajax()) {
                    return response()->json(['message' => "No Providers Found"]); 
                }else{
                    return back()->with('flash_success', 'No Providers Found! Please try again.');
                }
            }
        
            return $Providers;

        } catch (Exception $e) {
            if($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
            }else{
                return back()->with('flash_error', 'Something went wrong while sending request. Please try again.');
            }
        }
    }


    /**
     * Forgot Password.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
     }
    
    

    public function forgot_password(Request $request){

        $this->validate($request, [
                'email' => 'required|email|exists:users,email',
            ]);

        try{  
            
            $user = User::where('email' , $request->email)->first();

            $otp = mt_rand(100000, 999999);

            $user->otp = $otp;
            $user->save();

            Notification::send($user, new ResetPasswordOTP($otp));

            return response()->json([
                'message' => 'OTP sent to your email!',
                'user' => $user
            ]);

        }catch(Exception $e){
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }


    /**
     * Reset Password.
     *
     * @return \Illuminate\Http\Response
     */

    public function reset_password(Request $request){

        $this->validate($request, [
                'password' => 'required|confirmed|min:6',
                'id' => 'required|numeric|exists:users,id'
            ]);

        try{

            $User = User::findOrFail($request->id);
            $User->password = bcrypt($request->password);
            $User->save();

            if($request->ajax()) {
                return response()->json(['message' => 'Password Updated']);
            }

        }catch (Exception $e) {
             return response()->json(['error' => trans('api.something_went_wrong')]);
            /*if($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            }*/
        }
    }

    /**
     * help Details.
     *
     * @return \Illuminate\Http\Response
     */

    public function help_details(Request $request){

        try{
            if($request->ajax()) {
                return response()->json([
                    'contact_number' => Setting::get('contact_number',''), 
                    'contact_email' => Setting::get('contact_email','')
                     ]);
            }else{
                return response()->json([
                    'contact_number' => Setting::get('contact_number',''), 
                    'contact_email' => Setting::get('contact_email','')
                     ]);
            }

        }catch (Exception $e) {
            if($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            }
        }
    }
    
    public function besafe(Request $request){
            /*$json='  {
        "contact": [{
            "userName": "123456",
            "phoneNumber": "123456"
        }],
        "rideId": 11
      }'; */       
        $data=json_decode($json,true);
        $UserRequests = UserRequests::find($data['rideId']);
        $source=$UserRequests['s_address'];
        $dest=$UserRequests['d_address'];
        if(!empty($data['contact'])){
            foreach($data['contact'] as $k=>$v){
                $content='';
                $content='Hello '.$v['userName'].' I am travelling from '.$source.' to '.$dest.'.';
                $mobileNumber=$v['phoneNumber']; 
                $process = curl_init();
                curl_setopt($process, CURLOPT_URL,'https://api.twilio.com/2010-04-01/Accounts/ACcc5c4530fcc567cd17366e245e0204a0/Messages.json');
                curl_setopt($process, CURLOPT_HEADER, 1);
                curl_setopt($process, CURLOPT_USERPWD, "ACcc5c4530fcc567cd17366e245e0204a0:f3813b9a7964cada65742fd2ef06fda6");
                curl_setopt($process, CURLOPT_TIMEOUT, 30);
                curl_setopt($process, CURLOPT_POST, 1);
                $postdata=array(
                            'To'=>'+919357676843',
                            'From'=>'+15404405904 ',
                            'Body'=>strip_tags($content)
                        ); 
                curl_setopt($process, CURLOPT_POSTFIELDS, $postdata);
                curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
                $return = curl_exec($process);
                curl_close($process); 
            }
        }
        echo json_encode(array('success'=>1,'message'=>'message has been sent.'));
        die;
    }
    
    
    public function contactus(Request $request){
        
         $validator=\Validator::make($request->all(), [
            'title' => 'required',
            'message' => 'required',
            
        ]);
        if ($validator->fails()) {
            $errors=$validator->errors();
            if(!empty($errors->first('title'))){
               return response()->json(['error' => $errors->first('title')]);  
            }
            if(!empty($errors->first('message'))){
               return response()->json(['error' => $errors->first('message')]);  
            }
            
        }
        try{
           DB::table('contact_messages')->insert([
               'title'=>$request['title'],
               'message'=>$request['message'],
               'user_id'=>Auth::user()->id,
               'type'=>1
           ]);
            
            $subject = "Message from Contact Us";   
            $to = 'swaran.lata@imarkinfotech.com';
            $logo='http://clientstagingdev.com/yourcab/public/uploads/logo.png';             
            $htmlContent='<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head> 
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
	<link href="http://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet" type="text/css">
	<title>Your Cab Email</title>
</head>

	<body marginheight="0" topmargin="0" marginwidth="0" style="bgcolor:blue ;margin: 0px; font:12px arial; color:#000;">	<table cellspacing="0" border="0" align="center" cellpadding="0" width="600" style="border:1px solid #ccc; margin-top:10px;">
			<tr>
				<td>
					<table cellspacing="0" border="0" align="center" cellpadding="20" width="100%">
						<!-- -->
						<tr align="center" >
							<td style="font-family:arial; padding-bottom:40px;"><strong>
                          <img src="'.$logo.'" alt="Your Cab">
							</strong></td>
						</tr><!-- -->
					</table>
					<table cellspacing="0" border="0" align="center" cellpadding="10" width="100%" style="border:0px solid #efefef; margin-top:0px; padding:40px;">
						<tr>
						<td><h2>Hello Admin,</h2></td>
						</tr>
						<tr>
						    <td>                            
                            <p>You have received a new message from Contact Us Form. </p><br>The contact information are mentioned below.<br></td>
						</tr>
                        <tr><td><strong>Name</strong> : '.ucfirst(Auth::user()->first_name).' '.ucfirst(Auth::user()->last_name).'</td></tr>
                        <tr><td><strong>Title</strong> : '.$request['title'].'</td></tr>
                        <tr><td><strong>Message</strong> : '.$request['message'].'</td></tr>
						<tr>
							<td>
								<table cellspacing="0" border="0" cellpadding="0" width="100%">	
									<tr>
										<td><h3>Best Regards</h3>
											<h3>Your Cab Team</h3>
										</td>
									</tr>
								</table>
							</td>
							<td width="30"></td> 
						</tr>
					</table>
					<table cellspacing="0" border="0" align="center" cellpadding="0" width="100%" style="border:0px solid #efefef; margin-top:20px; padding:0px;">
						<tr>
							<td align="center" style="font-family:PT Sans,sans-serif; font-size:13px; padding:15px 0; border-top:1px solid #efefef;"> 
							<strong><b>Your Cab</b></strong></td> 
						</tr>
					</table>
				</td>   
			</tr>
		</table></body>
</html>';
            require 'phpmailer/PHPMailerAutoload.php';
            $mail = new \PHPMailer;
            $mail->IsSMTP();
            $mail->Mailer = "smtp";
            $mail->SMTPAuth = true;
            $mail->Host = "mail.smtp2go.com";
            $mail->SMTPSecure = 'ssl';
            $mail->Port =443;
            $mail->Username = "swaran.lata@imarkinfotech.com";
            $mail->Password = "bQvXa66Yetn6";
            $mail->setFrom('swaran.lata@imarkinfotech.com', 'Your Cab');
            $mail->addAddress($to);
            $mail->Subject = $subject;
            $mail->msgHTML($htmlContent);
            $mail->send();            
            return response()->json([
               'success' => 1,
               'message'=>'Contact message has been sent.',
               'error'=>'No Error Found'
           ]);             
        }catch(Exception $e){
            return response()->json(['error' => trans('api.something_went_wrong')]); 
        }
         
        
    }
    
    
    public function getLastThreeLocation(){            
        // $UserRequests = UserRequests::UserTripsThree(Auth::user()->id);
          $data=DB::table('user_requests')->where('user_requests.user_id', '=', Auth::user()->id)
                    ->where('user_requests.status', '=', 'COMPLETED')
                    ->groupBy('user_requests.d_address')
                    ->select('user_requests.d_address')                    
                    ->limit(3)->get();       
        
          if(!empty($data)){
             $arr=array();
             foreach($data as $k=>$v){
                $arr[$k]['d_address']=$v->d_address; 
             }
           return $arr;  
         }else{
           return response()->json(['error'=>'No Last search locations found.']);  
         }     
        
        
    }
    
    public function delete_trip(Request $request){
        $data=$request->all();    
        $userId=Auth::user()->id;
        $UserRequest = UserRequests::where('id', $data['id'])->first();
        if(!empty($UserRequest)){
            if($UserRequest['user_id']!=$userId){
               return response()->json(['error' => 'You are not authorize to delete the trip.']);   
            }else{
                DB::table('delete_trips')->insert(array(
                'trip_id'=>$data['id'],
                'deleted_by'=>'user',
                'user_id'=>$userId
                ));
               return response()->json(['message' => 'Trip deleted successfully.']);   
               
            }
        }else{
           return response()->json(['error' => 'No Trip Found.']);  
        }

        
    }

}
