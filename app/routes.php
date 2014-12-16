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
Route::get('quiz', function(){
	$question = Question::random()->take(1)->first();
	$options = [$question->answer, $question->option1, $question->option2, $question->option3 ];
	shuffle($options);
	return View::make('quiz.index', compact('question', 'options'));
});

Route::get('importer', function(){

	#$html = new Htmldom('http://www.apnatoronto.com/canadian-citizenship-test2/');
	$data = file_get_contents('/tmp/index.html');
	$html = new Htmldom($data);
	

	for ( $x=1; $x<=25; $x++){
		$qid = '#mtq_question_text-'.$x.'-1';
		
		foreach ( $html->find($qid) as $question ){
			echo $question->plaintext;
			$sibling = $question->next_sibling();

			$answers = $html->find('.mtq_correct_marker');
			foreach($answers as $answer){
				echo $answer->id;
			}

			// find the options
			for($y=1; $y<=4; $y++){
				$id = '#mtq_answer_text-'.$x.'-'.$y.'-1';
				$options = $html->find($id);
				foreach($options as $option){
					echo "$id " .$option->plaintext . "<br>";
				}
			}
		}		
		die;
		foreach($questions as $question){
			echo $question->plaintext;
			$sibling = $question->next_sibling();
			$answer = $sibling->find('.mtq_marker .mtq_correct_marker');
		}
		for($y=1; $y<=4; $y++){
			$id = '#mtq_answer_text-'.$x.'-'.$y.'-1';
			$options = $html->find($id);
			foreach($options as $option){
				echo "$id " .$option->plaintext . "<br>";
			}
		}

		
		echo $answer->id;
		die;
	}
	die;
	$questions = $html->find('.mtq_question_text');
	foreach($questions as $question){
		echo $question->id; //mtq_question_text-5-1
		$qid = $question->id;
		$answer = str_replace('question', 'answer', $qid);
		$answer = str_replace('-1', '*', $answer);
		echo $question->plaintext . '<br />';
		$sibling = $question->next_sibling();
		$options = $sibling->find('.mtq_answer_text');
		foreach($options as $option){
			echo $option->plaintext;
		}
		die();
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
