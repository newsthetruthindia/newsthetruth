<ul class="sub-menu">
	@if( !empty( $v->subitems ) )
		@foreach($v->subitems as $kk => $vv)
			<li>
		        <input type="hidden"  class="item_id" name="item[{{$kk}}][item_id]" value="{{ $vv->id }}">
		        <input type="hidden"  class="item_type" name="item[{{$kk}}][item_type]" value="{{ $vv->type }}">
		        <input type="hidden"  class="item_name" name="item[{{$kk}}][item_name]" value="{{ $vv->display_name }}">
		        <input type="hidden"  class="item_link" name="item[{{$kk}}][item_link]" value="{{ $vv->slug }}">
		        <span>{{ $vv->display_name }}</span>
		        <span class="edit-item"><i class="fa fa-edit"></i></span><span class="delete-item"><i class="fa fa-trash"></i></span>
		        @include('Menues.menu_item', ['v' => $vv])
		    </li>
		@endforeach
	@endif
</ul>
<button class="btn btn-primary add_submenu">Add Sub Menu</button>