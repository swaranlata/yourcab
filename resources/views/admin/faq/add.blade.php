@extends('admin.layout.base')
 
@section('title', 'Add Faq ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
            <a href="{{url('admin/faq/index')}}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Back</a>

			<h5 style="margin-bottom: 2em;">Add Faq</h5>
            @if (session('success'))
                <div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    {{ session('success') }}
                </div>
            @endif
            <form class="form-horizontal" action="" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				<div class="form-group row">
					<label for="first_name" class="col-xs-12 col-form-label">Title</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ old('title') }}" name="title" id="title" placeholder="Title">
					</div>
				</div>
                <div class="form-group row">
					<label for="first_name" class="col-xs-12 col-form-label">User Type</label>
					<div class="col-xs-10">
                        <select class="form-control" name="type">
                        <?php 
                            $array=array(''=>'Select Type','1'=>'Passenger','2'=>'Driver');
                            foreach($array as $k=>$v){
                                $selected='';
                                if($k== old('type')){
                                    $selected='selected';
                                }
                            ?>
                                <option <?php echo $selected; ?> value="<?php echo $k;  ?>"><?php echo $v; ?></option>
                            <?php
                            }
                            ?>
                                 
                        </select>
					</div>
				</div>
                <div class="form-group row">
					<label for="first_name" class="col-xs-12 col-form-label">Description</label>
					<div class="col-xs-10">
						 <textarea name="content" id="myeditor">{{old('content')}}</textarea>
					</div>
				</div>
				
				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">Add Faq</button>
						<a href="{{url('admin/faq/index')}}" class="btn btn-default">Cancel</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>
@endsection
@section('scripts')
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
<script type="text/javascript">
    CKEDITOR.replace('myeditor');
</script>
@endsection
