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
        $this->renjaId = isset( $parameters['perjanjian_kinerja_id'] ) ? $parameters['perjanjian_kinerja_id'] : 0;
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

                //type target
                if ( $y->type_target == '1' ){
                    $target = $y->target_max.' '.$y->satuan_target;
                }else if ( $y->type_target == '2' ){
                    $target = $y->target_min.' - '.$y->target_max.' '.$y->satuan_target;
                }

                $i['id']                        = $x->id;
                $i['no']                        = $no;
                $i['sasaran_strategis']         = $x->label;
                $i['indikator_id']              = $y->id;
                $i['indikator']                 = $y->label;
                $i['target']                    = $target;
                $i['satuan_target']             = $y->satuan_target;
                $i['target_min']                = $y->target_min;
                $i['target_max']                = $y->target_max;
                array_push($response['data'], $i);
                $id_sasaran = $x->id;
            }
        }else{

            $i['id']                        = $x->id;
            $i['no']                        = $no;
            $i['sasaran_strategis']         = $x->label;
            $i['indikator_id']              = "";
            $i['indikator']                 = "";
            $i['target']                    = "";
            $i['satuan_target']             = "";
            $i['target_min']                = "";
            $i['target_max']                = "";
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
                $query->where('perjanjian_kinerja_id', '=', $renjaId );
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
