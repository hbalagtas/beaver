@extends('layouts.default')
@section('page_title', 'Quiz')

@section('head')
<style>
	.passed {
		color: green;
	}
	.failed {
		color: red;
	}
</style>
@stop

@section('content')
<div class="container">
	Correct Answers: {{ Session::get('score') }} Incorrect Answers: {{ Session::get('mistakes') }}

	<?php 
	$pct = 0;
	$mistakes = (int)Session::get('mistakes');
	$score = (int)Session::get('score');
	
	if ( $mistakes != 0 ){
		$pct = round( ($score / ($mistakes + $score)) * 100, 0);
	} else {
		$pct = 100;		
	}
	
	if ($pct >= 75) {
		$status = '<p class="passed">'. $pct .'% PASSED</i>';
	} else {
		$status = '<p class="failed">'. $pct . '% FAILED</i>';
	}
	?>

	<h1>{{$status}}</h1>

	<?php Session::flush(); ?>

	<a href="quiz" class="btn btn-primary">Start Over</a>
@stop