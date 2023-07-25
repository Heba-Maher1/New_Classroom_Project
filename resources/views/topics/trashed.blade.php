<x-main-layout title="Topics">
    <div class="container my-5">
        <h1 class="my-5">Trashed Topics</h1>
        <ul class="p-0">
            @foreach ($topics as $topic)
            <div class="card mb-4">
                <div class="card-body">
                    <li class="list-inline-item d-flex align-items-center justify-content-between">
                         {{-- <div class="circle bg-success rounded-circle me-3" style="width:30px;height:30px;"></div>  --}}
                         Classroom {{ $topic->classroom_id}} - {{ $topic->name }}
                        <div class="buttons d-flex ">
                            <form action="{{ route ('topics.restore' , $topic->id)}}" method="post">
                                @csrf
                                @method('put')
                                <button type="submit" class="btn"><i class="fa-solid fa-trash-can-arrow-up"></i></button> 
                              </form>

                            <form action="{{ route ('topics.force-delete' , $topic->id)}}" method="post">
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
</x-main-layout>