@include('layouts.header')
@include('layouts.sidebar')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet">
<style>
    .select2-results__option--selectable {color: #000;}
    .select2-container .select2-selection--single {
        height: 38px;
    }
    .select2-selection__rendered {
        line-height: 38px;
    }
    .select2-selection__arrow {
        height: 38px;
    }
    .select2-container--default .select2-search--inline .select2-search__field {
        color: #000;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {background-color: #1b2a47;}
</style>
<div class="product-status">
    <div class="container-fluid">
        @if ($errors->any())
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline12-list">
                    <div class="sparkline12-hd">
                        <div class="main-sparkline12-hd">
                            <h1>Add New Post</h1>
                        </div>
                    </div>
                    <div class="sparkline12-graph">
                        <div class="basic-login-form-ad">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="all-form-element-inner">
                                        <form action="{{ route('save-post') }}" method="post">
                                            @csrf
                                            @method('post')
                                            <input type="hidden" name="post_id" required class="form-control" value="{{ !empty($data->id) ? $data->id :'' }}" />
                                            <div class="row">
                                                <div class="col-lg-8 col-md-8 col-sm-12 col-12">
                                                    <div class="form-group-inner">
                                                        @if(!empty($data->id))
                                                            <div class="row">
                                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                    <label class="login2 pull-right pull-right-pro">Permalink</label>
                                                                </div>
                                                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                                                    <a class="bg-primary" href="{{ url($data->slug) }}" target="_blank">{{url($data->slug)}}</a>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="row">
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Title</label>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                                <div class="chosen-select-single mg-b-20">
                                                                    <input type="text" class="form-control" name="title" value="{{ !empty($data->title) ? $data->title : old('title','')}}">
                                                                    @error('title')
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Sub Title</label>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                                <div class="chosen-select-single mg-b-20">
                                                                    <input type="text" class="form-control" name="subtitle" value="{{ !empty($data->subtitle) ? $data->subtitle : old('subtitle','')}}">
                                                                    @error('subtitle')
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-2 col-md-2 col-sm-12 col-12">
                                                                <label class="login2 pull-right pull-right-pro">Description</label>
                                                            </div>
                                                            <div class="col-lg-10 col-md-10 col-sm-12 col-12">
                                                                <div class="chosen-select-single mg-b-20">
                                                                    <textarea id="editor_classic" class="form-control" name="description" value="{{ !empty($data->description) ? $data->description :  old('description','')}}"> {{ !empty($data->description) ? $data->description : old('description','')}}</textarea>
                                                                    @error('description')
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                <label class="login2 pull-right pull-right-pro">Excerpt</label>
                                                            </div>
                                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                                                <div class="chosen-select-single mg-b-20">
                                                                    <textarea class="form-control" name="excerpt" value="{{ empty($data->excerpt) ? !empty($data->description) ? strip_tags($data->description) : '': $data->excerpt }}"> {{ empty($data->excerpt) ? !empty($data->description) ? strip_tags($data->description) : '': $data->excerpt }}</textarea>
                                                                    @error('excerpt')
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Slug</label>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                                <div class="chosen-select-single mg-b-20">
                                                                    <input type="text" class="form-control" name="slug" readonly value="{{ !empty($data->slug) ? $data->slug : old('slug','')}}">
                                                                    @error('slug')
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Video URL</label>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                                <div class="chosen-select-single mg-b-20">
                                                                    <input type="text" class="form-control" name="video_url" value="{{ !empty($data->video_url) ? $data->video_url : old('video_url','')}}">
                                                                    @error('video_url')
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Place</label>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                                <div class="chosen-select-single mg-b-20">
                                                                    <input type="text" class="form-control" name="postmeta[place]" value="{{ !empty($meta['place']) ? $meta['place'] : old('postmeta.place','')}}">
                                                                    @error('postmeta.place')
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Credit</label>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                                <div class="chosen-select-single mg-b-20">
                                                                    <input type="text" class="form-control" name="postmeta[credit]" value="{{ !empty($meta['credit']) ? $meta['credit'] : old('postmeta.credit','')}}">
                                                                    @error('video_url')
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>   
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                                <div class="chosen-select-single mg-b-20">
                                                                    <label class="login2 pull-right pull-right-pro">Primary menu order </label>
                                                                    <input type="number" class="form-control" name="primary_menu_order" value="{{ !empty($data->primary_menu_order) ? $data->primary_menu_order : ''}}">
                                                                    @error('primary_menu_order')
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                                <div class="chosen-select-single mg-b-20">
                                                                    <label class="login2 pull-right pull-right-pro">Secondary menu order </label>
                                                                    <input type="number" class="form-control" name="secondary_menu_order" value="{{ !empty($data->secondary_menu_order) ? $data->secondary_menu_order : ''}}">
                                                                    @error('secondary_menu_order')
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                                <div class="chosen-select-single mg-b-20">
                                                                    <label class="login2 pull-right pull-right-pro">Footer menu order </label>
                                                                    <input type="number" class="form-control" name="footer_menu_order" value="{{ !empty($data->footer_menu_order) ? $data->footer_menu_order : ''}}">
                                                                    @error('footer_menu_order')
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>                        
                                                        </div>
                                                    </div> 
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 block-image">
                                                                <h3>Photo Gallery </h3>
                                                                <?php
                                                                if( !empty($data->gallery) && count( $data->gallery ) > 0 ){
                                                                    foreach( $data->gallery as $gal ){
                                                                        if( !empty( $gal->cat_data ) ){
                                                                            dpImageUploader( ['callback'=>'uploadAttachments', 'imgname'=>'post_gallery[]', 'imgclass'=>'dp_product_img', 'imgvalue'=>$gal->cat_data, 'isdelete'=>true] );
                                                                        }
                                                                    }
                                                                }else{
                                                                    dpImageUploader( ['callback'=>'uploadAttachments', 'imgname'=>'post_gallery[]', 'imgclass'=>'dp_product_img', 'imgvalue'=>'', 'isdelete'=>true] );
                                                                }
                                                                ?>
                                                                <div class="dp-uploader dp-add-image-block dp-attachments-uploader dp_add_image_block">
                                                                    <div class="upload-block">Add more Image</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                                <label class="login2 pull-right pull-right-pro">Mark as Top News</label>
                                                            </div>
                                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <input type="checkbox" name="mark_as_top"{{ !empty($data->top_post) && $data->top_post =='1'? ' checked':''}}>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="time" name="to_time" class="form-control" value="{{ !empty($data->top_post_to_time) ? $data->top_post_to_time : old('top_post_to_time','')}}">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="time" name="from_time" class="form-control" value="{{ !empty($data->top_post_form_time) ? $data->top_post_form_time : old('top_post_form_time','')}}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <label class="login2">Avoid Scheduling</label>
                                                                <input type="checkbox" class="pull-right" name="avoid_scheduling"{{ !empty($data->ignore_top_scheduling) && $data->ignore_top_scheduling =='1'? ' checked':''}}>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                                <label class="login2 pull-right pull-right-pro">Category</label>
                                                            </div>
                                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                                                <div class="chosen-select-single mg-b-20">
                                                                    <?php
                                                                        $old_cats = !empty( $data->categories ) ? $data->categories()->pluck('category_id')->toArray():[];
                                                                        //dd($old_cats);
                                                                    ?>
                                                                    <select data-placeholder="Select Categories" class="form-control select2-category" multiple tabindex="-1" name="category[]">
                                                                        <option value="" >None</option>
                                                                        @if( $categories )
                                                                            @foreach( $categories as $category )
                                                                                <option value="{{ $category->id }}" {{ !empty($old_cats) && in_array($category->id, $old_cats)?' selected':''}}>{{ $category->title }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    @error('category')
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                                <label class="login2 pull-right pull-right-pro">Tags</label>
                                                            </div>
                                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                                                <div class="chosen-select-single mg-b-20">
                                                                    <?php
                                                                        $old_tagss = !empty( $data->tags ) ? $data->tags()->pluck('tag_id')->toArray():[];
                                                                    ?>
                                                                    <select data-placeholder="Choose a Tag" class="form-control select2-tag" multiple tabindex="-1" name="tags[]">
                                                                        <option value="" >None</option>
                                                                        @if( $tags )
                                                                            @foreach( $tags as $tag )
                                                                                <option value="{{ $tag->id }}" {{ !empty($old_tagss) && in_array($tag->id, $old_tagss)?' selected':''}}>{{ $tag->title }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    @error('tags')
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                                <label class="login2 pull-right pull-right-pro">Publish at</label>
                                                            </div>
                                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                                                <input type="date" name="publish_date" class="form-control" value="{{ !empty($data->post_publish_time) ? date('Y-m-d', strtotime($data->post_publish_time) ) : '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                @if( !empty( $data->thumbnails ) )
                                                                    <input type="hidden" name="thumbnail" id="attachment_id" value={{$data->thumbnails->id}} />
                                                                    <div id="post-thumbnail">
                                                                        <img src="{{ url($data->thumbnails->url) }}" />
                                                                    </div>
                                                                    <button class="btn btn-success attach_thumbnail form-control" data-target="attachment_id">Change Thumbnail</button>
                                                                @else
                                                                    <input type="hidden" name="thumbnail" id="attachment_id" />
                                                                    <div id="post-thumbnail"></div>
                                                                    <button class="btn btn-success attach_thumbnail form-control" data-target="attachment_id">Upload Thumbnail</button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <label class="login2">Photo Credit</label>
                                                                 <div class="chosen-select-single mg-b-20">
                                                                    <input type="text" class="form-control" name="postmeta[pic_credit]" value="{{ !empty($meta['pic_credit']) ? $meta['pic_credit'] : old('postmeta.pic_credit','')}}">
                                                                    @error('pic_credit')
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <label class="login2">Thumbnail Caption</label>
                                                                 <div class="chosen-select-single mg-b-20">
                                                                    <input type="text" class="form-control" name="postmeta[thumb_cap]" value="{{ !empty($meta['thumb_cap']) ? $meta['thumb_cap'] : old('postmeta.thumb_cap','')}}">
                                                                    @error('thumb_cap')
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <label class="login2">Thumbnail Description</label>
                                                                 <div class="chosen-select-single mg-b-20">
                                                                    <input type="text" class="form-control" name="postmeta[img_meta]" value="{{ !empty($meta['img_meta']) ? $meta['img_meta'] : old('postmeta.img_meta','')}}">
                                                                    @error('img_meta')
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                @if( empty($data->reviewed_by) &&!empty($data->id) && !empty( Auth::user()->details->role ) && Auth::user()->details->role->review_post )
                                                                    <label class="login2">Mark as reviewed(Once you reviewed that cannot be changed)</label>
                                                                    <input type="checkbox" class="pull-right" name="mark_as_reviewed"{{ !empty($data->mark_as_reviewed) && $data->mark_as_reviewed =='1'? ' checked':''}}>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                @if( !empty($data->id) && !empty( Auth::user()->details->role ) && Auth::user()->details->role->publish_post )
                                                                    <label class="login2">Change Status</label>
                                                                    <select data-placeholder="Choose a Status" class="form-control selectpicker" tabindex="-1" name="status">
                                                                        <option value="drafted"{{ !empty($data->status) && $data->status == 'drafted'?' selected':''}} >Draft</option>
                                                                        <option value="open"{{ !empty($data->status) && $data->status == 'open'?' selected':''}} >Open</option>
                                                                        @if( !empty($data->reviewed_by) )
                                                                            <option value="published"{{ !empty($data->status) && $data->status == 'published'?' selected':''}} >Publish</option>
                                                                        @endif
                                                                    </select>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <label class="login2">Visibility</label>
                                                                <select data-placeholder="Choose a Status" class="form-control selectpicker" tabindex="-1" name="visibility">
                                                                    <option value="public"{{ !empty($data->visibility) && $data->visibility == 'public'?' selected':''}} >Public</option>
                                                                    <option value="private"{{ !empty($data->visibility) && $data->visibility == 'private'?' selected':''}} >Private</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                @if( !empty($meta['add_url']) )
                                                                    <input type="hidden" name="postmeta[add_url]" id="add_id" value="{{ $meta['add_url'] }}" />
                                                                    <div class="add-thumbnail">
                                                                        <img src="{{ $meta['add_url']}}" />
                                                                    </div>
                                                                    <button class="btn btn-success attach_thumbnail form-control" data-target="add_id" data-type="url">Change Addvertisement Image</button>
                                                                @else
                                                                    <input type="hidden" name="postmeta[add_url]" id="add_id" />
                                                                    <div class="add-thumbnail"></div>
                                                                    <button class="btn btn-success attach_thumbnail form-control" data-target="add_id" data-type="url">Upload Addvertisement Image</button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                @if( !empty($meta['mobile_add_url']) )
                                                                    <input type="hidden" name="postmeta[mobile_add_url]" id="mobile_add_id" value="{{ $meta['mobile_add_url'] }}" />
                                                                    <div class="add-thumbnail">
                                                                        <img src="{{ $meta['mobile_add_url']}}" />
                                                                    </div>
                                                                    <button class="btn btn-success attach_thumbnail form-control" data-target="mobile_add_id" data-type="url">Mobile Addvertisement Image</button>
                                                                @else
                                                                    <input type="hidden" name="postmeta[mobile_add_url]" id="mobile_add_id" />
                                                                    <div class="add-thumbnail"></div>
                                                                    <button class="btn btn-success attach_thumbnail form-control" data-target="mobile_add_id" data-type="url">Mobile Addvertisement Image</button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="login-btn-inner">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="login-horizental cancel-wp pull-left">
                                                                    <button class="btn btn-sm btn-primary login-submit-cs" type="submit">Update</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="audio">
                                    @if( !empty($data->description) )
                                        @if( !empty($data->audio_clip_url) )
                                            <div class="generate-audio-div">
                                                <audio controls>
                                                    <source src="{{  $data->audio_clip_url  }}" type="audio/mpeg"/>
                                                </audio>
                                                <script>
                                                    var post_content = "{{ $data->description }}";
                                                </script>
                                                <div id="generate_audio_text" style="display:none;">
                                                    <form>
                                                        @csrf
                                                        @method('post')
                                                        <label>Preview Audio Script</label>
                                                        <textarea name="text" class="form-control" rows="20"></textarea>
                                                        <input type="hidden" name="post_id" value="{{ $data->id }}" />
                                                        <button class="btn btn-primary" id="generate_audio">Save As Audio</button>
                                                    </form>
                                                </div>
                                                <button class="btn btn-primary" id="generate_audio_script">Re-Generate Audio</button>
                                            </div>
                                        @else
                                            <div class="generate-audio-div">
                                                <script>
                                                    var post_content = "{{ $data->description }}";
                                                </script>
                                                <div id="generate_audio_text" style="display:none;">
                                                    <form>
                                                        @csrf
                                                        @method('post')
                                                        <label>Preview Audio Script</label>
                                                        <textarea name="text" class="form-control" rows="20"></textarea>
                                                        <input type="hidden" name="post_id" value="{{ $data->id }}" />
                                                        <button class="btn btn-primary" id="generate_audio">Save As Audio</button>
                                                    </form>
                                                </div>
                                                <button class="btn btn-primary" id="generate_audio_script">Generate Audio</button>
                                            </div>
                                        @endif
                                    @endif
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
<script>
$(document).ready(function () {
  $('.select2-category').select2({
    placeholder: "Select Category",
    allowClear: true,width: '100%'
  });
  $('.select2-tag').select2({
    placeholder: "Select Tag",
    allowClear: true,width: '100%'
  });
});
</script>
