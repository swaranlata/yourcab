@extends('admin.layout.base')

@section('title', 'Send Message')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
            <a href="{{ route('admin.provider.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Back</a>

			<h5 style="margin-bottom: 2em;">Send Message</h5>
            <?php 
            if(!empty($errors->all())){
                    $dataErrors=$errors->all(); 
                    foreach($dataErrors as $k=>$v){                  
                        echo $v;
                    }                
                }?>

            <form class="form-horizontal" action="{{route('admin.sendmessage')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
                
                
                
                
                
				<div class="form-group row">
              		<label for="users" class="col-xs-12 col-form-label">All Users</label>
					<div class="col-xs-10">
                        <select multiple class="form-control" name="users[]">
                           <?php if(!empty($users)){    
                                  foreach($users as $k=>$v){
                                 ?>
                            <option value="usus_<?php echo $v['email'];?>"><?php echo $v['first_name'].' '.$v['last_name']; ?> - Passenger</option>
                           <!-- <option value="<?php //echo $v['mobile'];?>"><?php //echo $v['first_name'].' '.$v['last_name']; ?> - Passenger</option>-->
                            <?php
                                }}?> 
                            <?php if(!empty($drivers)){    
                                  foreach($drivers as $k=>$v){
                                 ?>
                            <option value="dr_dr_<?php echo $v['email'];?>"><?php echo $v['first_name'].' '.$v['last_name']; ?> - Driver</option>
                            <!--<option value="<?php //echo $v['mobile'];?>"><?php //echo $v['first_name'].' '.$v['last_name']; ?> - Driver</option>-->
                            <?php
                                }}?>
                            
                           
                        </select>
					</div>
				</div>

				<div class="form-group row">
					<label for="message" class="col-xs-12 col-form-label">Message</label>
					<div class="col-xs-10">
						 <textarea name="content" id="myeditor"></textarea>
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">Send</button>
						
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
