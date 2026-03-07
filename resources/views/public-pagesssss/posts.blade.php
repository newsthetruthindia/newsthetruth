@include('public-pages.header')
<section class="home-archive untold-story">
	<div class="container-fluid">
		<div class="row bg-white">
			<div class="mt-0">
				<h1>All Posts</h1>
			</div>
		</div>
	</div>
</div>
<section class="home-archive untold-story">
	<div class="container-fluid">
		<div class="row bg-white">
			<div class="col-lg-9 news-section">
				@if( !empty( $posts )  )
					@foreach( $posts as $the_data)
						@if( !empty( $the_data ) )
							<div class="row article">
								<div class="col-xs-12 col-md-4 video">
									@if( $the_data->thumbnails)
										<img src="{{ url($the_data->thumbnails->url) }}" />
									@else
										<img width="450" height="260" src="{{ asset('public/img/product/bg-1.jpg') }}" alt="" />
									@endif
								</div>
								<div class="col-xs-12 col-md-7 title">
									<h4><a href="{{ url($the_data->slug) }}">{{ $the_data->title }}</a></h4>
									{!! $the_data->excerpt !!}...
									<span class="read-more">
										<a href="{{ url($the_data->slug) }}">
											Read more
										</a>
									</span>
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
		<div class="row">
		    <div class="d-flex justify-content-center">
                {!! $posts->links() !!}
            </div>
		</div>
	</div>
</section>
@include('public-pages.footer')