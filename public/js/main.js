(function ($) {
 "use strict";

	/*----------------------------
	 jQuery MeanMenu
	------------------------------ */
	jQuery('nav#dropdown').meanmenu();	
	/*----------------------------
	 jQuery myTab
	------------------------------ */
	$('#myTab a').click(function (e) {
		  e.preventDefault()
		  $(this).tab('show')
		});
		$('#myTab3 a').click(function (e) {
		  e.preventDefault()
		  $(this).tab('show')
		});
		$('#myTab4 a').click(function (e) {
		  e.preventDefault()
		  $(this).tab('show')
		});

	  $('#single-product-tab a').click(function (e) {
		  e.preventDefault()
		  $(this).tab('show')
		});
	
	$('[data-toggle="tooltip"]').tooltip(); 
	
	$('#sidebarCollapse').on('click', function () {
                     $('#sidebar').toggleClass('active');
                     
                 });
		// Collapse ibox function
			$('#sidebar ul li').on('click', function () {
				var button = $(this).find('i.fa.indicator-mn');
				button.toggleClass('fa-plus').toggleClass('fa-minus');
				
			});
	/*-----------------------------
			Menu Stick
		---------------------------------*/
		$(".sicker-menu").sticky({topSpacing:0});
			
		$('#sidebarCollapse').on('click', function () {
			$("body").toggleClass("mini-navbar");
			SmoothlyMenu();
		});
		$(document).on('click', '.header-right-menu .dropdown-menu', function (e) {
			  e.stopPropagation();
			});
 

	   
/*--------------------------
 scrollUp
---------------------------- */	
	$.scrollUp({
        scrollText: '<i class="fa fa-angle-up"></i>',
        easingType: 'linear',
        scrollSpeed: 900,
        animation: 'fade'
    }); 	   
 
})(jQuery);

function convertToSlug(Text) {
  return Text.toLowerCase()
             .replace(/ /g, '-')
             .replace(/[^\w-]+/g, '');
}

jQuery(document).ready(function($){
    $("input[name='title']").on('change', function(){
        if($(this).val()){
        	$(this).closest('form').find("input[name='slug']").val(convertToSlug($(this).val()));
        }
    });

    $(document).on('click', '.image-selector', function(e){
    	//alert();
    	$(this).siblings().removeClass('selected');
    	$(this).addClass('selected');
    	window['target_attachment_id'] = $(this).data('id');
    	window['target_attachment_url'] = $(this).find('img').attr('src');
    });
    $(document).on('click', '#mediachoosemodal .image-selector', function(e){
      //alert();
      $(window['image-target']).css('background-image', "url('"+$(this).find('img').attr('src')+"')");
      $(window['image-target']).find('.dp_product_img').val($(this).data('id'));
      $(window['image-target']).find('.upload_block').removeClass('add').addClass('remove').html('<i class="fa fa-minus"></i>');
      $('#mediachoosemodal').modal('hide');
    });
    $(document).on('click', '.attach_thumbnail', function(e){
    	e.preventDefault();
    	$('#mediamodal').modal();
    	window['modal-target'] = $(this).data('target');
      window['modal-type'] = $(this).data('type');
    	$.ajax({
          type:'GET',
          url:base_url+'/media/list/json',
          processData: false,
          contentType: false,
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          beforeSend:function(){
              $('#loader').show();
          },
          success:function(_data){
              $('#loader').hide();
              if(_data){
              	medias = JSON.parse(_data);
              	let html = '<div class="row search"><div class="col-md-12"><input type="text" class="form-control" id="image_search" placeholder="Search Image"></div></div>';
              	html += '<div class="row mideas">';
              	for( let i = 0; i<medias.medias.data.length; i++){
              		if( medias.medias.data[i].mimetype == 'image/jpeg'|| medias.medias.data[i].mimetype == 'image/png' || medias.medias.data[i].mimetype == 'image/webp'){
	              		html += '<div class="col-md-2 image-selector" data-id="'+medias.medias.data[i].id+'"><img src="'+base_url+'/'+medias.medias.data[i].url+'" height="200" width="200"/></div>';
	              	}
              	}
              	html += '</div>';
              	if( medias.medias.next_page_url ){
                    html += '<div class="row load_more"><div class="col-md-12"><button class="btn btn-success" id="next_page_url" data-value="'+medias.medias.next_page_url+'">Load More...</button></div></div>';
                }
              	//console.log($('#mediamodal').find('#uploaded'));
              	$('#mediamodal').find('#uploaded').html(html);
              }

          },
      });
    });
    $(document).on('click', '.dp_uploader .upload_block.add', function(e){
      e.preventDefault();
      $('#mediachoosemodal').modal();
      window['image-target'] = $(this).closest('.dp_uploader');
      $.ajax({
          type:'GET',
          url:base_url+'/media/list/json',
          processData: false,
          contentType: false,
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          beforeSend:function(){
              $('#loader').show();
          },
          success:function(_data){
              $('#loader').hide();
              if(_data){
                medias = JSON.parse(_data);
                let html = '<div class="row mideas">';
                for( let i = 0; i<medias.medias.data.length; i++){
                  if( medias.medias.data[i].mimetype == 'image/jpeg' || medias.medias.data[i].mimetype == 'image/png' || medias.medias.data[i].mimetype == 'image/webp'){
                    html += '<div class="col-md-2 image-selector" data-id="'+medias.medias.data[i].id+'"><img src="'+base_url+'/'+medias.medias.data[i].url+'" height="200" width="200"/></div>';
                  }
                }
                html += '</div>';
                if( medias.medias.next_page_url ){
                    html += '<div class="row load_more"><div class="col-md-12"><button class="btn btn-success" id="next_page_url" data-value="'+medias.medias.next_page_url+'">Load More...</button></div></div>';
                }
                //console.log($('#mediamodal').find('#uploaded'));
                $('#mediachoosemodal').find('#uploaded').html(html);
              }
          },
      });
    });
    $(document).on('click', '.dp_uploader .upload_block.remove', function(e){
      e.preventDefault();
      $(this).closest('.dp_uploader').find('.upload_block').removeClass('remove').addClass('add').html('<i class="fa fa-plus"></i>');
      $(this).closest('.dp_uploader').find('input').val('');
      $(this).closest('.dp_uploader').css('background-image', 'unset');
      
    });
    $(document).on('click', '#next_page_url', function (e) {
        e.preventDefault();
        let _this = $(this);
        let url = _this.data('value');
        $.ajax({
          type:'GET',
          url:url,
          processData: false,
          contentType: false,
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          beforeSend:function(){
              $('#loader').show();
          },
          success:function(_data){
              $('#loader').hide();
              if(_data){
                medias = JSON.parse(_data);
                let html = '';
                for( let i = 0; i<medias.medias.data.length; i++){
                  if( medias.medias.data[i].mimetype == 'image/jpeg' || medias.medias.data[i].mimetype == 'image/png' || medias.medias.data[i].mimetype == 'image/webp'){
                    html += '<div class="col-md-2 image-selector" data-id="'+medias.medias.data[i].id+'"><img src="'+base_url+'/'+medias.medias.data[i].url+'" height="200" width="200"/></div>';
                  }
                }
                _this.closest('.load_more').siblings('.mideas').append(html);
                if( medias.medias.next_page_url ){
                    _this.data('value', medias.medias.next_page_url);
                }else{
                    _this.remove();
                }
              }
          },
        });
    });
    $(document).on('input', '#image_search', function (e) {
        e.preventDefault();
        let _this = $(this);
        let alp = $(this).val();
        let _url = base_url+'/media/list/json';
        if( alp.length > 2 ){
            _url = base_url+'/media/list/json/'+alp;
        }
        $.ajax({
            type:'GET',
            url:_url,
            processData: false,
            contentType: false,
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend:function(){
              $('#loader').show();
            },
            success:function(_data){
                $(_this).focus();
                $('#loader').hide();
                if(_data){
                    medias = JSON.parse(_data);
                    let html = '<div class="row search"><div class="col-md-12"><input type="text" class="form-control" id="image_search" placeholder="Search Image" value="'+alp+'"></div></div>';
                    html += '<div class="row mideas">';
                    for( let i = 0; i<medias.medias.data.length; i++){
                      if( medias.medias.data[i].mimetype == 'image/jpeg'|| medias.medias.data[i].mimetype == 'image/png' || medias.medias.data[i].mimetype == 'image/webp'){
                      	html += '<div class="col-md-2 image-selector" data-id="'+medias.medias.data[i].id+'"><img src="'+base_url+'/'+medias.medias.data[i].url+'" height="200" width="200"/></div>';
                      }
                    }
                    html += '</div>';
                    if( medias.medias.next_page_url ){
                    html += '<div class="row load_more"><div class="col-md-12"><button class="btn btn-success" id="next_page_url" data-value="'+medias.medias.next_page_url+'">Load More...</button></div></div>';
                    }
                    //console.log($('#mediamodal').find('#uploaded'));
                    $('#mediamodal').find('#uploaded').html(html);
                }
            },
        });
    });
    $(document).on('click', '.set-image', function(e){
    	e.preventDefault();
    	let modal_id = $(this).closest('.modal').attr('id');
    	let attachment_id = window['target_attachment_id'];
    	
    	$('#'+modal_id).modal('hide');
      if(window['modal-type'] == 'url'){
        $(document).find('#'+window['modal-target']).val(window['target_attachment_url']);
        $(document).find('#'+window['modal-target']).siblings('.add-thumbnail').html('<img src="'+window['target_attachment_url']+'"/>');
      }else{
        $(document).find('#'+window['modal-target']).val(attachment_id);
        $('#post-thumbnail').html('<img src="'+window['target_attachment_url']+'"/>');
      }
    });
    $(document).on('submit', '#onscreen_upload', function(e){
    	e.preventDefault();
      let _this = $(this);
    	let form = $(this)[0];
    	var form_data = new FormData(form);
        $.ajax({
            type:'POST',
            data:form_data,
            url:base_url+'/media/savejson',
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend:function(){
                $('#loader').show();
            },
            success:function(_data){
                $('#loader').hide();
                if( _data && _data.error ){
                  alert('Media Upload failed');
                }else{
                  _this.closest('.tab-content').find('#uploaded').prepend('<div class="col-md-2 image-selector" data-id="'+_data.id +'"><img src="'+ base_url+'/'+_data.url +'" height="200" width="200"></div>');
                  _this.closest('.tabbable').find('.nav.nav-tabs .pull-left').eq(0).find('a').trigger('click');
                }
            },
        });
    });

    $(document).on('click', '.dp_add_image_block', function(e){
      e.preventDefault();
      var html = '<div class="dp-uploader dp_uploader dp-attachments-uploader" data-cb="uploadAttachments" data-target="dp_product_img">'+
                    '<span class="delete-img-block delete_img_block"><i class="fa fa-trash"></i></span>'+
                '<div class="upload-block">'+
                  '<div class="upload_block add"><i class="fa fa-plus"></i></div>'+
                  '<div class="upload_block remove hide"><i class="fa fa-minus"></i></div>'+
                '</div>'+
                '<input type="hidden" name="post_gallery[]" class="dp_product_img" value=""/>'+
              '</div>';
      $(html).insertBefore($(this));
    });
    $(document).on('click', '.remove_add', function(e){
      e.preventDefault();
      $(this).siblings('.add-thumbnail').html('');
      $(this).siblings('.attach_thumbnail').html('Upload Addvertise Image');
      $(this).siblings('#add_id').val('');
      $(this).siblings('#mobile_add_id').val('');
    });
    
    $(document).on('click', '.delete_img_block', function(e){
      e.preventDefault();
      $(this).closest('.dp_uploader').remove();
    });
    if( $(document).find('#editor_classic').length > 0 ){
      KothingEditor.create("editor_classic", {
          display: "block",
          width: "100%",
          height: "auto",
          popupDisplay: "full",
          katex: katex,
          font : [
                'Arial',
                'Arial Black',
                'tohoma',
                'Courier New,Courier',
                'Franklin',
                'Helvetica',
                'Verdana',
                'Trebuchet MS',
                'Impact',
                'Gill Sans',
                'Times New Roman',
                'Monotype Corsiva',
                'Georgia',
                'Monaco',
                'Lucida',
                'nyt-cheltenham,georgia',
                'Lato',
                'Courier',
                'Bradley Hand',
                'Brush Script MT',
                'Luminari',
                'Comic Sans MS',
                'Palatino',
                'Baskerville',
                'Andalé Mono',
                'Merriweather',
                'ReithSans',
            ],
          toolbarItem: [
            ["undo", "redo"],
            ["font", "fontSize", "formatBlock"],
            [
              "bold",
              "underline",
              "italic",
              "strike",
              "subscript",
              "superscript",
              "fontColor",
              "hiliteColor",
            ],
            ["outdent", "indent", "align", "list", "horizontalRule"],
            ["link", "table", "image", "audio", "video"],
            ["lineHeight", "paragraphStyle", "textStyle"],
            ["showBlocks", "codeView"],
            ["math"],
            ["preview", "print", "fullScreen"],
            ["save", "template"],
            ["removeFormat"],
          ],
          templates: [
            {
              name: "Template-1",
              html: "<p>HTML source1</p>",
            },
            {
              name: "Template-2",
              html: "<p>HTML source2</p>",
            },
          ],
          charCounter: true,
        });
    }
  $(document).on('click', '#generate_audio_script', function(e){
    e.preventDefault();
    /*split_content= post_content.split(/[.!?]&amp;nbsp;/g);
    const wrappedSentences = split_content.map(sentence => `<s>${sentence}</s>`);
    post_content = wrappedSentences.join('')*/
    post_content = post_content.replace(/&lt;br&gt;/g, '<break time="300ms"/>').replace(/&lt;p&gt;/g, '<P><break time="800ms"/>').replace(/&lt;\/p&gt;/g, '</P>');
    post_content = post_content.replace(/&lt;strong&gt;/g, '<emphasis level="moderate">').replace(/&lt;\/strong&gt;/g, '</emphasis>');
    post_content = post_content.replace(/&lt;h4&gt;/g, '<emphasis level="moderate">');
    post_content = post_content.replace(/&lt;h2&gt;/g, '<emphasis level="strong">');
    post_content = post_content.replace(/&lt;h3&gt;/g, '<emphasis level="strong">');
    post_content = post_content.replace(/&lt;\/h4&gt;/g, '</emphasis>');
    post_content = post_content.replace(/&lt;\/h2&gt;/g, '</emphasis>');
    post_content = post_content.replace(/&lt;\/h3&gt;/g, '</emphasis>');
    post_content = post_content.replace(/&amp;/g, '&').replace(/&nbsp;/g, ' ');
    post_content = post_content.replace(/&lt;(.*?);&gt;/g, '').replace(/&lt;(.*?)&gt;/g, '');
    $('#generate_audio_text').show().find('textarea').html(post_content).val(post_content);
  });

  $(document).on('click', '#generate_audio', function(e){
    e.preventDefault();
    let _this = $(this);
    let text = $(this).siblings('textarea').val();
    let form = $(this).closest('form')[0];
    var form_data = new FormData(form);
    form_data.append('type', 'ssml');
    $.ajax({
        type:'POST',
        data:form_data,
        url:base_url+'/admin/post/audio/generate',
        //url:base_url+'/media/savejson',
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend:function(){
            $('#loader').show();
        },
        success:function(_data){
            $('#loader').hide();
            if( _data && _data.error ){
              alert('failed to generate audio');
            }else{
              //_this.closest('#audio').find('.generate-audio-div').html('');
              if( _data.audio_clip_url ){
                _this.closest('#audio').find('.generate-audio-div').html('<audio controls><source src="'+_data.audio_clip_url+'" type="audio/mpeg"/></audio>');
              }
            }
        },
    });
  });
  setInterval(setNotification, 5000);
});

function setNotification(){
  $.ajax({
      type:'GET',
      url:base_url+'/admin/notifications/top',
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      beforeSend:function(){
          //$('#cover-spin').show();
      },
      success:function(_data){
          _data = JSON.parse( _data );
          if( _data && _data.error ){
          }else{
            let html = '';
            if( _data.item && _data.item.data ){
              for( let i in _data.item.data ){
                let d = new Date(_data.item.data[i]['created_at']);
                html += '<li>'+
                          '<a href="#">'+
                              '<div class="notification-content">'+
                                  '<span class="notification-date">'+d.getDate()+'/'+d.getMonth()+'/'+d.getYear()+'</span>'+
                                  '<h2>Notified at</h2>'+
                                  '<p>'+_data.item.data[i]['description']+'</p>'+
                              '</div>'+
                          '</a>'+
                      '</li>';
              }
            }
            $(document).find('#notification_menu').html(html);
          }
      },
  });
}