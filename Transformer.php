<?php
namespace App\Transformers;
abstract class Transformer {

	/**
	 * Creates the application.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
	/**
	 * Transform a school
	 *
	 * @param  array $school
	 * @return array
	 */
	public function transformCollection(array $school)
	{
		return array_map([$this,'transform'],$school);
	}	

}
