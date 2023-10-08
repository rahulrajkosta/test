<?php
namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;

use Maatwebsite\Excel\Concerns\WithBatchInserts;
use App\Product;
use App\Category;
use App\Helpers\CustomHelper;

use DB;
use App\QuestionNotValid;
use App\Question;


class QuestionNewImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading{

    //    public function  __construct($exam_id)
    // {
    //     $this->exam_id= $exam_id;
    // }

 public function model(array $row)
    {    
        $type =  1;            
        $diff = 1;
        $question_name = $row['question_name'] ?? '';
        $option_1 = $row['option_1'] ?? '';
        $option_2 = $row['option_2'] ??'';
        $option_3 = $row['option_3'] ?? '';
        $option_4 = $row['option_4'] ??'';
        $right_option = $row['right_option'] ??'';
        $difficulti_level = $row['difficulti_level'] ??'';
        $dbArray = [];
        if($question_name !='' && $option_1 !='' && $option_2 !='' && $option_3 !='' && $option_4 !='' && $right_option !='' && $difficulti_level !='' ){

            $dbArray['exam_id'] = '';
            $dbArray['question_name'] = $question_name;
            $dbArray['option_1'] = $option_1;
            $dbArray['option_2'] = $option_2;
            $dbArray['option_3'] = $option_3;
            $dbArray['option_4'] = $option_4;
            $dbArray['right_option'] = $right_option;
            $dbArray['difficulti_level'] = $difficulti_level;
            Question::create($dbArray);

        }else{

            $dbArray['exam_id'] = '';
            $dbArray['question_name'] = $question_name;
            $dbArray['option_1'] = $option_1;
            $dbArray['option_2'] = $option_2;
            $dbArray['option_3'] = $option_3;
            $dbArray['option_4'] = $option_4;
            $dbArray['right_option'] = $right_option;
            $dbArray['difficulti_level'] = $difficulti_level;
            QuestionNotValid::create($dbArray);

        }



       
        return ;
    }


    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }


    

    /* end of class */
}