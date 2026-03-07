@include('layouts.header')
@include('layouts.sidebar')
<div class="product-status mg-b-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap">
                    <h4>Page List</h4>
                    <div class="add-product">
                        <a href="{{ route('add-page') }}">Add New</a>
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
                                    <td>{{ $post->title }} </td>
                                    <td>{{ $post->updated_at }}</td>
                                    <td>{{ $post->user->firstname }} {{ $post->user->firstname }}</td>
                                    <td>
                                        <a data-toggle="tooltip" title="Edit" class="pd-setting-ed" href="{{ route('page-edit', ['post'=>$post->id]) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                        <a data-toggle="tooltip" title="Trash" class="pd-setting-ed" href="{{ route('page-delete', ['post'=>$post->id]) }}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                        
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