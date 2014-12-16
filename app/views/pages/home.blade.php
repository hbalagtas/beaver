@extends('layouts.default')

@section('content')
	<h1>Welcome to Beaver</h1>
	{{ var_dump( Auth::user() ) }}
@stop