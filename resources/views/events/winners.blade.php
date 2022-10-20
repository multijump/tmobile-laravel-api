@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12 intro-text-list">
      <h1>Winners for event: {{ $event->title }}</h1>
    </div>
  </div>
  @if($winnerCount != 0)
  <div class="row justify-content-center">
    <div class="col-md-12">
      @if($winnerCount == 1)
        <h1>New Winner</h1>
      @else
        <h1>{{ $winnerCount }} New Winners</h1>
      @endif
    </div>
  </div>
  <div class="row justify-content-center table-row">
    <div class="col-md-12">
      <table class="table table-bordered dataTable no-footer dtr-inline" style="width: 100%">
        <thead>
          <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Time Picked</th>
          <tr>
        </thead>
        <tbody>
        </tbody>
        @foreach ($winners as $winner)
          @if($loop->index < $winnerCount)
          <tr>
            <td>{{$winner["participant_first_name"]}}</td>
            <td>{{$winner["participant_last_name"]}}</td>
            <td>{{$winner["participant_email"]}}</td>
            <td>{{$winner["participant_phone"]}}</td>
            <td>{{$winner["participant_selected_date"]}} {{$winner["participant_selected_time"]}}</td>
          </tr>
          @endif
        @endforeach
      </table>
    </div>
  </div>
</div>
@endif
@if($winnerCount < count($winners))
<div class="row justify-content-center">
  <div class="col-md-12">
    <h1>Previous Winners</h1>
  </div>
</div>
<div class="row justify-content-center table-row">
  <div class="col-md-12">
    <table class="table table-bordered dataTable no-footer dtr-inline" style="width: 100%">
      <thead>
        <tr>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Time Picked</th>
        <tr>
      </thead>
      <tbody>
      </tbody>
      @foreach ($winners as $winner)
        @if($loop->index >= $winnerCount)
        <tr>
          <td>{{$winner["participant_first_name"]}}</td>
          <td>{{$winner["participant_last_name"]}}</td>
          <td>{{$winner["participant_email"]}}</td>
          <td>{{$winner["participant_phone"]}}</td>
          <td>{{$winner["participant_selected_date"]}} {{$winner["participant_selected_time"]}}</td>
        </tr>
        @endif
      @endforeach
    </table>
  </div>
</div>
@endif
@endsection