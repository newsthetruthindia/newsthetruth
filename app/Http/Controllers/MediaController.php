<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use Image;


class MediaController extends Controller{

    public $upload_url;

    public $upload_path;

    public $image_sizes=[
        'small'         =>[320, 240, true, true],
        'medium'        =>[640, 480, true, true],
        'large'         =>[1024, 768, true, true],
        'exlarge'       =>[1920, 1080, true, true],
        'full'          =>[1366, 920, true, true],
        'desktop'       =>[1366, 768, true, true],
        'widedt'        =>[1366, 720, true, true],
        'smallthumb'    =>[150, 150, true, true],
        'exsmallthumb'  =>[100, 100,  true, true],
        'thumb'         =>[480, 480,  true, true],
        'lgthumb'       =>[520, 520, true, true],
        'medthumb'      =>[240, 240,  true, true],
        'potrait'       =>[768, 1024, true, true],
        'medpotrait'    =>[240, 320,  true, true],
        'smpotrait'     =>[480, 640,  true, true],
        'banner'        =>[1200, 450, true, true],
        'smproduct'     =>[300, 300, true, true],
        'mdproduct'     =>[600, 600,  true, true],
        'lgproduct'     =>[900, 900,  true, true],
        'xsproduct'     =>[1500, 1500,  true, true],
    ];
    
    public $img_types = [
                            'image/png',
                            'image/jgp',
                            'image/jpeg',
                            'image/gif',
                            'image/webp'
                            //'image/x-icon',
                            //'image/vnd.microsoft.icon',
                        ];

    public $video_types = [
                                'video/mp4',
                                'video/mpeg',
                                'video/avi',
                                'video/quicktime'
                            ];
    
    public $audio_types = [
                                'audio/mpeg',
                                'audio/mp3',
                                'audio/x-wav',
                                'audio/wave',
                                'audio/wav'
                            ];
    
    public $doc_types = [
                            'application/pdf',
                            'application/csv',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'text/plain',
                            'application/msword',
                            'application/vnd.ms-office'
                        ];

    public function __construct(){
        $year = date('Y');
        $month = date('m');
        $realpath = public_path('uploads'. '/' . $year . '/' . $month);
        $this->upload_path = $realpath;
        $this->upload_url  = 'public/uploads/' . $year . '/' . $month .'/';
        if( !is_dir( $realpath ) ) mkdir($realpath, 0777, true);
    }

    public function getImageTypes(){
        $images = $this->img_types;
        return $images;
    }
    public function getAudioTypes(){
        return $this->audio_types;
    }
    public function getVideoTypes(){
        return $this->video_types;
    }
    public function getDocTypes(){
        return $this->doc_types;
    }

    public function list(Request $req){
        $offset= !empty(get_option('regenerated_img'))?get_option('regenerated_img')->value:0;
        $media = Media::orderBy('id', 'DESC')->paginate(18);
        return view('medias.lists')->with(['medias'=>$media, 'offset'=>$offset]);
    }

    public function listJson(Request $req, $alp=''){
        $media = Media::where('name', 'like', '%'. $alp . '%')->orderBy('id', 'DESC')->paginate(12);
        echo json_encode(['medias'=>$media]);
        die;
    }

    public function add(Request $req){
        // code...
    }

    public function generateAndStore( $image ){
        
        $input['file'] = time().'.'.$image->getClientOriginalExtension();

        $destinationPath = $this->upload_path;
        $imgFile = Image::make($image->getRealPath());
        $imgFile->resize(150, 150, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$input['file']);
        $image->move($destinationPath, $input['file']);
                //dd($imgFile);
              
    }

    public function edit(Request $req, Media $media){
        return $media;
    }
    
    public function generationReset(Request $req, Media $media){
        delete_option('regenerated_img', '0');
        return redirect()->to( route( 'medias' ) );
    }

    public function save( Request $req ) {
        if( $req->file( 'files' ) ) {
            $destinationPath = $this->upload_path;
            foreach( $req->file( 'files' ) as $file ) {
                $filename = str_replace('/(\w+)/g', '-', $file->getClientOriginalName());
                $ext  = $file->getClientOriginalExtension();
                $name_only = substr( $filename, 0, strpos( $filename, '.'.$ext ) );
                $name_only = str_replace(' ', '-', $name_only);
                $name_only = str_replace('.', '', $name_only);
                $media = new Media();
                $media->mimetype =$file->getMimeType();
                $media->type ='media';
                $media->extension =$ext;
                $media->name =$name_only;
                $media->url =$this->upload_url.$file->getClientOriginalName(); 
                $media->path =$destinationPath.'/'.$file->getClientOriginalName(); 
                if( in_array( $file->getMimeType(), $this->img_types ) ) {
                    $media->mimetype = 'image/webp';
                    $media->extension ='webp';
                    $media->url =$this->upload_url.$name_only.'.webp'; 
                    $media->path =$destinationPath.'/'.$name_only.'.webp'; 
                    //$input['file'] = $file->getClientOriginalName().'.'.$file->getClientOriginalExtension();                    
                    $input['file'] = $file->getClientOriginalName();                    
                    $imgFile = Image::make($file->getRealPath());  
                    $imgFile->encode('webp', 100)->save($destinationPath.'/'.$name_only.'.webp');
                    foreach( $this->image_sizes as $size_key=>$size ) {
                        $nimgFile = Image::make(file_get_contents($destinationPath.'/'.$name_only.'.webp') );
                        if(isset($size[2]) && $size[2]){
                            if(isset($size[3]) && $size[3] ){
                                if( $nimgFile->height() > $nimgFile->width() ){
                                    $nimgFile->resize( null, $size[1], function ($constraint) {
                                        $constraint->aspectRatio();
                                    })->resizeCanvas($size[0], $size[1], 'center', false, '#000000')->encode('webp', 100)->save($destinationPath.'/'.$name_only.'-'.$size[0].'x'.$size[1].'.webp');
                                }else{
                                    $nimgFile->resize( $size[0], null, function ($constraint) {
                                        $constraint->aspectRatio();
                                    })->resizeCanvas($size[0], $size[1], 'center', false, '#000000')->encode('webp', 100)->save($destinationPath.'/'.$name_only.'-'.$size[0].'x'.$size[1].'.webp');
                                }
                            }else{
                                $nimgFile->resize($size[0], null, function ($constraint) {
                                    $constraint->aspectRatio();
                                })->crop($size[0], $size[1])->encode('webp', 100)->save($destinationPath.'/'.$name_only.'-'.$size[0].'x'.$size[1].'.webp');
                            }
                        }else{
                            $nimgFile->resize($size[0], $size[1], function ($constraint) {
                                $constraint->aspectRatio();
                            })->save($destinationPath.'/'.$size_key.'__'.$name_only.'-'.$size[0].'x'.$size[1].'.'.$name_only.'.webp');
                        }                        
                    }                    
                    
                }else{
                    $input['file'] = $filename;  
                    $file->move($destinationPath, $input['file']);
                }
                $media->save();
            }
        }
        $all_media = Media::paginate(18);
        $the_page_no = !empty( $req->page_no ) ? $req->page_no <= $all_media->lastPage() ? $req->page_no : $all_media->lastPage():1;
        return redirect()->to( route( 'medias', ['page'=> $the_page_no]) );
    }

    public function savejson( Request $req ) {
        if( $req->file( 'files' ) ) {
            $destinationPath = $this->upload_path;
            foreach( $req->file( 'files' ) as $file ) {
                $filename = str_replace('/(\w+)/g', '-', $file->getClientOriginalName());
                $ext  = $file->getClientOriginalExtension();
                $name_only = substr( $filename, 0, strpos( $filename, '.'.$ext ) );
                $name_only = str_replace(' ', '-', $name_only);
                $name_only = str_replace('.', '', $name_only);
                $media      = new Media();
                $media->mimetype =$file->getMimeType();
                $media->type ='media';
                $media->extension =$file->getClientOriginalExtension();
                $media->name =$name_only;
                $media->url =$this->upload_url.$file->getClientOriginalName(); 
                $media->path =$destinationPath.'/'.$file->getClientOriginalName(); 
                if( in_array( $file->getMimeType(), $this->img_types ) ) {
                     $media->mimetype = 'image/webp';
                    $media->extension ='webp';
                    $media->url =$this->upload_url.$name_only.'.webp'; 
                    $media->path =$destinationPath.'/'.$name_only.'.webp'; 
                    //$input['file'] = $file->getClientOriginalName().'.'.$file->getClientOriginalExtension();                    
                    $input['file'] = $file->getClientOriginalName();                    
                    $imgFile = Image::make($file->getRealPath());  
                    $imgFile->encode('webp', 100)->save($destinationPath.'/'.$name_only.'.webp');
                    foreach( $this->image_sizes as $size_key=>$size ) {
                        $nimgFile = Image::make(file_get_contents($destinationPath.'/'.$name_only.'.webp') );
                        if(isset($size[2]) && $size[2]){
                            if(isset($size[3]) && $size[3] ){
                                if( $nimgFile->height() > $nimgFile->width() ){
                                    $nimgFile->resize( null, $size[1], function ($constraint) {
                                        $constraint->aspectRatio();
                                    })->resizeCanvas($size[0], $size[1], 'center', false, '#000000')->encode('webp', 100)->save($destinationPath.'/'.$name_only.'-'.$size[0].'x'.$size[1].'.webp');
                                }else{
                                    $nimgFile->resize( $size[0], null, function ($constraint) {
                                        $constraint->aspectRatio();
                                    })->resizeCanvas($size[0], $size[1], 'center', false, '#000000')->encode('webp', 100)->save($destinationPath.'/'.$name_only.'-'.$size[0].'x'.$size[1].'.webp');
                                }
                            }else{
                                $nimgFile->resize($size[0], null, function ($constraint) {
                                    $constraint->aspectRatio();
                                })->crop($size[0], $size[1])->encode('webp', 100)->save($destinationPath.'/'.$name_only.'-'.$size[0].'x'.$size[1].'.webp');
                            }
                        }else{
                            $nimgFile->resize($size[0], $size[1], function ($constraint) {
                                $constraint->aspectRatio();
                            })->save($destinationPath.'/'.$size_key.'__'.$name_only.'-'.$size[0].'x'.$size[1].'.'.$name_only.'.webp');
                        }                        
                    }
                }else{
                    $input['file'] = $filename;  
                    $file->move($destinationPath, $input['file']);
                }
                $media->save();
                return $media;die;
            }
        }
        return ['error'=>true,'msg'=>''];
    }

    public function delete(Request $req, Media $media){
        $name       = $media->name;
        $url        = $media->url;
        $ext        = $media->extension;
        $year       = date('Y', strtotime($media->created_at) );
        $month       = date('m', strtotime($media->created_at) );
        $exact_path = 'uploads/'.$year.'/'.$month.'/';
        foreach( $this->image_sizes as $size_key=>$size ) {
            $img_link = '';
            //$img_name = substr($name, 0, strpos($name, $ext));
            if(isset($size[2]) && $size[2]){
                $img_link = public_path($exact_path.$name.'-'.$size[0].'x'.$size[1].'.'.$ext);
            }else{
                $img_link = public_path($exact_path.$size_key.'__'.$name.'-'.$size[0].'x'.$size[1].'.'.$ext);
            }
            if( file_exists($img_link )){
                unlink($img_link);
            }
        }
        $real_image = public_path($exact_path.$name.'.'.$ext);
        if( file_exists($real_image ) ){
            unlink($real_image);
        }
        $media->delete();
        $all_media = Media::paginate(18);
        $the_page_no = !empty( $req->page_no ) && $req->page_no <= $all_media->lastPage() ? $req->page_no : $all_media->lastPage();
        return redirect()->to( route( 'medias', ['page'=> $the_page_no]) );
    }

    public function regenerate(Request $req){
        if( empty($req->id) ){
            $offset= !empty(get_option('regenerated_img'))?get_option('regenerated_img')->value:0;
            $medias = Media::whereIn('mimetype', $this->img_types)->offset($offset)->limit(25)->get();
            if( !empty( $medias ) ) update_option('regenerated_img', $offset+25);
        }else{
            $medias = Media::where('id', $req->id)->get();
        }
        //dd($medias);
        if( !empty( $medias ) ){
            foreach( $medias as $m ){
                $name       = $m->name;
                $url        = $m->url;
                $ext        = $m->extension;
                $year       = date('Y', strtotime($m->created_at) );
                $month       = date('m', strtotime($m->created_at) );
                $exact_path = 'uploads/'.$year.'/'.$month.'/';
                
                if( in_array( $m->mimetype, $this->img_types ) ) {
                    $destinationPath = public_path('uploads'. '/' . $year . '/' . $month);
                    //dd($destinationPath);
                    if( file_exists($destinationPath.'/'.$name.'.'.$ext ) ){
                        try{
                            $imgFiles = Image::make(file_get_contents($destinationPath.'/'.$name.'.'.$ext) );
                            $nname = str_replace(' ', '-', $name);
                            $nname = str_replace('.', '', $nname);
                            //dd($nname);
                            foreach( $this->image_sizes as $size_key=>$size ) {
                                $img_link = '';
                                //$img_name = substr($name, 0, strpos($name, $ext));
                                if(isset($size[2]) && $size[2]){
                                    $img_link = public_path($exact_path.$name.'-'.$size[0].'X'.$size[1].'.'.$ext);
                                }else{
                                    $img_link = public_path($exact_path.$size_key.'__'.$name.'-'.$size[0].'X'.$size[1].'.'.$ext);
                                }
                                if( file_exists($img_link )){
                                    unlink($img_link);
                                }
                                $reginFile = Image::make(file_get_contents($destinationPath.'/'.$name.'.'.$ext) );
                                if(isset($size[2]) && $size[2]){
                                    if(isset($size[3]) && $size[3] ){
                                        if( $reginFile->height() > $reginFile->width() ){
                                            $reginFile->resize( null, $size[1], function ($constraint) {
                                                $constraint->aspectRatio();
                                            })->resizeCanvas($size[0], $size[1], 'center', false, '#000000')->encode('webp', 100)->save($destinationPath.'/'.$nname.'-'.$size[0].'x'.$size[1].'.webp');
                                        }else{
                                            $reginFile->resize( $size[0], null, function ($constraint) {
                                                $constraint->aspectRatio();
                                            })->resizeCanvas($size[0], $size[1], 'center', false, '#000000')->encode('webp', 100)->save($destinationPath.'/'.$nname.'-'.$size[0].'x'.$size[1].'.webp');
                                        }
                                    }else{
                                        $reginFile->resize($size[0], null, function ($constraint) {
                                            $constraint->aspectRatio();
                                        })->crop($size[0], $size[1])->encode('webp', 75)->save($destinationPath.'/'.$nname.'-'.$size[0].'x'.$size[1].'.webp');
                                    }
                                }else{
                                    $reginFile->resize($size[0], $size[1], function ($constraint) {
                                        $constraint->aspectRatio();
                                    })->encode('webp', 75)->save($destinationPath.'/'.$size_key.'__'.$nname.'-'.$size[0].'x'.$size[1].'.webp');
                                    $reginFile->destroy();
                                }
                            }
                            $imgFiles->encode('webp', 75)->save($destinationPath.'/'.$nname.'.webp');
                            $m_update = Media::where('id', $m->id)->first();
                            $upload_url = 'public/uploads/'. $year . '/' . $month.'/'.$nname.'.webp';
                            $m_update->path = $destinationPath.'/'.$nname.'.webp';
                            $m_update->url = $upload_url; 
                            $m_update->name = $nname;
                            $m_update->type = 'image/webp';
                            $m_update->extension = 'webp';
                            $m_update->save();
                            if( empty($req->id) ){
                                return ['error'=>false, 'message'=>'Regenerated '.($offset+25).'image'];
                            }else{
                                return ['error'=>false, 'message'=>'Regenerated image'];
                            }
                        }catch( Exception $e ){
                            return ['error'=>true, 'message'=>$e->getMessage()];
                        }
                    }
                }
            } 
        }else{
            return ['error'=>true, 'message'=>'No media found to be regenerated or type could\'t be converted.'];
        }
    }
}
