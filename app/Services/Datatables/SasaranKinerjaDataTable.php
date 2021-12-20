<?php
namespace App\Services\Datatables;

use App\Models\SasaranKinerja;

class SasaranKinerjaDataTable {
    private $search;
    private $take;
    private $orderBy;
    private $orderDirection;
    private $query;

    public function __construct( $parameters )
    {
        $this->setLocalParameters( $parameters );
       $this->query = SasaranKinerja::SELECT(   'id AS id',
                                                'periode_penilaian->tahun AS periode_tahun',
                                                'pegawai_yang_dinilai->nama AS nama_pegawai',
                                                'pegawai_yang_dinilai->nip AS nip_pegawai',
                                                'jenis_jabatan_skp',
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
    $this->applySearch();
    $this->applyOrder();
    $this->applySkpdId();

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

        $i['id']                        = $x->id;
        $i['periode_id']                = $x->periode_pk_id;
        $i['periode_tahun']             = $x->periode_tahun;
        $i['nama_pegawai']              = $x->nama_pegawai;
        $i['nip_pegawai']               = $x->nip_pegawai;
        $i['jenis_jabatan_skp']         = $x->jenis_jabatan_skp;
        $i['created_at']                = $x->created_at;

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
                //$query->whereJsonContains('pegawai_yang_dinilai->skpd->id', 28 );
                $query->where('skpd_id', '=', $skpdId );
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
