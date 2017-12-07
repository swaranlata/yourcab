@extends('admin.layout.base')

@section('title', 'Messages ')

@section('content')
<div class="content-area py-1 send-message-page">
    <div class="container-fluid">
     <div class="box box-block bg-white">
<h5 class="mb-1">Send Message</h5>
           <div class="response" style="display:none;"></div>
<ul class="nav nav-tabs">
  <li class="active"><a class="select" data-toggle="tab" href="#all">All</a></li>
  <li><a class="select" data-toggle="tab" href="#users">Passengers</a></li>
  <li><a class="select" data-toggle="tab" href="#drivers">Drivers</a></li>
</ul>
<div class="tab-content">
  
  <div id="users" class="tab-pane selectedTab fade">
    <!--<h3>Passengers</h3>-->

     <form class="form-horizontal users" action="" method="POST" enctype="multipart/form-data" role="form">
      {{csrf_field()}}
          <input type="button" class="btnSubmitData" name="userForm" value="Send Message">
         <input type="hidden" name="type" value="users"/>
          <input type="hidden" name="search" id="usearch"/>
         <input type="hidden" id="usercity" name="city" value="users"/>
         <input type="hidden" id="newusers" name="users[]" value=""/>         
      <table class="table table-striped table-bordered dataTable" id="example">
    <!--  <table class="table table-striped table-bordered dataTable" id="table-1">-->
                <thead>
                    <tr>
                        <th><input class="isCheckedAll" type="checkbox" data-tab='user'/> </th>
                        <th>Sr. No</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>City</th>
                        <th>Mobile</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($users)){
                        foreach($users as $k=>$v){
                    ?>    
                    <tr>
                    
                    <td> <input class="isChecked" data-id="u" type="checkbox" name="newusers[]" value="<?php echo $v['id'];?>"/></td>
                        <td><?php echo $k+1;?></td>
                    <td><?php echo $v['first_name'].' '.$v['last_name'];?></td>
                    <td><?php echo $v['email']; ?></td>
                    <td><?php echo $v['city']; ?></td>
                    <td><?php echo $v['mobile']; ?></td>
                    </tr>
                    <?php
                    }
                    }?>                   
               
                </tbody>
                <tfoot>
                    <tr>
                        <th><input class="isCheckedAll" type="checkbox" data-tab='user'/>  </th>
                        <th>Sr. No</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>City</th>
                        <th>Mobile</th>
                    </tr>
                </tfoot>
            </table>        
      <input type="button" class="btnSubmitData" name="userForm" value="Send Message">
    </form>
      
  </div>
  <div id="drivers" class="tab-pane selectedTab fade">
    <!--<h3>Drivers</h3>-->
    <form class="form-horizontal drivers" action="" method="POST" enctype="multipart/form-data" role="form">
      {{csrf_field()}}
         <input type="button" class="btnSubmitDataDriver" name="userForm" value="Send Message">
        <input type="hidden" name="type" value="drivers"/>
        <input type="hidden" name="search" id="dsearch"/>
         <input type="hidden" id="newdrivers" name="drivers[]" value=""/>
        
       <table style="width:100%;" class="table table-striped table-bordered dataTable" id="example1">
                    <thead>
                        <tr>
                            <th><input class="isCheckedAll" type="checkbox" data-tab='driver'/>  </th>
                            <th>Sr. No</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>City</th>
                            <th>Mobile</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($drivers)){
                            foreach($drivers as $kk=>$vv){
                        ?>    
                        <tr>

                        <td> <input class="isChecked" data-id="d"  type="checkbox" name="newdrivers[]" value="<?php echo $vv['id'];?>"/></td>
                        <td><?php echo $kk+1;?></td>
                        <td><?php echo $vv['first_name'].' '.$vv['last_name'];?></td>
                        <td><?php echo $vv['email']; ?></td>
                        <td><?php echo $vv['city']; ?></td>
                        <td><?php echo $vv['mobile']; ?></td>
                        </tr>
                        <?php
                        }
                        }?>                  

                    </tbody>
                    <tfoot>
                        <tr>
                            <th><input class="isCheckedAll" type="checkbox" data-tab='driver'/> </th>
                            <th>Sr. No</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>City</th>
                            <th>Mobile</th>
                        </tr>
                    </tfoot>
  </table>
       <input type="button" class="btnSubmitDataDriver" name="userForm" value="Send Message">
    </form>
  </div>
  <div id="all" class="tab-pane  selectedTab fade in active">
    <!--<h3>All</h3>-->
      
       <form class="form-horizontal all" action="" method="POST" enctype="multipart/form-data" role="form">
           {{csrf_field()}}
           <input type="button" class="btnSubmitDataAll" name="userForm" value="Send Message">
           <input type="hidden" name="type" value="all"/>
           <input type="hidden" name="search" id="allsearch"/>
           <input id="nU" type="hidden" name="users[]"  value=""/>
           <input  id="nD"  type="hidden" name="drivers[]"  value=""/>
            <table class="table table-striped table-bordered dataTable" id="example2">
                <thead>
                    <tr>
                        <th><input class="isCheckedAll" type="checkbox" data-tab='all'/> </th>
                        <th>Sr. No</th>
                        <th>Full Name</th>
                        <th>User Type</th>
                        <th>Email</th>
                        <th>City</th>
                        <th>Mobile</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($users)){
                        foreach($users as $k=>$v){
                    ?>    
                    <tr>
                   
                    <td> <input class="isChecked" data-id="aU" type="checkbox" name="newusers[]"  value="<?php echo $v['id'];?>"/></td>
                        <td><?php echo $k+1;?></td>
                    <td><?php echo $v['first_name'].' '.$v['last_name'];?></td>
                    <td>Passenger</td>
                    <td><?php echo $v['email']; ?></td>
                    <td><?php echo $v['city']; ?></td>
                    <td><?php echo $v['mobile']; ?></td>
                    </tr>
                    <?php
                    }
                    }?>  
                     <?php
                    $counter=count($users)+1;
                    if(!empty($drivers)){
                        foreach($drivers as $kk=>$vv){
                    ?>    
                    <tr>
                    
                    <td><input  class="isChecked" data-id="aD"   type="checkbox" name="newdrivers[]" value="<?php echo $vv['id'];?>"/></td>
                    <td><?php echo $counter;?></td>
                    <td><?php echo $vv['first_name'].' '.$vv['last_name'];?></td>
                    <td>Driver</td>
                    <td><?php echo $vv['email']; ?></td>
                    <td><?php echo $vv['city']; ?></td>
                    <td><?php echo $vv['mobile']; ?></td>
                    </tr>
                    <?php
                         $counter++;
                        }
                    }?>    
               
                </tbody>
                <tfoot>
                    <tr>
                        <th><input class="isCheckedAll" type="checkbox" data-tab='all'/> </th>
                        <th>Sr. No</th>
                        <th>Full Name</th>
                        <th>User Type</th>
                        <th>Email</th>
                        <th>City</th>
                        <th>Mobile</th>
                    </tr>
                </tfoot>
            </table>
            <input type="button" class="btnSubmitDataAll" name="userForm" value="Send Message">
      </form>
  </div>
</div>
            
            
            
           
          
        </div>
    </div>
</div>
@endsection