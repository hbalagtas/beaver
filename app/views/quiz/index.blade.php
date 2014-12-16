@extends('layouts.default')
@section('content')

<h1>{{ $question->id}} {{ $question->question }}</h1>
@foreach($options as $option)
<input type="radio" name="question" class="question" value="{{$option}}" data-qid="{{$question->id}}"> {{ $option}} <br />
@endforeach


<div class="alert alert-info hide">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<strong>Title!</strong> Alert body ...
</div>
@stop

@section('script')
<script>
	$(document).ready(function(){
		$('.question').change(function(){
			var _answer = $(this).val();
			console.log($(this).val());
			var _qid = $(this).data('qid');
			console.log($(this).data('qid'));
			$.post( "checkanswer", { qid: _qid, answer: _answer} ).done( function(data){
				$('.alert').removeClass('hide');
				if ( data.correct ){
					$('.alert').html('correct');
				} else {
					$('.alert').html('incorrect answer is ' + data.answer);
				}
			});
		});	


	});
</script>
@stop