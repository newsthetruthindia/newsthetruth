@include('layouts.header')
@include('layouts.sidebar')
<div class="product-status mg-t-30">
<div class="container-fluid">
    <form action="{{ route('save-site-settings') }}" method="post">
        @csrf
        @method('post')
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-6">
                <div class="admin-pro-accordion-wrap responsive-mg-b-30">
                    <div class="panel-group adminpro-custon-design" id="accordion">
                        <div class="panel panel-default">
                            <div class="panel-heading accordion-head">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Home Page</a>
                                </h4>
                            </div>
                            <div id="collapse1" class="panel-collapse panel-ic collapse in">
                                <div class="panel-body admin-panel-content animated bounce">
                                    <div class="form-group-inner">
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Site Title</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" value="{{ !empty($settings['site_title']) ? $settings['site_title'] : '' }}" name="settings[site_title]">
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Site Description</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <textarea class="form-control" value="{{ !empty($settings['site_description']) ? $settings['site_description'] : '' }}" name="settings[site_description]">
                                                    {{ !empty($settings['site_description']) ? $settings['site_description'] : '' }}
                                                </textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Keywords</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" value="{{ !empty($settings['site_keywords']) ? $settings['site_keywords'] : '' }}" name="settings[site_keywords]">
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">G-Tag</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" value="{{ !empty($settings['g_tag']) ? $settings['g_tag'] : '' }}" name="settings[g_tag]">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <label class="login2 ">Multilingual</label>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="i-checks pull-left">
                                                    <label>
                                                        <input type="checkbox"{{ !empty($settings['multi_lingual']) && $settings['multi_lingual'] =='1'? ' checked': '' }} value="1" name="settings[multi_lingual]">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <label class="login2 ">Disable Just In</label>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="i-checks pull-left">
                                                    <label>
                                                        <input type="checkbox"{{ !empty($settings['enable_just_in']) && $settings['enable_just_in'] =='1'? ' checked': '' }} value="1" name="settings[enable_just_in]">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <label class="login2 ">Is an e-commerce site </label>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="i-checks pull-left">
                                                    <label>
                                                        <input type="checkbox"{{ !empty($settings['ecom_enabled']) && $settings['ecom_enabled'] =='1'? ' checked': '' }} value="1" name="settings[ecom_enabled]">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <label class="login2 ">Is an e commerce site </label>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="i-checks pull-left">
                                                    <label>
                                                        <input type="checkbox"{{ !empty($role->ecom_enabled) && $role->ecom_enabled =='1'? ' checked': '' }} value="1" name="ecom_enabled">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading accordion-head">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Logo and icons</a>
                                </h4>
                            </div>
                            <div id="collapse2" class="panel-collapse panel-ic collapse">
                                <div class="panel-body admin-panel-content animated bounce">
                                    <div class="form-group-inner">
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Site Icon</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <?php
                                                if( !empty( $settings['site_icon'] ) ){
                                                    dpImageUploader( ['callback'=>'uploadAttachments', 'imgname'=>'settings[site_icon]', 'imgclass'=>'dp_product_img', 'imgvalue'=>$settings['site_icon'], 'isdelete'=>false] );
                                                }else{
                                                    dpImageUploader( ['callback'=>'uploadAttachments', 'imgname'=>'settings[site_icon]', 'imgclass'=>'dp_product_img', 'imgvalue'=>'', 'isdelete'=>false] );
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Site Logo</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <?php
                                                if( !empty( $settings['site_logo'] ) ){
                                                    dpImageUploader( ['callback'=>'uploadAttachments', 'imgname'=>'settings[site_logo]', 'imgclass'=>'dp_product_img', 'imgvalue'=>$settings['site_logo'], 'isdelete'=>false] );
                                                }else{
                                                    dpImageUploader( ['callback'=>'uploadAttachments', 'imgname'=>'settings[site_logo]', 'imgclass'=>'dp_product_img', 'imgvalue'=>'', 'isdelete'=>false] );
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-6">
                <div class="admin-pro-accordion-wrap responsive-mg-b-30">
                    <div class="panel-group adminpro-custon-design" id="accordion2">
                        <div class="panel panel-default">
                            <div class="panel-heading accordion-head">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse21">Menues and Navigation</a>
                                </h4>
                            </div>
                            <div id="collapse21" class="panel-collapse panel-ic collapse in">
                                <div class="panel-body admin-panel-content animated bounce">
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <label class="login2">Secondary Menu</label>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="i-checks pull-left">
                                                    <select data-placeholder="Choose a Status" class="form-control selectpicker" tabindex="-1" name="settings[secondary_menu]">
                                                        @foreach( $menus as $menu )
                                                            <option value="{{ $menu->id }}"{{!empty($settings['secondary_menu']) && $settings['secondary_menu'] == $menu->id? ' selected': ''}}>{{ $menu->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <label class="login2">Footer Menu</label>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="i-checks pull-left">
                                                    <select data-placeholder="Choose a Status" class="form-control selectpicker" tabindex="-1" name="settings[footer_menu]">
                                                        @foreach( $menus as $menu )
                                                            <option value="{{ $menu->id }}"{{!empty($settings['footer_menu']) && $settings['footer_menu'] == $menu->id? ' selected': ''}}>{{ $menu->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <label class="login2">Primary Menu</label>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="i-checks pull-left">
                                                    <select data-placeholder="Choose a Status" class="form-control selectpicker" tabindex="-1" name="settings[primary_menu]">
                                                        @foreach( $menus as $menu )
                                                            <option value="{{ $menu->id }}"{{!empty($settings['primary_menu']) && $settings['primary_menu'] == $menu->id? ' selected': ''}}>{{ $menu->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading accordion-head">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse22">Add Section</a>
                                </h4>
                            </div>
                            <div id="collapse22" class="panel-collapse panel-ic collapse">
                                <div class="panel-body admin-panel-content animated bounce">
                                    <div class="form-group-inner">
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Global Addvertisement</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <?php
                                                if( !empty( $settings['global_add'] ) ){
                                                    dpImageUploader( ['callback'=>'uploadAttachments', 'imgname'=>'settings[global_add]', 'imgclass'=>'dp_product_img', 'imgvalue'=>$settings['global_add'], 'isdelete'=>false] );
                                                }else{
                                                    dpImageUploader( ['callback'=>'uploadAttachments', 'imgname'=>'settings[global_add]', 'imgclass'=>'dp_product_img', 'imgvalue'=>'', 'isdelete'=>false] );
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Pop-up  Addvertise URL</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="text" class="form-control" value="{{ !empty($settings['global_add_url']) ? $settings['global_add_url'] : '' }}" name="settings[global_add_url]">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Global Addvertisement For Mobile</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <?php
                                                if( !empty( $settings['mobile_global_add'] ) ){
                                                    dpImageUploader( ['callback'=>'uploadAttachments', 'imgname'=>'settings[mobile_global_add]', 'imgclass'=>'dp_product_img', 'imgvalue'=>$settings['mobile_global_add'], 'isdelete'=>false] );
                                                }else{
                                                    dpImageUploader( ['callback'=>'uploadAttachments', 'imgname'=>'settings[mobile_global_add]', 'imgclass'=>'dp_product_img', 'imgvalue'=>'', 'isdelete'=>false] );
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Pop-up  Addvertise URL</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="text" class="form-control" value="{{ !empty($settings['mobile_global_add_url']) ? $settings['mobile_global_add_url'] : '' }}" name="settings[mobile_global_add_url]">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Post Left Addvertisement</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <?php
                                                if( !empty( $settings['post_left'] ) ){
                                                    dpImageUploader( ['callback'=>'uploadAttachments', 'imgname'=>'settings[post_left]', 'imgclass'=>'dp_product_img', 'imgvalue'=>$settings['post_left'], 'isdelete'=>false] );
                                                }else{
                                                    dpImageUploader( ['callback'=>'uploadAttachments', 'imgname'=>'settings[post_left]', 'imgclass'=>'dp_product_img', 'imgvalue'=>'', 'isdelete'=>false] );
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">>Post Left  Addvertise URL</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="text" class="form-control" value="{{ !empty($settings['post_left_add_url']) ? $settings['post_left_add_url'] : '' }}" name="settings[post_left_add_url]">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Popup Addvertisement</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <?php
                                                if( !empty( $settings['pop_up_add'] ) ){
                                                    dpImageUploader( ['callback'=>'uploadAttachments', 'imgname'=>'settings[pop_up_add]', 'imgclass'=>'dp_product_img', 'imgvalue'=>$settings['pop_up_add'], 'isdelete'=>false] );
                                                }else{
                                                    dpImageUploader( ['callback'=>'uploadAttachments', 'imgname'=>'settings[pop_up_add]', 'imgclass'=>'dp_product_img', 'imgvalue'=>'', 'isdelete'=>false] );
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Pop-up  Addvertise URL</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="text" class="form-control" value="{{ !empty($settings['pop_up_add_url']) ? $settings['pop_up_add_url'] : '' }}" name="settings[pop_up_add_url]">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Popup Addvertisement Mobile</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <?php
                                                if( !empty( $settings['mobile_pop_up_add'] ) ){
                                                    dpImageUploader( ['callback'=>'uploadAttachments', 'imgname'=>'settings[mobile_pop_up_add]', 'imgclass'=>'dp_product_img', 'imgvalue'=>$settings['mobile_pop_up_add'], 'isdelete'=>false] );
                                                }else{
                                                    dpImageUploader( ['callback'=>'uploadAttachments', 'imgname'=>'settings[mobile_pop_up_add]', 'imgclass'=>'dp_product_img', 'imgvalue'=>'', 'isdelete'=>false] );
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Mobile Pop-up URL</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="text" class="form-control" value="{{ !empty($settings['mobile_pop_up_add_url']) ? $settings['mobile_pop_up_add_url'] : '' }}" name="settings[mobile_pop_up_add_url]">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Header Right</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <?php
                                                if( !empty( $settings['header_right'] ) ){
                                                    dpImageUploader( ['callback'=>'uploadAttachments', 'imgname'=>'settings[header_right]', 'imgclass'=>'dp_product_img', 'imgvalue'=>$settings['header_right'], 'isdelete'=>false] );
                                                }else{
                                                    dpImageUploader( ['callback'=>'uploadAttachments', 'imgname'=>'settings[header_right]', 'imgclass'=>'dp_product_img', 'imgvalue'=>'', 'isdelete'=>false] );
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Header Right URL</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="text" class="form-control" value="{{ !empty($settings['header_right_url']) ? $settings['header_right_url'] : '' }}" name="settings[header_right_url]">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Header Left</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <?php
                                                if( !empty( $settings['header_left'] ) ){
                                                    dpImageUploader( ['callback'=>'uploadAttachments', 'imgname'=>'settings[header_left]', 'imgclass'=>'dp_product_img', 'imgvalue'=>$settings['header_left'], 'isdelete'=>false] );
                                                }else{
                                                    dpImageUploader( ['callback'=>'uploadAttachments', 'imgname'=>'settings[header_left]', 'imgclass'=>'dp_product_img', 'imgvalue'=>'', 'isdelete'=>false] );
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Header Right URL</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="text" class="form-control" value="{{ !empty($settings['header_left_url']) ? $settings['header_left_url'] : '' }}" name="settings[header_left_url]">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading accordion-head">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse23">Social Follow</a>
                                </h4>
                            </div>
                            <div id="collapse23" class="panel-collapse panel-ic collapse">
                                <div class="panel-body admin-panel-content animated bounce">
                                    <div class="form-group-inner">
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Enable Social Follow</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="checkbox"{{ !empty($settings['enable_social_follow']) && $settings['enable_social_follow'] =='1'? ' checked': '' }} value="1" name="settings[enable_social_follow]">
                                                </label>
                                            </div>
                                        </div>
                                        <div clas
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Facebook Url</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="text" class="form-control" value="{{ !empty($settings['follow_facebook']) ? $settings['follow_facebook'] : '' }}" name="settings[follow_facebook]">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Twitter Url</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="text" class="form-control" value="{{ !empty($settings['follow_twitter']) ? $settings['follow_twitter'] : '' }}" name="settings[follow_twitter]">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Linkdin Url</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="text" class="form-control" value="{{ !empty($settings['follow_linkdin']) ? $settings['follow_linkdin'] : '' }}" name="settings[follow_linkdin]">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Instagram Url</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="text" class="form-control" value="{{ !empty($settings['follow_instagram']) ? $settings['follow_instagram'] : '' }}" name="settings[follow_instagram]">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Youtube Url</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="text" class="form-control" value="{{ !empty($settings['follow_youtube']) ? $settings['follow_youtube'] : '' }}" name="settings[follow_youtube]">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">WhatsApp Url</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="text" class="form-control" value="{{ !empty($settings['follow_whatsapp']) ? $settings['follow_whatsapp'] : '' }}" name="settings[follow_whatsapp]">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Telegram Url</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="text" class="form-control" value="{{ !empty($settings['follow_telegram']) ? $settings['follow_telegram'] : '' }}" name="settings[follow_telegram]">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading accordion-head">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse24">Social Share</a>
                                </h4>
                            </div>
                            <div id="collapse24" class="panel-collapse panel-ic collapse">
                                <div class="panel-body admin-panel-content animated bounce">
                                    <div class="form-group-inner">
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Enable Social Share</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="checkbox"{{ !empty($settings['enable_social_share']) && $settings['enable_social_share'] =='1'? ' checked': '' }} value="1" name="settings[enable_social_share]">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Enable Share on Facebook</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="checkbox"{{ !empty($settings['share_facebook']) && $settings['share_facebook'] =='1'? ' checked': '' }} value="1" name="settings[share_facebook]">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Enable Share on Linkedin</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="checkbox"{{ !empty($settings['share_linkdin']) && $settings['share_linkdin'] =='1'? ' checked': '' }} value="1" name="settings[share_linkdin]">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Enable Share on Twitter</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="checkbox"{{ !empty($settings['share_twitter']) && $settings['share_twitter'] =='1'? ' checked': '' }} value="1" name="settings[share_twitter]">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Enable Share on Instagram</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="checkbox"{{ !empty($settings['share_instagram']) && $settings['share_instagram'] =='1'? ' checked': '' }} value="1" name="settings[share_instagram]">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Enable Share on Tumbler</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="checkbox"{{ !empty($settings['share_tumbler']) && $settings['share_tumbler'] =='1'? ' checked': '' }} value="1" name="settings[share_tumbler]">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Enable Share on Pinterrest</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="checkbox"{{ !empty($settings['share_pinterrest']) && $settings['share_pinterrest'] =='1'? ' checked': '' }} value="1" name="settings[share_pinterrest]">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Enable Share on WhatsApp</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="checkbox"{{ !empty($settings['share_whatsapp']) && $settings['share_whatsapp'] =='1'? ' checked': '' }} value="1" name="settings[share_whatsapp]">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-lg-4">
                                                <label class="login2 ">Enable Share on Telegram</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>
                                                    <input type="checkbox"{{ !empty($settings['share_telegram']) && $settings['share_telegram'] =='1'? ' checked': '' }} value="1" name="settings[share_telegram]">
                                                </label>
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
        <div class="row">
            <button class="btn btn-sm btn-primary">Update</button>
        </div>
    </form>
</div>
</div>


@include('layouts.footer')
<!-- <script type="text/javascript">
    $(document).ready(function(){
        $('#add-role').on('click', function(e){
            e.preventDefault();
            $.ajax({
                url:"{{ route('settings-role-add') }}",
                method:'GET',
                success:function(res){
                    if(res && res.form ){
                        $('#role-form').html(res.form);
                    }
                },
            });
        });

        $(document).on('click', '.role-edit', function(e){
            e.preventDefault();
            var id = $(this).data('id');
            $.ajax({
                url:"{{ route('settings-role-edit') }}",
                method:'POST',
                data:{'id':id, '_token':"{{ csrf_token() }}"},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(res){
                    if(res && res.form ){
                        $('#role-form').html(res.form);
                    }
                },
            });
        });

        $(document).on('click', '.role-delete', function(e){
            e.preventDefault();
            let _this = $(this);
            var id = _this.data('id');
            $.ajax({
                url:"{{ route('settings-role-delete') }}",
                method:'POST',
                data:{'id':id, '_token':"{{ csrf_token() }}"},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(res){
                    if(res && !res.error ){
                        _this.closest('.row').remove();
                    }
                },
            });
        });

        $(document).on('submit', '#save_role', function(e){
            e.preventDefault();
            var type = $('#role-form').find('#role_id').data('type');
            var form = $('#save_role')[0];
            var form_data = new FormData(form);
            $.ajax({
                type:'POST',
                data:form_data,
                url:"{{ route('settings-roles-update') }}",
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(res){
                    if(res && res.role ){
                        $('#role-form').find('#role_id').val(res.role.id);
                        if( type == 'add'){
                            $('#role-form').find('#role_id').data('type', 'edit');
                            var html = '<div class="row">'+
                                    '<div class="col-lg-6"><small>'+ res.role.role_name +'</small></div>'+
                                    '<div class="col-lg-2"><small class="role-edit" data-id="'+res.role.id+'"><i class="fa fa-edit"></i></small></div>'+
                                    '<div class="col-lg-2"><small class="role-delete" data-id="'+res.role.id+'"><i class="fa fa-trash"></i></small></div>'+
                                '</div>';
                            $(html).appendTo('.roles-list');
                        }
                    }
                },
            });
        });
    });
</script> -->