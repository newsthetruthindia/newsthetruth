@include('public-pages.header')

<section class="home-archive untold-story">
	<div class="container-fluid">
		<div class="row">
			<h1>{{ $the_page->title }}</h1>
			@if( $the_page->subtitle )
				<h2>{{ $the_page->title }}</h2>
			@endif
		</div>
		<div class="row bg-white">
			<div class="col-lg-9 news-section">
				<div class="row article">
					{!! $the_page->description !!}
				</div>
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