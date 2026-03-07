@include('layouts.header')
@include('layouts.sidebar')
<div class="product-status mg-b-30">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap">
                    <h4 class="text-white">Journalism List</h4>
                    
                    <table class="table text-white">
                        <tr>
                            <th>Title</th>
                            <th>Modified</th>
                            <th>Created By</th>
                            <th>Posted</th>
                            <th>Accepted</th>
                            <th>Setting</th>
                        </tr>
                        @if( !empty( $posts ) )
                            @foreach( $posts as $post )
                                <tr>
                                    <td>{{ $post->title }} </td>
                                    <td>{{ $post->updated_at }}</td>
                                    <td>{{ $post->user->firstname }} {{ $post->user->firstname }}</td>
                                    <td>{{ $post->posted?'Yes':'NO' }}</td>
                                    <td>{{ !empty($post->accept_by)?'Yes':'No' }}</td>
                                    <td>
                                        <a data-toggle="tooltip" title="View" class="pd-setting-ed text-white" href="{{ route('citizen-journalism-view', ['post'=>$post->id]) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                        <a data-toggle="tooltip" title="Ignore" class="pd-setting-ed text-white" href="{{ route('citizen-journalism-ignore', ['post'=>$post->id]) }}"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                        <a data-toggle="tooltip" title="Make Post" class="pd-setting-ed text-white" href="{{ route('citizen-journalism-post', ['post'=>$post->id]) }}"><i class="fa fa-flag-o" aria-hidden="true"></i></a>
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