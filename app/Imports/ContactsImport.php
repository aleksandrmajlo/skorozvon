<?php

namespace App\Imports;

use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class ContactsImport implements ToModel, WithHeadingRow
{
    private $rows = 0;
    private $allrows = 0;


    public function model(array $row)
    {
        $count=Contact::where('idbank',$row['id'])->count();
        ++$this->allrows;
        if ($count===0){
            ++$this->rows;
            return new Contact([
                'fullname'     => $row['fullname'],
                'phone'    => $row['phone'],
                'email'    => $row['email'],
                'inn'    => $row['inn'],
                'address'    => $row['address'],
                'idbank'    => $row['id'],
                'organization'  => $row['organizationname'],
                'user_id'    => $id = Auth::id(),
            ]);
        }
    }

    public function getRowCount()
    {
        return $this->rows;
    }
    public function getAllrowCount()
    {
        return $this->allrows;
    }

}
