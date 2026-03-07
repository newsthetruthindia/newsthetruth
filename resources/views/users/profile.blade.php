@include('layouts.header')
@include('layouts.sidebar')
<style>
    .profile-picture-block{
        background: #8f8f8f;
        height: 100px;
        width: 100px;
        position: relative;
        padding: 0;
    }
    .profile-picture-block img{
        height: 100px;
        width: 100px;
        z-index: -1;
    }
    .profile-picture-block .remove_pic{
        position: absolute;
        top: 0;
        right: 0;
        z-index: 1;
        cursor: pointer;
        color: #fdfdfd;
        border: #626262 1px solid;
        background-color: #b5b5b5;
    }
</style>
<div class="product-status mg-b-30">
<div class="container-fluid">
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline12-list">
            <div class="sparkline12-hd">
                <div class="main-sparkline12-hd">
                    <h1>All Form Element</h1>
                </div>
            </div>
            <div class="sparkline12-graph">
                <div class="basic-login-form-ad">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="all-form-element-inner">
                                <form name="user_profile_save" action="{{ route('user-profile') }}" enctype="multipart/form-data" method="post">
                                    @csrf
                                    @method('post')
                                    <input type="hidden" name="user_id" required class="form-control" value="{{ $user->id }}" />
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="login2 pull-right pull-right-pro">Profile Picture</label>
                                            </div>
                                            @if( !empty( $user->thumbnails ) )
                                                <div class="profile-picture-block col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <img class="" src="{{ url('public/'.$user->thumbnails->url) }}" height="100" width="100" />
                                                    <span class="remove_pic"><i class="fa fa-close"></i></span>
                                                </div>
                                                 <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="display: none;">
                                                    <input type="file" class="form-control" name="profile_picture" />
                                                </div>
                                            @else
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <input type="file" class="form-control" name="profile_picture" />
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="login2 pull-right pull-right-pro">First Name</label>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <input type="text" name="firstname" required class="form-control" value="{{ $user->firstname }}" />
                                                @error('firstname')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="login2 pull-right pull-right-pro">Last Name</label>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <input type="text" class="form-control" name="lastname" value="{{ $user->lastname }}" />
                                                @error('lastname')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="login2 pull-right pull-right-pro">Email address</label>
                                            </div>
                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                {{ $user->email }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="login2 pull-right pull-right-pro">Street Address</label>
                                            </div>
                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                <input type="text" class="form-control" name="address1" value="{{ !empty($user->details->address1)?$user->details->address1:'' }}"  />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="login2 pull-right pull-right-pro">Address2</label>
                                            </div>
                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                <input type="text" class="form-control" name="address2" value="{{ !empty($user->details->address2)?$user->details->address2:'' }}"  />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="login2 pull-right pull-right-pro">City</label>
                                            </div>
                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                <input type="text" class="form-control" name="city" value="{{ !empty($user->details->city)?$user->details->city:'' }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="login2 pull-right pull-right-pro">State</label>
                                            </div>
                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                <input type="text" class="form-control" name="state" value="{{ !empty($user->details->state)?$user->details->state:'' }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="login2 pull-right pull-right-pro">Country</label>
                                            </div>
                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                <input type="text" class="form-control" name="country" value="{{ !empty($user->details->country)?$user->details->country:'' }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="login2 pull-right pull-right-pro">Zip</label>
                                            </div>
                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                <input type="text" class="form-control" name="zip" value="{{ !empty($user->details->zip)?$user->details->zip:'' }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="login2 pull-right pull-right-pro">Fax</label>
                                            </div>
                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                <input type="text" class="form-control" name="fax" value="{{ !empty($user->details->fax)?$user->details->fax:'' }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="login2 pull-right pull-right-pro">Alternate Email</label>
                                            </div>
                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                <input type="text" class="form-control" name="alternate_email" value="{{ !empty($user->details->alternate_email)?$user->details->alternate_email:'' }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="login2 pull-right pull-right-pro">Alternate Phone</label>
                                            </div>
                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                <input type="text" class="form-control" name="alternate_phone" value="{{ !empty($user->details->alternate_phone)?$user->details->alternate_phone:'' }}" />
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <!-- <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-6 col-sm-8 col-xs-6">
                                                <label class="login2 pull-right pull-right-pro">Gender
                                                    </label>
                                            </div>
                                            <div class="col-lg-10 col-md-6 col-sm-4 col-xs-6">
                                                <div class="bt-df-checkbox pull-left">
                                                    <label>
                                                            <input class="pull-left radio-checked" checked="" type="checkbox"> Mail</label>
                                                    <label>
                                                            <input class="pull-left radio-checked" type="checkbox"> Femail</label>
                                                    <label>
                                                            <input class="pull-left radio-checked" type="checkbox"> Other</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
                                                <label class="login2 pull-right pull-right-pro">Gender </label>
                                            </div>
                                            <div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
                                                <div class="bt-df-checkbox pull-left">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="i-checks pull-left">
                                                                <label>
                                                                    <input type="radio"{{ !empty($user->details->gender) && $user->details->gender =='male'? ' checked': '' }} value="male" name="gender"> <i></i> Mail 
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="i-checks pull-left">
                                                                <label>
                                                                    <input type="radio"{{ !empty($user->details->gender) && $user->details->gender =='female'? ' checked': '' }} value="female" name="gender"> <i></i> Female 
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="i-checks pull-left">
                                                                <label>
                                                                    <input type="radio"{{ !empty($user->details->gender) && $user->details->gender =='other'? ' checked': '' }} value="other" name="gender"> <i></i> Other 
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                                                <label class="login2 pull-right pull-right-pro">Designation</label>
                                            </div>
                                            <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                                                <div class="form-select-list">
                                                    <input type="text" class="form-control" name="designation" value="{{ !empty($user->details->designation)?$user->details->designation:'' }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                                                <label class="login2 pull-right pull-right-pro">Salary</label>
                                            </div>
                                            <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                                                <div class="form-select-list">
                                                    <input type="text" class="form-control" name="salary" value="{{ !empty($user->details->salary)?$user->details->salary:'' }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                                                <label class="login2 pull-right pull-right-pro">Date of Birth</label>
                                            </div>
                                            <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                                                <div class="form-select-list">
                                                    <input type="text" class="form-control" name="dob" value="{{ !empty($user->details->dob)?$user->details->dob:'' }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                                                <label class="login2 pull-right pull-right-pro">Bio</label>
                                            </div>
                                            <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                                                <div class="form-select-list">
                                                    <textarea name="bio" value="{{ !empty($user->details->bio)?$user->details->bio:'' }}" class="form-control">
                                                        {{ !empty($user->details->bio)?$user->details->bio:'' }}
                                                    </textarea>
                                                </div>
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
<script>
    $(document).ready(function(){
        $('.remove_pic').on('click', function(){
            $(this).closest('div').siblings('div').show();
            $(this).closest('div').remove();
        });
    });
</script>