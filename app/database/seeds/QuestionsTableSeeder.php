<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class QuestionsTableSeeder extends Seeder {

	public function run()
	{
		$questions_file = storage_path() . '/private/questions.csv';
		$csv = new parseCSV($questions_file);

		foreach($csv->data as $row){
			if ( Question::whereQuestion($row['question'])->count() === 0 ){
				Question::create($row);	
			}			
		}
		
	}

}