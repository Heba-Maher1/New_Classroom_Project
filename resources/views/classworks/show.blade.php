<x-main-layout title="Show">
    <div class="container pt-5">
        
      <x-alert name="success" class="alert-success" />
      <x-alert name="error" class="alert-danger" />

        <div class="row">
            <div class="col-lg-8">
                <div class="header d-flex">
                    <div class="content w-100">
                        <h2><span class="text-success">{{$classwork->type}}</span> / <span class="fs-4">{{$classwork->title}}</span> </h2>
                        <p class="text-secondary">{{auth()->user()->name}} &#8226; {{$classwork->updated_at->format('d M')}} (Time of last modification: )</p>
                        @if ($classwork->type == 'assignment')<p style="font-weight:bold">{{$classwork->options['grade']}} mark</p>@endif
                        <hr class="text-success">
                        <p class="description">{!! $classwork->description !!}</p>
                        <hr class="text-success">
                    </div>
                </div>

                <h6 class="text-success text-decoration-none">Add a comment</h6>
                <form action="{{route('comments.store')}}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{$classwork->id}}">
                    <input type="hidden" name="type" value="classwork">
                    <x-form.form-floating name="content" placeholder="Content">
                        <x-form.textarea name="content" placeholder="Content"></x-form.textarea>
                    </x-form.form-floating>
                    <button type="submit" class="btn btn-success mt-3">Comment</button>
                </form>
            </div>

            <div class="col-lg-4">
                @can('submissions.create' , [$classwork])
                    <div class="bordered-rounded p-3 bg-light">
                        <h4>Submissions</h4> 
                        @if($submissions->count())
                        <ul>
                            @foreach ($submissions as $submission)
                                <li><a href="{{ route('submissions.file', $submission->id)}}">File #{{ $loop->iteration }}</a></li>
                            @endforeach
                        </ul>
                        @else
                        <form action="{{ route('submissions.store' , $classwork->id)}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <x-form.form-floating name="files.0" placeholder="Upload Files">
                                <x-form.input type="file" name="files[]" multiple accept="image/*" placeholder="Select Files" />
                            </x-form.form-floating>
                            <button type="submit" class="btn bg-green text-white">Submit</button>    
                            
                        </form>
                        @endif
                    </div>
                    @endcan    
                    <div class="container text-dark">
                        <h3 class="mb-4">Comments</h3>
                        @foreach ($classwork->comments as $comment)
                        <div class="card mb-3">
                            <div class="card-body d-flex">
                                <img class="rounded-circle shadow-1-strong me-3" src="https://ui-avatars.com/api/?name={{ $comment->user->name }}" alt="avatar" width="40" height="40" />
                                <div class="w-100">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="text-primary fw-bold mb-0">
                                            {{ $comment->user->name }}
                                        </h6>
                                        <p class="mb-0">{{ $comment->created_at->diffForHumans(null , true, true)}}</p>
                                    </div>
                                    <p>{{ $comment->content }}</p>
                                    <div class="d-flex justify-content-between align-items-end">
                                        <p class="small mb-0" style="color: #aaa;">
                                            <a href="#!" class="link-grey">Remove</a> •
                                            <a href="#!" class="link-grey">Reply</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                
            </div>
            
              
        </div>
    </div>
</x-main-layout>

<style>
    .text-decoration-none {
        text-decoration: none !important;
    }

    .description {
        line-height: 1.6;
    }

    .comments {
        max-height: 600px;
        overflow-y: auto;
    }

    .comment {
        background-color: #f5f5f5;
    }
</style>


                {{-- <div class="border rounded p-4">
                    <div class="mt-4">
                        <h6 class="text-success text-decoration-none">Comments</h6>
                        <div class="comments">
                            @foreach ($classwork->comments as $comment)
                            <div class="comment border p-3 mb-3 rounded">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="https://ui-avatars.com/api/?name={{ $comment->user->name }}" width="40" height="40" alt="{{ $comment->user->name }}">
                                    <div class="ml-3">
                                        <p class="mb-0">{{ $comment->content }}</p>
                                        <small class="text-muted">By: {{ $comment->user->name }} &#8226; {{$comment->created_at->diffForHumans(null , true, true)}}</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div> --}}