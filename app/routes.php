<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'PagesController@home');
//
Route::get('flush', function(){
	Session::flush();
});

Route::get('quiz', function(){

	if ( !Session::has('previous_questions') ){
		Session::set('previous_questions', [0]);
	}

	Log::info(Session::get('previous_questions'));
	#dd(Session::get('previous_questions'));
	$question = Question::whereNotIn('id', Session::get('previous_questions') )->random()->take(1)->first();
	Log::info($question->id);
	
	$options = [$question->answer, $question->option1, $question->option2, $question->option3 ];
	shuffle($options);

	if ( !Session::has('score') ){
		Session::set('score', 0);
	}
	if ( !Session::has('mistakes') ){
		Session::set('mistakes', 0);
	}

	if ( !Session::has('total_questions') ){
		Session::set('total_questions', 1);
	} else {
		Session::set('total_questions', Session::get('total_questions') + 1);
	}

	if (Session::get('total_questions') > 25 ){
		return View::make('quiz.results');
	}

	return View::make('quiz.index', compact('question', 'options'));
});

Route::post('checkanswer', function(){
	$qid = Input::get('qid');
	$q = Question::find($qid);

	if ( Session::has('previous_questions') ){
		$previous_questions = Session::get('previous_questions');
		$previous_questions[] = $q->id;
		Session::set('previous_questions', $previous_questions);
	}

	if ( Session::has('score') ){
		$score = Session::get('score');
	} else {
		Session::set('score', 0);
		$score = 0;
	}

	if ( Session::has('mistakes') ){
		$mistakes = Session::get('mistakes');
	} else {
		Session::set('mistakes', 0);
		$mistakes = 0;
	}
	
	if (Input::get('answer') == $q->answer){
		$correct = true;
		$score++;
		Session::set('score', $score);
	} else {
		$correct = false;
		$mistakes++;
		Session::set('mistakes', $mistakes);
	}

	$payload = [ 'qid'=> $qid, 'correct' => $correct, 'answer' => $q->answer ];
	return Response::json($payload);
});

Route::get('importer', function(){

	$html = new Htmldom('http://www.apnatoronto.com/canadian-citizenship-test10/');
	#$data = file_get_contents('/tmp/index.html');
	#$html = new Htmldom($data);
	

	for ( $x=1; $x<=25; $x++){
		$qid = '#mtq_question_text-'.$x.'-1';
		
		foreach ( $html->find($qid) as $question ){
			$question_text = $question->plaintext;
			#echo $question->plaintext;
			$sibling = $question->next_sibling();

			$answers = $sibling->find('.mtq_correct_marker');
			foreach($answers as $answer){
				$answer = str_replace('marker', 'answer_text', '#'.$answer->id);
				#echo $answer;
			}

			// find the options
			$q_options = array();
			for($y=1; $y<=4; $y++){
				$id = '#mtq_answer_text-'.$x.'-'.$y.'-1';
				$options = $html->find($id);
				
				foreach($options as $option){
					if ( $id == $answer ) {
						$answer = $option->plaintext;
					} else {
						$q_options[] = $option->plaintext;	
					}
					#echo "$id " .$option->plaintext . "<br>";
				}
			}
		}		
		
		$q['question'] = $question_text;
		$q['answer'] = $answer;
		for($o=1; $o<=3; $o++){
			$q['option'.$o] = $q_options[$o-1];
		}
		
		if ( Question::whereQuestion($question_text)->count() === 0 ){
			echo "Creating question: " . $question_text . "<br>";
			Question::create($q);
		}
	}
	
});

// Confide routes
Route::get('users/create', 'UsersController@create');
Route::post('users', 'UsersController@store');
Route::get('users/login', 'UsersController@login');
Route::post('users/login', 'UsersController@doLogin');
Route::get('users/confirm/{code}', 'UsersController@confirm');
Route::get('users/forgot_password', 'UsersController@forgotPassword');
Route::post('users/forgot_password', 'UsersController@doForgotPassword');
Route::get('users/reset_password/{token}', 'UsersController@resetPassword');
Route::post('users/reset_password', 'UsersController@doResetPassword');
Route::get('users/logout', 'UsersController@logout');
