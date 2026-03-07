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
                            <h1>Seo Review</h1>
                        </div>
                    </div>
                    <div class="sparkline12-graph">
                        <div class="basic-login-form-ad">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="all-form-element-inner">
                                        <div class="row">
                                            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-8">
                                                <div class="form-group-inner">
                                                    <div class="row">
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                            <label class="login2 pull-right pull-right-pro">Permalink</label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                                            <a class="bg-primary" href="{{ url($data->slug) }}" target="_blank">{{url($data->slug)}}</a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            {{ !empty($data->title) ? $data->title : ''}}
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            {{ !empty($data->subtitle) ? $data->subtitle : ''}}
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            {!! !empty($data->description) ? $data->description : '' !!}
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            {!! !empty($data->excerpt) ? $data->excerpt : '' !!}
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            {{ !empty($data->video_url) ? $data->video_url : ''}}
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <?php
                                                            if( !empty($data->gallery) && count( $data->gallery ) > 0 ){
                                                                foreach( $data->gallery as $gal ){
                                                                    if( !empty( $gal->cat_data ) ){
                                                                        ?>
                                                                        <div class="col-md-3">
                                                                            <img src="{{ url($gal->cat_data->url )}}">
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                            ?>
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
                                                                    {{ !empty($data->top_post) && $data->top_post =='1'? 'A top Post':''}}
                                                                </div>
                                                                <div class="col-md-6">
                                                                    {{ !empty($data->top_post_to_time) ? $data->top_post_to_time : ''}}
                                                                </div>
                                                                <div class="col-md-6">
                                                                    {{ !empty($data->top_post_form_time) ? $data->top_post_form_time : ''}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            {{ !empty($data->ignore_top_scheduling) && $data->ignore_top_scheduling =='1'? ' Schedule Avoiding':''}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group-inner">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            @if(!empty( $data->categories ) )
                                                                @foreach( $data->categories() as $cat)
                                                                    @if( !empty( $cat->cat_data ))
                                                                        <span class="bg-primary">{{ $cat->cat_data->title }}</span>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            @if(!empty( $data->tags ) )
                                                                @foreach( $data->tags() as $tag)
                                                                    @if( !empty( $tag->tag_data ))
                                                                        <span class="bg-primary">{{ $tag->tag_data->title }}</span>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            @if( !empty( $data->thumbnails ) )
                                                                <div id="post-thumbnail">
                                                                    <img src="{{ url($data->thumbnails->url) }}" />
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            {{ !empty($data->status) ? strtoupper($data->status):'' }}
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            {{ !empty($data->visibility) ? strtoupper($data->visibility):'' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="all-form-element-inner">
                                        <form action="{{ route('seo-update-post') }}" method="post">
                                            @csrf
                                            @method('post')
                                            <input type="hidden" name="post_id" required class="form-control" value="{{ !empty($data->id) ? $data->id :'' }}" />
                                            <input type="hidden" name="page_no" required class="form-control" value="{{ !empty($page_no) ? $page_no :'' }}" />
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Title</label>
                                                            </div>
                                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                                <div class="chosen-select-single mg-b-20">
                                                                    <input type="text" class="form-control" name="postmeta[seo_title]" value="{{ !empty($meta['seo_title']) ? $meta['seo_title']:'' }}">
                                                                    
                                                                </div>
                                                            </div>
                                                        </div><div class="row">
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Keywords( You can put multiple keywords in coma seperated format [i.e. News, Top News, Most Popular news])</label>
                                                                <label class="login2 pull-right pull-right-pro"></label>
                                                            </div>
                                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                                                <div class="chosen-select-single mg-b-20">
                                                                    <input type="text" class="form-control" name="postmeta[seo_keyword]" value="{{ !empty($meta['seo_keyword'])?$meta['seo_keyword']:''}}">
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
                                                                    <textarea class="form-control" name="postmeta[seo_description]" value="{{ !empty(!empty($meta['seo_description'] ) ? $meta['seo_description'] : $data->description) ? strip_tags($data->description) : ''}}"> {{ !empty(!empty($meta['seo_description'] ) ? $meta['seo_description'] : $data->description) ? strip_tags($data->description) : ''}}</textarea>
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
                                                                    <textarea class="form-control" name="postmeta[seo_excerpt]" value="{{ !empty(!empty($meta['seo_excerpt'] ) ? $meta['seo_excerpt'] : $data->description) ? strip_tags($data->description) : ''}}"> {{ !empty(!empty($meta['seo_excerpt'] ) ? $meta['seo_excerpt'] : $data->description) ? strip_tags($data->description) : ''}}</textarea>
                                                                    @error('excerpt')
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                    @enderror
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