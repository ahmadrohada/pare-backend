<?php
namespace App\Services\Datatables;

use App\Models\RencanaKinerja;
use App\Models\ManualIndikatorKinerja;

class RencanaKinerjaDataTable {
    private $search;
    private $take;
    private $orderBy;
    private $orderDirection;
    private $query;

    public function __construct( $parameters )
    {
        $this->setLocalParameters( $parameters );
        $this->query = RencanaKinerja::with(['IndikatorKinerjaIndividu','Parent']);
        //$this->query = RencanaKinerja::query();
    }

    private function setLocalParameters( $parameters )
    {
        $this->sasaranKinerjaId = isset( $parameters['sasaran_kinerja_id'] ) ? $parameters['sasaran_kinerja_id'] : 0;
        $this->take = isset( $parameters['take'] ) ? $parameters['take'] : 10;
        $this->orderBy = isset( $parameters['order_by'] ) ? $parameters['order_by'] : 'created_at';
        $this->orderDirection = isset( $parameters['order_direction'] ) ? $parameters['order_direction'] : 'DESC';
        $this->search = isset( $parameters['search'] ) ? $parameters['search'] : '';
        $this->jenis = isset( $parameters['jenis_rencana_kinerja'] ) ? $parameters['jenis_rencana_kinerja'] : 0;
    }
    // ... Other methods and set up
    public function search()
    {
    $this->applySearch();
    $this->applyOrder();
    $this->applySasaranKinerjaId();
    $this->applyJenis();

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
        if ( sizeof($x->IndikatorKinerjaIndividu) ){
            foreach( $x->IndikatorKinerjaIndividu AS $y ){

                //id MAnual indikator
                $manual = ManualIndikatorKinerja::WHERE('indikator_kinerja_individu_id','=',$y->id)->Select('id')->first();
                if ($manual){
                    $manual_indikator_id = $manual->id;
                }else{
                    $manual_indikator_id = 0;
                }

                //type target
                if ( $y->type_target == '1' ){
                    $target = $y->target_max.' '.$y->satuan_target;
                }else if ( $y->type_target == '2' ){
                    $target = $y->target_min.' - '.$y->target_max.' '.$y->satuan_target;
                }

                $i['id']                            = $x->id;
                $i['no']                            = $no;
                $i['rencana_kinerja']               = $x->label;
                $i['indikator_id']                  = $y->id;
                $i['indikator_kinerja_individu']    = $y->label;
                $i['manual_indikator_kinerja_id']   = $manual_indikator_id;
                $i['parent_id']                     = $x->parent_id;
                $i['parent_label']                  = ( $x->parent_id != null )?$x->Parent->label:"";
                $i['target']                        = $target;
                $i['satuan_target']                 = $y->satuan_target;
                $i['target_min']                    = $y->target_min;
                $i['target_max']                    = $y->target_max;
                $i['perspektif']                    = $y->perspektif;
                $i['aspek']                         = ucfirst($y->aspek);
                array_push($response['data'], $i);
            }
        }else{

            $i['id']                            = $x->id;
            $i['no']                            = $no;
            $i['rencana_kinerja']               = $x->label;
            $i['indikator_id']                  = "";
            $i['indikator_kinerja_individu']    = "";
            $i['manual_indikator_kinerja_id']   = "disabled";
            $i['parent_id']                     = $x->parent_id;
            $i['parent_label']                  = ( $x->parent_id != null )?$x->Parent->label:"";
            $i['target']                        = "";
            $i['satuan_target']                 = "";
            $i['target_min']                    = "";
            $i['target_max']                    = "";
            $i['perspektif']                    = "";
            $i['aspek']                         = "";
            array_push($response['data'], $i);
        }



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
                $query->where('sasaran_kinerja_id', '=', $sasaranKinerjaId );
            });
        }
    }

    private function applyJenis()
    {
          if( $this->jenis != '' ){
            $jenis = urldecode( $this->jenis );
            $this->query->where(function( $query ) use ( $jenis ){
                $query->where('jenis_rencana_kinerja', '=', $jenis );
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
