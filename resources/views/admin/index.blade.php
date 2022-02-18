@extends('layouts.admin.app')
@section('content')
<h1>Statistic</h1>

<table class="table">
  <thead>
    <tr>
      <th scope="col">No. Users</th>
      <th scope="col">No. Posts</th>
    </tr>
  </thead>
  <tbody>
       <td scope="row">
            {{ $statistics['users'] }}
       </td>
       <td>
            {{ $statistics['posts'] }}
       </td>
  </tbody>
</table>
@endsection
