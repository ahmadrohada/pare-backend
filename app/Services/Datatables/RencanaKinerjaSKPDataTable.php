<?php
namespace App\Services\Datatables;

use App\Models\RencanaKinerja;

class RencanaKinerjaSKPDataTable {
    private $search;
    private $take;
    private $orderBy;
    private $orderDirection;
    private $query;

    public function __construct( $parameters )
    {
        $this->setLocalParameters( $parameters );
       $this->query = RencanaKinerja::with(['Indikator']);
    }

    private function setLocalParameters( $parameters )
    {
        $this->skpId = isset( $parameters['skp_id'] ) ? $parameters['skp_id'] : 0;
        $this->take = isset( $parameters['take'] ) ? $parameters['take'] : 10;
        $this->orderBy = isset( $parameters['order_by'] ) ? $parameters['order_by'] : 'created_at';
        $this->orderDirection = isset( $parameters['order_direction'] ) ? $parameters['order_direction'] : 'DESC';
        $this->search = isset( $parameters['search'] ) ? $parameters['search'] : '';
    }
    // ... Other methods and set up
    public function search()
    {
    $this->applySkpId();
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
        //jika indikator nya tidak null
        if ( sizeof($x->indikator) ){
            foreach( $x->indikator AS $y ){
                $i['id']                        = $x->id;
                $i['rencana_kinerja']           = $x->label;
                $i['indikator_kinerja_individu']= $y->label;
                $i['target']                    = $y->target;
                $i['satuan_target']             = $y->satuan_target;
                $i['row']                       = 3;
                array_push($response['data'], $i);
            }
        }else{
            $i['id']                        = $x->id;
            $i['rencana_kinerja']           = $x->label;
            $i['indikator_kinerja_individu']= "";
            $i['target']                    = "";
            $i['satuan_target']             = "";
            $i['row']                       = 3;
            array_push($response['data'], $i);
        }



    }

    return [
        'data'          => $response['data'],
        'pagination'    => $pagination,

    ];


    }
    // ... Other methods and set up
    private function applySkpId()
    {
        if( $this->skpId != '' ){
            $skpId = urldecode( $this->skpId );

            $this->query->where(function( $query ) use ( $skpId ){
                $query->where('skp_id', '=', $skpId );
                      //->orWhere('username', 'LIKE', '%'.$search.'%');
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
