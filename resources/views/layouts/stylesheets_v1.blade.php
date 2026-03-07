 <link rel="icon" type="image/x-icon" href="{{ asset('public/v1/img/NTT_fav.png') }}">
 <!-- <link rel="manifest" href="{{ asset('public/v1/img/favicons/manifest.json') }}"> -->

 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
 <link href="../../css2?family=League+Spartan:wght@300;400;500;600;700;800;900&family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">
 <link rel="stylesheet" href="{{ asset('public/v1/css/app.min.css') }}">
 <link rel="stylesheet" href="{{ asset('public/v1/css/fontawesome.min.css') }}">
 <link rel="stylesheet" href="{{ asset('public/v1/css/style.css') }}">
 <link rel="stylesheet" href="{{ asset('public/v1/css/custom-style.css') }}?data={{time()}}">
 <script type="text/javascript">
     var csrf_token = "{{ csrf_token() }}";
     var base_url = "{{ url('') }}";
 </script>