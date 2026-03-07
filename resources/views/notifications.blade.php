@include('layouts.header')
@include('layouts.sidebar')
<div class="product-status mg-b-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap">
                    <h4>Notifications</h4>
                    <table>
                        <tr>
                            <th>Index</th>
                            <th>Id</th>
                            <th>Description</th>
                            <th>Setting</th>
                        </tr>
                        @if( !empty( $notifications ) )
                            <?php $i=0; ?>
                            @foreach( $notifications as $notification )
                                <?php $i++; ?>
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $notification->id }}</td>
                                    <td>
                                        {{ $notification->description }}
                                    </td>
                                    <td>
                                        @if( !$notification->is_read )
                                            <a data-toggle="tooltip" title="Edit" class="pd-setting-ed" href="{{ route('notifications-update', ['notification'=>$notification->id]) }}"><i class="fa fa-paper" aria-hidden="true"></i> Mark as read </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                    <div class="d-flex justify-content-center">
                        {!! $notifications->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')