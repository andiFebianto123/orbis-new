@php
function remove_bs($Str) 
{  
    $StrArr = str_split($Str); $NewStr = '';
    foreach ($StrArr as $Char) {    
        $CharNo = ord($Char);
        if ($CharNo == 163 || $CharNo == 92 ||$CharNo == 13) { 
            $NewStr .= $Char; 
            continue; 
        } // keep £ 
        if ($CharNo > 31 && $CharNo < 127)
        {
                $NewStr .= $Char;    
        }
    }  
    return $NewStr;
}
@endphp
<html>
    <style>
        br {mso-data-placement:same-cell;}
    </style>
<table>
    <thead>
    <tr>
        <th>{{$title}}</th>
    </tr>
    <tr>
        @foreach($columnHeader as $index => $header)
            <th>{{$header}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
        @foreach($dataColumn as $index => $data)
            <tr>
                @foreach($columnHeader as $indexColumn => $headerColumn)
                <?php
                    if(isset($columnHeader['rc_dpw_name']) && ($indexColumn == 'rc_dpw_name')){
                        $realColumnData = $data['rdpw'];
                    }else{
                        $realColumnData = preg_match("/\r|\n/", $data[$indexColumn]) ? remove_bs($data[$indexColumn]) : $data[$indexColumn];
                    }
                ?>
                        @php
                           // $realColumnData = preg_match("/\r|\n/", $data[$indexColumn]) ? remove_bs($data[$indexColumn]) : $data[$indexColumn];
                        @endphp
                    {{--

                    @php
                    $realColumnData = $data[$indexColumn];
                    @endphp
                    --}}

                    <td>
                        {{$realColumnData}}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
</html>