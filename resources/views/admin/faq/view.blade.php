@extends('admin.layout.base')
 
@section('title', 'Edit Faq ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
            <a href="{{url('admin/faq/index')}}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Back</a>

			<h5 style="margin-bottom: 2em;">View Faq</h5>
           
            
            <form class="form-horizontal" action="" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				<div class="form-group row">
					<label for="first_name" class="col-xs-12 col-form-label"><strong>Title</strong></label>
					<div class="col-xs-10">
                        <label for="first_name" class="col-xs-12 col-form-label"><?php echo $edit['title']; ?></label>
						
					</div>
				</div>
                <div class="form-group row">
					<label for="first_name" class="col-xs-12 col-form-label"><strong>User Type</strong></label>
					<div class="col-xs-10">
                         <?php if($edit['type']==1){
    $type='Passenger';
  
}else{
     $type='Driver';
} ?>
                        <label for="first_name" class="col-xs-12 col-form-label"><?php echo $type; ?></label>
                       
					</div>
				</div>
                <div class="form-group row">
					<label for="first_name" class="col-xs-12 col-form-label"><strong>Description</strong></label>
					<div class="col-xs-10">
                        <label for="first_name" class="col-xs-12 col-form-label"><?php echo $edit['content']; ?></label>
						
					</div>
				</div>
				
				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-10">
						<a href="{{url('admin/faq/index')}}" class="text-center btn btn-primary">Back to Faqs</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>
@endsection

