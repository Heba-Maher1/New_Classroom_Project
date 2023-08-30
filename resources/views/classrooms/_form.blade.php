

    {{-- <div class="form-floating mb-3">
        <input type="text" value="{{ old('name' , $classroom->name)}}" @class(['form-control' , 'is-invalid' => $errors->has('name')]) class="form-control" name="name" id="name" placeholder="name">
        <label for="name">Name</label>
    </div> --}}

    <x-form.form-floating name="name" placeholder="{{__('Name')}}">
        <x-form.input name="name" :value="$classroom->name" placeholder="{{__('Name')}}" />
    </x-form.form-floating>

    <x-form.form-floating name="section" placeholder="{{__('Section')}}">
        <x-form.input name="section" :value="$classroom->section" placeholder="Section" />
    </x-form.form-floating>
    
    <x-form.form-floating name="subject" placeholder="{{__('Subject')}}">
        <x-form.input name="subject" :value="$classroom->subject" placeholder="Subject" />
    </x-form.form-floating>
    
    <x-form.form-floating name="room" placeholder="{{__('Room')}}">
        <x-form.input name="room" :value="$classroom->room" placeholder="Room" />
    </x-form.form-floating>

    <div class="form-floating mb-3">
        @if($classroom->cover_image_path)
        <img src="{{Storage::disk('public')->url($classroom->cover_image_path)}}" alt="">
        @endif
        <input type="file"  @class(['form-control' , 'is-invalid' => $errors->has('cover_image')]) class="form-control" name="cover_image" id="cover_image" placeholder="cover_image">
        <label for="cover_image">{{__("classeo")}}</label> 
    </div>
    
    <button type="submit" class="btn btn-success">{{__("$button_lable")}}</button>

