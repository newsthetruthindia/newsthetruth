<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CitizenJournalism;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Validator;

class CitizenController extends Controller
{

    public function list(Request $req)
    {
        if (empty(Auth::user()->google2fa_secret)) {
            return redirect('unauthenticated');
        }
        if (!empty(Auth::user()->details->role)) {
            if (!Auth::user()->details->role->create_post) {
                return redirect('401');
            }
            $posts = CitizenJournalism::where('ignored_by', null)->where('accept_by', null)->orderBy('id', 'DESC')->paginate(10);
            return view('Posts.journalisms')->with(['posts' => $posts]);
        } else {
            return redirect('401');
        }
    }
    public function publicList(Request $req)
    {
        $posts = CitizenJournalism::orderBy('id', 'DESC')->paginate(10);
        return view('public-pages.citizenlist')->with(['posts' => $posts, 'body_classes' => 'home page-template-default page no-sidebar']);
    }

    public function add(Request $req)
    {
        return view('public-pages.citizenpost_v1')->with(['body_classes' => 'home page-template-default page no-sidebar']);
    }

    public function edit(Request $req, CitizenJournalism $post)
    {
        $data = $post;
        return view('public-pages.citizenpost_v1')->with(['data' => $data, 'body_classes' => 'home page-template-default page no-sidebar']);
    }

    public function save(Request $req)
    {
        Validator::make($req->all(), [
            'title' => 'required|string|max:255',
            'datetime' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'place' => 'required|string|max:255',
            'description' => 'required',
            'attachment_file' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi,mkv|max:51200', // 50MB
        ])->validate();

        $id = $req->post_id;
        $post = CitizenJournalism::where('id', $id)->first();
        if (empty($post)) {
            $post = new CitizenJournalism();
            $post->user_id = Auth::user()->id;
        }

        // Handle File Upload
        if ($req->hasFile('attachment_file')) {
            $file = $req->file('attachment_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = 'public/uploads/citizen';

            // Ensure directory exists
            if (!file_exists(base_path($path))) {
                mkdir(base_path($path), 0755, true);
            }

            $file->move(base_path($path), $filename);
            $post->attachment_url = url($path . '/' . $filename);
        }

        $post->title = $req->title;
        $post->datetime = !empty($req->datetime) ? date('Y-m-d H:i:s', strtotime($req->datetime)) : date('Y-m-d H:i:s');
        $post->subtitle = $req->subtitle;
        $post->description = $req->description;
        $post->place = $req->place;
        $post->credit = $req->credit;

        try {
            $post->save();
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to save: ' . $e->getMessage()]);
        }

        $notification = new NotificationController();
        $notification->description('A citizen journalism post has been submitted/updated: ' . $post->title);
        $notification->type('users');
        $notification->send();

        return redirect()->to(route('add-citizen-journalism'))->with('success', 'Your story has been submitted successfully!');
    }

    public function delete(Request $req, CitizenJournalism $post)
    {
        $post->delete();
        return redirect()->to(route('list-citizen-journalism'));
    }
    public function makePost(Request $req, CitizenJournalism $post)
    {
        if (!empty(Auth::user()->details->role)) {
            if (!Auth::user()->details->role->create_post) {
                return redirect('401');
            }
            $post->accept_by = Auth::user()->id;
            $post->posted = true;
            $post->save();
            $ppost = new Post();
            $ppost->title = $post->title;
            $ppost->user_id = Auth::user()->id;
            $ppost->slug = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($post->title)), '-');
            $ppost->subtitle = $post->subtitle;
            $ppost->description = $post->description;
            $ppost->excerpt = implode(' ', array_slice(explode(' ', strip_tags($post->description)), 0, 100));
            $ppost->save();
            if (!empty($ppost->id)) {
                return redirect()->to(route('post-edit', ['post' => $ppost->id]));
            } else {
                return view('Posts.citizenpost')->with(['data' => $post]);
            }
        } else {
            return redirect('401');
        }
    }
    public function view(Request $req, CitizenJournalism $post)
    {
        if (!empty(Auth::user()->details->role)) {
            if (!Auth::user()->details->role->view_post_list) {
                return redirect('401');
            }
            return redirect()->to('admin/post/list');
            return view('posts.citizenpost')->with(['data' => $post]);
        } else {
            return redirect('401');
        }
    }
    public function ignore(Request $req, CitizenJournalism $post)
    {
        if (!empty(Auth::user()->details->role)) {
            if (!Auth::user()->details->role->create_post) {
                return redirect('401');
            }
            $post->ignored_by = Auth::user()->id;
            $post->save();
            return redirect()->to(route('lists-citizen-journalism'));
        } else {
            return redirect('401');
        }
    }
}
