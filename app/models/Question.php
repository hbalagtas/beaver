<?php

class Question extends \Eloquent {
	protected $fillable = ['question', 'answer', 'option1', 'option2', 'option3'];

	public function scopeRandom($query)
	{
	    return $query->orderBy(DB::raw('RAND()'));
	}
}