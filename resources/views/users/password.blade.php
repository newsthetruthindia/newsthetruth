@include('layouts.header')
@include('layouts.sidebar')
<div class="product-status mg-b-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline12-list">
                    <div class="sparkline12-hd">
                        <div class="main-sparkline12-hd">
                            <h1>Change Password</h1>
                        </div>
                        @if($errors->any())
                            <h4 class="bg-danger">{{$errors->first()}}</h4>
                        @endif
                    </div>
                    <div class="sparkline12-graph">
                        <div class="basic-login-form-ad">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="all-form-element-inner">
                                        <form action="{{ route('update-password') }}" method="post">
                                            @csrf
                                            @method('POST')
                                            <input type="hidden" name="user_id" required class="form-control" value="{{ $user->id }}" />
                                            
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="login2 pull-right pull-right-pro">Old Password</label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                        <input type="password" class="form-control" name="old_password"  />
                                                        @error('old_password')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="login2 pull-right pull-right-pro">New Password</label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                        <input type="password" class="form-control" name="new_password"  />
                                                        @error('new_password')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="login2 pull-right pull-right-pro">Confirm Password</label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                        <input type="password" class="form-control" name="confirm_password"  />
                                                        @error('confirm_password')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="login-btn-inner">
                                                <div class="row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9">
                                                        <div class="login-horizental cancel-wp pull-left">
                                                            <button class="btn btn-sm btn-primary login-submit-cs" type="submit">Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')