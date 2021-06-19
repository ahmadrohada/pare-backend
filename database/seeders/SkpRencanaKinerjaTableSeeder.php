<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SkpRencanaKinerja;

class SkpRencanaKinerjaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id'                        => 1,
                'skp_id'                    => 1,
                'jenis_kinerja'             => 'kinerja_utama',
                'type_kinerja_utama'        => 'perjanjian_kinerja',
                'penyelarasan_kinerja_utama'=> '',
                'label'                     => 'Meningkatkan standar penerapan sistem merit',
                'parent_id'                 => null,
                //'revisi'                    => 0,
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
            [
                'id'                        => 2,
                'skp_id'                    => 1,
                'jenis_kinerja'             => 'kinerja_utama',
                'type_kinerja_utama'        => 'perjanjian_kinerja',
                'penyelarasan_kinerja_utama'=> '',
                'label'                     => 'Meningkatnya Akuntabilitas Kinerja BKPSDM',
                'parent_id'                 => null,
                //'revisi'                    => 0,
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
            [
                'id'                        => 3,
                'skp_id'                    => 3,
                'jenis_kinerja'             => 'kinerja_utama',
                'type_kinerja_utama'        => '',
                'penyelarasan_kinerja_utama'=> 'direct_cascading',
                'label'                     => 'Meningkatkan penerapan merit sistem pada aspek pengembangan karier',
                'parent_id'                 => 1,
                //'revisi'                    => 0,
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
            [
                'id'                        => 4,
                'skp_id'                    => 3,
                'jenis_kinerja'             => 'kinerja_utama',
                'type_kinerja_utama'        => '',
                'penyelarasan_kinerja_utama'=> 'direct_cascading',
                'label'                     => 'Meningkatkan penerapan merit sistem pada aspek manajemen kinerja',
                'parent_id'                 => 1,
                //'revisi'                    => 0,
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
            [
                'id'                        => 5,
                'skp_id'                    => 3,
                'jenis_kinerja'             => 'kinerja_utama',
                'type_kinerja_utama'        => '',
                'penyelarasan_kinerja_utama'=> 'direct_cascading',
                'label'                     => 'Meningkatkan penerapan merit sistem pada aspek mutasi dan promosi',
                'parent_id'                 => 1,
                //'revisi'                    => 0,
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
            [
                'id'                        => 6,
                'skp_id'                    => 4,
                'jenis_kinerja'             => 'kinerja_utama',
                'type_kinerja_utama'        => '',
                'penyelarasan_kinerja_utama'=> 'direct_cascading',
                'label'                     => 'Tertatanya kelompok jabatan fungsional',
                'parent_id'                 => 3,
                //'revisi'                    => 0,
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
            [
                'id'                        => 7,
                'skp_id'                    => 4,
                'jenis_kinerja'             => 'kinerja_utama',
                'type_kinerja_utama'        => '',
                'penyelarasan_kinerja_utama'=> 'direct_cascading',
                'label'                     => 'Terfasilitasinya penilaian angka kredit jabatan fungsional',
                'parent_id'                 => 3,
                //'revisi'                    => 0,
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
            [
                'id'                        => 8,
                'skp_id'                    => 5,
                'jenis_kinerja'             => 'kinerja_utama',
                'type_kinerja_utama'        => '',
                'penyelarasan_kinerja_utama'=> 'direct_cascading',
                'label'                     => 'Terlaksanakanya penilaian kompetensi pegawai',
                'parent_id'                 => 3,
                //'revisi'                    => 0,
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
            [
                'id'                        => 9,
                'skp_id'                    => 5,
                'jenis_kinerja'             => 'kinerja_utama',
                'type_kinerja_utama'        => '',
                'penyelarasan_kinerja_utama'=> 'direct_cascading',
                'label'                     => 'Terfasilitasinya layanan pendidikan lanjutan pegawai',
                'parent_id'                 => 3,
                //'revisi'                    => 0,
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
            [
                'id'                        => 10,
                'skp_id'                    => 5,
                'jenis_kinerja'             => 'kinerja_utama',
                'type_kinerja_utama'        => '',
                'penyelarasan_kinerja_utama'=> 'direct_cascading',
                'label'                     => 'Terfasilitasinya layanan pendidikan lanjutan pegawai',
                'parent_id'                 => 3,
                //'revisi'                    => 0,
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],

            //ANALIS JABATAN
            [
                'id'                        => 11,
                'skp_id'                    => 6,
                'jenis_kinerja'             => 'kinerja_utama',
                'type_kinerja_utama'        => '',
                'penyelarasan_kinerja_utama'=> 'direct_cascading',
                'label'                     => 'Tersedianya data PAK masing Pejabat Fungsional di SIMPEG',
                'parent_id'                 => 6,
                //'revisi'                    => 0,
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
            [
                'id'                        => 12,
                'skp_id'                    => 6,
                'jenis_kinerja'             => 'kinerja_utama',
                'type_kinerja_utama'        => '',
                'penyelarasan_kinerja_utama'=> 'direct_cascading',
                'label'                     => 'Layanan administrasi kepegawain pejabat fungsional dilakukan dengan cepat dan akurat',
                'parent_id'                 => 6,
                //'revisi'                    => 0,
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
            [
                'id'                        => 13,
                'skp_id'                    => 6,
                'jenis_kinerja'             => 'kinerja_utama',
                'type_kinerja_utama'        => '',
                'penyelarasan_kinerja_utama'=> 'direct_cascading',
                'label'                     => 'Layanan administrasi kepegawain pejabat fungsional dilakukan dengan cepat dan akurat',
                'parent_id'                 => 6,
                //'revisi'                    => 0,
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
            [
                'id'                        => 14,
                'skp_id'                    => 6,
                'jenis_kinerja'             => 'kinerja_utama',
                'type_kinerja_utama'        => '',
                'penyelarasan_kinerja_utama'=> 'direct_cascading',
                'label'                     => 'Administrasi kepegawaian pejabat fungsional terdokumentasikan dengan rapih',
                'parent_id'                 => 6,
                //'revisi'                    => 0,
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
            [
                'id'                        => 15,
                'skp_id'                    => 6,
                'jenis_kinerja'             => 'kinerja_utama',
                'type_kinerja_utama'        => '',
                'penyelarasan_kinerja_utama'=> 'direct_cascading',
                'label'                     => 'Administrasi kepegawaian pejabat fungsional terdokumentasikan dengan rapih',
                'parent_id'                 => 6,
                //'revisi'                    => 0,
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
            [
                'id'                        => 16,
                'skp_id'                    => 6,
                'jenis_kinerja'             => 'kinerja_utama',
                'type_kinerja_utama'        => '',
                'penyelarasan_kinerja_utama'=> 'direct_cascading',
                'label'                     => 'Dokumen administrasi kepegawaian pejabat fungsional tersampaikan tepat sasaran',
                'parent_id'                 => 6,
                //'revisi'                    => 0,
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],

            //Analis Kompetensi
            [
                'id'                        => 17,
                'skp_id'                    => 7,
                'jenis_kinerja'             => 'kinerja_utama',
                'type_kinerja_utama'        => '',
                'penyelarasan_kinerja_utama'=> 'direct_cascading',
                'label'                     => 'Layanan usulan uji kompetensi tepat waktu',
                'parent_id'                 => 8,
                //'revisi'                    => 0,
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
            [
                'id'                        => 18,
                'skp_id'                    => 7,
                'jenis_kinerja'             => 'kinerja_utama',
                'type_kinerja_utama'        => '',
                'penyelarasan_kinerja_utama'=> 'direct_cascading',
                'label'                     => 'Tersedianya data pegawai yang dinilai kompetensinya secara valid',
                'parent_id'                 => 8,
                //'revisi'                    => 0,
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
            [
                'id'                        => 19,
                'skp_id'                    => 7,
                'jenis_kinerja'             => 'kinerja_utama',
                'type_kinerja_utama'        => '',
                'penyelarasan_kinerja_utama'=> 'direct_cascading',
                'label'                     => 'Terlaksananya persiapan akomodasi pelaksanaan penilain kompetensi ',
                'parent_id'                 => 8,
                //'revisi'                    => 0,
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],



        ];

        SkpRencanaKinerja::insert($data);
    }
}
