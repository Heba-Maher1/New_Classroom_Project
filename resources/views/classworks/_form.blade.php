<x-alert name="success" class="alert-success" />
<x-alert name="error" class="alert-danger" />

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
</div>
@endif


<div class="row">
    <div class="col-md-8">        
          <x-form.form-floating name="title" placeholder="Title">
            <x-form.input name="title" placeholder="Title" :value="$classwork->title" />
          </x-form.form-floating>

          <x-form.form-floating name="description" placeholder="Description">
              <x-form.textarea name="description" placeholder="Description" :value="$classwork->description" />
          </x-form.form-floating>
    </div>
    <div class="col-md-4">
        <x-form.form-floating name="published_at" placeholder="published_at">
            <x-form.input type="date" name="published_at" :value="$classwork->published_date" placeholder="published_at" />
        </x-form.form-floating>

        <div class="mb-3">
          @foreach($classroom->students as $student)
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="students[]" value="{{ $student->id }}" id="std-{{ $student->id }}" @checked(!isset($assigned) || in_array( $student->id , $assigned))>
            <label class="form-check-label" for="std-{{ $student->id }}">
              {{ $student->name }}
            </label>
          </div>  
          @endforeach
        </div>

        @if($type == 'assignment')
            <x-form.form-floating name="options.grade" placeholder="Grade">
                <x-form.input type="number" name="options[grade]" :value="$classwork->options['grade'] ?? ''" placeholder="grade" min="0"  />
            </x-form.form-floating>
            <x-form.form-floating name="options.due" placeholder="Due Date">
                <x-form.input type="date" name="options[due]" :value="$classwork->options['due'] ?? ''"   placeholder="Due Date" />
            </x-form.form-floating>
        @endif

          <x-form.form-floating name="topic_id" placeholder="Topic Id">
            <select class="form-select" name="topic_id" id="topic_id" >
              <option value="">No Topic</option>
              @foreach($classroom->topics as $topic)
              <option @selected($topic->id == $classwork->topic_id) value="{{ $topic->id }}">{{ $topic->name }}</option>
              @endforeach
            </select>
          </x-form.form-floating>
      </div>  
</div>  
