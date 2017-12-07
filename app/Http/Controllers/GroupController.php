<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\Helper;
use Auth;
use Redirect;
use Setting;
use Exception;
use Validator;
use DB;
use App\Group;



class GroupController extends Controller
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
    
    public function index(){
       $groups=Group::get();    
       return view('admin.group.index',['groups'=>$groups]);
    }
    
    public function add(Request $request){
       /* if($request->isMethod('post')){
            $validator=Validator::make($request->all(),[
               'title'=>'required',
               'content'=>'required'
            ]);
           if($validator->fails()){
             $errors=$validator->errors();
             return redirect('/admin/faq/add')->withErrors($errors);
           }else{
              DB::table('faqs')->insert([
                  'title'=>$request['title'],
                  'content'=>$request['content']
              ]); 
              return redirect('/admin/group/add')->with('success','Group added successfully.'); 
           }            
        } */      
        return view('admin.group.add');
    }
    
    public function status($id=null){
        $faq=Faq::find($id);
        if(!empty($faq->status)){
         $status=0;
        }else{
         $status=1;
        }
        $faq->id=$id;
        $faq->status=$status;
        $faq->save();
        if(!empty($status)){
         $act='deactivated';
        }else{
         $act='activated';
        }
        return redirect('/admin/faq/index')->with('success','Faq status has been '.$act.' successfully.');
    } 
    
    public function delete($id=null){
        $faq=Faq::find($id);
        $faq->delete();
        return redirect('/admin/faq/index')->with('success','Faq status has been deleted successfully.');
    }
    
    public function edit(Request $request,$id=null){
        $faq=Faq::find($id);
        if($request->isMethod('post')){
             $validator=Validator::make($request->all(),[
               'title'=>'required',
               'content'=>'required'
            ]);
           if($validator->fails()){
             $errors=$validator->errors();
             return redirect('/admin/faq/edit/'.$id)->withErrors($errors);
           }else{
                $faq->title=$request->title;
                $faq->content=$request->content;
                $faq->save();
               return redirect('/admin/faq/edit/'.$id)->with('success','Faq details has been updated.');
           } 
         }
        $faq=Faq::find($id);
        return view('admin.faq.edit',['edit'=>$faq]);
        
    }
    
}