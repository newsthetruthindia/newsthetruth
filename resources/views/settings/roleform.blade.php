
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">        
        <div class="all-form-element-inner">
            <form action="#" method="post" id="save_role" >
                @csrf
                @method('POST')

                <input type="hidden" name="role_id" id="role_id" required class="form-control" value="{{ !empty($role->id)?$role->id:'' }}" data-type="{{ !empty($role->id)?'edit':'add' }}"/>
                <div class="form-group-inner">
                    <div class="row">
                        <div class="col-lg-8">
                            <label class="login2 ">Role Name </label>
                        </div>
                        <div class="col-lg-4">
                            <div class="i-checks pull-left">
                                <label>
                                    <input type="text" class="form-control pull-left" required value="{{ !empty($role->role_name) ? $role->role_name : '' }}" name="role_name">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="admin-pro-accordion-wrap responsive-mg-b-30">
                    <div class="panel-group adminpro-custon-design" id="accordion">
                        @if( Auth::user()->details->role->manage_user_settings )
                            <div class="panel panel-default">
                                <div class="panel-heading accordion-head">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">User Permission</a>
                                    </h4>
                                </div>
                                <div id="collapse1" class="panel-collapse panel-ic collapse in">
                                    <div class="panel-body admin-panel-content animated bounce">
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Can create User </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->create_user) && $role->create_user =='1'? ' checked': '' }} value="1" name="create_user">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Can Edit User </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->edit_user) && $role->edit_user =='1'? ' checked': '' }} value="1" name="edit_user">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Can view User </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->view_user) && $role->view_user ===1? ' checked': '' }} value="1" name="view_user">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Delete User</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->delete_user) && $role->delete_user =='1'? ' checked': '' }} value="1" name="delete_user">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if( Auth::user()->details->role->manage_post_settings )
                            <div class="panel panel-default">
                                <div class="panel-heading accordion-head">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Posts Permission</a>
                                    </h4>
                                </div>
                                <div id="collapse2" class="panel-collapse panel-ic collapse">
                                    <div class="panel-body admin-panel-content animated bounce">
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">View all Post </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->view_post_list) && $role->view_post_list =='1'? ' checked': '' }} value="1" name="view_post_list">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Crearte Post </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->create_post) && $role->create_post =='1'? ' checked': '' }} value="1" name="create_post">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Edit Post </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->edit_post) && $role->edit_post =='1'? ' checked': '' }} value="1" name="edit_post">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Delete Post </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->delete_post) && $role->delete_post =='1'? ' checked': '' }} value="1" name="delete_post">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Review Post </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->review_post) && $role->review_post =='1'? ' checked': '' }} value="1" name="review_post">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Publish Post </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->publish_post) && $role->publish_post =='1'? ' checked': '' }} value="1" name="publish_post">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Seo Optimizer </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->update_post_seo) && $role->update_post_seo =='1'? ' checked': '' }} value="1" name="update_post_seo">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Set Post Priority</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->set_post_priority) && $role->set_post_priority =='1'? ' checked': '' }} value="1" name="set_post_priority">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Add Post Comment</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->add_post_comment) && $role->add_post_comment =='1'? ' checked': '' }} value="1" name="add_post_comment">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Edit Post Comment</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->edit_post_comment) && $role->edit_post_comment =='1'? ' checked': '' }} value="1" name="edit_post_comment">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Edit Other User's Comment</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->edit_others_post_comment) && $role->edit_others_post_comment =='1'? ' checked': '' }} value="1" name="edit_others_post_comment">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Delete any Comment</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->delete_post_comment) && $role->delete_post_comment =='1'? ' checked': '' }} value="1" name="delete_post_comment">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Delete Other User's Comment</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->delete_others_post_comment) && $role->delete_others_post_comment =='1'? ' checked': '' }} value="1" name="delete_others_post_comment">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if( Auth::user()->details->role->manage_post_settings )
                            <div class="panel panel-default">
                                <div class="panel-heading accordion-head">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse5">Pages Permission</a>
                                    </h4>
                                </div>
                                <div id="collapse5" class="panel-collapse panel-ic collapse">
                                    <div class="panel-body admin-panel-content animated bounce">
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Crearte Page </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->create_page) && $role->create_page =='1'? ' checked': '' }} value="1" name="create_page">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Edit Page </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->edit_page) && $role->edit_page =='1'? ' checked': '' }} value="1" name="edit_page">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Delete Page </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->delete_page) && $role->delete_page =='1'? ' checked': '' }} value="1" name="delete_page">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">View All Pages </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->view_page_list) && $role->view_page_list =='1'? ' checked': '' }} value="1" name="view_page_list">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if( Auth::user()->details->role->manage_category_settings )
                            <div class="panel panel-default">
                                <div class="panel-heading accordion-head">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Category & Tags permission</a>
                                    </h4>
                                </div>
                                <div id="collapse3" class="panel-collapse panel-ic collapse">
                                    <div class="panel-body admin-panel-content animated bounce">
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Create Category</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->create_category) && $role->create_category =='1'? ' checked': '' }} value="1" name="create_category">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Edit Category</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->edit_category) && $role->edit_category =='1'? ' checked': '' }} value="1" name="edit_category">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Delete Category</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->delete_category) && $role->delete_category =='1'? ' checked': '' }} value="1" name="delete_category">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Create Tags</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->create_tag) && $role->create_tag =='1'? ' checked': '' }} value="1" name="create_tag">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Edit tags</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->edit_tag) && $role->edit_tag =='1'? ' checked': '' }} value="1" name="edit_tag">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Delete tags</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->delete_tag) && $role->delete_tag =='1'? ' checked': '' }} value="1" name="delete_tag">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if( Auth::user()->details->role->manage_other_settings )
                            <div class="panel panel-default">
                                <div class="panel-heading accordion-head">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">Other Permissions</a>
                                    </h4>
                                </div>
                                <div id="collapse4" class="panel-collapse panel-ic collapse">
                                    <div class="panel-body admin-panel-content animated bounce">
                                        
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Permanant Delete</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->parmanant_delete) && $role->parmanant_delete =='1'? ' checked': '' }} value="1" name="parmanant_delete">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">View Settings</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->view_settings) && $role->view_settings =='1'? ' checked': '' }} value="1" name="view_settings">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Update Settings</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->update_settings) && $role->update_settings =='1'? ' checked': '' }} value="1" name="update_settings">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Manage User's Settings</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->manage_user_settings) && $role->manage_user_settings =='1'? ' checked': '' }} value="1" name="manage_user_settings">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Manage Category Settings</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->manage_category_settings) && $role->manage_category_settings =='1'? ' checked': '' }} value="1" name="manage_category_settings">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Manage Post Settings</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->manage_post_settings) && $role->manage_post_settings =='1'? ' checked': '' }} value="1" name="manage_post_settings">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Manage Other Settings</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->manage_other_settings) && $role->manage_other_settings =='1'? ' checked': '' }} value="1" name="manage_other_settings">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Create Gallery</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->create_gallery) && $role->create_gallery =='1'? ' checked': '' }} value="1" name="create_gallery">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Delete Gallery</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->delete_gallery) && $role->delete_gallery =='1'? ' checked': '' }} value="1" name="delete_gallery">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Update Gallery</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->update_gallery) && $role->update_gallery =='1'? ' checked': '' }} value="1" name="update_gallery">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <label class="login2 ">Manage menu</label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="i-checks pull-left">
                                                        <label>
                                                            <input type="checkbox"{{ !empty($role->manage_menu) && $role->manage_menu =='1'? ' checked': '' }} value="1" name="manage_menu">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="login-btn-inner">
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-9">
                            <div class="login-horizental cancel-wp pull-left">
                                <button class="btn btn-oval btn-primary" type="submit" id="update_role">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>