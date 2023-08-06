<x-main-layout title="people">
    <x-navbar :area="route('classrooms.show', $classroom->id)"
              :ass="route('classrooms.classworks.index', $classroom->id)"
              :people="route('classrooms.people', $classroom->id)"
    />

    <div class="container mt-5">
        {{-- <h1 class="mb-4">CLASSROOM: {{ $classroom->name }}</h1> --}}
        <x-alert name="success" class="alert-success"/>
        <x-alert name="error" class="alert-danger"/>

        <h2>Teachers</h2>
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($classroom->users()->where('role', 'teacher')->orderBy('name')->get() as $user)
                <tr>
                    <td></td>
                    <td>{{ $user->name }}</td>
                    <td>
                        <form action="{{ route('classrooms.people.destroy', $classroom->id) }}" method="post">
                            @csrf
                            @method('delete')
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                        </form> 
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h2>Students</h2>
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($classroom->users()->where('role', 'student')->orderBy('name')->get() as $user)
                <tr>
                    <td></td>
                    <td>{{ $user->name }}</td>
                    <td>
                        <form action="{{ route('classrooms.people.destroy', $classroom->id) }}" method="post">
                            @csrf
                            @method('delete')
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                        </form> 
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</x-main-layout>
