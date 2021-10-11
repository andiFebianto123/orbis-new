<?php

namespace App\Exports;

use App\Models\Church;
use App\Models\ChurchAnnualView;
use App\Models\ChurchAnnualDesignerView;
use App\Models\MinistryRole;
use App\Models\PastorAnnualView;
use App\Models\PastorAnnualDesignerView;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportAnnualReport implements FromView,WithEvents
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
        else if($type == "new_church"){
            $title = "New Church This Year";
            $dataColumn = ChurchAnnualDesignerView::year(Carbon::now()->year)->get()->toArray();
        }
        else if($type == "new_pastor"){
            $title = "New Pastor This Year";
            $dataColumn = PastorAnnualDesignerView::year(Carbon::now()->year)->get()->toArray();
        }
        else if($type == "recent_church"){
            $title = "Recently Inactive Church";
            $dataColumn = ChurchAnnualDesignerView::year(Carbon::now()->year)->where('status', '=','Non-active')->get()->toArray();
        }
        else if($type == "recent_pastor"){
            $title = "Recently Inactive Pastor";
            $dataColumn = PastorAnnualDesignerView::year(Carbon::now()->year)->where('status', '=', 'Inactive')->get()->toArray();
        }
        else if($type == "all_church"){
            $title = "All Church";
            $dataColumn = ChurchAnnualDesignerView::get()->toArray();
        }
        else if($type == "all_pastor"){
            $title = "All Pastor";
            $dataColumn = PastorAnnualDesignerView::get()->toArray();
        }
        
        return view('exports.export_report',[
            'title' => $title,
            'columnHeader' => $this->columnHeader,
            'dataColumn' => $dataColumn
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $lastColumn = $event->sheet->getHighestColumn();
                $lastRow = $event->sheet->getHighestRow();
                
                $formatedListNameValue = function($string){
                    return preg_replace("/,+/", "\n", $string );
                };
                
                $formatedListLeadershipNameValue = function($string){
                    return str_replace('<br>', "\n", $string );
                };

                $formatedListChurchNameValue = function($string){
                    return str_replace('<br>', "\n", $string );
                };
                $formatedListPhoneValue = function($string){
                    return preg_replace_callback("/\+62|;08+|;+/", function ($s) use (&$counter) {
                        if($s[0] == ";08" || $s[0] == ";"){
                            return $s[0] == ";08" ? "\n$s[0]" : "\n";
                        }
                        else{
                            if ($counter++ != 0) {
                                return "\n$s[0]";
                           }
                        }
                        return $s[0];
                   }, $string );
                };
                $formatedListEmailValue = function($string){
                    return preg_replace_callback("/.com;+|.co.id;+|.com,+|.co.id,+|.com +|.co.id +/", function($s) use(&$counter){
                       return "$s[0]\n";
                    }, $string );
                };
                $pastorNameHeader = "";
                $churchNameHeader = "";
                $leadPastorNameHeader = "";
                $phoneHeader = "";
                $emailHeader = "";
                $after_last_col = $lastColumn;
                $after_last_col++;
                for ($column="A"; $column != $after_last_col; $column++) { 
                    $valueCell = $event->sheet->getCell($column."2")->getValue();

                    if($valueCell == 'Lead Pastor Name'){
                        $pastorNameHeader = $column;
                    }
                    if($valueCell == 'Leadership Structure'){
                        $leadPastorNameHeader = $column;
                    }
                    if($valueCell == "Church Name"){
                        $churchNameHeader = $column;
                    }
                    if($valueCell == "Phone"){
                        $phoneHeader = $column;
                    }
                    if($valueCell == "Email"){
                        $emailHeader = $column;
                    }
                    if($valueCell == "Year" || $valueCell == "Churches" || $valueCell == 'Pastor' || $valueCell == 'Postal Code' || $valueCell == "Anniversary" || $valueCell == 'Gender'){
                        $event->sheet->getColumnDimension($column)->setWidth(10);
                    }
                    if($valueCell == 'RC / DPW'  || $valueCell == 'Church Type' || $valueCell == 'City' || $valueCell == 'Province' || $valueCell ==  'Country'|| $valueCell == "Church Status"  || $valueCell == "Province / State" || $valueCell == "Marital Status" || $valueCell == "Date of Birth" || $valueCell == "Spouse Name" || $valueCell == "Spouse Date of Birth" || $valueCell == "Status" || $valueCell == "First Licensed On" || $valueCell == "Card" || $valueCell == "Valid Card Start" || $valueCell == "Valid Card End" ){
                        $event->sheet->getColumnDimension($column)->setWidth(20);
                    }
                    if($valueCell == 'First Name' || $valueCell == 'Last Name' || $valueCell == "Current Certificate Number" || $valueCell =='Coordinator'){
                        $event->sheet->getColumnDimension($column)->setWidth(30);
                    }
                    if($valueCell == 'Contact Person' || $valueCell == 'Phone' || $valueCell == 'Fax' || $valueCell == 'Email'  || $valueCell == 'Founded On' || $valueCell == 'Service Time Church' || $valueCell == 'Notes'){
                        $event->sheet->getColumnDimension($column)->setWidth(35);
                    }
                    if($valueCell == 'Leadership Structure' || $valueCell == 'Church Name' || $valueCell == 'Lead Pastor Name' || $valueCell == "Church Address" || $valueCell == 'Office Address' || $valueCell == "Address"){
                        $event->sheet->getColumnDimension($column)->setWidth(40);
                    }
                }


                if($pastorNameHeader != ""){
                    for($i=3;$i <= $lastRow; $i++){
                        $unFormattedNameList = $event->sheet->getCell($pastorNameHeader . $i)->getValue();
                        $event->sheet->setCellValue($pastorNameHeader . $i, $formatedListNameValue($unFormattedNameList));
                        if(strpos($formatedListNameValue($unFormattedNameList),"\n") !== false){
                            $event->sheet->getRowDimension($i)->setRowHeight(45);  
                        }
                    }
                }
                if($churchNameHeader != ""){
                    for($i=3;$i <= $lastRow; $i++){
                        $unFormattedChurchNameList = $event->sheet->getCell($churchNameHeader . $i)->getValue();
                        $event->sheet->setCellValue($churchNameHeader . $i, $formatedListChurchNameValue($unFormattedChurchNameList));
                        if(strpos($formatedListChurchNameValue($unFormattedChurchNameList),"\n") !== false){
                            $event->sheet->getRowDimension($i)->setRowHeight(45);  
                        }
                    }
                }
                
                if($leadPastorNameHeader != ""){
                    for($i=3;$i <= $lastRow; $i++){
                        $unFormattedLeadPastorNameList = $event->sheet->getCell($leadPastorNameHeader . $i)->getValue();
                        $event->sheet->setCellValue($leadPastorNameHeader . $i, $formatedListLeadershipNameValue($unFormattedLeadPastorNameList));
                        if(strpos($formatedListLeadershipNameValue($unFormattedLeadPastorNameList),"\n") !== false){
                            $event->sheet->getRowDimension($i)->setRowHeight(45);  
                        }
                    }
                }
                if($phoneHeader != ""){
                    for($i=3;$i <= $lastRow; $i++){
                        $unFormattedPhoneList = $event->sheet->getCell($phoneHeader . $i)->getValue();
                        $event->sheet->setCellValue($phoneHeader . $i, $formatedListPhoneValue($unFormattedPhoneList));
                        if(strpos($formatedListPhoneValue($unFormattedPhoneList),"\n") !== false){
                            $event->sheet->getRowDimension($i)->setRowHeight(45);  
                        }
                    }
                }
                if($emailHeader != ""){
                    for($i=3;$i <= $lastRow; $i++){
                        $unFormattedEmailList = $event->sheet->getCell($emailHeader . $i)->getValue();
                        $event->sheet->setCellValue($emailHeader . $i, $formatedListEmailValue($unFormattedEmailList));
                        if(strpos($formatedListEmailValue($unFormattedEmailList),"\n") !== false){
                            $event->sheet->getRowDimension($i)->setRowHeight(45);  
                        }
                    }
                }
                $event->sheet->mergeCells('A1:' . $lastColumn . '1');
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadSheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('A1:' . $lastColumn . '2')->getFont()->setBold(true);
            }
        ];
    }
}
