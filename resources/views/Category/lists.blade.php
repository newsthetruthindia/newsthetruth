@include('layouts.header')
@include('layouts.sidebar')
<div class="product-status mg-b-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap">
                    <h4>Category List</h4>
                    <div class="add-product">
                        <a href="{{ route('add-category') }}">Add Category</a>
                    </div>
                    <table>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Parent</th>
                            <th>Modified</th>
                            <th>Posts</th>
                            <th>Setting</th>
                        </tr>
                        @if( !empty( $categories ) )
                            @foreach( $categories as $category )
                                <tr>
                                    <td><img src="{{ asset('public/img/new-product/5-small.jpg') }}" alt="" /></td>
                                    <td>{{ $category->title }} </td>
                                    <td>
                                        {{ $category->category_id }}
                                    </td>
                                    <td>{{ $category->modified_at }}</td>
                                    <td>{{ $category->user_id }}</td>
                                    <td>
                                        <a data-toggle="tooltip" title="Edit" class="pd-setting-ed" href="{{ route('category-edit', ['cat'=>$category->id]) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                        <a data-toggle="tooltip" title="Trash" class="pd-setting-ed" href="{{ route('category-delete', ['cat'=>$category->id]) }}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                        
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                    <div class="d-flex justify-content-center">
                        {!! $categories->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')