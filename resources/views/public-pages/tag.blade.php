@include('public-pages.header')

<section class="home-archive untold-story">
	<div class="container-fluid">
		<div class="row">
			<div class="mt-0">
				<h1>{{ $cat_data->title }} News</h1>
				@if( $cat_data->subtitle )
					<h2>{{ $cat_data->title }}</h2>
				@endif
			</div>
		</div>
	</div>
</div>
<section class="home-archive untold-story">
	<div class="container-fluid">
		<div class="row bg-white">
			<div class="col-lg-9 news-section">
				@if( !empty( $posts ) && count( $posts ) > 0 )
					@foreach( $posts as $item)
						@if( !empty( $item ) )
						<div class="row article">
							<div class="col-xs-12 col-md-7 title">
								<a href="{{ url($item->slug) }}">
									<h4>{{ substr($item->title,0,100); }}...</h4>
								</a>
								{{ substr($item->excerpt,0,100); }}...
								<span class="read-more">
									<a href="{{ url($item->slug) }}">
										Read more
									</a>
								</span>
							</div>
							<div class="col-xs-12 col-md-4 video">
							    <a href="{{ url($item->slug) }}">
    								@if( $item->thumbnails)
    									<img src="{{ url($item->thumbnails->url) }}" />
    								@else
    									<img src="{{ asset('public/img/product/bg-1.jpg') }}" alt="" />
    								@endif
								</a>
							</div>
						@endif
					</div>
					@endforeach
					<div class="row">
						<div class="d-flex justify-content-center">
                        	{!! $posts->links() !!}
						</div>
                    </div>
				@endif
			</div>
			<div class="col-lg-3 add-section">
				<div class="add">
					@if( !empty( $the_archive->metas ) && !empty( $the_archive->metas()->where('key', 'add_url')->first() ))
					<?php
						$the_dares_addUrl_items = $the_archive->metas()->where('key', 'add_url')->first()->description;
					?>
						@if( !empty($the_dares_addUrl_items ) )
							<img src="{{ $the_dares_addUrl_items }}" alt="" />
						@else
							<img src="{{ asset('public/img/product/add.jpg') }}" alt="" />
						@endif
					@else
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
					@endif
				</div>
			</div>
		</div>
	</div>
</section>
<section class="home-archive">
	<div class="container-fluid">
		<div class="row heading">
			<div class="col-md-12">
				<h3> Other Tags </h3>
			</div>
		</div>
		<div class="row bg-white">
			<div class="col-lg-12">
				<div class="all-catagories bg-grey">
					<ul>
						<ul class="tags">
                            @foreach( $tags as $tag )
                                @if( !empty( $tag ) )
                                    <li><a class="label tag" href="{{ url( $tag->slug ) }}">{{ $tag->title }} </a></li>
                                @endif
                            @endforeach
                        </ul>
					</ul>
				</div>			
			</div>
		</div>
	</div>
</section>
<section class="home-archive">
	<div class="container-fluid">
		<div class="row heading">
			<div class="col-md-12">
				<h3> LATEST NEWS </h3>
			</div>
		</div>
		<div class="row bg-white">
			<div class="col-lg-9 news-section">
				@if( !empty( $the_latest ) && count( $the_latest ) > 0 )
					@foreach( $the_latest as $key => $latest )
						<div class="row article">
							<div class="col-md-1 index"><span>{{ ($key+1) }}.</span></div>
							<div class="col-xs-12 col-md-7 title">
								{!! $latest->excerpt !!}...
								<span class="read-more">
									<a href="{{ url($latest->slug) }}">
										Read more
									</a>
								</span>
							</div>
							<div class="col-xs-12 col-md-4 video">
							    <a href="{{ url($latest->slug) }}">
    								@if( $latest->thumbnails)
    									<img src="{{ url($latest->thumbnails->url) }}" />
    								@else
    									<img src="{{ asset('public/img/product/bg-1.jpg') }}" alt="" />
    								@endif
								</a>
							</div>
						</div>
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
@include('public-pages.footer')