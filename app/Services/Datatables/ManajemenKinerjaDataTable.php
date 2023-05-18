<?php
namespace App\Services\Datatables;

use App\Models\Periode;
use App\Models\PerjanjianKinerja;
use App\Models\SasaranKinerja;
use App\Models\MatriksPeran;

class ManajemenKinerjaDataTable {
    private $search;
    private $take;
    private $orderBy;
    private $orderDirection;
    private $query;
    private $skpd_id;

    public function __construct( $parameters )
    {
        $this->setLocalParameters( $parameters );
        $this->query = Periode::query();
    }

    private function setLocalParameters( $parameters )
    {
        $this->take = isset( $parameters['take'] ) ? $parameters['take'] : 20;
        $this->orderBy = isset( $parameters['order_by'] ) ? $parameters['order_by'] : 'created_at';
        $this->orderDirection = isset( $parameters['order_direction'] ) ? $parameters['order_direction'] : 'DESC';
        $this->search = isset( $parameters['search'] ) ? $parameters['search'] : '';
        $this->skpd_id = isset( $parameters['skpd_id'] ) ? $parameters['skpd_id'] : 0;
    }
    // ... Other methods and set up
    public function search()
    {
    $this->applySearch();
    $this->applyOrder();

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

        $i['id']                     = $x->id;
        $i['periode']                = $x->tahun;

        $i['perjanjian_kinerja']     = PerjanjianKinerja::WHERE('periode->tahun','=',$x->tahun)->WHERE('skpd_id','=',$this->skpd_id )->WHERE('status','=','close')->exists();
        $i['skp_jpt']                = SasaranKinerja::WHERE('periode_penilaian->tahun','=',$x->tahun)->WHERE('skpd_id','=',$this->skpd_id)->WHERE('jenis_jabatan_skp','=','JABATAN PIMPINAN TINGGI')->exists();
        $i['mph']                    = MatriksPeran::WHERE('periode','=',$x->tahun)->WHERE('skpd_id','=',$this->skpd_id)->WHERE('role','=','koordinator')->exists();

        

        array_push($response['data'], $i);

    }

    return [
        'data'          => $response['data'],
        'pagination'    => $pagination,

    ];


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
                $query->where('tahun', 'LIKE', '%'.$search.'%');
                      //->orWhere('username', 'LIKE', '%'.$search.'%');
            });
        }
    }
}
