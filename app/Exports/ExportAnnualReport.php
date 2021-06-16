<?php

namespace App\Exports;

use App\Models\ChurchAnnualView;
use App\Models\ChurchAnnualDesignerView;
use App\Models\PastorAnnualView;
use App\Models\PastorAnnualDesignerView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class ExportAnnualReport implements FromView,WithEvents,ShouldAutoSize
{
    use RegistersEventListeners;

    protected $type;
    protected $columnHeader;
    protected $year;
    protected $filterBy;

    public function __construct(String $type, Array $columnHeader, Int $year = 0, Array $filterBy = []){
        $this->type = $type;
        $this->columnHeader = $columnHeader;
        $this->year = $year;
        $this->filterBy = $filterBy;
    }
    public function view(): View
    {
        $type = $this->type;
        if($type == 'church_annual'){
            $title = 'Church Annual Report';
            $dataColumn = ChurchAnnualView::get()->toArray();
        }
        else if($type == 'church_detail'){
            $title = 'Church List ' . $this->year;
            $dataColumn = ChurchAnnualDesignerView::year($this->year)->get()->toArray();
        }
        else if ($type == 'church_designer'){
            $title = 'Church Report';
            $dataColumn = ChurchAnnualDesignerView::rcDpw($this->filterBy['rc_dpw_name'] ?? null)->type($this->filterBy['entities_type'] ?? null)->country($this->filterBy['country_name'] ?? null)->status($this->filterBy['status'] ?? null)->get()->toArray();
            
        }
        else if($type == 'pastor_annual'){
            $title = 'Pastor Annual Report';
            $dataColumn = PastorAnnualView::get()->toArray();
        }
        else if($type == 'pastor_detail'){
            $title = "Pastor List ". $this->year;
            $dataColumn = PastorAnnualDesignerView::year($this->year)->get()->toArray();
        }
        else if($type == 'pastor_designer'){
            $title = "Pastor Report";
            $dataColumn = PastorAnnualDesignerView::rcDpw($this->filterBy['rc_dpw_name'] ?? null)->country($this->filterBy['country_name'] ?? null)->shortDesc($this->filterBy['short_desc'] ?? null)->status($this->filterBy['status'] ?? null)->card($this->filterBy['card'] ?? null)->dayValid($this->filterBy['filter_type'])->get()->toArray();
        }

        return view('exports.export_report',[
            'title' => $title,
            'columnHeader' => $this->columnHeader,
            'dataColumn' => $dataColumn
        ]);
    }

    public static function afterSheet(AfterSheet $event)
    {
        $lastColumn = $event->sheet->getHighestColumn();
        $lastRow = $event->sheet->getHighestRow();
        $event->sheet->mergeCells('A1:' . $lastColumn . '1');
        $event->sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadSheet\Style\Alignment::HORIZONTAL_CENTER);
        $event->sheet->getStyle('A1:' . $lastColumn . '2')->getFont()->setBold(true);
        $event->sheet->getStyle('A3:' . $lastColumn . $lastRow)->getAlignment()->setWrapText(true);
    }

}
