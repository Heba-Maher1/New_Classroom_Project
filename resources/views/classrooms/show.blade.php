<x-main-layout title="View">
    <div class="row">
        <x-navbar :area="route('classrooms.show', $classroom->id)" :ass="route('classrooms.classworks.index', $classroom->id)" :people="route('classrooms.people', $classroom->id)" />
    </div>
    <div class="container">
        <x-alert name="success" id="success" class="alert-success mt-3" />

        <img class="card-img-top my-3 position-relative rounded"
            src="{{ asset('storage/' . $classroom->cover_image_path) }}" alt="Card image cap">
        <h1 style="top: 30%; left: 20%;" class="position-absolute text-white">{{ $classroom->name }}
            (#{{ $classroom->id }})</h1>
        <h3 style="top: 40%; left: 20%;" class="position-absolute text-white">{{ $classroom->section }}</h3>
        <div id="textToCopy" class="text-secondary py-3 px-3 border mb-4" style="display: none">{{ $invitation_link }}
        </div>
        <a id="copyBtn" onclick="copyText()" style="top: 50%; left: 20%;cursor:pointer;"
            class="position-absolute text-white"><i class="fa-solid fa-copy me-2"></i>Copy Invitation Link</a>
        <div id="copyNotification" class="text-success" style="display: none;">Text copied!</div>


        <div class="row">
            <div class="col-md-3">
                <div class="border rounded p-3 mb-4 text-center">
                    <span class="text-success fs-3 ">{{ $classroom->code }}</span>
                </div>
                <div class="border rounded p-3 mb-4">
                    <p class="text-secondary font-weight-bold fs-5 ">{{ __('Required Task') }}</p>
                    <p class="text-secondary  ">{{ __('Great, no work needed soon.') }}</p>
                    <h6 class="text-success text-end">{{ __('Show All') }}</p>
                </div>
                <div class="d-flex justify-content-between mb-5">
                    @can('create-topic', ['App\Models\Topic', $classroom])
                        <form action="{{ route('topics.create', $classroom) }}" method="post">
                            @csrf
                            @method('get')
                            <button type="submit" class="btn btn-success">{{ __('Add Topic') }}</button>
                        </form>
                    @endcan
                    <a href="{{ route('classrooms.classworks.index', $classroom->id) }}"
                        class="btn btn-success">{{ __('Classworks') }}</a>
                </div>
            </div>
            <div class="col-md-9">
                <form action="{{ route('posts.store', $classroom->id) }}" method="POST">
                    @csrf
                    <div class="input-group mb-4 shadow bg-white post-collapse" id="post-area" style="border: none">
                        <span class="input-group-text" id="basic-addon1" style="border: none">
                            <img class="me-3" src="/user.png" style="width:2.5rem;" alt="">
                        </span>
                        <input type="text" name="content" class="form-control p-4 post-input"
                            placeholder="{{ __('Write a post...') }}" aria-label="announcement"
                            aria-describedby="basic-addon1">
                        <button type="submit" class="btn btn-success post-button">{{ __('Post') }}</button>
                    </div>
                </form>


                @foreach ($classroom->posts as $post)
                    <li class="list-group-item border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img class="rounded-circle me-3"
                                    src="https://ui-avatars.com/api/?name={{ $post->user->name }}" width="40"
                                    height="40" alt="{{ $post->user->name }}">
                                <div>
                                    <p class="mb-0">{{ $post->content }} {{ __('By') }}
                                        {{ $post->user->name }}</p>
                                    <p class="mb-0 small text-muted">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="border-0 bg-white dropdown-toggle" type="button"
                                    id="postMenu{{ $post->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="postMenu{{ $post->id }}">
                                    <li><a class="dropdown-item" href="#">{{ __('Edit') }}</a></li>
                                    <li>
                                        <form action="{{ route('posts.destroy', $post->id) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="dropdown-item">{{ __('Delete') }}</button>
                                        </form>
                                    </li>
                                </ul>
                                <div class="d-flex mt-4">
                                    <button class="dropdown-item" data-bs-toggle="modal"
                                        data-bs-target="#commentModal{{ $post->id }}"><i
                                            class="fa-solid fa-comment"></i></button>
                                    <button class="dropdown-item" data-bs-toggle="modal"
                                        data-bs-target="#commentModal{{ $post->id }}"><i
                                            class="fa-solid fa-heart"></i></button>
                                </div>

                            </div>
                        </div>

                    </li>

                    <div class="modal fade" id="commentModal{{ $post->id }}" tabindex="-1"
                        aria-labelledby="commentModal{{ $post->id }}Label" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="commentModal{{ $post->id }}Label">Comments</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Existing comments here -->
                                    @foreach ($post->comments as $comment)
                                        <div class="mb-2 border-bottom">
                                            <div class="d-flex align-items-center">
                                                <img class="rounded-circle me-3"
                                                    src="https://ui-avatars.com/api/?name={{ $comment->user->name }}"
                                                    width="40" height="40" alt="{{ $post->user->name }}">
                                                <div>
                                                    <p class="mb-0"><strong>{{ $comment->user->name }}</strong>
                                                        <span class="text-secondary">
                                                            {{ $comment->created_at->diffForHumans(null, true, true) }}</span>
                                                    </p>
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
                                            <button type="button" class="btn bg-green text-white"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn bg-green text-white">Submit
                                                Comment</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach

                @foreach ($streams as $stream)
                    <li class="list-group-item border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img class="rounded-circle me-3"
                                    src="https://ui-avatars.com/api/?name={{ $stream->user->name }}" width="40"
                                    height="40" alt="{{ $stream->user->name }}">
                                <div>
                                    <p class="mb-0">{{ $stream->content }} {{ __('By') }}
                                        {{ $stream->user->name }}</p>
                                    <p class="mb-0 small text-muted">{{ $stream->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        document.getElementById("copyBtn").addEventListener("click", copyText);

        function copyText() {
            const textToCopy = document.getElementById("textToCopy").innerText;

            const tempTextarea = document.createElement("textarea");
            tempTextarea.value = textToCopy;
            document.body.appendChild(tempTextarea);
            tempTextarea.select();

            try {
                const successful = document.execCommand("copy");
                if (successful) {
                    const copyNotification = document.getElementById("copyNotification");
                    copyNotification.style.display = "block";
                    setTimeout(() => {
                        copyNotification.style.display = "none";
                    }, 3000);
                }
            } catch (err) {
                console.error("Oops, unable to copy:", err);
            }
            document.body.removeChild(tempTextarea);
        }
    </script>
</x-main-layout>
