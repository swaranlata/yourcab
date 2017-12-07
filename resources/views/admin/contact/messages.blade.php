@extends('admin.layout.base')

@section('title', 'Contact Us Messages ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1">Contact Us</h5>
                <table class="table table-striped table-bordered dataTable" id="example4">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sender Name</th>
                            <th>Email</th>
                            <th>User Type</th>
                            <th>Title</th>
                            <th>Message</th>
                            <th>Action</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        
                      
                        if(!empty($contactData)){
                           foreach($contactData as $k=>$v){
                             ?>
                        <tr>
                            <td> <?php echo $v['id'];?></td>
                            <td> <?php echo $v['users']['first_name'].' '.$v['users']['last_name']?></td>
                            <td> <?php echo $v['users']['email'];?></td>
                            <td> <?php
                               if(!empty($v['type'])){
                                   $dataUser='Passenger';
                               }else{
                                   $dataUser='Driver';
                               }
                               
                               
                               echo $dataUser;?></td>
                            <td> <?php echo $v['title'];?></td>
                            <td> <?php echo substr($v['message'],0,80);?></td>
                            <td>
                                <input type="hidden" name="email" value="<?php echo $v['users']['email'];?>"/>
                                <input type="hidden" name="name" value=" <?php echo $v['users']['first_name'].' '.$v['users']['last_name']?>"/>
                                <input type="button" class="replybutton" name="reply" value="Reply" />
                                <a data-id=" <?php echo $v['id'];?>" href="javascript:void(0);"  class="viewMsg btn btn-primary" title="View">View</a>
                            
                            
                            
                            </td>
                        </tr>
                        
                           <?php
                           } 
                        }
                        ?>
                   
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Sender Name</th>
                            <th>Email</th>
                             <th>User Type</th>
                            <th>Title</th>
                            <th>Message</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
        </div>
    </div>
@endsection