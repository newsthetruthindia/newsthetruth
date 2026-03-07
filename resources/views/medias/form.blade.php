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
                                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-8">
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Title</label>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                                <div class="chosen-select-single mg-b-20">
                                                                    <input type="text" class="form-control" name="title" value="{{ !empty($data->title) ? $data->title : ''}}">
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
                                                                    <input type="text" class="form-control" name="subtitle" value="{{ !empty($data->subtitle) ? $data->subtitle : ''}}">
                                                                    @error('subtitle')
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                <label class="login2 pull-right pull-right-pro">Description</label>
                                                            </div>
                                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                                                <div class="chosen-select-single mg-b-20">
                                                                    <textarea id="editor_classic" class="form-control" name="description" value="{{ !empty($data->description) ? $data->description : ''}}"> {{ !empty($data->description) ? $data->description : ''}}</textarea>
                                                                    @error('description')
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
                                                                    <input type="text" class="form-control" name="slug" readonly value="{{ !empty($data->slug) ? $data->slug : ''}}">
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
                                                                    <input type="text" class="form-control" name="video_url" value="{{ !empty($data->video_url) ? $data->video_url : ''}}">
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
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-4">
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                                <label class="login2 pull-right pull-right-pro">Mark as Top News</label>
                                                            </div>
                                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <input type="checkbox" name="mark_as_top">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="time" name="to_time" class="form-control">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="time" name="from_time" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <label class="login2">Avoid Scheduling</label>
                                                                <input type="checkbox" class="pull-right" name="avoid_scheduling">
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
                                                                    <select data-placeholder="Choose a Role" class="form-control selectpicker" multiple tabindex="-1" name="category[]">
                                                                        <option value="" >None</option>
                                                                        @if( $categories )
                                                                            @foreach( $categories as $category )
                                                                                <option value="{{ $category->id }}" {{ !empty($data->category_id) && ($category->id==$data->category_id)?' selected':''}}>{{ $category->title }}</option>
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
                                                                    <select data-placeholder="Choose a Role" class="form-control selectpicker" multiple tabindex="-1" name="tags[]">
                                                                        <option value="" >None</option>
                                                                        @if( $tags )
                                                                            @foreach( $tags as $tag )
                                                                                <option value="{{ $tag->id }}" {{ !empty($data->category_id) && ($category->id==$data->category_id)?' selected':''}}>{{ $tag->title }}</option>
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
                                                                <input type="date" name="publish_date" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                                <label class="login2 pull-right pull-right-pro">Publish at</label>
                                                            </div>
                                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                                                <input type="text" name="postmeta[not]" class="form-control" value="{{ !empty($meta['not'])?$meta['not']:''}}">
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