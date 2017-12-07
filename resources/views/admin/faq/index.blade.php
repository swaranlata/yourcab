@extends('admin.layout.base')

@section('title', 'Faqs ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1">
                Faqs
                @if(Setting::get('demo_mode', 0) == 1)
                <span class="pull-right">(*personal information hidden in demo)</span>
                @endif
            </h5>
            @if(Session::has('success'))
            <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                {{ Session::get('success') }}
            </div>
            @endif
            <a href="{{url('admin/faq/add')}}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add New Faq</a>
            
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Type</th>  
                        <th>Description</th>   
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($faqs as $k=>$v)
                    <tr>
                        <td>{{$k+1}}</td>
                        <td>{{substr($v->title,0,30)}}...</td>
                        <td><?php
                            if($v->type=='1'){
                                  ?>Passenger
                            <?php
                            }else{
                                ?>
                            Driver
                            <?php
                            }
                            
                            
                            ?></td>
                        
                        <td>{{substr(strip_tags($v->content),0,50)}}...</td>
                        
                       <td> <?php if(!empty($v->status)){
                            ?>
                            <a class="btn btn-danger" href="{{url('admin/faq/status/'.$v->id)}}">Inactive</a>
                        <?php
                        }else{
                            ?><a  class="btn btn-success" href="{{url('admin/faq/status/'.$v->id)}}">Active</a><?php
                        }?>
                        </td>
                        <td><a style="margin-left: 1em;" class="btn btn-primary" href="{{url('admin/faq/edit/'.$v->id)}}"><i class="fa fa-pencil"></i></a>
                            <a style="margin-left: 1em;" class="btn btn-primary" href="{{url('admin/faq/view/'.$v->id)}}"><i class="fa fa-eye"></i></a>                            
                            <a style="margin-left: 1em;"  class="btn btn-primary" href="{{url('admin/faq/delete/'.$v->id)}}"><i class="fa fa-remove"></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Title</th><th>Type</th>
                        <th>Description</th>                        
                                                
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection