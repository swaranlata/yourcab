@extends('admin.layout.base')

@section('title', 'Provider Details ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
            	<h4>Provider Details</h4>
            	<div class="row">
            		<div class="col-md-12">
						<div class="box bg-white user-1">
						<?php $background = asset('admin/assets/img/photos-1/4.jpg'); ?>
							<p></p>
                            <br>
							<div class="u-content">
                                
                             
                              
                           
								<!--<div class="avatar box-64">
									<img class="b-a-radius-circle shadow-white" src="{{img($provider->picture)}}" alt="">
									<i class="status bg-success bottom right"></i>
								</div>-->
								
								<h5><a class="text-black" href="#">{{$provider->first_name}} {{$provider->last_name}}</a></h5>
								<p class="text-muted"><b>Email</b> : {{$provider->email}}</p>
                                <p class="text-muted">
									@if($provider->status == 'approved')
										<span class="tag tag-success">Approved</span>
									@else
										<span class="tag tag-success">Not Approved</span>
									@endif
								</p>
								<p class="text-muted"><b>Mobile </b>: {{$provider->mobile}}</p>
								<p class="text-muted"><b>Gender</b> : {{$provider->gender}}</p>
								<p class="text-muted"><b>Address</b> : {{$provider->address}}</p>
                                <p class="text-muted"><b>License No </b>: {{$provider->license_no}}</p>
								<p class="text-muted"><b>Taxi No </b>: {{$provider->taxi_no}}</p>
								<p class="text-muted"><b>Car Registration No</b> : {{$provider->car_registration_no}}</p>
								<p class="text-muted"><b>PSV No </b>: {{$provider->psv_no}}</p>
                                 <p class="text-muted" style="    width: 12%;
    text-align: center !important;
    margin-left: 44%;">   @if($provider->status == 'approved')
                                <a class="btn btn-danger btn-block" href="{{ route('admin.provider.disapprove', $provider->id ) }}">Disable</a>
                                @else
                                <a class="btn btn-success btn-block" href="{{ route('admin.provider.approve', $provider->id ) }}">Enable</a>
                                @endif
                                      </p>
                                    <p class="text-muted">    <a href="{{ route('admin.provider.show', $provider->id) }}" class="btn btn-primary"><i class="fa fa-eye"></i> </a>
                                        <a href="{{ route('admin.provider.edit', $provider->id) }}" class="btn btn-primary"><i class="fa fa-pencil"></i> </a>
                                        <a href="javascript:void(0);" data-id="<?php echo $provider->id; ?>" class="btn btn-primary sendMessageToparticularDriver"><i class="fa fa-inbox"></i>&nbsp;</a></p>
                                    
                                      <form action="" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="btn btn-default look-a-like" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</button>
                                      </form>
                                  <!--<p class="text-muted">
									@if($provider->is_activated == 1)
										<span class="tag tag-warning">Activated</span>
									@else
										<span class="tag tag-warning">Not Activated</span>
									@endif
								</p>-->
                               
							</div>
                            
						</div>
					</div>
            	</div>

            </div>
        </div>
    </div>

@endsection
