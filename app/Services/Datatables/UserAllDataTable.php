<?php
namespace App\Services\Datatables;

use App\Models\User;
use App\Models\RoleUser;

class UserAllDataTable {
    private $search;
    private $take;
    private $orderBy;
    private $orderDirection;
    private $query;

    public function __construct( $parameters )
    {
        $this->setLocalParameters( $parameters );
        //$querya = "CAST(id_golongan AS DECIMAL(10,2)) ASC";
        $this->query = User:: select(
                                    'users.username AS username',
                                    'users.id AS id_user',
                                    'users.nip AS nip',
                                    'users.pegawai AS pegawai',
                                    'users.pegawai->skpd AS skpd',
                                    'users.pegawai->nama_lengkap AS nama_lengkap',
                                    'users.pegawai->jabatan AS jabatan',
                                    'users.pegawai->jabatan->referensi->id_referensi AS jabatan_id',
                                    'users.pegawai->jabatan.nama AS nama_jabatan',
                                    'users.pegawai->golongan->referensi->id AS id_golongan',

        )
                                //->orderBY(CAST'users.pegawai->golongan->referensi->id AS FLOAT','ASC');
                                //->orderBYRaw($querya);
                                ->orderBY('users.id','DESC');
    }

    private function setLocalParameters( $parameters )
    {

        $this->take = isset( $parameters['take'] ) ? $parameters['take'] : 10;
        $this->orderBy = isset( $parameters['order_by'] ) ? $parameters['order_by'] : null;
        $this->orderDirection = isset( $parameters['order_direction'] ) ? $parameters['order_direction'] : 'DESC';
        $this->search = isset( $parameters['search'] ) ? $parameters['search'] : '';

    }
    // ... Other methods and set up
    public function search()
    {
    $this->applySearch();
    $this->applyOrder();


    $cafes = $this->query->paginate( $this->take );
    $pagination = array(
        'current_page'  => $cafes->currentPage(),
        'total_page'    => ( ($cafes->perPage() != 0 ) && ($cafes->total() != 0 )  ) ? ceil($cafes->total()/$cafes->perPage()) : 0,
        'per_page'      => $cafes->count(),
        'limit'         => (int)$cafes->perPage(),
        'total'         => $cafes->total(),
    );

    $data = $cafes->getCollection();
    $response['data'] = array();

    foreach( $data AS $x ){


                $h['pegawai']      = $x->pegawai;
                $h['jabatan']      = json_decode($x->jabatan);

                //$h['id_golongan']	= $x->id_golongan;
                $h['id']			= $x->id_user;
                $h['username']      = $x->username;
                $h['nip']           = $x->nip;
                $h['nama_lengkap']  = $x->nama_lengkap;

                //SKPD
                $data_skpd          = json_decode($x->skpd);
                $h['skpd']          = ($data_skpd != null )?$data_skpd->nama:null;


                array_push($response['data'], $h);


    }

    return [
        'data'          => $response['data'],
        'pagination'    => $pagination,

    ];


    }


    private function applyOrder()
    {
       /*  switch( $this->orderBy ){
            case 'likes':
                $this->query->withCount('likes as liked')
                     ->orderByRaw('liked DESC');
            break;
            default:
                //$this->query->orderBy( 'users.pegawai->jabatan->referensi->id_referensi', 'asc' );
            break;
        } */
    }

    public function applySearch()
    {
        if( $this->search != '' ){
            $search = urldecode( $this->search );

            $this->query->where(function( $query ) use ( $search ){

                $query->whereRaw('LOWER(JSON_EXTRACT(users.pegawai, "$.nama_lengkap")) like ?', ['"%' . strtolower($search) . '%"']);

            });
        }
    }
}
