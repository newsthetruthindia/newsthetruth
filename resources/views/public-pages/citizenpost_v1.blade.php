<!doctype html>
<html class="no-js" data-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Submit Journalism - {{env('MAIL_FROM_NAME')}}</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    @include('layouts.stylesheets_v1')
</head>

<body>
    @include('layouts.header_v1')

    <div class="space">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">
                    <div class="premium-card p-5 glass-header">
                        <div class="text-center mb-5">
                            <h2 class="sec-title mb-2">Publish Your Journalism</h2>
                            <p class="text-muted">Empower the truth. Share your story with the world.</p>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('save-citizen-journalism') }}" method="POST" enctype="multipart/form-data"
                            class="journalism-form">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ !empty($data->id) ? $data->id : '' }}">

                            <div class="row gy-4">
                                {{-- Title --}}
                                <div class="col-12">
                                    <label class="form-label fw-bold">Headline</label>
                                    <input type="text" name="title" class="form-control"
                                        placeholder="What's the main story?"
                                        value="{{ !empty($data->title) ? $data->title : old('title') }}" required>
                                    @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                {{-- Subtitle --}}
                                <div class="col-12">
                                    <label class="form-label fw-bold">Subtitle (Optional)</label>
                                    <input type="text" name="subtitle" class="form-control"
                                        placeholder="Add a catchy sub-headline"
                                        value="{{ !empty($data->subtitle) ? $data->subtitle : old('subtitle') }}">
                                </div>

                                {{-- Place & Date --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Location</label>
                                    <input type="text" name="place" class="form-control" placeholder="City, State"
                                        value="{{ !empty($data->place) ? $data->place : old('place') }}" required>
                                    @error('place') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Date & Time</label>
                                    <input type="datetime-local" name="datetime" class="form-control"
                                        value="{{ !empty($data->datetime) ? date('Y-m-d\TH:i', strtotime($data->datetime)) : old('datetime', date('Y-m-d\TH:i')) }}">
                                </div>

                                {{-- Description --}}
                                <div class="col-12">
                                    <label class="form-label fw-bold">The Story</label>
                                    <textarea name="description" id="story_editor" class="form-control" rows="8"
                                        placeholder="Tell us what happened in detail..."
                                        required>{{ !empty($data->description) ? $data->description : old('description') }}</textarea>
                                    @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                {{-- Media Upload --}}
                                <div class="col-12">
                                    <label class="form-label fw-bold text-primary"><i
                                            class="fas fa-camera-retro me-2"></i>Attached Media</label>
                                    <div class="upload-area p-4 text-center border-dashed rounded-3">
                                        <input type="file" name="attachment_file" class="form-control" id="mediaUpload">
                                        <p class="text-muted small mt-2">Upload images or videos (Max 50MB)</p>
                                        @if(!empty($data->attachment_url))
                                            <div class="mt-2 small">Current: <a href="{{ $data->attachment_url }}"
                                                    target="_blank">View File</a></div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Credit --}}
                                <div class="col-12">
                                    <hr class="my-4">
                                    <label class="form-label fw-bold">Reporting Credit</label>
                                    <input type="text" name="credit" class="form-control"
                                        placeholder="Your Name or Organization"
                                        value="{{ !empty($data->credit) ? $data->credit : old('credit') }}">
                                </div>

                                {{-- Submit --}}
                                <div class="col-12 text-center mt-5">
                                    <button type="submit" class="th-btn style3 py-3 px-5">Submit for Review <i
                                            class="fas fa-paper-plane ms-2"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .border-dashed {
            border: 2px dashed #ddd;
            transition: var(--ntt-transition);
        }

        .border-dashed:hover {
            border-color: var(--ntt-primary);
            background: rgba(140, 0, 0, 0.02);
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border-color: #eee;
        }

        .form-control:focus {
            border-color: var(--ntt-primary-light);
            box-shadow: 0 0 0 0.25rem rgba(140, 0, 0, 0.1);
        }
    </style>

    @include('layouts.footer_v1')
    @include('layouts.scripts_v1')
</body>

</html>