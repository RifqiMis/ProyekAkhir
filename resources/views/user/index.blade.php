@extends('layouts.global')
{{-- @extends('components.notifikasi') --}}

@section('title')
    User
@endsection

@section('content')
    <div class="container">
        <div class="mb-5">
            <h3 class="text-center">User</h3>
        </div>

        @include('components.notifikasi')

        {{-- isi konten --}}
        <div class="container">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">User</th>
                    <th scope="col">Email</th>
                    <th scope="col">Pilihan</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($users as $iteration => $user)
                    <tr>
                        <td>{{$iteration + $users->firstItem()}}</td>
                        <td>{{$user->name}} </td>
                        <td>{{$user->email}} </td>
                        <td>
                            <form action="{{url("user/{$user->id}")}}" method="post">
                                <a href="{{url("user/{$user->id}/edit")}}" class="btn btn-outline-secondary btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                {{ csrf_field() }}
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $users->links() }}
        </div>
    </div>
@endsection

