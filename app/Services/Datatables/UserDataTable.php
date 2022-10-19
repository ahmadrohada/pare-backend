<?php
namespace App\Services\Datatables;

use App\Models\User;
use App\Models\RoleUser;

class UserDataTable {
    private $search;
    private $take;
    private $orderBy;
    private $orderDirection;
    private $query;

    public function __construct( $parameters )
    {
        $this->setLocalParameters( $parameters );
        $querya = "CAST(id_golongan AS DECIMAL(10,2)) ASC";
        $this->query = User:: select(
                                    'users.username AS username',
                                    'users.id AS id_user',
                                    'users.nip AS nip',
                                    'users.pegawai->nama_lengkap AS nama_lengkap',
                                    'users.pegawai->jabatan AS jabatan',
                                    'users.pegawai->jabatan->referensi->id_referensi AS jabatan_id',
                                    'users.pegawai->jabatan.nama AS nama_jabatan',
                                    'users.pegawai->golongan->referensi->id AS id_golongan',

                                )
                                //->orderBY(CAST'users.pegawai->golongan->referensi->id AS FLOAT','ASC');
                                ->orderBYRaw($querya);
                                //->orderBY('users.id','DESC');
    }

    private function setLocalParameters( $parameters )
    {
        $this->skpdId = isset( $parameters['skpd_id'] ) ? $parameters['skpd_id'] : null;
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
    $this->applySkpdId();


    $cafes = $this->query->paginate( $this->take );
    $pagination = array(
        'current_page'  => ($cafes->total() != 0 )?$cafes->currentPage():0,
        'total_page'    => ( ($cafes->perPage() != 0 ) && ($cafes->total() != 0 )  ) ? ceil($cafes->total()/$cafes->perPage()) : 0,
        'per_page'      => $cafes->count(),
        'limit'         => (int)$cafes->perPage(),
        'total'         => $cafes->total(),
    );

    $data = $cafes->getCollection();
    $response['data'] = array();
    $no = 0;

    foreach( $data AS $x ){

        if ($x->jabatan ){
            //diulang sesuai jumlah jabatan
            foreach( json_decode($x->jabatan) AS $y ){
                //data pegawai

                //$h['nama_jabatan']      = json_decode($x->jabatan);

                //$h['id_golongan']	= $x->id_golongan;
                $h['id']			= $x->id_user;
                $h['username']      = $x->username;
                $h['nip']           = $x->nip;
                $h['nama_lengkap']  = $x->nama_lengkap;
                //data jabatan
                $h['jabatan']            = $y->nama;
                $h['jabatan_id']         = $y->id;


                //mengetahui jenis jabatan
                $jenis_jabatan = isset($y->referensi->referensi->jenis)?($y->referensi->referensi->jenis):null;
                $h['jenis_jabatan']     = $jenis_jabatan;
                $h['jabatan_referensi_id']  = $y->referensi->id_referensi;

                if ( $jenis_jabatan == 'struktural'){
                    //eselon
                    $h['jabatan_eselon_id']     = $y->referensi->referensi->id;
                    $h['jabatan_eselon']        = $y->referensi->referensi->eselon->eselon;

                }else if ( $jenis_jabatan == 'pelaksana'){
                    $h['jabatan_eselon_id']     = null;
                    $h['jabatan_eselon']        = null;
                }

                //golongan

                $h['jabatan_golongan_id']           = isset($y->golongan)?$y->golongan->referensi->id:null;
                $h['jabatan_golongan']              = isset($y->golongan)?$y->golongan->referensi->golongan:0;
                $h['jabatan_golongan_pangkat']      = isset($y->golongan)?$y->golongan->referensi->pangkat:0;


                //skpd
                $h['jabatan_skpd_nama']     = $y->skpd->nama;


                //is admin
                $admin = RoleUser::WHERE('user_id',$x->id_user)->WHERE('role_id','2')->exists();
                $h['is_admin'] = $admin;

                //push jika skpd nya relavan dengan yang dicari
                $h['jabatan_skpd_id']       = $y->skpd->id;
                if( $this->skpdId != null ) {
                    if ( $h['jabatan_skpd_id'] ==  $this->skpdId){
                        array_push($response['data'], $h);
                    }
                }else{
                    array_push($response['data'], $h);
                }


            }
        }

    }

    //$response['data'] = collect($response['data'])->sortByDesc('jabatan_golongan_id')->values();
    //$response['data'] = collect($response['data'])->sortBy('jabatan_referensi_id')->values();





    return [
        'data'          => $response['data'],
        'pagination'    => $pagination,

    ];


    }
    private function applySkpdId()
    {
        if( $this->skpdId != null ){
            $skpdId = urldecode( $this->skpdId );

            $this->query->where(function( $query ) use ( $skpdId ){
                $query->where('pegawai->skpd->id','=', $skpdId);
            });
        }
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
