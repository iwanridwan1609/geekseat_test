<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class GeekseatController extends Controller
{
    /**
     * function to get the witch kill in each year based on year input
     */
    public function getWitchKillInYear($year)
    {
        try{
            // Variable to store the number
            $total = 0;
            $numberBefore = 0;
            $twoNumberBefore = 1;

            //validate if year is <0 return 0
            if ($year < 0) {
                return $total; // return 0 because empty year
            }

            //loop through the number based on the year input
            for ($i = 1; $i <= $year; $i++) {
                // Add Number based on number before
                $number = $numberBefore + $twoNumberBefore;

                // update variable to number before
                $twoNumberBefore = $numberBefore;
                $numberBefore = $number;

                // count total of the number
                $total += $number;
            }

            return $total;

        //capture error and return error response
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * counting process to get the avarage of witch killing
     */
    public function count(Request $request)
    {
        try{
            // get all request data from form submitted
            $input = $request->all();

            //variable
            $ages=$input['age']; //getting all the age input
            $years=$input['year']; //getting all the year input
            $totalPeople = count($ages); //count how much person submitted

            $totalWitchKill = 0; //for storing how much witch kill on each villager year of death

            //error variable
            $errorCount = 0;
            $errorMsg = [];

            //loop through the ages to get each input
            foreach ($ages as $index=>$age) {
                // variable to store year based on index loop
                $year = $years[$index];

                //validate so year of death cannot below age of death
                if($year<$age){
                    $errorCount += 1;
                    $errorMsg[] = ['index'=> $index+1, 'msg'=> "Year of death canot lower than the age of death"];
                }

                //validate so age cannot below 0 or negative number
                if($age<=0){
                    $errorCount += 1;
                    $errorMsg[] = ['index'=> $index+1, 'msg'=> "Please input positive number for age of death"];
                }

                //validate so year cannot below 0 or negative number
                if($year<=0){
                    $errorCount += 1;
                    $errorMsg[] = ['index'=> $index+1, 'msg'=> "Please input positive number for year of death"];
                }

                // if there is an error before no need to continue the process and skip to next person
                if($errorCount<=0){
                    // count to get which year witch killing
                   $yearKilled = $year - $age;

                   //call function getWitchKillInYear to get total witch kill in years
                   $witchKill = $this->getWitchKillInYear($yearKilled);

                   // sum the result to total witch kill
                   $totalWitchKill += $witchKill;
                }
            }

            //check if there is an error return the notvalid status and the error message
            if($errorCount>=1){
                return response()->json(['status'=>'notvalid', 'errors'=> $errorMsg]);
            }

            //count the avarage kill based on total witch kill and total people killed
            $avg = round($totalWitchKill/$totalPeople, 2); //rounded it to 2 comma

            //return success response along with data
            return response()->json(['status'=>'success', 'total'=> $totalWitchKill, 'avg'=> $avg]);

        //capture error and return error response
        } catch (\Exception $e) {
            return response()->json(['status'=>'error', 'total'=> 0, 'avg'=> "-1"]);
        }
    }
}
