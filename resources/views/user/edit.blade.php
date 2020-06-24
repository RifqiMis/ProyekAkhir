@extends('layouts.global')

@section('title')
   Edit user
@endsection

@section('content')
    <div class="container">
        <h3 class="text-center">Edit user</h3>
    </div>
    <hr>
    <br>
    @include('components.notifikasi')
    {{-- isi konten --}}
        <div class="container mt-3">
            <form action="{{route('user.update',[$user->id])}}" method="POST">
                {{ method_field('PUT') }}
                @csrf
                <div class="form-group row mr-auto ml-auto">
                    <label class="col-1 col-form-label">Nama</label>
                    <div class="col-5">
                        <input type="text" class="form-control" name="name" placeholder="user" value="{{$user->name}}">
                    </div>
                    <label class="col-1 col-form-label">Role</label>
                    <div class="col-5">
                        <input type="text" class="form-control" name="role" value="{{$user->role}}" disabled>
                    </div>
                </div>
                <div class="form-group row mr-auto ml-auto">
                    <label class="col-1 col-form-label">Password</label>
                    <div class="col-5">
                        <input type="password" class="form-control" name="password" id="password" placeholder="password">
                        <small class="form-text text-muted">Kosongi jika tidak ingin mengganti password</small>
                    </div>
                    <label class="col-1 col-form-label">Email</label>
                    <div class="col-5">
                        <input type="email" class="form-control" name="email" value="{{$user->email}}">
                    </div>
                </div>
                <div class="form-group row mb-4 mr-auto ml-auto">
                    <label class="col-1 col-form-label"></label>
                    <div class="col-5">
                        <input type="password" class="form-control" name="retype_password" id="passwordConfirm" placeholder="Konfirmasi password">
                        <span id='message'></span>
                    </div>
                </div>
                <div class="form-group row mr-auto ml-auto">
                    <label class="col-1 col-form-label"></label>
                    <div class="col-11">
                        <button type="submit" id="submit" class="btn btn-sm btn-primary mb-2">Simpan</button>
                        <a href="{{route('user.index')}}" class="btn btn-sm btn-danger mb-2">kembali</a>
                    </div>
                </div>
            </form>
        </div>
    <br>
    <script type="application/javascript">

        $('#password').on('keyup', function() {
        if ($('#password').val() == $('#passwordConfirm').val()) {
            $('#message').html('Matching').css('color', 'green');
            $('#submit').prop('disabled', false);
        } else {
            $('#message').html('Not Matching').css('color', 'red');
            $('#submit').prop('disabled', true);
        }
        
        });

        $('#passwordConfirm').on('keyup', function() {
        if ($('#password').val() == $('#passwordConfirm').val()) {
            $('#message').html('Matching').css('color', 'green');
            $('#submit').prop('disabled', false);
        } else {
            $('#message').html('Not Matching').css('color', 'red');
            $('#submit').prop('disabled', true);
        }
        });
    </script>
@endsection

