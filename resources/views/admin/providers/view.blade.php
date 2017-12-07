@extends('admin.layout.base')

@section('title', 'Update Driver ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <a href="{{ route('admin.provider.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Back</a>

            <h5 style="margin-bottom: 2em;">Update Driver</h5>

            <form class="form-horizontal" action="{{route('admin.provider.update', $provider->id )}}" method="POST" enctype="multipart/form-data" role="form">
                {{csrf_field()}}
                <input type="hidden" name="_method" value="PATCH">
                <div class="form-group row">
                    <label for="first_name" class="col-xs-2 col-form-label">First Name</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ $provider->first_name }}" name="first_name" required id="first_name" placeholder="First Name">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="last_name" class="col-xs-2 col-form-label">Last Name</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ $provider->last_name }}" name="last_name" required id="last_name" placeholder="Last Name">
                    </div>
                </div>


             <!--   <div class="form-group row">
                    
                    <label for="picture" class="col-xs-2 col-form-label">Picture</label>
                    <div class="col-xs-10">
                    @if(isset($provider->picture))
                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{$provider->picture}}">
                    @endif
                        <input type="file" accept="image/*" name="avatar" class="dropify form-control-file" id="picture" aria-describedby="fileHelp">
                    </div>
                </div>-->

                <div class="form-group row">
                    <label for="mobile" class="col-xs-2 col-form-label">Mobile</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="number" value="{{ $provider->mobile }}" name="mobile" required id="mobile" placeholder="Mobile">
                    </div>
                </div>
                 <div class="form-group row">
					<label for="last_name" class="col-xs-2 col-form-label">License No.</label>
					<div class="col-xs-10">
						<input class="form-control" value="{{ $provider->license_no }}" type="text" value="{{ old('license_no') }}" name="license_no" required id="license_no" placeholder="License No">
					</div>
				</div>
                <div class="form-group row">
					<label for="last_name" class="col-xs-2 col-form-label">Car Registration No.</label>
					<div class="col-xs-10">
						<input class="form-control"  value="{{ $provider->car_registration_no }}" type="text" value="{{ old('car_registration_no') }}" name="car_registration_no" required id="car_registration_no" placeholder="Car Registration Number">
					</div>
				</div>
                <div class="form-group row">
					<label for="last_name" class="col-xs-2 col-form-label">Taxi No.</label>
					<div class="col-xs-10">
						<input class="form-control"  value="{{ $provider->taxi_no }}" type="text" value="{{ old('taxi_no') }}" name="taxi_no" required id="taxi_no" placeholder="Taxi Number">
					</div>
				</div>
                <div class="form-group row">
					<label for="last_name" class="col-xs-2 col-form-label">PSV No.</label>
					<div class="col-xs-10">
						<input class="form-control"  value="{{ $provider->psv_no }}" type="text" value="{{ old('psv_no') }}" name="psv_no" required id="psv_no" placeholder="PSV No">
					</div>
				</div>


                <div class="form-group row">
                    <label for="zipcode" class="col-xs-2 col-form-label"></label>
                    <div class="col-xs-10">
                        <button type="submit" class="btn btn-primary">Update Driver</button>
                        <a href="{{route('admin.provider.index')}}" class="btn btn-default">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
