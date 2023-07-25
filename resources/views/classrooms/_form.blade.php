

    <div class="form-floating mb-3">
        <input type="text" value="{{ old('name' , $classroom->name)}}" @class(['form-control' , 'is-invalid' => $errors->has('name')]) class="form-control" name="name" id="name" placeholder="name">
        <label for="name">Name</label>
    </div>

    <div class="form-floating mb-3">
        <input type="text" value="{{ old('section' , $classroom->section)}}" @class(['form-control' , 'is-invalid' => $errors->has('section')]) class="form-control" name="section" id="section" placeholder="section">
        <label for="section">Section</label>
    </div>
    
    
    <div class="form-floating mb-3">
        <input type="text" value="{{ old('subject' , $classroom->subject)}}" @class(['form-control' , 'is-invalid' => $errors->has('subject')]) class="form-control" name="subject" id="subject" placeholder="subject">
        <label for="subject">Subject</label>
    </div>
    
    <div class="form-floating mb-3">
        <input type="text" value="{{ old('room' , $classroom->room)}}" @class(['form-control' , 'is-invalid' => $errors->has('room')]) class="form-control" id="room" name="room" placeholder="room">
        <label for="room">Room</label>
    </div>
    
    <div class="form-floating mb-3">
        @if($classroom->cover_image_path)
        <img src="{{Storage::disk('public')->url($classroom->cover_image_path)}}" alt="">
        @endif
        <input type="file"  @class(['form-control' , 'is-invalid' => $errors->has('cover_image')]) class="form-control" name="cover_image" id="cover_image" placeholder="cover_image">
        <label for="cover_image">Cover Image</label> 
    </div>
    <button type="submit" class="btn btn-success">{{$button_lable}}</button>