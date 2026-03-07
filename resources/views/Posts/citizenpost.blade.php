@include('layouts.header')
@include('layouts.sidebar')
<div class="product-status mg-b-30">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap">
                    <h4 class="text-white">Journalism view</h4>
                    <div class="row">
                        <div class="col-lg-12"> 
                            {{ $data->title }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12"> 
                            {{ $data->subtitle }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12"> 
                            {!! $data->description !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6"> 
                            <a href="{{ $data->attachment_url }}" target="_blank">
                                {{ $data->attachment_url }}
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4"> 
                            {{ $data->place }}
                        </div>
                        <div class="col-lg-4"> 
                            {{ $data->credit }}
                        </div>
                        <div class="col-lg-4"> 
                            {{ $data->datetime }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4"> 
                            <a data-toggle="tooltip" title="Make Post" class="pd-setting-ed text-white" href="{{ route('citizen-journalism-post', ['post'=>$data->id]) }}"><i class="fa fa-flag-o" aria-hidden="true"></i> Post this journalism</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')