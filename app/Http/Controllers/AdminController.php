<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Helpers\Helper;
use Auth;
use Redirect;
use Setting;
use Exception;
use \Carbon\Carbon;
use Validator;
use App\User;
use App\Fleet;
use App\Admin;
use App\ContactMessage;
use App\Provider;
use App\UserPayment;
use App\ServiceType;
use App\UserRequests;
use App\ProviderService;
use App\UserRequestRating;
use App\UserRequestPayment;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }


    /**
     * Dashboard.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        try{

            $rides = UserRequests::has('user')->orderBy('id','desc')->get();
            $users = User::count();
            $records = Provider::count(); 
            $cancel_rides = UserRequests::where('status','CANCELLED')->get();
            $scheduled_rides = UserRequests::where('status','SCHEDULED')->count();
            $user_cancelled = $cancel_rides->where('cancelled_by','USER')->count();
            $provider_cancelled = $cancel_rides->where('cancelled_by','PROVIDER')->count();            
            $service = ServiceType::count();
            $fleet = Fleet::count();
            $revenue = UserRequestPayment::sum('total');
            $providers = Provider::take(10)->orderBy('rating','desc')->get();
           
            return view('admin.dashboard',compact('providers','fleet','scheduled_rides','service','rides','user_cancelled','provider_cancelled','cancel_rides','revenue','users','records'));
        }
        catch(Exception $e){
            return redirect()->route('admin.user.index')->with('flash_error','Something Went Wrong with Dashboard!');
        }
    }
    



    /**
     * Heat Map.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function heatmap()
    {
        try{
            $rides = UserRequests::has('user')->orderBy('id','desc')->get();
            $providers = Provider::take(10)->orderBy('rating','desc')->get();
            return view('admin.heatmap',compact('providers','rides'));
        }
        catch(Exception $e){
            return redirect()->route('admin.user.index')->with('flash_error','Something Went Wrong with Dashboard!');
        }
    }

    /**
     * Map of all Users and Drivers.
     *
     * @return \Illuminate\Http\Response
     */
    public function map_index()
    {
        return view('admin.map.index');
    }

    /**
     * Map of all Users and Drivers.
     *
     * @return \Illuminate\Http\Response
     */
    public function map_ajax()
    {
        try {

            $Providers = Provider::where('latitude', '!=', 0)
                    ->where('longitude', '!=', 0)
                    ->with('service')
                    ->get();

            $Users = User::where('latitude', '!=', 0)
                    ->where('longitude', '!=', 0)
                    ->get();

            for ($i=0; $i < sizeof($Users); $i++) { 
                $Users[$i]->status = 'user';
            }

            $All = $Users->merge($Providers);

            return $All;

        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        return view('admin.settings.application');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function settings_store(Request $request)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error','Disabled for demo purposes! Please contact us at info@appoets.com');
        }

        $this->validate($request,[
                'site_title' => 'required',
                'site_icon' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
                'site_logo' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
            ]);

        if($request->hasFile('site_icon')) {
            $site_icon = Helper::upload_picture($request->file('site_icon'));
            Setting::set('site_icon', $site_icon);
        }

        if($request->hasFile('site_logo')) {
            $site_logo = Helper::upload_picture($request->file('site_logo'));
            Setting::set('site_logo', $site_logo);
        }

        if($request->hasFile('site_email_logo')) {
            $site_email_logo = Helper::upload_picture($request->file('site_email_logo'));
            Setting::set('site_email_logo', $site_email_logo);
        }

        Setting::set('site_title', $request->site_title);
        Setting::set('store_link_android', $request->store_link_android);
        Setting::set('store_link_ios', $request->store_link_ios);
        Setting::set('provider_select_timeout', $request->provider_select_timeout);
        Setting::set('provider_search_radius', $request->provider_search_radius);
        Setting::set('sos_number', $request->sos_number);
        Setting::set('contact_number', $request->contact_number);
        Setting::set('contact_email', $request->contact_email);
        Setting::set('site_copyright', $request->site_copyright);
        Setting::set('social_login', $request->social_login);
        Setting::save();
        
        return back()->with('flash_success','Settings Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function settings_payment()
    {
        return view('admin.payment.settings');
    }
    
    public function contactmessages()
    {
       $msgs= ContactMessage::with('users')->get();
       return view('admin.contact.messages',['contactData'=>$msgs]);
    }
    
    public function reply_messages(Request $request){
        $data=$request->all();
        $fullname=$data['name'];
        $content=$data['content'];
        $email=$data['email'];
        $to = $email;
               $subject = "Reply From Admin";  
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
                            <td><h2>Hello '.ucfirst($fullname).',</h2></td>
                            </tr>
                            <tr>
                                <td> <p>You have received a reply message from Admin.   </p>                      
                                <h4><p>'.$content.'</p></h4></td>
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
        echo json_encode(array('status'=>'true','message'=>'Your message has been sent.'));
        die;
        
    }
    
    
    
    public function sendmessage(Request $request)
    {
         $driver=Provider::get();
         $user=User::get();  
        
      
        require 'phpmailer/PHPMailerAutoload.php';
        $mail = new \PHPMailer;
       if($request->isMethod('post')){
           $validators=Validator::make($request->all(),[
               'users'=>'required',
               'content'=>'required',
           ]);
           if($validators->fails()){
            $errors=$validators->errors();
            return Redirect::to('admin.sendmessage')->withErrors($errors);
           }
           $data=$request->all();
           $content=$data['content'];
           foreach($data['users'] as $k=>$v){
               if(strpos($v,'usus_')!==false){                   
                   $email=str_replace('usus_','',$v);
                   $user=User::where('email',$email)->get(); 
                   $fullname=$user[0]['first_name'].' '.$user[0]['last_name'];
               }else{
                   $email=str_replace('drdr_','',$v);
                   $driver=Provider::where('email',$email)->get();
                   $fullname=$driver[0]['first_name'].' '.$driver[0]['last_name'];
               }
               $to = $email;
               $subject = "Message From Admin";  
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
                            <td><h2>Hello '.ucfirst($fullname).',</h2></td>
                            </tr>
                            <tr>
                                <td> <p>You have received a new message from Admin.   </p>                      
                                <h4><p>'.$content.'</p></h4></td>
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
            /*  $process = curl_init();
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
                curl_close($process);*/ 
          
           }

           return back()->with('flash_success','Message sent Successfully');
       }
       return view('admin.contact.sendmessage',array('users'=>$user,'drivers'=>$driver));
    }
    
    
    public function test(){
        $name='swar';
       echo  $query='select * from `users` where `first_name` like "%'.$name.'%" or `last_name` like "%'.$name.'%" or `city` like "%'.$name.'%" or `email` like "%'.$name.'%" or `mobile` like "%'.$name.'%"';
        $u=DB::select($query);
         print_r($u);
        die;
         echo "<pre>";
       $_POST['search']=$name;
        $u=User::where('id','!=','')
                    ->orWhere('first_name', 'LIKE', '"%' .$_POST['search']. '%"')
                    ->orWhere('last_name', 'LIKE', '"%' .$_POST['search']. '%"')
                    ->orWhere('city', 'LIKE', '"%' .$_POST['search']. '%"')
                    ->orWhere('email', 'LIKE', '"%' .$_POST['search']. '%"')
                    ->orWhere('mobile', 'LIKE', '"%' .$_POST['search']. '%"')
                    ->get();
         print_r($u);
        die;
       
        
         $driver=Provider::get();
         $user=User::get(); 
        if(!empty($driver)){
            foreach($driver as $k=>$v){
               $pro = Provider::find($v['id']);
                 if(!empty($this->getCityName($v['latitude'],$v['longitude']))){
               $pro->city = $this->getCityName($v['latitude'],$v['longitude']);
                 }
               $pro->save();
                
                
                
             // $driver[$k]['city']=  $this->getCityName($v['latitude'],$v['longitude']);
            }            
        }
        if(!empty($user)){
            foreach($user as $k=>$v){
                $user = User::find($v['id']);
                if(!empty($this->getCityName($v['latitude'],$v['longitude']))){
                  $user->city = $this->getCityName($v['latitude'],$v['longitude']);  
                }
                
                $user->save();
            }            
        }
        
        die;
        
        
    }
    
    public function sendmessages(Request $request)
    {
        $driver=Provider::get();
        $user=User::get();  
        $cities=array();
        if(!empty($driver)){
            foreach($driver as $k=>$v){
              $driver[$k]['city']=  $this->getCityName($v['latitude'],$v['longitude']);
            }            
        }
        if(!empty($user)){
            foreach($user as $k=>$v){
              $user[$k]['city']=  $this->getCityName($v['latitude'],$v['longitude']);
            }            
        }
       require 'phpmailer/PHPMailerAutoload.php';
       $mail = new \PHPMailer;
       if($request->isMethod('post')){
           $data=$request->all();
           $content=$data['content'];
           foreach($data['users'] as $k=>$v){
               if(strpos($v,'usus_')!==false){                   
                   $email=str_replace('usus_','',$v);
                   $user=User::where('email',$email)->get(); 
                   $fullname=$user[0]['first_name'].' '.$user[0]['last_name'];
               }else{
                   $email=str_replace('drdr_','',$v);
                   $driver=Provider::where('email',$email)->get();
                   $fullname=$driver[0]['first_name'].' '.$driver[0]['last_name'];
               }
               $to = $email;
               $subject = "Message From Admin";  
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
                            <td><h2>Hello '.ucfirst($fullname).',</h2></td>
                            </tr>
                            <tr>
                                <td> <p>You have received a new message from Admin.   </p>                      
                                <h4><p>'.$content.'</p></h4></td>
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
            /*  $process = curl_init();
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
                curl_close($process);*/ 
          
           }
           return back()->with('flash_success','Message sent Successfully');
       }
       return view('admin.contact.sendmessages',array('users'=>$user,'drivers'=>$driver,'cities'=>$cities));
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
    
    
    
    public function post_messages(Request $request){        
        $data=$request->all();        
        if($data['type']=='users'){
            $data['users']=array_filter($data['users']);
            if(empty($data['users'])){
                echo json_encode(array('status'=>'false','message'=>'Please select the Users.'));
                die;
            }
            $usersString=implode(',',$data['users']);
            echo json_encode(array('status'=>'true','message'=>$usersString,'type'=>$data['type']));
            die;
        }
        elseif($data['type']=='drivers'){
           $data['drivers']=array_filter($data['drivers']);
           if(empty($data['drivers'])){
                echo json_encode(array('status'=>'false','message'=>'Please select the drivers.'));
                die;
            }
            $usersString=implode(',',$data['drivers']);
            echo json_encode(array('status'=>'true','message'=>$usersString,'type'=>$data['type']));
            die; 
        }
        else{
            $data['drivers']=array_filter($data['drivers']);
            $data['users']=array_filter($data['users']);
            if(empty($data['drivers']) and empty($data['users'])){
                echo json_encode(array('status'=>'false','message'=>'Please select the users.'));
                die;
            } 
            $driversString='';
            $usersString='';
            if(!empty($data['drivers'])){
                $driversString=implode(',',$data['drivers']);
            }
            if(!empty($data['users'])){
                $usersString=implode(',',$data['users']);
            }
            echo json_encode(array('status'=>'true','message'=>$usersString,'dids'=>$driversString,'type'=>$data['type']));
            die; 
        }
    }
    
    public function do_post_messages(){
        require 'phpmailer/PHPMailerAutoload.php';
        $mail = new \PHPMailer;
        if($_POST['type']=='users'){
           $_POST['users']=explode(',',$_POST['users']);
           foreach($_POST['users'] as $k=>$v){             
                $user=User::where('id',$v)->get(); 
                $fullname=$user[0]['first_name'].' '.$user[0]['last_name'];             
                $to = $user[0]['email'];
                $subject = "Message From Admin";  
                $getHtml=$this->sendemailtouser($fullname,$_POST['textarea']);
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
                $mail->msgHTML($getHtml);
                $mail->send();
           } 
        }elseif($_POST['type']=='drivers'){
           $_POST['drivers']=explode(',',$_POST['users']);
           foreach($_POST['drivers'] as $k=>$v){             
                $user=Provider::where('id',$v)->get(); 
                $fullname=$user[0]['first_name'].' '.$user[0]['last_name'];             
                $to = $user[0]['email'];
                $subject = "Message From Admin";  
                $getHtml=$this->sendemailtouser($fullname,$_POST['textarea']);
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
                $mail->msgHTML($getHtml);
                $mail->send();
           }  
        }else{
            if(!empty($_POST['users'])){
               $_POST['users']=explode(',',$_POST['users']);
               foreach($_POST['users'] as $k=>$v){             
                $user=User::where('id',$v)->get(); 
                $fullname=$user[0]['first_name'].' '.$user[0]['last_name'];             
                $to = $user[0]['email'];
                $subject = "Message From Admin";  
                $getHtml=$this->sendemailtouser($fullname,$_POST['textarea']);
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
                $mail->msgHTML($getHtml);
                $mail->send();
           }  
            }            
            if(!empty($_POST['drivers'])){
              $_POST['drivers']=explode(',',$_POST['drivers']);
              foreach($_POST['drivers'] as $k=>$v){             
                $user=Provider::where('id',$v)->get(); 
                $fullname=$user[0]['first_name'].' '.$user[0]['last_name'];             
                $to = $user[0]['email'];
                $subject = "Message From Admin";  
                $getHtml=$this->sendemailtouser($fullname,$_POST['textarea']);
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
                $mail->msgHTML($getHtml);
                $mail->send();
           }    
            }
            
        }
        echo json_encode(array('status'=>'true'));
        die;
    }
    
    public function sendemailtouser($fullname=null,$content=null){
         $logo='http://clientstagingdev.com/yourcab/public/uploads/logo.png';
        return $htmlContent='<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
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
                            <td><h2>Hello '.ucfirst($fullname).',</h2></td>
                            </tr>
                            <tr>
                                <td> <p>You have received a new message from Admin.   </p>                      
                                <h4><p>'.$content.'</p></h4></td>
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
        
        
        
        
        
        
        
    }
    
    public function convert_array($arr=null){
        $finalArray = json_decode(json_encode($arr), true);
        return $finalArray;
    }
    
    public function get_all_user_data(){
        $drivers='';
        $users='';
        if($_POST['type']=='user'){
            if(!empty($_POST['search'])){ 
                 $name=$_POST['search'];
                 $query='select * from `users` where `first_name` like "%'.$name.'%" or `last_name` like "%'.$name.'%" or `city` like "%'.$name.'%" or `email` like "%'.$name.'%" or `mobile` like "%'.$name.'%"';
                 $u=DB::select($query);
                 $u=$this->convert_array($u);             
            }else{
                $u=User::get();
            }            
            $all=array();
            if(!empty($u)){              
              foreach($u as $k=>$v){
                $all[]=$v['id'];  
              }
              $users=implode(',',$all);
            }
            
        }elseif($_POST['type']=='driver'){
            if(!empty($_POST['search'])){   
                 $name=$_POST['search'];
                 $query='select * from `providers` where `first_name` like "%'.$name.'%" or `last_name` like "%'.$name.'%" or `city` like "%'.$name.'%" or `email` like "%'.$name.'%" or `mobile` like "%'.$name.'%"';
                $u=DB::select($query);
                 $u=$this->convert_array($u);
               
            }else{
                $u=Provider::get();
            }
            $all=array();
            if(!empty($u)){
              foreach($u as $k=>$v){
                $all[]=$v['id'];  
              }
              $users=implode(',',$all);
            }
            
        }else{
            if(!empty($_POST['search'])){                
                 $name=$_POST['search'];
                 $query='select * from `users` where `first_name` like "%'.$name.'%" or `last_name` like "%'.$name.'%" or `city` like "%'.$name.'%" or `email` like "%'.$name.'%" or `mobile` like "%'.$name.'%"  or `usertype` like "%'.$name.'%"';
                 $u=DB::select($query);
                 $query='select * from `providers` where `first_name` like "%'.$name.'%" or `last_name` like "%'.$name.'%" or `city` like "%'.$name.'%" or `email` like "%'.$name.'%" or `mobile` like "%'.$name.'%"  or `usertype` like "%'.$name.'%"';
                 $d=DB::select($query); 
                $d=$this->convert_array($d);
                $u=$this->convert_array($u);
            }else{
                $d=Provider::get();
                $u=User::get();
            }
            $all=array();
            if(!empty($d)){
              foreach($d as $k=>$v){
                $all[]=$v['id'];  
              }
              $drivers=implode(',',$all);
            }  
           
            $all=array();
            if(!empty($u)){
              foreach($u as $k=>$v){
                $all[]=$v['id'];  
              }
              $users=implode(',',$all);
            }           
            
            
        }
        echo json_encode(array('users'=>$users,'drivers'=>$drivers));
        die;
    }
    
    
    public function get_contact_message(){
        $msgs= ContactMessage::with('users')->where('id',$_POST['id'])->first(); 
        echo json_encode(array('status'=>'true','message'=>$msgs));
        die;
    }
    /**
     * Save payment related settings.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function settings_payment_store(Request $request)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at info@appoets.com');
        }

        $this->validate($request, [
                'CARD' => 'in:on',
                'CASH' => 'in:on',
                'stripe_secret_key' => 'required_if:CARD,on|max:255',
                'stripe_publishable_key' => 'required_if:CARD,on|max:255',
                'daily_target' => 'required|integer|min:0',
                'tax_percentage' => 'required|numeric|min:0|max:100',
                'surge_percentage' => 'required|numeric|min:0|max:100',
                'commission_percentage' => 'required|numeric|min:0|max:100',
                'surge_trigger' => 'required|integer|min:0',
                'currency' => 'required'
            ]);

        Setting::set('CARD', $request->has('CARD') ? 1 : 0 );
        Setting::set('CASH', $request->has('CASH') ? 1 : 0 );
        Setting::set('stripe_secret_key', $request->stripe_secret_key);
        Setting::set('stripe_publishable_key', $request->stripe_publishable_key);
        Setting::set('daily_target', $request->daily_target);
        Setting::set('tax_percentage', $request->tax_percentage);
        Setting::set('surge_percentage', $request->surge_percentage);
        Setting::set('commission_percentage', $request->commission_percentage);
        Setting::set('surge_trigger', $request->surge_trigger);
        Setting::set('currency', $request->currency);
        Setting::set('booking_prefix', $request->booking_prefix);
        Setting::save();

        return back()->with('flash_success','Settings Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        return view('admin.account.profile');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function profile_update(Request $request)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at info@appoets.com');
        }

        $this->validate($request,[
            'name' => 'required|max:255',
           // 'email' => 'required',
            'picture' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
        ]);

        try{
            $admin = Auth::guard('admin')->user();
            $admin->name = $request->name;
            //$admin->email = $request->email;
            if($request->hasFile('picture')){
                $admin->picture = $request->picture->store('admin/profile');  
            }
            $admin->save();

            return redirect()->back()->with('flash_success','Profile Updated');
        }

        catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function password()
    {
        return view('admin.account.change-password');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function password_update(Request $request)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error','Disabled for demo purposes! Please contact us at info@appoets.com');
        }

        $this->validate($request,[
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        try {

           $Admin = Admin::find(Auth::guard('admin')->user()->id);

            if(password_verify($request->old_password, $Admin->password))
            {
                $Admin->password = bcrypt($request->password);
                $Admin->save();

                return redirect()->back()->with('flash_success','Password Updated');
            }else{
                 return back()->with('flash_error','Old password doesnt match with the current password.');
            }
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function payment()
    {
        try {
             $payments = UserRequests::where('paid', 1)
                    ->has('user')
                    ->has('provider')
                    ->has('payment')
                    ->orderBy('user_requests.created_at','desc')
                    ->get();
            
            return view('admin.payment.payment-history', compact('payments'));
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function help()
    {
        try {
            $str = file_get_contents('http://appoets.com/help.json');
            $Data = json_decode($str, true);
            return view('admin.help', compact('Data'));
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * User Rating.
     *
     * @return \Illuminate\Http\Response
     */
    public function user_review()
    {
        try {
            $Reviews = UserRequestRating::where('user_id', '!=', 0)->has('user', 'provider')->get();
            return view('admin.review.user_review',compact('Reviews'));
        } catch(Exception $e) {
            return redirect()->route('admin.setting')->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * Provider Rating.
     *
     * @return \Illuminate\Http\Response
     */
    public function provider_review()
    {
        try {
            $Reviews = UserRequestRating::where('provider_id','!=',0)->with('user','provider')->get();
            return view('admin.review.provider_review',compact('Reviews'));
        } catch(Exception $e) {
            return redirect()->route('admin.setting')->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProviderService
     * @return \Illuminate\Http\Response
     */
    public function destory_provider_service($id){
        try {
            ProviderService::find($id)->delete();
            return back()->with('message', 'Service deleted successfully');
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * Testing page for push notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function push_index()
    {
        $data = PushNotification::app('IOSUser')
            ->to('163e4c0ca9fe084aabeb89372cf3f664790ffc660c8b97260004478aec61212c')
            ->send('Hello World, i`m a push message');
        dd($data);

        $data = PushNotification::app('IOSProvider')
            ->to('a9b9a16c5984afc0ea5b681cc51ada13fc5ce9a8c895d14751de1a2dba7994e7')
            ->send('Hello World, i`m a push message');
        dd($data);
    }

    /**
     * Testing page for push notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function push_store(Request $request)
    {
        try {
            ProviderService::find($id)->delete();
            return back()->with('message', 'Service deleted successfully');
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * privacy.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */

    public function privacy(){
        return view('admin.pages.static')
            ->with('title',"Privacy Page")
            ->with('page', "privacy");
    }
    
    public function terms(){
        return view('admin.pages.terms')
            ->with('title',"terms")
            ->with('page', "terms");
    }
    public function aboutus(){
        return view('admin.pages.aboutus')
            ->with('title',"About Us")
            ->with('page', "Aboutus");
    }
    
    public function support(){
        return view('admin.pages.support')
            ->with('title',"Support")
            ->with('page', "support");
    }

    /**
     * pages.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function pages(Request $request){
        $data=$request->all();
        $this->validate($request, [
                'page' => 'required|in:page_privacy,terms,support,aboutus',
                'content' => 'required',
            ]);

        Setting::set($request->page, $request->content);
        Setting::save();

        return back()->with('flash_success', 'Content Updated!');
    }

    /**
     * account statements.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement($type = 'individual'){

        try{

            $page = 'Ride Statement';

            if($type == 'individual'){
                $page = 'Provider Ride Statement';
            }elseif($type == 'today'){
                $page = 'Today Statement - '. date('d M Y');
            }elseif($type == 'monthly'){
                $page = 'This Month Statement - '. date('F');
            }elseif($type == 'yearly'){
                $page = 'This Year Statement - '. date('Y');
            }

            $rides = UserRequests::with('payment')->orderBy('id','desc');
            $cancel_rides = UserRequests::where('status','CANCELLED');
            $revenue = UserRequestPayment::select(\DB::raw(
                           'SUM(ROUND(fixed) + ROUND(distance)) as overall, SUM(ROUND(commision)) as commission' 
                       ));

            if($type == 'today'){

                $rides->where('created_at', '>=', Carbon::today());
                $cancel_rides->where('created_at', '>=', Carbon::today());
                $revenue->where('created_at', '>=', Carbon::today());

            }elseif($type == 'monthly'){

                $rides->where('created_at', '>=', Carbon::now()->month);
                $cancel_rides->where('created_at', '>=', Carbon::now()->month);
                $revenue->where('created_at', '>=', Carbon::now()->month);

            }elseif($type == 'yearly'){

                $rides->where('created_at', '>=', Carbon::now()->year);
                $cancel_rides->where('created_at', '>=', Carbon::now()->year);
                $revenue->where('created_at', '>=', Carbon::now()->year);

            }

            $rides = $rides->get();
            $cancel_rides = $cancel_rides->count();
            $revenue = $revenue->get();

            return view('admin.providers.statement', compact('rides','cancel_rides','revenue'))
                    ->with('page',$page);

        } catch (Exception $e) {
            return back()->with('flash_error','Something Went Wrong!');
        }
    }


    /**
     * account statements today.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement_today(){
        return $this->statement('today');
    }

    /**
     * account statements monthly.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement_monthly(){
        return $this->statement('monthly');
    }

     /**
     * account statements monthly.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement_yearly(){
        return $this->statement('yearly');
    }


    /**
     * account statements.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement_provider(){

        try{

            $Providers = Provider::all();

            foreach($Providers as $index => $Provider){

                $Rides = UserRequests::where('provider_id',$Provider->id)
                            ->where('status','<>','CANCELLED')
                            ->get()->pluck('id');

                $Providers[$index]->rides_count = $Rides->count();

                $Providers[$index]->payment = UserRequestPayment::whereIn('request_id', $Rides)
                                ->select(\DB::raw(
                                   'SUM(ROUND(fixed) + ROUND(distance)) as overall, SUM(ROUND(commision)) as commission' 
                                ))->get();
            }

            return view('admin.providers.provider-statement', compact('Providers'))->with('page','Providers Statement');

        } catch (Exception $e) {
            return back()->with('flash_error','Something Went Wrong!');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function translation(){

        try{
            return view('admin.translation');
        }

        catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }
}
