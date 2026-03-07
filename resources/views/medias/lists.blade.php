@include('layouts.header')
@include('layouts.sidebar')
<div class="product-status mg-b-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap media-list">
                    <h4>Medias</h4>
                    
                    <div class="row">
                        <form action="{{ route('save-media') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('post')
                            <input type="file" name="files[]" multiple>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <span><button id="regenerate_img" class="btn btn-primary">Regenerate All Images</button></span>
                            <span class="total-regenerated">Regenerated {{$offset}} images</span>
                            <span><a href="{{route('media-generation-reset')}}" class="btn btn-primary">Reset Value</a></span>
                        </div>
                    </div> 
                    <div class="row">
                        @if( !empty( $medias ) )
                            @foreach( $medias as $media )
                                <div class="col-md-2">
                                    <?php
                                        $img_control = new \App\Http\Controllers\MediaController();
                                    ?>
                                    @if( in_array(  $media->mimetype, $img_control->getImageTypes() ) )
                                        @if( strpos( $media->url, 'public') > -1)
                                            <img class="media_handle" src="{{ url($media->url) }}" data-id="{{ $media->id }}" style="width:100%;" />
                                        @else
                                            <img class="media_handle" src="{{ url('public'.$media->url) }}" data-id="{{ url($media->id) }}" style="width:100%;" />
                                        @endif
                                    @elseif(in_array(  $media->mimetype, $img_control->getVideoTypes()))
                                        <video width="200" height="100" controls>
                                          <source src="{{ url($media->url) }}" type="{{ $media->mimetype }}">
                                        Your browser does not support the video tag.
                                        </video>

                                    @elseif(in_array(  $media->mimetype, $img_control->getAudioTypes()))
                                        <audio controls>
                                            <source src="{{ url($media->url) }}" type="audio/mpeg"/>
                                        </audio>
                                    @endif
                                    <a class="" href="{{ route('media-delete', ['media'=> $media->id, 'page_no'=>$medias->currentPage() ] ) }}"><i class="fa fa-trash-o"></i></a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="row">
                        <div class="d-flex justify-content-center text-center">
                            {!! $medias->links() !!}
                        </div>
                    </div>                  
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')
<script>
    $(document).ready(function(){
        $('#regenerate_img').on('click', function(e){
           e.preventDefault();
            $.ajax({
                type:'POST',
                url:base_url+'/media/regenerate/',
                processData: false,
                contentType: false,
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend:function(){
                  $('#loader').show();
                },
                success:function(_data){
                  $('#loader').hide();
                  if(_data){
                        if( !_data.error ){
                            $('.total-regenerated').text(_data.message);
                        }
                        alert(_data.message);
                  }
                },
            });
        });
        $(document).on('click', '#regen_thumb', function(e){
            e.preventDefault();
            let id = $(this).data('id');
            let form_data = new FormData();
            form_data.append('id', id);
            $.ajax({
                type:'POST',
                url:base_url+'/media/regenerate/',
                processData: false,
                contentType: false,
                data:form_data,
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend:function(){
                  $('#loader').show();
                },
                success:function(_data){
                  $('#loader').hide();
                },
            });
        });
        $(document).on('click', '.media_handle', function(e){
        	e.preventDefault();
        	let id = $(this).data('id');
        	
        	$('#popupmodal').modal();
        	$.ajax({
              type:'GET',
              url:base_url+'/media/edit/'+id,
              processData: false,
              contentType: false,
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              beforeSend:function(){
                  $('#loader').show();
              },
              success:function(_data){
                  $('#loader').hide();
                  if(_data){
                  	media = _data;
                  	let html = '<div class="row mideas">';
                  	html +='<img style="height:50vh; width:150vh;" src="'+base_url+'/'+media['url']+'">';
                  	html +='</div>';
                  	html +='<div class="row">';
                  	html +='<div class="col-md-6">';
                  	html +='<span class="text-secondary">Alt text</span><input type="text" class="form-control" name="alt" value="'+media['alt']+'">';
                  	html +='</div>';
                  	html +='<div class="col-md-6">';
                  	html +='<span class="text-secondary">Description </span><input type="text" class="form-control" name="description" value="'+media['description']+'">';
                  	html +='</div>';
                  	html +='<div class="col-md-6">';
                  	html +='<span id="regen_thumb" class="text-primary" data-id="'+media['id']+'">Regenerate Thumbnail</span>';
                  	html +='</div>';
                  	html +='</div>';
                  	$('#popupmodal').find('.modal-body').html(html);
                }
    
              },
          });
        });
    })
</script>