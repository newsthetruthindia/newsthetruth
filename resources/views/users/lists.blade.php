@include('layouts.header')
@include('layouts.sidebar')
<div class="product-status mg-b-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap">
                    <h4>User List</h4>
                    <div class="add-product">
                        @if( !empty( Auth::user()->details->role ) && Auth::user()->details->role->create_user )
                            <a href="{{ route('user-add') }}">Add User</a>
                        @endif
                    </div>
                    <table>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th>Setting</th>
                        </tr>
                        @if( !empty( $users ) )
                            @foreach( $users as $user )
                                <tr>
                                    <td>
                                        @if( !empty( $user->thumbnails ) )
                                            <img src="{{ url('public/'.$user->thumbnails->url) }}" alt="" />
                                        @else
                                            <img src="{{ asset('public/img/new-product/5-small.jpg') }}" alt="" />
                                        @endif
                                    </td>
                                    <td>{{ $user->firstname }} {{ $user->lastname }}</td>
                                    <td>
                                        @if( !empty($user->deleted_at ) )
                                            <button class="btn btn-default">Delete Request</button><br>{{$user->deleted_at}}
                                        @else
                                            @if( $user->email_verified )
                                                <button class="pd-setting">Active</button>
                                            @else
                                                <button class="ds-setting">Inactive</button>
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ strToUpper($user->type) }}</td>
                                    <td>
                                        @if( !empty( Auth::user()->details->role ) && Auth::user()->details->role->delete_user )
                                            @if( !empty($user->deleted_at ) && Auth::user()->details->role->parmanant_delete )
                                                <a data-toggle="tooltip" title="Delete Permanant" class="pd-setting-ed" href="{{ route('user-delete-permanant', ['id'=>$user->id]) }}"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                            @else
                                                <a data-toggle="tooltip" title="Trash" class="pd-setting-ed" href="{{ route('user-delete', ['id'=>$user->id]) }}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                            @endif
                                        @endif
                                        <a data-toggle="tooltip" title="Send Verification Mail" class="pd-setting-ed" href="{{ route('user-verification', ['id'=>$user->id]) }}"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>

                                        @if( !empty( Auth::user()->details->role ) && Auth::user()->details->role->edit_user )
                                            <a data-toggle="tooltip" title="Edit" class="pd-setting-ed" href="{{ route('user-edit', ['id'=>$user->id]) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                        @endif
                                        
                                        @if( ( Auth::user()->type == 'admin' || Auth::user()->type == 'superadmin' ) && $user->type != 'user' )
                                            <a data-toggle="tooltip" title="Send OAuth" class="pd-setting-ed" href="{{ route('user-send-gauth', ['id'=>$user->id]) }}"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                    <!-- <div class="custom-pagination">
                        <ul class="pagination">
                            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </div> -->
                    <div class="d-flex justify-content-center">
                        {!! $users->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')