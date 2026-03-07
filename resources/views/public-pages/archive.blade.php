@include('public-pages.header')
<section class="home-archive untold-story">
	<div class="container-fluid">
		<div class="row">
			<div class="mt-0">
				<h1>All Categories</h1>
			</div>
		</div>
	</div>
</div>
<section class="home-archive all-archives">
	<div class="container-fluid">
		<div class="row bg-white">
			<div class="col-sm-9 col-12 news-section">
				<div class="row article">
					@if( !empty( $categories )  )
						@foreach( $categories as $the_data)
							@if( !empty( $the_data ) )
								<div class="col-xs-12 col-md-4">
									<a href="{{ url($the_data->slug)}} ">
										{{$the_data->title}}
									</a>
								</div>
							@endif
						@endforeach
					@endif
				</div>
			</div>
			<div class="col-sm-3 col-12 add-section">
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