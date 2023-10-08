<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BusinessExport implements FromArray, WithHeadings {

    use Exportable;

    public function __construct($businessArr, $headings){
        //$this->request = $request;
        $this->businessArr = $businessArr;
        //$this->productQuery = $productQuery;
        $this->headings = $headings;
    }
/*
    public function query(){
    	$productQuery = $this->productQuery;
        return $productQuery;
    }*/

    public function array(): array {

        $businessArr = $this->businessArr;
        return $businessArr;
    }

    public function headings(): array {
        $headings = $this->headings;

        return $headings;
    }
}