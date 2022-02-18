@extends('layouts.admin.app')
@section('content')
    <h1>Posts</h1>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Question</th>
          <th scope="col">Time</th>
          <th scope="col">Created</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($posts as $post)
            <tr>
              <th scope="row">{{ $post->id }}</th>
              <td>{{ $post->question }}</td>
              <td>{{ $post->time }}</td>
              <td>{{ $post->created_at }}</td>
            </tr>
        @endforeach
      </tbody>
    </table>
@endsection
