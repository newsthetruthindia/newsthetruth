@include('public-pages.header', ['body_classes'=>$body_classes])
<div class="product-status mb-3">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline12-list">
                    <div class="sparkline12-hd">
                        <div class="main-sparkline12-hd text-center text-white">
                            <h1>Publish Your Journalism</h1>
                        </div>
                    </div>
                    <div class="sparkline12-graph text-white">
                        <div class="basic-login-form-ad">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="all-form-element-inner">
                                        <form action="{{ route('save-citizen-journalism') }}" method="post">
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
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="login2 pull-right pull-right-pro">Place</label>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <div class="chosen-select-single mg-b-20">
                                                            <input type="text" class="form-control" name="place" value="{{ !empty($data->place) ? $data->place : ''}}">
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
                                                            <input type="text" class="form-control" name="credit" value="{{ !empty($data->credit) ? $data->credit : ''}}">
                                                            @error('credit')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group-inner">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="login2 pull-right pull-right-pro">Date and time</label>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <div class="chosen-select-single mg-b-20">
                                                            <input type="datetime-local" class="form-control" name="datetime" value="{{ !empty($data->datetime) ? $data->datetime : ''}}">
                                                            @error('datetime')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="login2 pull-right pull-right-pro">Attachment Url</label>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <div class="chosen-select-single mg-b-20">
                                                            <input type="text" class="form-control" name="attachment_url" value="{{ !empty($data->attachment_url) ? $data->attachment_url : ''}}">
                                                            @error('attachment_url')
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
                                                <div class="row text-center">
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

@include('public-pages.footer')