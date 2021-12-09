<?php
namespace App\Services\Datatables;

use App\Models\Renja;

class RenjaDataTable {
    private $search;
    private $take;
    private $orderBy;
    private $orderDirection;
    private $query;

    public function __construct( $parameters )
    {
        $this->setLocalParameters( $parameters );
        //$this->query = Renja::with(['Periode']);
        $this->query = Renja::SELECT(   'id AS renja_id',
                                        'periode->tahun AS periode',
                                        'skpd->id AS skpd_id',
                                        'skpd->nama AS nama_skpd',
                                        'skpd->singkatan AS singkatan_skpd',
                                        'status AS status',
                                        'created_at AS created_at');
    }

    private function setLocalParameters( $parameters )
    {
        $this->skpdId = isset( $parameters['skpd_id'] ) ? $parameters['skpd_id'] : 0;
        $this->take = isset( $parameters['take'] ) ? $parameters['take'] : 10;
        $this->orderBy = isset( $parameters['order_by'] ) ? $parameters['order_by'] : 'created_at';
        $this->orderDirection = isset( $parameters['order_direction'] ) ? $parameters['order_direction'] : 'DESC';
        $this->search = isset( $parameters['search'] ) ? $parameters['search'] : '';
    }
    // ... Other methods and set up
    public function search()
    {
    $this->applySkpdId();
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

        $i['id']            = $x->renja_id;
        $i['periode']       = $x->periode;
        $i['nama_skpd']     = $x->nama_skpd;
        $i['singkatan_skpd']= $x->singkatan_skpd;
        $i['status']        = $x->status;
        $i['created_at']    = $x->created_at;
        array_push($response['data'], $i);


    }

    return [
        'data'          => $response['data'],
        'pagination'    => $pagination,

    ];


    }
    // ... Other methods and set up
    private function applySkpdId()
    {
        if( $this->skpdId != '' ){
            $skpdId = urldecode( $this->skpdId );

            $this->query->where(function( $query ) use ( $skpdId ){
                $query->where('skpd_id', '=', $skpdId );
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
