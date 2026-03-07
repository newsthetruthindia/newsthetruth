@include('layouts.header')
@include('layouts.sidebar')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="product-status mg-b-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline12-list">
                    <div class="sparkline12-hd">
                        <div class="main-sparkline12-hd">
                            <h1>Add New User</h1>
                        </div>
                    </div>
                    <div class="sparkline12-graph">
                        <div class="basic-login-form-ad">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="all-form-element-inner">
                                        <form action="{{ route('save-user') }}" method="post">
                                            @csrf
                                            @method('post')
                                            <input type="hidden" name="user_id" required class="form-control" value="{{ !empty($user->id) ? $user->id :'' }}" />
                                            <input type="hidden" name="user_type" required class="form-control" value="admin" />
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="login2 pull-right pull-right-pro">First Name</label>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <input type="text" name="firstname" required class="form-control" value="{{ !empty($user->firstname) ? $user->firstname :(!empty(old('firstname')) ? old('firstname') :'') }}" />
                                                        @error('firstname')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="login2 pull-right pull-right-pro">Last Name</label>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <input type="text" class="form-control" name="lastname" value="{{ !empty($user->lastname) ? $user->lastname :(!empty(old('lastname')) ? old('lastname') :'') }}" />
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
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        @if( !empty($user->id) )
                                                            <span class="form-control">
                                                                {{ !empty($user->email) ? $user->email :'' }}
                                                            </span>
                                                        @else
                                                            <input type="text" class="form-control" name="email" value="{{ !empty($user->email) ? $user->email :'' }}" />
                                                            @error('email')
                                                                <div class="alert alert-danger">{{ $message }}</div>
                                                            @enderror
                                                        @endif
                                                        @error('email')
                                                                <div class="alert alert-danger">{{ $message }}</div>
                                                            @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="login2 pull-right pull-right-pro">Phone</label>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <input type="text" class="form-control" name="phone" value="{{ !empty($user->phone) ? $user->phone :(!empty(old('phone')) ? old('phone') :'') }}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="login2 pull-right pull-right-pro">Password</label>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <input type="text" class="form-control" name="password" />
                                                        @error('password')
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
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <input type="text" class="form-control" name="c_password" value="{{ !empty($user->c_password) ? $user->c_passwpord :'' }}" />
                                                        @error('c_passwpord')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="login2 pull-right pull-right-pro">Role</label>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <div class="chosen-select-single mg-b-20">
                                                            <select data-placeholder="Choose a Role" class="form-control selectpicker" tabindex="-1" name="role_id">
                                                                @if( $roles )
                                                                    @foreach( $roles as $role )
                                                                        <option value="{{ $role->id }}" {{ !empty($user->details->role_id) && $user->details->role_id == $role->id ? 'selected' :'' }}>{{ $role->role_name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            @error('role_id')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="login2 pull-right pull-right-pro">Street Address</label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                        <input type="text" class="form-control" name="address1" value="{{ !empty($user->details->address1)?$user->details->address1:(!empty(old('address1')) ? old('address1') :'') }}"  />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="login2 pull-right pull-right-pro">Address2</label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                        <input type="text" class="form-control" name="address2" value="{{ !empty($user->details->address2)?$user->details->address2:(!empty(old('address2')) ? old('address2') :'') }}"  />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="login2 pull-right pull-right-pro">City</label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                        <input type="text" class="form-control" name="city" value="{{ !empty($user->details->city)?$user->details->city:(!empty(old('city')) ? old('city') :'') }}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="login2 pull-right pull-right-pro">State</label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                        <input type="text" class="form-control" name="state" value="{{ !empty($user->details->state)?$user->details->state:(!empty(old('state')) ? old('state') :'') }}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="login2 pull-right pull-right-pro">Country</label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                        <input type="text" class="form-control" name="country" value="{{ !empty($user->details->country)?$user->details->country:(!empty(old('country')) ? old('country') :'') }}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="login2 pull-right pull-right-pro">Zip</label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                        <input type="text" class="form-control" name="zip" value="{{ !empty($user->details->zip)?$user->details->zip:(!empty(old('zip')) ? old('zip') :'') }}" />
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
                                                        <input type="text" class="form-control" name="alternate_email" value="{{ !empty($user->details->alternate_email)?$user->details->alternate_email:(!empty(old('alternate_email')) ? old('alternate_email') :'') }}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="login2 pull-right pull-right-pro">Alternate Phone</label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                        <input type="text" class="form-control" name="alternate_phone" value="{{ !empty($user->details->alternate_phone)?$user->details->alternate_phone:(!empty(old('alternate_phone')) ? old('alternate_phone') :'') }}" />
                                                    </div>
                                                </div>
                                            </div>
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
                                                                            <input type="radio"{{ !empty($user->details->gender) && $user->details->gender =='male'? ' checked': '' }} value="male" name="gender"> <i></i> Male 
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