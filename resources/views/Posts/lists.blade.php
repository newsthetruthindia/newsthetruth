@include('layouts.header')
@include('layouts.sidebar')
<div class="product-status mg-b-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap">
                    <h4>Article List</h4>
                    <div class="list-filter">
                        <div class="filter-var">
                            <form action="{{ route('posts') }}" method="post">
                                @csrf
                                @method('get')
                                <div class="frm-grp">
                                    <span>Title</span>
                                    <input type="text" name="title" class="form-control" value="{{ !empty($params['title'])?$params['title']:'' }}" />
                                </div>
                                <div class="frm-grp">
                                    <span>Start Date</span>
                                    <input type="date" name="start_date" class="form-control" value="{{ !empty($params['start_date'])?$params['start_date']:'' }}" />
                                </div>
                                <div class="frm-grp">
                                    <span>End Date</span>
                                    <input type="date" name="end_date" class="form-control" value="{{ !empty($params['end_date'])?$params['end_date']:'' }}" />
                                </div>
                                <div class="frm-grp">
                                    <button class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                                    <a class="btn btn-primary" href="{{ route('posts') }}"><i class="fa fa-repeat" aria-hidden="true"></i></a>
                                </div>
                            </form>
                        </div>
                        <div class="add-product">
                            @if( !empty( Auth::user()->details->role ) && Auth::user()->details->role->create_post )
                                <a href="{{ route('add-post') }}">Add New</a>
                            @endif
                        </div>
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
                                    <td><img src="{{ (!empty($post->thumbnails) ? url($post->thumbnails->url) :asset('public/img/new-product/5-small.jpg')) }}" alt="" /></td>
                                    <td>{{ substr($post->title,0,30); }} </td>
                                    <td>{{ $post->updated_at }}</td>
                                    <td>{{ !empty( $post->user ) ? $post->user->firstname .' '. $post->user->lastname : '--archived employee--'}}</td>
                                    <td>
                                        @if( !empty( Auth::user()->details->role ) && Auth::user()->details->role->edit_post )
                                            <a data-toggle="tooltip" title="Edit" class="pd-setting-ed" href="{{ route('post-edit', ['post'=>$post->id,'page_no'=>$posts->currentPage()]) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                        @endif
                                        @if( !empty( Auth::user()->details->role ) && Auth::user()->details->role->update_post_seo )
                                            <a data-toggle="tooltip" title="Set SEO" class="pd-setting-ed" href="{{ route('post-seo', ['post'=>$post->id,'page_no'=>$posts->currentPage()]) }}"><i class="fa fa-area-chart" aria-hidden="true"></i></a>
                                        @endif
                                        @if( !empty( Auth::user()->details->role ) && Auth::user()->details->role->delete_post )
                                            @if( !empty($post->deleted_at ) && Auth::user()->details->role->parmanant_delete )
                                                <a data-toggle="tooltip" title="Delete Permanant" class="pd-setting-ed" href="{{ route('post-delete-permanant', ['post'=>$post->id, 'page_no'=>$posts->currentPage()]) }}"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                            @else
                                                <a data-toggle="tooltip" title="Trash" class="pd-setting-ed" href="{{ route('post-delete', ['post'=>$post->id, 'page_no'=>$posts->currentPage() ]) }}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
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