@include('layouts.header')
@include('layouts.sidebar')
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
                                <form action="{{ route('save-user-settings') }}" method="post">
                                    @csrf
                                    @method('POST')
                                    <input type="hidden" name="user_id" required class="form-control" value="{{ $user->id }}" />
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <label class="login2 pull-right pull-right-pro">Show Profile Picture to All </label>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="i-checks pull-left">
                                                    <label>
                                                        <input type="checkbox"{{ !empty($user->settings->show_profile_pic_to_all) && $user->settings->show_profile_pic_to_all =='1'? ' checked': '' }} value="1" name="show_profile_pic_to_all">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <label class="login2 pull-right pull-right-pro">Show Profile Picture to Employee </label>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="i-checks pull-left">
                                                    <label>
                                                        <input type="checkbox"{{ !empty($user->settings->show_profile_pic_to_employee) && $user->settings->show_profile_pic_to_employee =='1'? ' checked': '' }} value="1" name="show_profile_pic_to_employee">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <label class="login2 pull-right pull-right-pro">Enable Message Notification </label>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="i-checks pull-left">
                                                    <label>
                                                        <input type="checkbox"{{ !empty($user->settings->enable_message_notification) && $user->settings->enable_message_notification ===1? ' checked': '' }} value="1" name="enable_message_notification">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <label class="login2 pull-right pull-right-pro">Show Notifications </label>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="i-checks pull-left">
                                                    <label>
                                                        <input type="checkbox"{{ !empty($user->settings->enable_notification_notification) && $user->settings->enable_notification_notification =='1'? ' checked': '' }} value="1" name="enable_notification_notification">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <label class="login2 pull-right pull-right-pro">Enable Auto task Assign </label>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="i-checks pull-left">
                                                    <label>
                                                        <input type="checkbox"{{ !empty($user->settings->enable_auto_assign_tasks) && $user->settings->enable_auto_assign_tasks =='1'? ' checked': '' }} value="1" name="enable_auto_assign_tasks">
                                                    </label>
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
@include('layouts.footer')