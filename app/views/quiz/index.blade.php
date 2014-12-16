@extends('layouts.default')
@section('page_title', 'Quiz')
@section('content')

<div class="container">
	Correct Answers: {{ Session::get('score') }} Incorrect Answers: {{ Session::get('mistakes') }}


<h4>{{ $question->question }}</h4>

@foreach($options as $option)

		<div class="radio">
          <label>
          	<input type="radio" name="question" class="question" value="{{$option}}" data-qid="{{$question->id}}">{{$option}}
			<br><br>
          </label>
        </div>
@endforeach

<div class="">
	<div class="info hide">
		
	</div>	
</div>


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
				$('.info').removeClass('hide');
				if ( data.correct ){
					$('.info').html('<input class="btn btn-primary" id="next" type="button" value="Correct! Next question">');

				} else {
					$('.info').html('<p>Incorrect! ' + data.answer + '</p> <input class="btn btn-primary" id="next" type="button" value="Next question">');
					$('.question').attr('disabled', true);
				}

				$('#next').click(function(){
					window.location.reload();
				});
			});
		});	



	});
</script>
@stop