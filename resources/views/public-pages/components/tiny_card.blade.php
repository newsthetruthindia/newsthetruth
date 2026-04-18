<div class="premium-card h-100 p-0 border-0 bg-white">
    <div class="blog-img mb-0" style="aspect-ratio: 16/10; overflow: hidden; border-radius: 8px;">
        <a href="{{ route('public.page', ['x' => $post->slug]) }}">
            @if(!empty($post->thumbnails))
            <img
                src="{{ url($post->thumbnails->url) }}"
                srcset="{{ get_image_srcset($post->thumbnails->id) }}"
                alt="{{ $post->title }}"
                class="w-100 h-100 object-fit-cover transition-all duration-500 hover:scale-110" />
            @else
            <img
                src="{{ asset('public/v1/img/blog/blog_5_2_4.jpg') }}"
                alt="{{ $post->title }}"
                class="w-100 h-100 object-fit-cover" />
            @endif
        </a>
    </div>

    <div class="blog-content p-2">
        <h3 class="fw-bold lh-sm mb-1" style="font-size: 0.85rem;">
            <a class="text-dark text-decoration-none hover-line" href="{{ route('public.page', ['x' => $post->slug]) }}">
                {{ \Illuminate\Support\Str::limit($post->title, 50) }}
            </a>
        </h3>

        <div class="blog-meta mt-auto">
            <div class="text-muted x-small d-flex align-items-center" style="font-size: 0.7rem;">
                {{ \Carbon\Carbon::parse($post->post_publish_time)->format('d M') }}
            </div>
        </div>
    </div>
</div>
