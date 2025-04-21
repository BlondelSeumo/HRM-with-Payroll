<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\Branch;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EventExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = Event::where('created_by', \Auth::user()->creatorId())->get();

        foreach ($data as $k => $events) {
            $data[$k]["branch_id"]     = Branch::where('id',$events->branch_id)->pluck('name')->first();
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            "ID",
            "Branch Id",
            "Department Id",
            "Employee Id",
            "Title",
            "Start Date",
            "End Date",
            "Color",
            "Description",
            "Created By",
            "Created At",
            "Updated At",
        ];
    }
}
