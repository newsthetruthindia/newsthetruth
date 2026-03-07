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
                            <h1>Add Quick Post</h1>
                        </div>
                    </div>
                    <div class="sparkline12-graph">
                        <div class="basic-login-form-ad">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="all-form-element-inner">
                                        <form action="{{ route('save-quick-post') }}" method="post">
                                            @csrf
                                            @method('post')
                                            <input type="hidden" name="post_id" required class="form-control" value="{{ !empty($data->id) ? $data->id :'' }}" />
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
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                        <div class="chosen-select-single mg-b-20">
                                                            <textarea id="editor_classic" class="form-control" name="description" value="{{ !empty($data->description) ? $data->description : ''}}"> {{ !empty($data->description) ? $data->description : ''}}</textarea>
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
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
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
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="login2 pull-right pull-right-pro">Place</label>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <div class="chosen-select-single mg-b-20">
                                                            <input type="text" class="form-control" name="postmeta[place]" value="{{ !empty($meta['place']) ? $meta['place'] : ''}}">
                                                            @error('place')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="login2 pull-right pull-right-pro">Credit</label>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <div class="chosen-select-single mg-b-20">
                                                            <input type="text" class="form-control" name="postmeta[credit]" value="{{ !empty($meta['credit']) ? $meta['credit'] : ''}}">
                                                            @error('video_url')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>   
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-12 col-sm-4 col-xs-12">
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
                                                    <div class="col-lg-4 col-md-12 col-sm-4 col-xs-12">
                                                        <label class="login2 pull-right pull-right-pro">Category</label>
                                                        <div class="chosen-select-single mg-b-20">
                                                            <?php
                                                                $old_cats = !empty( $data->categories ) ? $data->categories()->pluck('category_id')->toArray():[];
                                                                //dd($old_cats);
                                                            ?>
                                                            <select data-placeholder="Select Categories" class="form-control selectpicker" multiple tabindex="-1" name="category[]">
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
                                                    <div class="col-lg-4 col-md-12 col-sm-4 col-xs-12">
                                                        <label class="login2 pull-right pull-right-pro">Tags</label>
                                                        <div class="chosen-select-single mg-b-20">
                                                            <?php
                                                                $old_tagss = !empty( $data->tags ) ? $data->tags()->pluck('tag_id')->toArray():[];
                                                            ?>
                                                            <select data-placeholder="Choose a Tag" class="form-control selectpicker" multiple tabindex="-1" name="tags[]">
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
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <button class="btn btn-primary">Save</button>
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