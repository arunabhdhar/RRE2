<?php namespace App\Http\Controllers;

use DB;
use Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Schools;
use Illuminate\Http\Request;
use App\Transformers\SchoolTransformer;
//use Transformers\Transformer;
class SchoolsController extends Controller {

	protected $schoolTransformer;
	
	public function __construct()
	{
		//$this->middleware('auth'); 
		
		$this->schoolTransformer = new SchoolTransformer(); 
		
	} 


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		$limit = Input::get('limit')?:2000;
		$schools = Schools::paginate($limit);
		
		return ([
		'data' => $this->schoolTransformer->transformCollection($schools->all())
		]);
		
		return $schools->all();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//validate
		if(!input::get('name') or !input::get('address') or !input::get('website'))
		{	// return error kind of 400,204,422
			return $this->setStatusCode(422)->respondWithError('parameters failed validation for a school!'); exit;
		}
		//Insert
		$input =  Input::all();
		$school = new Schools();
		$school->name = $input['name'];
		$school->address = $input['address'];
		$school->type = $input['type'];
		$school->website = $input['website'];
		
		$inserted = $school->save();
		if(isset($inserted)){
			return $this->setStatusCode(201)->respond([
			'message' => 'School Successfully Created.'
			]);
			
		}else{	// return response code 187, 406 		 
			return $this->setStatusCode(187)->respond([
			'message' => 'Data Not Acceptable.'
			]);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
		$school = Schools::find($id);
		if(!isset($school)){
		 
			return $this->respondNotFound('School does not exist');
			
		 }
		 
		return $this->respond([
		'data' => $this->transform($school)
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
		$input =  Input::all();
		$fields = '';
		foreach($input as $key => $value) {
			$fields .= empty($fields) ? $key." = '" . addslashes( $value ) . "'" : ", " . $key . " = '" . addslashes( $value ) . "'";
		}		
		if(Schools::find($id)){
			if(DB::update('update schools SET '.$fields.' WHERE id = '.$id.'')){
				return new jsonResponse([
					'data' => Schools::find($id)->toArray(),
					'success' => '200'
				],200);	
			}else{
				return new jsonResponse([
					'error' => [
						'message' => 'Not Updated'
					]
				],304);
			}			
		}else{
			return $this->respondNotFound('Id does not exist');			
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
		$school = new Schools();
		if($school = Schools::find($id)){
 		 $school->delete();
		}else{
			return $this->respondNotFound('Id does not exist');
		}
		return new jsonResponse([
					'data' => $school->toArray(),
					'message' => 'School Deleted Successfully!'
		],200);
	}

}
