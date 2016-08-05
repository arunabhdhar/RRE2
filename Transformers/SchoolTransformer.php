<?php
namespace App\Transformers;
class SchoolTransformer extends Transformer{

	/**
	 * Creates the application.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
		
	/**
	 * Transform a school
	 *
	 * @param  int  $school
	 * @return array
	 */
	public function transform($school)
	{		
		return [
			'SchoolName' => $school['name'],
			'SchoolAddress' => $school['address'],
			'SchoolType' => $school['type'],
			'SchoolWebsite' => $school['website'],
			];
		
	}
	
	

}
