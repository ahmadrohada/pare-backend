<?php
namespace App\Http\Traits;
use App\Models\SasaranStrategis;

trait PerjanjianKinerja
{


    protected function TraitSasaranStrategisSKPD($renja_id){

        $sasaran = SasaranStrategis::with(['Indikator'])
                    /* ->WhereHas('Tujuan', function($q) use($renja_id){
                        $q->where('renja_id',$renja_id);
                        $q->whereNull('deleted_at');
                    }) */
                    ->WhereHas('Indikator', function($q){
                        $q->whereNull('deleted_at');
                    })
                    ->ORDERBY('id','ASC')
                    ->get();

        $item = array();
        foreach( $sasaran AS $x ){
            foreach( $x->Indikator AS $y ){
                $item[] = array(

                    //Sasaran
                    'sasaran_id'                => $x->id,
                    'sasaran_label'             => $x->label,
                    //Indikator Sasaran
                    'indikator'                 => $y->label,
                    'target'                    => $y->target.' '.$y->satuan

                );
            }
        }


        return $item;
    }

}
