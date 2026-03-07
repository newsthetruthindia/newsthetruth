@include('layouts.header')
@include('layouts.sidebar')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="product-status mg-b-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline12-list">
                    <div class="sparkline12-hd">
                        <div class="main-sparkline12-hd">
                            <h1>Add New Menu</h1>
                        </div>
                    </div>

<div class="sparkline12-graph">
<div class="basic-login-form-ad">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="all-form-element-inner">
                <form action="{{ route('save-menu') }}" method="post">
                    @csrf
                    @method('post')
                    <input type="hidden" name="menu_id" required class="form-control" value="{{ !empty($menu->id) ? $menu->id :'' }}" />
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group-inner">
                                @if(!empty($menu->id))
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                            <label class="login2 pull-right pull-right-pro">Shortcode</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                            <a class="bg-primary" href="" target="_blank"></a>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="login2 pull-right pull-right-pro">Title</label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="chosen-select-single mg-b-20">
                                            <input type="text" class="form-control" name="title" value="{{ !empty($menu->name) ? $menu->name : ''}}">
                                            @error('title')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label class="login2 pull-right pull-right-pro">Items</label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="chosen-select-single mg-b-20">
                                            <div class="menu-items">
                                                <ul id="menu_items">
                                                    @if( !empty( $menu->items ) )
                                                        @foreach( $menu->items as $k=>$v )
                                                            <li>
                                                                <input type="hidden"  class="item_id" name="item[{{$k}}][item_id]" value="{{ $v->id }}">
                                                                <input type="hidden"  class="item_type" name="item[{{$k}}][item_type]" value="{{ $v->type }}">
                                                                <input type="hidden"  class="item_name" name="item[{{$k}}][item_name]" value="{{ $v->display_name }}">
                                                                <input type="hidden"  class="item_link" name="item[{{$k}}][item_link]" value="{{ $v->slug }}">
                                                                <span>{{ $v->display_name }}</span>
                                                                <span class="edit-item"><i class="fa fa-edit"></i></span><span class="delete-item"><i class="fa fa-trash"></i></span>
                                                                @include('Menues.menu_item', ['v' => $v])
                                                            </li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                                <button class="btn btn-primary" id="add_menu">Add Menu</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="login-btn-inner">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="login-horizental cancel-wp pull-left">
                                            <button class="btn btn-sm btn-primary login-submit-cs" type="submit">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.footer')
<script>
    $(document).ready(function(){
        $('#add_menu').on('click', function(e){
            let i = $(document).find('#menu_items li').length;
            e.preventDefault();
            let html = '<li>'+
                            '<input type="hidden" value="static" class="item_type" name="item['+i+'][item_type]">'+
                            '<input type="hidden" value="Home" class="item_name" name="item['+i+'][item_name]">'+
                            '<input type="hidden" value="/" class="item_link" name="item['+i+'][item_link]">'+
                            '<div class="row menu_controll">'+
                               '<div class="col-md-2 type_choosing">'+
                                    '<select class="form-control">'+
                                        '<option value="static">Static Link</option>'+
                                        '<option value="cat">Category</option>'+
                                        '<option value="tag">Tag</option>'+
                                        '<option value="post">Post</option>'+
                                        '<option value="page">Page</option>'+
                                    '</select>'+
                                '</div>'+
                                '<div class="col-md-3 item_choosing" style="display:none;">'+
                                    '<select class="form-control">'+
                                    '</select>'+
                                '</div>'+
                                '<div class="col-md-2 item_text">'+
                                    '<input type="text" class="form-control" />'+
                                '</div>'+
                                '<div class="col-md-3 item_link">'+
                                    '<input type="text" class="form-control" />'+
                                '</div>'+
                                '<div class="col-md-2">'+
                                    '<span class="btn btn-primary save_item">Save</span>'+
                                '</div>'+
                            '</div>'+
                        '</li>';
            $('#menu_items').append(html);
            $(this).attr('disabled', true);
        }); 
        
        $(document).on('change', '.type_choosing select', function(){
            let type = $(this).val();
            $(this).closest('li').find('.item_type').val(type);
            $(this).closest('li').find('.item_name').val('');
            $(this).closest('li').find('.item_link').val('');
            if( type =='cat' ){
                $.ajax({
                    url:"{{ route('categoriesjson') }}",
                    method:'GET',
                    success:function(res){
                        res = JSON.parse(res);
                        if( res && res.cats && res.cats.length > 0 ){
                            let html = '<option>Choose Category</option>';
                            for( let i in res.cats ){
                                let cat_data = res.cats[i];
                                html += '<option value="'+cat_data.slug+'">'+cat_data.title+'</option>';                                
                            }
                            $(document).find('.menu_controll').find('.item_choosing').show().find('select').html(html);
                        }
                    },
                });
            }else if( type =='tag' ){
                $.ajax({
                    url:"{{ route('tagsjson') }}",
                    method:'GET',
                    success:function(res){
                        res = JSON.parse(res);
                        if( res && res.tags && res.tags.length > 0 ){
                            let html = '<option>Choose Tag</option>';
                            for( let i in res.tags ){
                                let cat_data = res.tags[i];
                                html += '<option value="'+cat_data.slug+'">'+cat_data.title+'</option>';                                
                            }
                            $(document).find('.menu_controll').find('.item_choosing').show().find('select').html(html);
                        }
                    },
                });
            }else if( type =='post' ){
                $.ajax({
                    url:"{{ route('postsjson') }}",
                    method:'GET',
                    success:function(res){
                        res = JSON.parse(res);
                        if( res && res.posts && res.posts.length > 0 ){
                            let html = '<option>Choose Post</option>';
                            for( let i in res.posts ){
                                let p_data = res.posts[i];
                                html += '<option value="'+p_data.slug+'">'+p_data.title+'</option>';                                
                            }
                            $(document).find('.menu_controll').find('.item_choosing').show().find('select').html(html);
                        }
                    },
                });
            }else if( type =='page' ){
                $.ajax({
                    url:"{{ route('pagejson') }}",
                    method:'GET',
                    success:function(res){
                        res = JSON.parse(res);
                        if( res && res.pages && res.pages.length > 0 ){
                            let html = '<option>Choose Page</option>';
                            for( let i in res.pages ){
                                let p_data = res.pages[i];
                                html += '<option value="'+p_data.slug+'">'+p_data.title+'</option>';                                
                            }
                            $(document).find('.menu_controll').find('.item_choosing').show().find('select').html(html);
                        }
                    },
                });
            }else{
                $(document).find('.menu_controll .item_choosing').hide();
            }
        });

        $(document).on('change', '.item_choosing select', function(e){
            if( $(this).val() ){
                let target_val = $(e.target).find('option:selected').text();
                $(this).closest('li').find('.item_name').val(target_val);
                $(this).closest('li').find('.item_link').val(base_url+'/'+$(this).val());
                $(this).closest('.menu_controll').find('.item_text input').val(target_val);
                $(this).closest('.menu_controll').find('.item_link input').val(base_url+'/'+$(this).val());
            }
        });

        $(document).on('click', '.save_item', function(e){
            e.preventDefault();
            e.stopPropagation();
            let _this = $(this);
            let wrapper = _this.closest('.menu_controll');
            if( wrapper.find('.type_choosing select').val() ){
                wrapper.closest('li').find('.item_type').val(wrapper.find('.type_choosing select').val());
            }else{
                wrapper.find('.type_choosing select').addClass('input-error');
                return;
            }
            if( wrapper.find('.item_text input').val() ){
                wrapper.closest('li').find('.item_name').val(wrapper.find('.item_text input').val());
                wrapper.closest('li').append('<span>'+wrapper.find('.item_text input').val()+'</span>');
            }else{
                wrapper.find('.item_text input').addClass('input-error');
                return;
            }
            if( wrapper.find('.item_link input').val() ){
                wrapper.closest('li').find('.item_link').val(wrapper.find('.item_link input').val());
            }else{
                wrapper.find('.item_link input').addClass('input-error');
                return;
            }
            wrapper.closest('li').append('<span class="edit-item"><i class="fa fa-edit"></i></span>').append('<span class="delete-item"><i class="fa fa-trash"></i></span>')
            if( window['menu_flag'] ){
                if( window['menu_flag'] != 'edit') {
                    wrapper.closest('li').append('<ul class="sub-menu"></ul>'+
                                '<button class="btn btn-primary add_submenu">Add Sub Menu</button>');
                }
                window['menu_flag'] = '';
            }else{
                wrapper.closest('li').append('<ul class="sub-menu"></ul>'+
                                '<button class="btn btn-primary add_submenu">Add Sub Menu</button>');
            }
            
            if(wrapper.closest('ul').hasClass('sub-menu')){
                wrapper.closest('ul.sub-menu').siblings('.add_submenu').removeAttr('disabled');
            }else{
                $('#add_menu').removeAttr('disabled');
            }
             wrapper.remove();
        });

        $(document).on('click', '.delete-item', function () {
            $(this).closest('li').remove();
        });

        $(document).on('click', '.add_submenu', function(e){
            e.preventDefault();
            let last_parent_index = $(this).closest('li').find('.item_type').attr('name');
            last_parent_index = last_parent_index.replace('[item_type]', '');
            let i = $(this).closest('li').find('ul li').length;
            let html = '<li>'+
                            '<input type="hidden" value="post" class="item_type" name="'+last_parent_index+'[item]['+i+'][item_type]">'+
                            '<input type="hidden" value="My Post" class="item_name" name="'+last_parent_index+'[item]['+i+'][item_name]">'+
                            '<input type="hidden" value="my_post" class="item_link" name="'+last_parent_index+'[item]['+i+'][item_link]">'+
                            '<div class="row menu_controll">'+
                               '<div class="col-md-2 type_choosing">'+
                                    '<select class="form-control">'+
                                        '<option value="static">Static Link</option>'+
                                        '<option value="cat">Category</option>'+
                                        '<option value="tag">Tag</option>'+
                                        '<option value="post">Post</option>'+
                                        '<option value="page">Page</option>'+
                                    '</select>'+
                                '</div>'+
                                '<div class="col-md-3 item_choosing" style="display:none;">'+
                                    '<select class="form-control">'+
                                        '<option>1st Post</option>'+
                                        '<option>2nd Post</option>'+
                                    '</select>'+
                                '</div>'+
                                '<div class="col-md-2 item_text">'+
                                    '<input type="text" class="form-control" />'+
                                '</div>'+
                                '<div class="col-md-3 item_link">'+
                                    '<input type="text" class="form-control" />'+
                                '</div>'+
                                '<div class="col-md-2">'+
                                    '<span class="btn btn-primary save_item">Save</span>'+
                                '</div>'+
                            '</div>'+
                        '</li>';
            $(this).parent().find('ul.sub-menu').append(html);
            $(this).attr('disabled', true);
        });

        $(document).on('click', '.edit-item', function(e){
            window['menu_flag'] = 'edit';
            let _this = $(this);
            let link = _this.siblings('.item_link').val();
            let text = _this.siblings('.item_name').val();
            let html =  '<div class="row menu_controll">'+
                           '<div class="col-md-2 type_choosing">'+
                                '<select class="form-control">'+
                                    '<option value="static">Static Link</option>'+
                                    '<option value="cat">Category</option>'+
                                    '<option value="tag">Tag</option>'+
                                    '<option value="post">Post</option>'+
                                    '<option value="page">Page</option>'+
                                '</select>'+
                            '</div>'+
                            '<div class="col-md-3 item_choosing" style="display:none;">'+
                                '<select class="form-control">'+
                                '</select>'+
                            '</div>'+
                            '<div class="col-md-2 item_text">'+
                                '<input type="text" class="form-control" value="'+text+'" />'+
                            '</div>'+
                            '<div class="col-md-3 item_link">'+
                                '<input type="text" class="form-control" value="'+link+'"/>'+
                            '</div>'+
                            '<div class="col-md-2">'+
                                '<span class="btn btn-primary save_item">Save</span>'+
                            '</div>'+
                        '</div>';
            $(html).insertBefore($(this));
            $(this).siblings('span').remove();
            $(this).remove();
        });
    });
</script>