@include('public-pages.header')

<section class="home-archive untold-story">
	<div class="container-fluid">
		<div class="row bg-white">
			<div class="col-md-12">
				<h1>Send mail to user</h1>
			</div>
		</div>
		@if ($errors->any())
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                </div>
            </div>
        @endif
		<div class="row bg-white">
			<div class="col-lg-12 news-section">
				<div class="row article">
					<form action="{{ route('mail-control') }}" enctype="multipart/form-data">
					    @csrf
                        @method('post')
                        <div class="col-12">
                            <input type="text" name='to' class="form-control" />
                        </div>
                        <div class="col-12">
                            <input type="text" name='description' class="form-control" />
                        </div>
                        <div class="col-12">
                            <button class="btn btn-success">Send</button>
                        </div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
@include('public-pages.footer')