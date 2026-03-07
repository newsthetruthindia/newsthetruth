@include('layouts.header')
@include('layouts.sidebar')
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
                                        <form action="{{ route('save-justin') }}" method="post">
                                            @csrf
                                            @method('post')
                                            <input type="hidden" name="post_id" required class="form-control" value="{{ !empty($data->id) ? $data->id :'' }}" />
                                            <div class="row">
                                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                <label class="login2 pull-right pull-right-pro">Description</label>
                                                            </div>
                                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
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
                                                                <label class="login2 pull-right pull-right-pro">Parent</label>
                                                            </div>
                                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                                <div class="chosen-select-single mg-b-20">
                                                                    <select name="parent" class="form-control">
                                                                        <option value=''>Choose Parent</option>
                                                                        @if( !empty( $posts ) )
                                                                            @foreach( $posts as $p )
                                                                                <option value="{{ $p->id }}" {{ !empty( $data->parent->id ) && $data->parent->id == $p->id? ' selected':'' }} >{{ substr($p->excerpt, 0, 50) }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    @error('parent')
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                   
                                                    <div class="form-group-inner">
                                                        
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.footer')