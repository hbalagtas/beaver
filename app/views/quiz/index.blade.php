@extends('layouts.default')
@section('content')

<div class="container">
	Score: {{ Session::get('score') }}


<h4>{{ $question->question }}</h4>

@foreach($options as $option)

		<div class="radio">
          <label>
          	<input type="radio" name="question" class="question" value="{{$option}}" data-qid="{{$question->id}}">{{$option}}
			<br><br>
          </label>
        </div>
@endforeach

<div class="col-xs-6 col-sm-6 col-md-6 col-lg-3">
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
					$('.info').html('<p>Incorrect answer is ' + data.answer + '</p>');
				}

				$('#next').click(function(){
					window.location.reload();
				});
			});
		});	



	});
</script>
@stop