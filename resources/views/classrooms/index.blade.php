<x-main-layout title="{{ __('Classrooms') }}">
    <div class="container">

        <x-alert name="success" class="alert-success mt-4" />
        <x-alert name="error" class="alert-danger" />

        <ul id="classrooms">
        </ul>

        <div class="row">
            @foreach ($classrooms as $classroom)
                <div class="col-md-3">
                    <x-card :image="$classroom->cover_image_path" :name="$classroom->name" :section="$classroom->section" :room="$classroom->room" :classroom="$classroom"
                        :id="$classroom->id" />
                </div>
            @endforeach
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script>
            axios.get('/api/V1/classrooms')
                .then(function(response) {
                    let ul = document.getElementById('classrooms');
                    for (let i in response.data) {
                        ul.innerHTML += `<li>${response.data[i].name}</li>`;
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                });
        </script>
    @endpush
</x-main-layout>
