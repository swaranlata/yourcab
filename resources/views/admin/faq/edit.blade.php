@extends('admin.layout.base')
 
@section('title', 'Edit Faq ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
            <a href="{{url('admin/faq/index')}}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Back</a>

			<h5 style="margin-bottom: 2em;">Edit Faq</h5>
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
						<input class="form-control" type="text" value="<?php echo $edit['title']; ?>" name="title" id="title" placeholder="Title">
					</div>
				</div>
                <div class="form-group row">
					<label for="first_name" class="col-xs-12 col-form-label">User Type</label>
					<div class="col-xs-10">
                        <select class="form-control" name="type">
                            <?php if($edit['type']==1){
    ?><option value="1">Passenger</option>
                             <option  value="2">Driver</option>
                            <?php
}else{
    ?> <option  value="2">Driver</option>
                            <option value="1">Passenger</option>
                            
                            <?php
} ?>
                            
                           
                        </select>
						<!--<input class="form-control" type="text" value="{{ old('title') }}" name="title" id="title" placeholder="Title">-->
					</div>
				</div>
                <div class="form-group row">
					<label for="first_name" class="col-xs-12 col-form-label">Description</label>
					<div class="col-xs-10">
						 <textarea name="content" id="myeditor"><?php echo $edit['content']; ?></textarea>
					</div>
				</div>
				
				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">Edit Faq</button>
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
