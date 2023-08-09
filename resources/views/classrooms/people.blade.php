<x-main-layout title="People">
    <x-navbar :area="route('classrooms.show', $classroom->id)"
              :ass="route('classrooms.classworks.index', $classroom->id)"
              :people="route('classrooms.people', $classroom->id)"
    />

    <div class="container mt-5">
        <x-alert name="success" class="alert-success"/>
        <x-alert name="error" class="alert-danger"/>

        <div class="people-section">
            <h2 class="section-title">Teachers</h2>
            <ul class="people-list">
                @foreach ($classroom->users()->where('role', 'teacher')->orderBy('name')->get() as $user)
                <li class="person-item">
                    <div class="person-avatar">
                        <img src="https://ui-avatars.com/api/?name={{ $user->name }}" alt="{{ $user->name }}">
                    </div>
                    <div class="person-info">
                        <p class="person-name m-0">{{ $user->name }}</p>
                    </div>
                    <div class="options">
                        <a class="options-icon" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v" style="font-size: 15px" ></i>
                        </a>
                        <div class="dropdown-menu">
                            <form action="{{ route('classrooms.people.destroy', $classroom->id) }}" method="post">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <button type="submit" class="dropdown-item"><i class="fas fa-trash"></i> Remove</button>
                            </form>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="people-section">
            <h2 class="section-title">Students</h2>
            <ul class="people-list">
                @foreach ($classroom->users()->where('role', 'student')->orderBy('name')->get() as $user)
                <li class="person-item">
                    <div class="person-avatar">
                        <img src="https://ui-avatars.com/api/?name={{ $user->name }}" alt="{{ $user->name }}">
                    </div>
                    <div class="person-info">
                        <p class="person-name m-0">{{ $user->name }}</p>
                    </div>
                    <div class="options">
                        <a class="options-icon" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v" style="font-size: 15px"></i>
                        </a>
                        <div class="dropdown-menu">
                            <form action="{{ route('classrooms.people.destroy', $classroom->id) }}" method="post">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <button type="submit" class="dropdown-item"><i class="fas fa-trash"></i> Remove</button>
                            </form>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-main-layout>

<style>
    .people-section {
        margin-bottom: 3rem;
    }

    .section-title {
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .people-list {
        list-style: none;
        padding: 0;
    }

    .person-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        /* border: 1px solid #e1e1e1;
        border-radius: 10px; */
        border-bottom: 1px solid #e0e0e0;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
    }

    .person-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .person-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 1rem;
    }

    .person-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .person-info {
        flex: 1;
    }

    .options {
        position: relative;
        display: inline-block;
    }

    .options-icon {
        color: #777;
        font-size: 1.5rem;
    }

    .options-icon:hover {
        color: #333;
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 10;
    }
</style>
