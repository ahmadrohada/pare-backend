<?php
namespace App\Services\Datatables;

use App\Models\RencanaAksi;
use App\Models\ManualIndikatorKinerja;
use App\Models\SasaranStrategis;
use App\Models\MatriksHasil;
use App\Models\SasaranKinerja;


class RencanaAksiDataTable {
    private $search;
    private $take;
    private $orderBy;
    private $orderDirection;
    private $query;

    public function __construct( $parameters )
    {
        $this->setLocalParameters( $parameters );
        $this->query = RencanaAksi::with(['RencanaKinerja']);
    }

    private function setLocalParameters( $parameters )
    {
        $this->sasaranKinerjaId = isset( $parameters['sasaran_kinerja_id'] ) ? $parameters['sasaran_kinerja_id'] : 0;
        $this->take = isset( $parameters['take'] ) ? $parameters['take'] : 10;
        $this->orderBy = isset( $parameters['order_by'] ) ? $parameters['order_by'] : 'created_at';
        $this->orderDirection = isset( $parameters['order_direction'] ) ? $parameters['order_direction'] : 'DESC';
        $this->search = isset( $parameters['search'] ) ? $parameters['search'] : '';
        
        //JENIS JABATAN SKP
        $data = SasaranKinerja::WHERE('id',$this->sasaranKinerjaId)->first();
        $this->jenisJabatan = $data->jenis_jabatan_skp;


    }
    // ... Other methods and set up
    public function search()
    {
    $this->applySearch();
    $this->applyOrder();
    $this->applySasaranKinerjaId();

    $cafes = $this->query->paginate( $this->take );
    $pagination = array(
        'current_page'  => $cafes->currentPage(),
        'total_page'    => ( ($cafes->perPage() != 0 ) && ($cafes->total() != 0 )  ) ? Floor($cafes->total()/$cafes->count()) : 0,
        'per_page'      => $cafes->count(),
        'limit'         => (int)$cafes->perPage(),
        'total'         => $cafes->total(),
    );

    $data = $cafes->getCollection();
    $response['data'] = array();
    $no = 0;




    foreach( $data AS $x ){
        $no = $no+1;
        $i['id']                = $x->id;
        $i['no']                = $no;
        $i['rencana_kerja']     = "tes";
        $i['label']             = $x->label;
        $i['target']            = "ini target";
       
        array_push($response['data'], $i);
           

    }


    return [
        'data'          => $response['data'],
        'pagination'    => $pagination,

    ];


    }
    // ... Other methods and set up
    private function applySasaranKinerjaId()
    {
        if( $this->sasaranKinerjaId != '' ){
            $sasaranKinerjaId = urldecode( $this->sasaranKinerjaId );

            $this->query->where(function( $query ) use ( $sasaranKinerjaId ){
                //$query->where('sasaran_kinerja_id', '=', $sasaranKinerjaId );
            });
        }
    }

    

    private function applyOrder()
    {
        switch( $this->orderBy ){
            case 'likes':
                $this->query->withCount('likes as liked')
                     ->orderByRaw('liked DESC');
            break;
            default:
                $this->query->orderBy( $this->orderBy, $this->orderDirection );
            break;
        }
    }

    public function applySearch()
    {
        if( $this->search != '' ){
            $search = urldecode( $this->search );

            $this->query->where(function( $query ) use ( $search ){
                $query->where('label', 'LIKE', '%'.$search.'%');
                      //->orWhere('username', 'LIKE', '%'.$search.'%');
            });
        }
    }
}
