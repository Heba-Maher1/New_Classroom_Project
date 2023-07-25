<x-main-layout title="View">
    <div class="container">
        <img class="card-img-top my-3 position-relative rounded" src="{{asset('storage/'.$classroom->cover_image_path) }}" alt="Card image cap">
        <h1 style="top: 260px;left:150px;" class="position-absolute text-white">{{ $classroom->name}} (#{{$classroom->id}})</h1>
        <h3 style="top: 330px;left:150px;" class="position-absolute text-white">{{ $classroom->section}}</h3>
        <div class="row">
            <div class="col-md-3">
                <div class="border rounded p-3 mb-4 text-center">
                    <span class="text-success fs-3 ">{{ $classroom->code }}</span>
                </div>
                <div class="border rounded p-3 mb-4">
                    <p class="text-secondary font-weight-bold fs-5 ">Required Task</p>
                    <p class="text-secondary  ">Great, no work needed soon.</p>
                    <h6 class="text-success text-end">Show All</p>
                </div>
                <form action="{{ route ('topics.create' , $classroom)}}" method="post">
                    @csrf
                    @method('get')
                    <button type="submit" class="btn btn-sm btn-success">Add Topic</button>
                </form>
            </div>
            <div class="col-md-9">
                <div class="input-group mb-4 shadow bg-white" style="border: none">
                    <span class="input-group-text" id="basic-addon1" style="border: none">
                        <img class="me-3" src="/user.png" style="width:2.5rem;" alt="" >
                    </span>
                    <input  type="text" class="form-control p-4" placeholder="class announcement" aria-label="announcement" aria-describedby="basic-addon1" style="border: none">
                  </div>
    
                  
                <ul class="p-0">
                    @foreach ($topics as $topic)
                    <div class="card mb-4">
                        <div class="card-body">
                            <li class="list-inline-item d-flex align-items-center justify-content-between">
                                 {{-- <div class="circle bg-success rounded-circle me-3" style="width:30px;height:30px;"></div>  --}}
                                {{ $topic->name }}
                                <div class="buttons d-flex ">
                                    <a href="{{ route ('topics.edit' , $topic->id)}}" class="btn"><i class="fa-solid fa-pen"></i></a>
                                    <form action="{{ route ('topics.destroy' , $topic->id)}}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn"><i class="fa-solid fa-trash"></i></button> 
                                    </form>
                                </div>
                            </li>
                        </div>
                      </div>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    </div>
    </x-main-layout>
    
    
    