<?php
/**
 * Tests the MeasureController functions that store, edit and delete measures 
 * @author  (c) @iLabAfrica, Emmanuel Kitsao, Brian Kiprop, Thomas Mapesa, Anthony Ereng
 */
class MeasureControllerTest extends TestCase 
{
	/**
	 * Default preparations for tests
	 *
	 * @return void
	 */
	public function setup()
	{
		parent::setUp();
		Artisan::call('migrate');
		Artisan::call('db:seed');
		$this->setVariables();
	}
	/**
	 * Tests the store funtion in the MeasureController
	 *
	 * @return void
	 */    
 	public function testifSaveWorks() 
  	{
		echo "\n\nMEASURE CONTROLLER TEST\n\n";
  		// Store the Measure Types
		$this->runStore($this->inputNumeric);
		$this->runStore($this->inputAlphanumeric);
		$this->runStore($this->inputAutocomplete);
		$this->runStore($this->inputFreetext);
		
		//Check if measure was saved
		$measurestored = Measure::orderBy('id','desc')->take(4)->get()->toArray();
		//Freetext
		$this->assertEquals($measurestored[0]['name'] , $this->inputFreetext['name']);
		$this->assertEquals($measurestored[0]['measure_type_id'] , $this->inputFreetext['measure_type_id']);
		$this->assertEquals($measurestored[0]['description'] , $this->inputFreetext['description']);

		//Autocomplete
		$this->assertEquals($measurestored[1]['name'] , $this->inputAutocomplete['name']);
		$this->assertEquals($measurestored[1]['measure_type_id'] , $this->inputAutocomplete['measure_type_id']);
		$this->assertEquals($measurestored[1]['description'] , $this->inputAutocomplete['description']);

		//Alphanumeric
		$this->assertEquals($measurestored[2]['name'] , $this->inputAlphanumeric['name']);
		$this->assertEquals($measurestored[2]['measure_type_id'] , $this->inputAlphanumeric['measure_type_id']);
		$this->assertEquals($measurestored[2]['description'] , $this->inputAlphanumeric['description']);

		//Numeric
		$this->assertEquals($measurestored[3]['name'] , $this->inputNumeric['name']);
		$this->assertEquals($measurestored[3]['measure_type_id'] ,$this->inputNumeric['measure_type_id']);
		$this->assertEquals($measurestored[3]['unit'] ,$this->inputNumeric['unit']);
		$this->assertEquals($measurestored[3]['description'] ,$this->inputNumeric['description']);
		
		$measurerangestored = MeasureRange::orderBy('id','desc')->take(6)->get()->toArray();

		$this->assertEquals($measurerangestored[5]['age_min'], $this->inputNumeric['agemin'][0]);
		$this->assertEquals($measurerangestored[5]['age_max'], $this->inputNumeric['agemax'][0]);
		$this->assertEquals($measurerangestored[5]['gender'], $this->inputNumeric['gender'][0]);
		$this->assertEquals($measurerangestored[5]['range_lower'], $this->inputNumeric['rangemin'][0]);
		$this->assertEquals($measurerangestored[5]['range_upper'], $this->inputNumeric['rangemax'][0]);

		$this->assertEquals($measurerangestored[4]['age_min'], $this->inputNumeric['agemin'][1]);
		$this->assertEquals($measurerangestored[4]['age_max'], $this->inputNumeric['agemax'][1]);
		$this->assertEquals($measurerangestored[4]['gender'], $this->inputNumeric['gender'][1]);
		$this->assertEquals($measurerangestored[4]['range_lower'], $this->inputNumeric['rangemin'][1]);
		$this->assertEquals($measurerangestored[4]['range_upper'], $this->inputNumeric['rangemax'][1]);
		
		$this->assertEquals($measurerangestored[3]['alphanumeric'], $this->inputAlphanumeric['val'][0]);
		$this->assertEquals($measurerangestored[2]['alphanumeric'], $this->inputAlphanumeric['val'][1]);

		$this->assertEquals($measurerangestored[1]['alphanumeric'], $this->inputAutocomplete['val'][0]);
		$this->assertEquals($measurerangestored[0]['alphanumeric'], $this->inputAutocomplete['val'][1]);
  	}

  	/**
  	 * Tests the update funtion in the MeasureController
	 * 
	 * @return void
     */
	public function testIfUpdateWorks()
	{	
		//Save again because teardown() dropped the db :(
		$this->runStore($this->inputNumeric);
		$this->runStore($this->inputAlphanumeric);
		$this->runStore($this->inputAutocomplete);
		$this->runStore($this->inputFreetext);

		$measurestored = Measure::orderBy('id','desc')->take(4)->get()->toArray();
		$measureUricAcid = Measure::where('name', 'LIKE', '%uric%')->first();

		// Update the Measure Types
		// The second argument is the test type ID
		$this->runUpdate($this->inputNumericUpdate, $measureUricAcid->id);
		$this->runUpdate($this->inputAlphanumericUpdate, $measurestored[2]['id']);
		$this->runUpdate($this->inputAutocompleteUpdate, $measurestored[1]['id']);
		$this->runUpdate($this->inputFreetextUpdate, $measurestored[0]['id']);
		
		$measureupdated = Measure::orderBy('id','desc')->take(4)->get()->toArray();
		$measureNewUricAcid = Measure::find($measureUricAcid->id);

		// Check that the measure values for the uric acid measure were updated
		$this->assertEquals($measureNewUricAcid->name , $this->inputNumericUpdate['name']);
		$this->assertEquals($measureNewUricAcid->measure_type_id ,$this->inputNumericUpdate['measure_type_id']);
		$this->assertEquals($measureNewUricAcid->unit ,$this->inputNumericUpdate['unit']);
		$this->assertEquals($measureNewUricAcid->description ,$this->inputNumericUpdate['description']);
		
		$this->assertEquals($measureupdated[2]['name'] , $this->inputAlphanumericUpdate['name']);
		$this->assertEquals($measureupdated[2]['measure_type_id'] , $this->inputAlphanumericUpdate['measure_type_id']);
		$this->assertEquals($measureupdated[2]['description'] , $this->inputAlphanumericUpdate['description']);
		
		$this->assertEquals($measureupdated[1]['name'] , $this->inputAutocompleteUpdate['name']);
		$this->assertEquals($measureupdated[1]['measure_type_id'] , $this->inputAutocompleteUpdate['measure_type_id']);
		$this->assertEquals($measureupdated[1]['description'] , $this->inputAutocompleteUpdate['description']);
		
		$this->assertEquals($measureupdated[0]['name'] , $this->inputFreetextUpdate['name']);
		$this->assertEquals($measureupdated[0]['measure_type_id'] , $this->inputFreetextUpdate['measure_type_id']);
		$this->assertEquals($measureupdated[0]['description'] , $this->inputFreetextUpdate['description']);

		$measurerangeupdated = MeasureRange::orderBy('id','desc')->take(6)->get()->toArray();

		$this->assertEquals($measurerangeupdated[5]['age_min'], $this->inputNumericUpdate['agemin'][0]);
		$this->assertEquals($measurerangeupdated[5]['age_max'], $this->inputNumericUpdate['agemax'][0]);
		$this->assertEquals($measurerangeupdated[5]['gender'], $this->inputNumericUpdate['gender'][0]);
		$this->assertEquals($measurerangeupdated[5]['range_lower'], $this->inputNumericUpdate['rangemin'][0]);
		$this->assertEquals($measurerangeupdated[5]['range_upper'], $this->inputNumericUpdate['rangemax'][0]);

		$this->assertEquals($measurerangeupdated[4]['age_min'], $this->inputNumericUpdate['agemin'][1]);
		$this->assertEquals($measurerangeupdated[4]['age_max'], $this->inputNumericUpdate['agemax'][1]);
		$this->assertEquals($measurerangeupdated[4]['gender'], $this->inputNumericUpdate['gender'][1]);
		$this->assertEquals($measurerangeupdated[4]['range_lower'], $this->inputNumericUpdate['rangemin'][1]);
		$this->assertEquals($measurerangeupdated[4]['range_upper'], $this->inputNumericUpdate['rangemax'][1]);
		
		$this->assertEquals($measurerangeupdated[3]['alphanumeric'], $this->inputAlphanumericUpdate['val'][0]);
		$this->assertEquals($measurerangeupdated[2]['alphanumeric'], $this->inputAlphanumericUpdate['val'][1]);

		$this->assertEquals($measurerangeupdated[1]['alphanumeric'], $this->inputAutocompleteUpdate['val'][0]);
		$this->assertEquals($measurerangeupdated[0]['alphanumeric'], $this->inputAutocompleteUpdate['val'][1]);
	}
	
	/**
  	 * Tests the update funtion in the MeasureController
	 * 
	 * @return void
     */
	public function testIfDeleteWorks()
	{
		//Save again because teardown() dropped the db :(
		$this->runStore($this->inputNumeric);
		$measurestored = Measure::orderBy('id','desc')->take(1)->get()->toArray();
		$id = $measurestored[0]['id'];

		//To Do:: Delete for measureranges
		$measureController = new MeasureController();
		$measureController->delete($id);

		$measureidone = Measure::withTrashed()->find($id);
		$this->assertNotNull($measureidone->deleted_at);
	}

	/**
  	 *Executes the store funtion in the MeasureController
  	 * @param  array $input Measure details
	 * @return void
  	 */
	public function runStore($input)
	{
		Input::replace($input);
	    $measure = new MeasureController;
	    $measure->store();
	}

  	/**
  	 * Executes the update funtion in the MeasureController
  	 * @param  array $input Measure details, int $id ID of the Mesure stored (array for numeric Measure)
	 * @return void
  	 */
	public function runUpdate($input, $id)
	{
		Input::replace($input);
	    $measure = new MeasureController;
	    $measure->update($id);
	}

	public function setVariables(){

    		// Initial sample storage data
		$this->inputNumeric = array(
			'name' => 'UricAcid',
			'measure_type_id' => '1',
			'unit' => 'mg/dl',
			'description' => 'Description',
			'agemin' =>['1', '2'], 
			'agemax' => ['4', '5'], 
			'gender' => ['1', '1'],
			'rangemin' => ['12', '32'],
			'rangemax' => ['32', '34'],
			'interpretation' => ['inter1', 'inta1'],
		);

		$this->inputAlphanumeric = array(
			'name' => 'BloodGrouping',
			'measure_type_id' => '2',
			'unit' => 'Unit',
			'description' => 'Description',
			'val' => ['O-','O+'],
			'interpretation' => ['inter1', 'inta1'],
		);

		$this->inputAutocomplete = array(
			'name' => 'Autocomplete',
			'measure_type_id' => '3',
			'unit' => 'Unit',
			'description' => 'Description',
			'val' => ['One','Two'],
			'interpretation' => ['inter1', 'inta1'],
		);

		$this->inputFreetext = array(
			'name' => 'CSFforBiochemistry',
			'measure_type_id' => '4',
			'unit' => 'Unit',
			'description' => 'Description'
		);

		// Editing sample data
		$this->inputNumericUpdate = array(
			'name' => 'Numeric',
			'measure_type_id' => '1',
			'unit' => 'nUnit',
			'description' => 'nDescription',
			'agemin' =>['11', '21'], 
			'agemax' => ['41', '51'], 
			'gender' => ['11', '11'],
			'rangemin' => ['22', '42'],
			'rangemax' => ['42', '44'],
			'interpretation' => ['inter2', 'inta2'],
		);

		$this->inputAlphanumericUpdate = array(
			'name' => 'AlphaNumeric',
			'measure_type_id' => '2',
			'unit' => 'aUnit',
			'description' => 'aDescription',
			'val' => ['A','B'],
			'interpretation' => ['inter2', 'inta2'],
		);

		$this->inputAutocompleteUpdate = array(
			'name' => 'AutoComplete',
			'measure_type_id' => '3',
			'unit' => 'aUnit',
			'description' => 'aDescription',
			'val' => ['aOne','aTwo'],
			'interpretation' => ['inter2', 'inta2'],
		);

		$this->inputFreetextUpdate = array(
			'name' => 'FreeText',
			'measure_type_id' => '4',
			'unit' => 'fUnit',
			'description' => 'fDescription'
		);
    }
}