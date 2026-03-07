@include('public-pages.header')
<section class="home-archive untold-story">
	<div class="container-fluid">
		<div class="row bg-white">
			<div class="mt-0">
				<h1>{{ $the_post->title }}</h1>
				@if( $the_post->subtitle )
					<h2>{{ $the_post->title }}</h2>
				@endif
			</div>
		</div>
	</div>
</div>
<section class="post-single">
	<div class="container-fluid">
		<div class="row bg-white">
			<div class="col-lg-9 news-section">
				@if( !empty( $the_post->audio_clip_url ) )
					<div class="row text-center">
						<hr>
						<audio controls>
                            <source src="{{  $the_post->audio_clip_url  }}" type="audio/mpeg"/>
                        </audio>
						<hr>
					</div>
				@endif
				<div class="row news-info">
					<div class="col-lg-12">
						<i class="fa fa-map-marker"></i>Place : <b>{{ !empty($meta['place']) ? $meta['place'] : 'Kolkata'}}</b>
						&nbsp;&nbsp;&nbsp;
						<i class="fa fa-user"></i> Reported By : {{ !empty($meta['credit']) ? $meta['credit'] : $the_post->user->firstname.' '.$the_post->user->lastname}}
						&nbsp;&nbsp;&nbsp;
						<i class="fa fa-clock-o"></i> {{ $the_post->created_at }}
					</div>
				</div>
				@if( !empty( $the_post->thumbnails ) )
					<div class="row thumbnails">
						<div class="col-lg-12">
							<img src="{{ url($the_post->thumbnails->url) }}" />
						</div>
					</div>
				@endif
				<div class="row article">
					{!! $the_post->description !!}
				</div>
				
				@if( $the_post->video_url )
					<div class="row text-center">
						<iframe style="width:100%; height:480px;" src="{{ $the_post->video_url }}">
						</iframe>
					</div>
				@endif
				@if( !empty( $the_post->gallery ) )
					<div class="row photo-story">
						<div class="col-lg-12 text-center"><h3>Photo Gallery</h3></div>
						<div class="col-lg-12 text-center">
							@foreach($the_post->gallery as $e_t_g_k => $e_t_g_v )
								@php
									$e_t_g_image = getAttachmentById($e_t_g_v->media_id);
								@endphp
								@if( !empty( $e_t_g_image->url ) )
									<img src="{{ url($e_t_g_image->url) }}" alt="" />
								@endif
							@endforeach
						</div>
					</div>
				@endif
				@if( !empty( $settings['enable_social_share'] ) && $settings['enable_social_share'] =='1' )
					<div class="row social-share text-center">
						<div class="col-lg-12"><h3>Share on...</h3></div>
						<div class="col-lg-12">
							<ul>
								@if( !empty( $settings['share_facebook'] ) && $settings['share_facebook'] =='1' && !empty( $the_post->thumbnails->url ) )
									<li>
										<a href="https://www.facebook.com/sharer/sharer.php?u={{ url($the_post->slug) }}&t={{ $the_post->title }}&p[ images ][ 0 ]={{ url($the_post->thumbnails->url) }}" target="_blank" data-toggle="tooltip" title="Facebook">
											<image src="{{ asset('public/img/social/facebook.svg') }}"/>
										</a>
									</li>
								@endif
								@if( !empty( $settings['share_twitter'] ) && $settings['share_twitter'] =='1' )
									<li>
										<a href="https://twitter.com/intent/tweet?text={{ $the_post->title }}&amp;url={{ url($the_post->slug) }}&amp;via=NewsTheTruth" target="_blank" data-toggle="tooltip" title="Twitter">
											<image src="{{ asset('public/img/social/twitter.svg') }}"/>
										</a>
									</li>
								@endif
								@if( !empty( $settings['share_linkdin'] ) && $settings['share_linkdin'] =='1' )
									<li>
										<a href="https://www.linkedin.com/shareArticle?mini= true&url={{ url($the_post->slug) }}&amp;title={{ $the_post->title }}" target="_blank" data-toggle="tooltip" title="Linkedin">
											<image src="{{ asset('public/img/social/linkedin.svg') }}"/>
										</a>
									</li>
								@endif
								@if( !empty( $settings['share_pinterrest'] ) && $settings['share_pinterrest'] =='1' && !empty( $the_post->thumbnails->url ) )
									<li>
										<a href="https://pinterest.com/pin/create/button/?url={{ url($the_post->slug) }}&amp;media={{ url($the_post->thumbnails->url) }}&amp;description={{ $the_post->title }}" target="_blank" data-toggle="tooltip" title="Pinterest">
											<image src="{{ asset('public/img/social/pinterest.svg') }}"/>
										</a>
									</li>
								@endif
								@if( !empty( $settings['share_tumbler'] ) && $settings['share_tumbler'] =='1' )
									<li>
										<a href="https://www.tumblr.com/share/link?url={{ url($the_post->slug) }}&name={{ $the_post->title }}&description={{ $the_post->description }}" target="_blank" data-toggle="tooltip" title="Tumblr">
											<image src="{{ asset('public/img/social/tumblr.svg') }}"/>
										</a>
									</li>
								@endif
								@if( !empty( $settings['share_whatsapp'] ) && $settings['share_whatsapp'] =='1' )
									<li>
										<a href='https://web.whatsapp.com/send?text={{ url($the_post->slug) }} data-action="share/whatsapp/share"' target="_blank" data-toggle="tooltip" title="Whatsapp">
											<image src="{{ asset('public/img/social/whatsapp.svg') }}"/>
										</a>
									</li>	
								@endif
								@if( !empty( $settings['share_telegram'] ) && $settings['share_telegram'] =='1' )
									<li>
										<a href='https://telegram.me/share/url?url={{ url($the_post->slug) }};text={{ $the_post->title }}' target="_blank" data-toggle="tooltip" title="Telegram">
											<image src="{{ asset('public/img/social/telegram.svg') }}"/>
										</a>
									</li>	
								@endif
							</ul>
						</div>
					</div>
				@endif
			</div>
			<div class="col-lg-3 add-section">
				<div class="add">
					@if( !empty( $the_post->metas()->where('key', 'add_url')->first() ))
						<?php
							$post_addUrl_items = $the_post->metas()->where('key', 'add_url')->first()->description;
						?>
						@if( !empty($post_addUrl_items ) )
							<img src="{{ $post_addUrl_items }}" alt="" />
						@else
							@if( !empty( $the_post->categories[0]->cat_data->metas()->where('key', 'add_url')->first() ))
								<?php
									$cat_addurl_items = $the_post->categories[0]->cat_data->metas()->where('key', 'add_url')->first()->description;
								?>
								@if( !empty($cat_addurl_items ) )
									<img src="{{ $cat_addurl_items }}" alt="" />
								@else
									<img src="{{ asset('public/img/product/add.jpg') }}" alt="" />
								@endif
							@else
								<img src="{{ asset('public/img/product/add.jpg') }}" alt="" />
							@endif
						@endif
					@elseif( !empty( $the_post->categories[0]->cat_data->metas()->where('key', 'add_url')->first() ))
						<?php
							$cat_addurl_items = $the_post->categories[0]->cat_data->metas()->where('key', 'add_url')->first()->description;
						?>
						@if( !empty($cat_addurl_items ) )
							<img src="{{ $cat_addurl_items }}" alt="" />
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
				<h3> Top Posts </h3>
			</div>
		</div>
		<div class="row bg-white">
			<div class="col-lg-9 news-section">
				@if( !empty( $top_post ) && count( $top_post ) > 0 )
					@foreach( $top_post as $key => $latest )
						<div class="row article">
							<div class="col-md-1 index"><span>{{ ($key+1) }}.</span></div>
							<div class="col-md-7 title">
								<h4>{{ $latest->title }}</h4>
								{!! $latest->excerpt !!}...
								<span class="read-more">
									<a href="{{ url($latest->slug) }}">
										Read more
									</a>
								</span>
							</div>
							<div class="col-md-4 video">
								@if( $latest->thumbnails)
									<img src="{{ url($latest->thumbnails->url) }}" />
								@else
									<img src="{{ asset('public/img/product/bg-1.jpg') }}" alt="" />
								@endif
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
							<div class="col-md-7 title">
								<h4>{{ $latest->title }}</h4>
								{!! $latest->excerpt !!}...
								<span class="read-more">
									<a href="{{ url($latest->slug) }}">
										Read more
									</a>
								</span>
							</div>
							<div class="col-md-4 video">
								@if( $latest->thumbnails)
									<img src="{{ url($latest->thumbnails->url) }}" />
								@else
									<img src="{{ asset('public/img/product/bg-1.jpg') }}" alt="" />
								@endif
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