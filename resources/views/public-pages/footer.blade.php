<footer class="home-archive">
    <div class="container-fluid">
        <div class="row bg-white">            
            <div class="col-lg-12 text-center">
                @if( !empty( \App\Http\Controllers\SiteSettingsController::check('site_logo') ) )
                    @php
                        $logo = getAttachmentById(\App\Http\Controllers\SiteSettingsController::check('site_logo')->description);
                    @endphp
                    @if( !empty( $logo->url ) )
                        <a href="/"><img class="main-logo" src="{{ url($logo->url) }}" alt="" style="width: 300px; height: auto;"/></a>
                    @endif
                @else
                    <img class="main-logo" src="{{ asset('public/img/logo/logo.jpg') }}" alt="" style="width: 200px; height: auto;"/>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-4  text-left">
                <h5>FROM BENGAL TO THE WORLD</h5>
            </div>
            <div class="col-md-4">
                <div class="contact text-center">
                    <p>Call - 6290 457 301</p>
                </div>
                @if( !empty( $settings['enable_social_follow'] ) && $settings['enable_social_follow'] =='1' )
                    <div class="social-share text-center">
                        <h3>Follow us</h3>
                        <ul>
                            @if( !empty( $settings['follow_facebook'] ) )
                                <li>
                                    <a href="{{ $settings['follow_facebook'] }}" target="_blank" data-toggle="tooltip" title="Facebook">
                                        <image src="{{ asset('public/img/social/facebook-alt.svg') }}"/>
                                    </a>
                                </li>
                            @endif
                            @if( !empty( $settings['follow_linkdin'] ) )
                                <li>
                                    <a href="{{ $settings['follow_linkdin'] }}" target="_blank" data-toggle="tooltip" title="Linkedin">
                                        <image src="{{ asset('public/img/social/linkedin-alt.svg') }}"/>
                                    </a>
                                </li>
                            @endif
                            @if( !empty( $settings['follow_twitter'] ) )
                                <li>
                                    <a href="{{ $settings['follow_twitter'] }}" target="_blank" data-toggle="tooltip" title="Twitter">
                                        <image src="{{ asset('public/img/social/twitter-alt.svg') }}"/>
                                    </a>
                                </li>
                            @endif
                            @if( !empty( $settings['follow_instagram'] ) )
                                <li>
                                    <a href="{{ $settings['follow_instagram'] }}" target="_blank" data-toggle="tooltip" title="Instagram">
                                        <image src="{{ asset('public/img/social/instagram-alt.svg') }}"/>
                                    </a>
                                </li>
                            @endif
                            @if( !empty( $settings['follow_youtube'] ) )
                                <li>
                                    <a href="{{ $settings['follow_youtube'] }}" target="_blank" data-toggle="tooltip" title="Youtube">
                                        <image src="{{ asset('public/img/social/youtube-alt.svg') }}"/>
                                    </a>
                                </li>
                            @endif
                            @if( !empty( $settings['follow_telegram'] ) )
                                <li>
                                    <a href="{{ $settings['follow_telegram'] }}" target="_blank" data-toggle="tooltip" title="Telegram">
                                        <image src="{{ asset('public/img/social/telegram-alt.svg') }}"/>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>
            <div class="col-md-4">
                <div class="contact text-right footer-menu">
                    @if( !empty( \App\Http\Controllers\SiteSettingsController::check('footer_menu') ) )
                        @php
                            $fmenu_id = \App\Http\Controllers\SiteSettingsController::check('footer_menu');
                            $fmenu = App\Models\Menu::where('id', $fmenu_id->description )->first();
                        @endphp
                        @if( !empty( $fmenu->items ) )
                            @foreach( $fmenu->items as $k =>$v )
                            <p><a href="{{ $v->slug }}" class="nav-link">{{ $v->display_name }}</a></p>
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</footer>
        
    <script src="{{ asset('public/js/vendor/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('public/js/datapicker/bootstrap-datepicker.js') }}"></script>
    <!-- bootstrap JS
        ============================================ -->
    <script src="{{ asset('public/js/bootstrap.min.js') }}"></script>
    <!-- wow JS
        ============================================ -->
    <script src="{{ asset('public/js/bootstrap-toggle.min.js') }}"></script>
    
    <!-- meanmenu JS
        ============================================ -->
    <script src="{{ asset('public/js/jquery.meanmenu.js') }}"></script>
    
    <!-- sticky JS
        ============================================ -->
    <script src="{{ asset('public/js/jquery.sticky.js') }}"></script>
    <script src="{{ asset('public/js/Owl/dist/owl.carousel.min.js') }}"></script>
    <script type="text/javascript">
    $(function() {

  var siteSticky = function() {
        $(".js-sticky-header").sticky({topSpacing:0});
    };
    siteSticky();
    var siteMenuClone = function() {
        setTimeout(function() { 
        var counter = 0;
      $('.site-mobile-menu .has-children').each(function(){
        var $this = $(this);
        $this.prepend('<span class="arrow-collapse collapsed">');
        $this.find('.arrow-collapse').attr({
          'data-toggle' : 'collapse',
          'data-target' : '#collapseItem' + counter,
        });
        $this.find('> ul').attr({
          'class' : 'collapse',
          'id' : 'collapseItem' + counter,
        });
        counter++;
      });
    }, 1000);
    $('body').on('click', '.arrow-collapse', function(e) {
      var $this = $(this);
      if ( $this.closest('li').find('.collapse').hasClass('show') ) {
        $this.removeClass('active');
      } else {
        $this.addClass('active');
      }
      e.preventDefault();      
    });
    $(window).resize(function() {
        var $this = $(this),
            w = $this.width();

        if ( w > 768 ) {
            if ( $('body').hasClass('offcanvas-menu') ) {
                $('body').removeClass('offcanvas-menu');
            }
        }
    })
    $('body').on('click', '.js-menu-toggle', function(e) {
        var $this = $(this);
        e.preventDefault();

        if ( $('body').hasClass('offcanvas-menu') ) {
            $('body').removeClass('offcanvas-menu');
            $this.removeClass('active');
        } else {
            $('body').addClass('offcanvas-menu');
            $this.addClass('active');
        }
    }) 

        // click outisde offcanvas
        $(document).mouseup(function(e) {
        var container = $(".site-mobile-menu");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
          if ( $('body').hasClass('offcanvas-menu') ) {
                    $('body').removeClass('offcanvas-menu');
                }
        }
        });
    }; 
    siteMenuClone();
    $('#datepicker').datepicker({
    format: 'mm/dd/yyyy',
}).change(dateChanged).on('changeDate', dateChanged);
function dateChanged(ev) {
    $(this).datepicker('hide');
    console.log($('#startdate').val())
}
   $('#carosoul').owlCarousel({
        items:1,
        loop:true,
        nav:false,
        dots:true,
        navText:["&#x27;next&#x27;","&#x27;prev&#x27;"],
        autoPlay:true,
        autoplaySpeed:1000,
        autoplayTimeout: 5000,
        autoplayHoverPause:true,
    });
    
     $('#just_in').owlCarousel({
        items:1,
        loop:true,
        nav:false,
        dots:false,
        navText:["&#x27;next&#x27;","&#x27;prev&#x27;"],
        autoPlay:true,
        autoplaySpeed:1000,
        autoplayTimeout: 5000,
        autoplayHoverPause:true,
    });

    $('#just_in_items').owlCarousel({
        items:5,
        loop:true,
        nav:true,
        dots:false,
        navText:["&#x27;next&#x27;","&#x27;prev&#x27;"],
        autoPlay:true,
        autoplaySpeed:1000,
        autoplayTimeout: 5000,
        autoplayHoverPause:true,
    });
    /*$('#second-cor').owlCarousel({
        items:5,
        loop:true,
        nav:true,
        dots:true,
        navText:["&#x27;next&#x27;","&#x27;prev&#x27;"],
    });*/

    $('.owl-carousel .owl-item').on('mouseenter',function(e){
        $(this).closest('.owl-carousel').trigger('stop.owl.autoplay');
    });
    $('.owl-carousel .owl-item').on('mouseleave',function(e){
        $(this).closest('.owl-carousel').trigger('play.owl.autoplay',[500]);
    });
    if ( $(window).width() > 767 ) {
        if(localStorage.getItem('popState') !== 'shown'){
            $('#popup').show();
        }
        $('#popup-close').click(function(){
            localStorage.setItem('popState','shown')
            $('#popup').hide();
        });
        $(window).scroll(function(){
            var sticky = $('.site-head'),
              scroll = $(window).scrollTop();
            
            if (scroll >= 100) sticky.addClass('fixed');
            else sticky.removeClass('fixed');
        });
    }else{
        if(localStorage.getItem('mpopState') !== 'shown'){
            $('#mobile-popup').show();
        }
        $('#mpopup-close').click(function(){
            localStorage.setItem('mpopState','shown')
            $('#mobile-popup').hide();
        });
        $(window).scroll(function(){
            var sticky = $('.site-head'),
              scroll = $(window).scrollTop();
            
            if (scroll >= 30) sticky.addClass('fixed');
            else sticky.removeClass('fixed');
        });
    }
   
    if ( ($(window).width() < 1100) && ($(window).height() > $(window).width()) ) {
        $(document).find('.support-button').find('span, button').each((i, e)=>{
            let html = $(e).html();
            $('.site-mobile-menu .site-nav-wrap').append('<li>'+html+'</li>')
        });
        $('.support-button').remove();
    }
    
});
</script>
</body>

</html>