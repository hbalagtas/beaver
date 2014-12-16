@extends('layouts.default')
@section('content')

<h1>{{ $question->question }}</h1>
@foreach($options as $option)
<input type="radio" value=""> {{ $option}} <br />
@endforeach
@stop