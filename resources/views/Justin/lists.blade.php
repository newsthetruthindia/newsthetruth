@include('layouts.header')
@include('layouts.sidebar')
<div class="product-status mg-b-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap">
                    <h4>Just in articles</h4>
                    <div class="add-product">
                        @if( !empty( Auth::user()->details->role ) && Auth::user()->details->role->create_post )
                            <a href="{{ route('add-justin') }}">Add New</a>
                        @endif
                    </div>
                    <table>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Modified</th>
                            <th>Author</th>
                            <th>Setting</th>
                        </tr>
                        @if( !empty( $posts ) )
                            @foreach( $posts as $post )
                                <tr>
                                    <td><img src="{{ asset('public/img/new-product/5-small.jpg') }}" alt="" /></td>
                                    <td>{!! substr($post->description,0,100); !!} </td>
                                    <td>{{ $post->updated_at }}</td>
                                    <td>{{ !empty($post->user->firstname)?$post->user->firstname:'' }} {{ !empty($post->user->lastname)?$post->user->lastname:'' }}</td>
                                    <td>
                                        @if( !empty( Auth::user()->details->role ) && Auth::user()->details->role->edit_post )
                                            <a data-toggle="tooltip" title="Edit" class="pd-setting-ed" href="{{ route('justin-edit', ['post'=>$post->id]) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                        @endif
                                        @if( !empty( Auth::user()->details->role ) && Auth::user()->details->role->delete_post )
                                             @if( !empty($post->deleted_at ) && Auth::user()->details->role->parmanant_delete )
                                                <a data-toggle="tooltip" title="Delete Permanant" class="pd-setting-ed" href="{{ route('justin-delete-permanant', ['post'=>$post->id]) }}"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                            @else
                                                <a data-toggle="tooltip" title="Trash" class="pd-setting-ed" href="{{ route('justin-delete', ['post'=>$post->id]) }}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                    <div class="d-flex justify-content-center">
                        {!! $posts->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')