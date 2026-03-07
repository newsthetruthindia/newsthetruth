<link rel="icon" type="image/x-icon" href="{{ asset('public/v1/img/NTT_fav.png') }}">
<!-- <link rel="manifest" href="{{ asset('public/v1/img/favicons/manifest.json') }}"> -->

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
<link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">
<link rel="stylesheet" href="{{ asset('public/v1/css/app.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/v1/css/fontawesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/v1/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('public/v1/css/design-system.css') }}">
<link rel="stylesheet" href="{{ asset('public/v1/css/custom-style.css') }}?data={{time()}}">
<script type="text/javascript">
    var csrf_token = "{{ csrf_token() }}";
    var base_url = "{{ url('') }}";
</script>