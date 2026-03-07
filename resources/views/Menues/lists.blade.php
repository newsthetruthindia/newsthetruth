@include('layouts.header')
@include('layouts.sidebar')
<div class="product-status mg-b-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap">
                    <h4>Menu List</h4>
                    <div class="add-product">
                        <a href="{{ route('add-menu') }}">Add New</a>
                    </div>
                    <table>
                        <tr>
                            <th>Title</th>
                            <th>Modified</th>
                            <th>Setting</th>
                        </tr>
                        @if( !empty( $menues ) )
                            @foreach( $menues as $menu )
                                <tr>
                                    <td><img src="{{ asset('public/img/new-product/5-small.jpg') }}" alt="" /></td>
                                    <td>{{ $menu->name }} </td>
                                    <td>{{ $menu->updated_at }}</td>
                                    <td>
                                        <a data-toggle="tooltip" title="Edit" class="pd-setting-ed" href="{{ route('menu-edit', ['menu'=>$menu->id]) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                        <a data-toggle="tooltip" title="Trash" class="pd-setting-ed" href="{{ route('menu-delete', ['menu'=>$menu->id]) }}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                    <div class="d-flex justify-content-center">
                        {!! $menues->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')