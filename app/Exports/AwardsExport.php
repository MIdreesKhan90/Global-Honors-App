<?php

namespace App\Exports;

use App\Models\CustomerList;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AwardsExport implements FromCollection, WithHeadings
{
    protected $list;

    public function __construct(CustomerList $list)
    {
        $this->list = $list;
    }

    public function collection()
    {
        // Load the awards with their relations
        $this->list->load(['awards.sponsors', 'awards.businessFunctions', 'awards.industries']);

        return $this->list->awards->map(function ($award) {
            // Map the award and its relations to the desired format
            return [
                'Name' => $award->name,
                'Sponsor' => $award->sponsors->name ?? '---',
                'Deadline' => $award->deadline_date ?? '---',
                'Award URL' => $award->details_url ?? '---',
                'Category URL' => $award->categories_url ?? '---',
                'Cost' => $award->entry_cost ?? '---',
                'Industry' => $award->industries->name ?? '---',
                'Business Function' => $award->businessFunctions->name ?? '---',
                'Location' => $award->location ?? '---',
            ];
        });
    }

    public function headings(): array
    {
        // Define the headings for your Excel file
        return [
            'Name',
            'Sponsor',
            'Deadline',
            'Award URL',
            'Category URL',
            'Cost',
            'Industry',
            'Business Function',
            'Location',
        ];
    }
}
