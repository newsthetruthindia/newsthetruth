@include('layouts.header')
@include('layouts.sidebar')
<div class="product-status mg-b-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap">
                    <h4>Tag List</h4>
                    <div class="add-product">
                        <a href="{{ route('add-tag') }}">Add tag</a>
                    </div>
                    <table>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Modified</th>
                            <th>Posts</th>
                            <th>Setting</th>
                        </tr>
                        @if( !empty( $tags ) )
                            @foreach( $tags as $tag )
                                <tr>
                                    <td><img src="{{ asset('public/img/new-product/5-small.jpg') }}" alt="" /></td>
                                    <td>{{ $tag->title }} </td>
                                    <td>{{ $tag->modified_at }}</td>
                                    <td>{{ $tag->user_id }}</td>
                                    <td>
                                        <a data-toggle="tooltip" title="Edit" class="pd-setting-ed" href="{{ route('tag-edit', ['tag'=>$tag->id]) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                        <a data-toggle="tooltip" title="Trash" class="pd-setting-ed" href="{{ route('tag-delete', ['tag'=>$tag->id]) }}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                        
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                    <div class="d-flex justify-content-center">
                        {!! $tags->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')