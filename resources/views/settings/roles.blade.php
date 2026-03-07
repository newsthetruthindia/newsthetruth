@include('layouts.header')
@include('layouts.sidebar')
<div class="product-status mg-t-30" style="margin:5%;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="row">
                            <div class="col-12 card">
                                <div class="card-header">
                                    <a class="btn btn-blue-grey btn-default" href="{{ route('settings-role-add') }}" id="add-role">Add ROle</a>
                                </div>
                            </div>
                        </div>
                        <div class="row roles-list">
                            @if( !empty( $roles ) )
                                @foreach( $roles as $role )
                                    <div class="row role">
                                        <div class="col-lg-6"><small>{{ $role->role_name }}</small></div>
                                        <div class="col-lg-2"><small class="role-edit" data-id="{{ $role->id }}"><i class="fa fa-edit"></i></small></div>
                                        <div class="col-lg-2"><small class="role-delete" data-id="{{ $role->id }}"><i class="fa fa-trash"></i></small></div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div id="role-form"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@include('layouts.footer')
<script type="text/javascript">
    $(document).ready(function(){
        $('#add-role').on('click', function(e){
            e.preventDefault();
            $.ajax({
                url:"{{ route('settings-role-add') }}",
                method:'GET',
                beforeSend:function(){
                    $('#loader').show();
                },
                success:function(res){
                    $('#loader').hide();
                    if(res && res.form ){
                        $('#role-form').html(res.form);
                    }
                },
            });
        });

        $(document).on('click', '.role-edit', function(e){
            e.preventDefault();
            var id = $(this).data('id');
            $.ajax({
                url:"{{ route('settings-role-edit') }}",
                method:'POST',
                data:{'id':id, '_token':"{{ csrf_token() }}"},
                beforeSend:function(){
                    $('#loader').show();
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(res){
                    $('#loader').hide();
                    if(res && res.form ){
                        $('#role-form').html(res.form);
                    }
                },
            });
        });

        $(document).on('click', '.role-delete', function(e){
            e.preventDefault();
            let _this = $(this);
            var id = _this.data('id');
            $.ajax({
                url:"{{ route('settings-role-delete') }}",
                method:'POST',
                data:{'id':id, '_token':"{{ csrf_token() }}"},
                beforeSend:function(){
                    $('#loader').show();
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(res){
                    $('#loader').hide();
                    if(res && !res.error ){
                        _this.closest('.row').remove();
                    }
                },
            });
        });

        $(document).on('submit', '#save_role', function(e){
            e.preventDefault();
            var type = $('#role-form').find('#role_id').data('type');
            var form = $('#save_role')[0];
            var form_data = new FormData(form);
            $.ajax({
                type:'POST',
                data:form_data,
                url:"{{ route('settings-roles-update') }}",
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend:function(){
                    $('#loader').show();
                },
                success:function(res){
                    $('#loader').hide();
                    if(res && res.UserRole ){
                        $('#role-form').find('#role_id').val(res.UserRole.id);
                        if( type == 'add'){
                            $('#role-form').find('#role_id').data('type', 'edit');
                            var html = '<div class="row">'+
                                    '<div class="col-lg-6"><small>'+ res.UserRole.role_name +'</small></div>'+
                                    '<div class="col-lg-2"><small class="role-edit" data-id="'+res.UserRole.id+'"><i class="fa fa-edit"></i></small></div>'+
                                    '<div class="col-lg-2"><small class="role-delete" data-id="'+res.UserRole.id+'"><i class="fa fa-trash"></i></small></div>'+
                                '</div>';
                            $(html).appendTo('.roles-list');
                        }else{

                        }
                    }
                },
            });
        });
    });
</script>