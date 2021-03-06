<?php
namespace App\Http\Traits;
use App\Models\SasaranStrategis;

trait PerjanjianKinerjaTrait
{


    protected function TraitSasaranStrategisSKPD($perjanjian_kinerja_id){

        $sasaran = SasaranStrategis::with(['Indikator'])
                    /* ->WhereHas('Tujuan', function($q) use($perjanjian_kinerja_id){
                        $q->where('perjanjian_kinerja_id',$perjanjian_kinerja_id);
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
