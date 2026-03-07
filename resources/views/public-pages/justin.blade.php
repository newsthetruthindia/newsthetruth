@include('public-pages.header')
<section class="home-archive untold-story">
	<div class="container-fluid">
		<div class="row">
			<div class="mt-0">
				<h1>All Posts</h1>
			</div>
		</div>
	</div>
</div>
<section class="home-archive untold-story">
	<div class="container-fluid">
		<div class="row bg-white">
			<div class="col-lg-9 news-section just-in">
				@if( !empty( $data['just_in'] )  )
					@foreach( $data['just_in'] as $the_data)
						@if( !empty( $the_data ) )
							<div class="row text-center">
								<div class="col-xs-12">
								    <div class="text-danger">
    								    <?php date_default_timezone_set('Asia/Kolkata'); ?>
    									<i class="fa fa-clock-o"></i> {{ date('d-m-Y h:i:s a', strtotime($the_data->updated_at.'+330 minutes') ) }}
    								</div>
    								<br>
									@if( $the_data->thumbnails)
										<img src="{{ url($the_data->thumbnails->url) }}" />
									@else
										<img width="450" height="260" src="{{ asset('public/img/product/bg-1.jpg') }}" alt="" />
									@endif
								</div>
								<div class="col-xs-12">
									<h3>{{ $the_data->title }}</h3>
                					{!! $the_data->description !!}
								</div>
							</div>
						@endif
					@endforeach
				@endif
			</div>
			<div class="col-lg-3 add-section">
				<div class="add">
					@if( !empty( \App\Http\Controllers\SiteSettingsController::check('global_add') ) )
                        @php
                            $logo = getAttachmentById(\App\Http\Controllers\SiteSettingsController::check('global_add')->description);
                        @endphp
                        @if( !empty( $logo->url ) )
                            <img class="main-logo" src="{{ url($logo->url) }}" alt="" style="width: 100%;"/>
                        @else
                        	<img src="{{ asset('public/img/product/add.jpg') }}" alt="" />
                        @endif
                    @else
                        <img src="{{ asset('public/img/product/add.jpg') }}" alt="" />
                    @endif
				</div>
			</div>
		</div>
	</div>
</section>
<section class="home-archive">
	<div class="container-fluid just-in">
		<div class="row heading">
			<div class="col-md-12">
				<h2><img src="{{ asset('public/img/ezgif.com-optimize.gif') }}" /></h2>
			</div>
		</div>
		<div class="row bg-white">
			<div class="col-lg-9 news-section">
				@foreach( $data['just_ins'] as $the_data)
					@if( !empty( $the_data ) )
						<div class="row article">
							<div class="col-md-4 video">
								@if( $the_data->thumbnails)
									<img src="{{ url($the_data->thumbnails->url) }}" />
								@else
									<img src="{{ asset('public/img/product/bg-1.jpg') }}" alt="" />
								@endif
							</div>
							<div class="col-md-7 title">
							    <a href="{{ route('justin', ['id'=>$the_data->id]) }}">
    								{!! $the_data->excerpt !!}...
    								<span class="read-more text-danger">
    								    <?php date_default_timezone_set('Asia/Kolkata'); ?>
    									Updated on {{ date('d-m-Y h:i:s a', strtotime($the_data->updated_at.'+330 minutes') ) }}
    								</span>
								</a>
							</div>
						</div>
					@endif
				@endforeach
			</div>
			<div class="col-lg-3 add-section">
				<div class="add">
					@if( !empty( \App\Http\Controllers\SiteSettingsController::check('global_add') ) )
	                    @php
	                        $logo = getAttachmentById(\App\Http\Controllers\SiteSettingsController::check('global_add')->description);
	                    @endphp
	                    @if( !empty( $logo->url ) )
	                        <img class="main-logo" src="{{ url($logo->url) }}" alt="" style="width: 100%;"/>
	                    @else
	                    	<img src="{{ asset('public/img/product/add.jpg') }}" alt="" />
	                    @endif
	                @else
	                    <img src="{{ asset('public/img/product/add.jpg') }}" alt="" />
	                @endif
				</div>
			</div>
		</div>
	</div>
</section>
@include('public-pages.footer')