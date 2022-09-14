<?php
namespace App\Services\Datatables;

use App\Models\SasaranKinerja;

class SasaranKinerjaBawahanDataTable {
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
                                                'pegawai_yang_dinilai->jabatan AS jabatan_pegawai',
                                                'pegawai_yang_dinilai->nip AS nip_pegawai',

                                                'pejabat_penilai->nama AS nama_pejabat_penilai',
                                                'pejabat_penilai->jabatan AS jabatan_pejabat_penilai',
                                                'pejabat_penilai->nip AS nip_pejabat_penilai',

                                                'atasan_pejabat_penilai->nama AS nama_atasan_pejabat_penilai',
                                                'atasan_pejabat_penilai->jabatan AS jabatan_atasan_pejabat_penilai',
                                                'atasan_pejabat_penilai->nip AS nip_atasan_pejabat_penilai',

                                                'pejabat_penilai',
                                                'atasan_pejabat_penilai',


                                                'jenis_jabatan_skp',
                                                'status AS status',
                                                'created_at AS created_at');
    }

    private function setLocalParameters( $parameters )
    {
        $this->nipPejabatPenilai = isset( $parameters['nip_pejabat_penilai'] ) ? $parameters['nip_pejabat_penilai'] : 0;
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
    $this->applyNipPejabatPenilai();

    $cafes = $this->query->paginate( $this->take );
    $pagination = array(
        'currentPage'   => $cafes->currentPage(),
        'total_page'    => ( ($cafes->perPage() != 0 ) && ($cafes->total() != 0 )  ) ? Floor($cafes->total()/$cafes->count()) : 0,

        'limit'         => (int)$cafes->perPage(),
        'total'         => $cafes->total(),

        'pageSize'      => $cafes->count(),
        'pageCount'     => ( ($cafes->perPage() != 0 ) && ($cafes->total() != 0 )  ) ? Floor($cafes->total()/$cafes->count()) : 0,
    );

    $data = $cafes->getCollection();
    $response['data'] = array();
    $no = 0;

    foreach( $data AS $x ){
        $no = $no+1;

        $i['id']                        = $x->id;
        $i['periode_id']                = $x->periode_pk_id;
        $i['periode_tahun']             = $x->periode_tahun;

        $i['nama_pegawai']              = $x->nama_pegawai;
        $i['jabatan_pegawai']           = $x->jabatan_pegawai;
        $i['nip_pegawai']               = $x->nip_pegawai;

        $i['nama_pejabat_penilai']            = $x->nama_pejabat_penilai;
        $i['jabatan_pejabat_penilai']         = $x->jabatan_pejabat_penilai;
        $i['nip_pejabat_penilai']             = $x->nip_pejabat_penilai;

        $i['nama_atasan_pejabat_penilai']     = $x->nama_atasan_pejabat_penilai;
        $i['jabatan_atasan_pejabat_penilai']  = $x->jabatan_atasan_pejabat_penilai;
        $i['nip_atasan_pejabat_penilai']      = $x->nip_atasan_pejabat_penilai;


        $i['pejabat_penilai']                   = json_decode($x->pejabat_penilai);
        $i['atasan_pejabat_penilai']            = json_decode($x->atasan_pejabat_penilai);

        $i['rencana_kerja']                     = json_decode($x->RencanaKinerja);


        $i['jenis_jabatan_skp']         = $x->jenis_jabatan_skp;
        $i['created_at']                = $x->created_at;
        $i['status']                    = $x->status;

        array_push($response['data'], $i);

    }

    return [
        'data'          => $response['data'],
        'pagination'    => $pagination,

    ];


    }
    // ... Other methods and set up
    private function applyNipPejabatPenilai()
    {
        if( $this->nipPejabatPenilai != '' ){
            $nipPejabatPenilai = urldecode( $this->nipPejabatPenilai );

            $this->query->where(function( $query ) use ( $nipPejabatPenilai ){
                $query->where('pejabat_penilai->nip', '=', $nipPejabatPenilai );
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

                $query->whereRaw('LOWER(JSON_EXTRACT(pegawai_yang_dinilai, "$.nama")) like ?', ['"%' . strtolower($search) . '%"'])
                        ->orwhere('jenis_jabatan_skp', 'LIKE', '%'.$search.'%')
                        ->orwhere('periode_penilaian->tahun', 'LIKE', '%'.$search.'%');
                      //->orWhere('username', 'LIKE', '%'.$search.'%');
            });
        }
    }
}
