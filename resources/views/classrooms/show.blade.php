<x-main-layout title="View">
    <x-navbar :area="route('classrooms.show' , $classroom->id )" :ass="route('classrooms.classworks.index' , $classroom->id)" :people="route('classrooms.people' , $classroom->id)"/>
          <div class="container">
                <x-alert name="success" id="success" class="alert-success" />

                <img class="card-img-top my-3 position-relative rounded" src="{{asset('storage/'.$classroom->cover_image_path) }}" alt="Card image cap">
                <h1 style="top: 260px;left:150px;" class="position-absolute text-white">{{ $classroom->name}} (#{{$classroom->id}})</h1>
                <h3 style="top: 330px;left:150px;" class="position-absolute text-white">{{ $classroom->section}}</h3>
                <div class="row">
                    <div class="col-md-3">
                        <div class="border rounded p-3 mb-4 text-center">
                            <span class="text-success fs-3 ">{{ $classroom->code }}</span>
                            {{-- <p class="w-25"><a href="{{ $invitation_link }}">{{ $invitation_link }}</a></p> --}}
                        </div>
                        <div class="border rounded p-3 mb-4">
                            <p class="text-secondary font-weight-bold fs-5 ">Required Task</p>
                            <p class="text-secondary  ">Great, no work needed soon.</p>
                            <h6 class="text-success text-end">Show All</p>
                        </div>
                        <div class="d-flex justify-content-between mb-5">
                            <form action="{{ route ('topics.create' , $classroom)}}" method="post">
                                @csrf
                                @method('get')
                                <button type="submit" class="btn btn-success">Add Topic</button>
                            </form>
                            <a href="{{route('classrooms.classworks.index' , $classroom->id)}}" class="btn btn-success">Classworks</a>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <form action="{{ route('posts.store' , $classroom->id)}}" method="POST">
                            @csrf
                            <div class="input-group mb-4 shadow bg-white post-collapse" id="post-area" style="border: none">
                                <span class="input-group-text" id="basic-addon1" style="border: none">
                                    <img class="me-3" src="/user.png" style="width:2.5rem;" alt="">
                                </span>
                                <input type="text" name="content" class="form-control p-4 post-input" placeholder="Write a post..." aria-label="announcement" aria-describedby="basic-addon1">
                                <button type="submit" class="btn btn-success post-button">Post</button>
                            </div>
                        </form>

            
                        @foreach ($classroom->posts as $post)
                        <li class="list-group-item border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle me-3" src="https://ui-avatars.com/api/?name={{ $post->user->name }}" width="40" height="40" alt="{{ $post->user->name }}">
                                    <div>
                                        <p class="mb-0">{{ $post->content }} By {{ $post->user->name }}</p>
                                        <p class="mb-0 small text-muted">{{ $post->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="border-0 bg-white dropdown-toggle" type="button" id="postMenu{{ $post->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="postMenu{{ $post->id }}">
                                        <li><a class="dropdown-item" href="#">Edit</a></li>
                                        <li>
                                            <form action="{{ route('posts.destroy', $post->id) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="dropdown-item">Delete</button>
                                            </form>   
                                        </li>     
                                    </ul>
                                    <div class="d-flex mt-4">
                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#commentModal{{ $post->id }}"><i class="fa-solid fa-comment"></i></button>
                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#commentModal{{ $post->id }}"><i class="fa-solid fa-heart"></i></button>
                                    </div> 
                                    
                                </div>
                            </div>  
                            
                        </li>

                        <div class="modal fade" id="commentModal{{ $post->id }}" tabindex="-1" aria-labelledby="commentModal{{ $post->id }}Label" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="commentModal{{ $post->id }}Label">Comments</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Existing comments here -->
                                        @foreach ($post->comments as $comment)
                                            <div class="mb-2 border-bottom">
                                                <div class="d-flex align-items-center">
                                                    <img class="rounded-circle me-3" src="https://ui-avatars.com/api/?name={{ $comment->user->name }}" width="40" height="40" alt="{{ $post->user->name }}">
                                                    <div>
                                                        <p class="mb-0"><strong>{{ $comment->user->name }}</strong> <span class="text-secondary"> {{ $comment->created_at->diffForHumans(null , true, true)}}</span></p>
                                                    <p>{{ $comment->content }}</p>
                                                    </div>
                                                    </div>
                                            </div>
                                        @endforeach
                        
                                        <!-- Add comment form -->
                                        <form action="{{ route('comments.store') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $post->id }}">
                                            <input type="hidden" name="type" value="post">
                                            <textarea name="content" class="form-control" rows="3" placeholder="Add a comment"></textarea>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Submit Comment</button>
                                            </div>
                                        </form>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                            {{-- <div class="mt-3">
                                <form action="{{ route('comments.store') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $post->id }}">
                                    <input type="hidden" name="type" value="post">
                                    <textarea name="content" class="form-control" rows="3" placeholder="Add a comment"></textarea>
                                    <button type="submit" class="btn btn-primary mt-2">Submit Comment</button>
                                </form>
                            </div>
                            <div class="mt-3">
                                @foreach ($post->comments as $comment)
                                    <div class="mb-2">
                                        <strong>{{ $comment->user->name }}:</strong> {{ $comment->content }}
                                    </div>
                                @endforeach
                            </div> --}}
                        
                        @endforeach
                    </div>
                </div>
         </div>
    </x-main-layout>

