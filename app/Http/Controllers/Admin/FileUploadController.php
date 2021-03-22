<?php


namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ContactsImport;

class FileUploadController extends Controller
{

    public function fileUploadExcel(Request $request)
    {
        $extensions = array("xls","xlsx");
        $result = array($request->file('file')->getClientOriginalExtension());
        if(in_array($result[0],$extensions)){
            $conact = new ContactsImport;
            $rows = Excel::import($conact, request()->file('file'));
            $duplicate=$conact->getAllrowCount()-$conact->getRowCount();
            return back()
                ->with('success', 'Контактов добавлено - ' . $conact->getRowCount().'. Дублей -'.$duplicate);
        }else{
             return back()->with('error','Поле file должно быть файлом одного из следующих типов:xlsx,xls ');
        }

    }

}
