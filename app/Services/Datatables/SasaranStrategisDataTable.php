<?php
namespace App\Services\Datatables;

use App\Models\SasaranStrategis;

class SasaranStrategisDataTable {
    private $search;
    private $take;
    private $orderBy;
    private $orderDirection;
    private $query;

    public function __construct( $parameters )
    {
        $this->setLocalParameters( $parameters );
       $this->query = SasaranStrategis::with(['Indikator']);
    }

    private function setLocalParameters( $parameters )
    {
        $this->renjaId = isset( $parameters['renja_id'] ) ? $parameters['renja_id'] : 0;
        $this->take = isset( $parameters['take'] ) ? $parameters['take'] : 10;
        $this->orderBy = isset( $parameters['order_by'] ) ? $parameters['order_by'] : 'created_at';
        $this->orderDirection = isset( $parameters['order_direction'] ) ? $parameters['order_direction'] : 'DESC';
        $this->search = isset( $parameters['search'] ) ? $parameters['search'] : '';
    }
    // ... Other methods and set up
    public function search()
    {
    $this->applySearch();
    $this->applyOrder();
    $this->applyRenjaId();

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
    $id_sasaran = null;

    foreach( $data AS $x ){
        $no = $no+1;
        //jika indikator nya tidak null
        if ( sizeof($x->indikator) ){
            foreach( $x->indikator AS $y ){
                $primary = 1 ;
                if ( $id_sasaran == $x->id){
                    $primary = 0;
                }


                $i['id']                        = $x->id;
                $i['sasaran_strategis']         = $x->label;
                $i['indikator_id']              = $y->id;
                $i['indikator']                 = $y->label;
                $i['target']                    = $y->target;
                $i['satuan_target']             = $y->satuan_target;
                $i['primary']                   = $primary;
                array_push($response['data'], $i);
                $id_sasaran = $x->id;
            }
        }else{

            $i['id']                        = $x->id;
            $i['sasaran_strategis']         = $x->label;
            $i['indikator_id']              = "";
            $i['indikator']                 = "";
            $i['target']                    = "";
            $i['satuan_target']             = "";
            $i['primary']                   = 1;
            array_push($response['data'], $i);
        }



    }

    return [
        'data'          => $response['data'],
        'pagination'    => $pagination,

    ];


    }
    // ... Other methods and set up
    private function applyRenjaId()
    {
        if( $this->renjaId != '' ){
            $renjaId = urldecode( $this->renjaId );

            $this->query->where(function( $query ) use ( $renjaId ){
                $query->where('renja_id', '=', $renjaId );
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
