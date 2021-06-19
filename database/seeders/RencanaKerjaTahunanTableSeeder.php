<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RencanaKerjaTahunan;

class RencanaKerjaTahunanTableSeeder extends Seeder
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
                'id'            => 1,
                'renja_id'      => 1,
                'label'         => 'Tingkat penerapan sistem merit',
                'level'         => 's0',
                'type'          => 'tujuan',
                'parent_id'     => null,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 2,
                'renja_id'      => 1,
                'label'         => 'Meningkatkan standar penerapan sistem merit',
                'level'         => 's1',
                'type'          => 'sasaran',
                'parent_id'     => 1,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 3,
                'renja_id'      => 1,
                'label'         => 'Kepegawaian Daerah',
                'level'         => 's2',
                'type'          => 'program',
                'parent_id'     => 2,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 4,
                'renja_id'      => 1,
                'label'         => 'Pengembangan Sumber Daya Manusia',
                'level'         => 's2',
                'type'          => 'program',
                'parent_id'     => 2,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 5,
                'renja_id'      => 1,
                'label'         => 'Pengadaan, Pemberhentian dan Informasi Kepegawaian ASN',
                'level'         => 's3',
                'type'          => 'kegiatan',
                'parent_id'     => 3,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 6,
                'renja_id'      => 1,
                'label'         => 'Pengembangan Kompetensi ASN',
                'level'         => 's3',
                'type'          => 'kegiatan',
                'parent_id'     => 3,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 7,
                'renja_id'      => 1,
                'label'         => 'Mutasi dan Promosi ASN',
                'level'         => 's3',
                'type'          => 'kegiatan',
                'parent_id'     => 3,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 8,
                'renja_id'      => 1,
                'label'         => 'Penilaian dan Evaluasi Kinerja Aparatur',
                'level'         => 's3',
                'type'          => 'kegiatan',
                'parent_id'     => 3,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 9,
                'renja_id'      => 1,
                'label'         => 'Sertifikasi, Kelembagaan, Pengembangan Kompetensi Manajerial dan Fungsional',
                'level'         => 's3',
                'type'          => 'kegiatan',
                'parent_id'     => 4,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 10,
                'renja_id'      => 1,
                'label'         => 'Koordinasi Pelaksanaan Administrasi Pemberhentian',
                'level'         => 's4',
                'type'          => 'subkegiatan',
                'parent_id'     => 5,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 11,
                'renja_id'      => 1,
                'label'         => 'Penyusunan Rencana Kebutuhan, Jenis dan Jumlah Jabatan untuk Pelaksanaan Pengadaan ASN',
                'level'         => 's4',
                'type'          => 'subkegiatan',
                'parent_id'     => 5,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 12,
                'renja_id'      => 1,
                'label'         => 'Fasilitasi Lembaga Profesi ASN',
                'level'         => 's4',
                'type'          => 'subkegiatan',
                'parent_id'     => 5,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 13,
                'renja_id'      => 1,
                'label'         => 'Evaluasi Data, Informasi dan Sistem Informasi Kepegawaian',
                'level'         => 's4',
                'type'          => 'subkegiatan',
                'parent_id'     => 5,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 14,
                'renja_id'      => 1,
                'label'         => 'Pembinaan Jabatan Fungsional ASN',
                'level'         => 's4',
                'type'          => 'subkegiatan',
                'parent_id'     => 6,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 15,
                'renja_id'      => 1,
                'label'         => 'Fasilitas Pengembangan Karir dalam Jabatan Fungsional',
                'level'         => 's4',
                'type'          => 'subkegiatan',
                'parent_id'     => 6,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 16,
                'renja_id'      => 1,
                'label'         => 'Pengelolaan Assessment Center',
                'level'         => 's4',
                'type'          => 'subkegiatan',
                'parent_id'     => 6,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 17,
                'renja_id'      => 1,
                'label'         => 'Pengelolaan Pendidikan Lanjutan ASN',
                'level'         => 's4',
                'type'          => 'subkegiatan',
                'parent_id'     => 6,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 18,
                'renja_id'      => 1,
                'label'         => 'Pengelolaan Mutasi ASN',
                'level'         => 's4',
                'type'          => 'subkegiatan',
                'parent_id'     => 7,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 19,
                'renja_id'      => 1,
                'label'         => 'Pengelolaan Promosi ASN',
                'level'         => 's4',
                'type'          => 'subkegiatan',
                'parent_id'     => 7,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 20,
                'renja_id'      => 1,
                'label'         => 'Pengelolaan Kenaikan Pangkat ASN',
                'level'         => 's4',
                'type'          => 'subkegiatan',
                'parent_id'     => 7,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 21,
                'renja_id'      => 1,
                'label'         => 'Pelaksanaan Penilaian dan Evaluasi Kinerja Aparatur',
                'level'         => 's4',
                'type'          => 'subkegiatan',
                'parent_id'     => 8,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 22,
                'renja_id'      => 1,
                'label'         => 'Pengelolaan Pemberian Penghargaan Bagi Pegawai',
                'level'         => 's4',
                'type'          => 'subkegiatan',
                'parent_id'     => 8,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 23,
                'renja_id'      => 1,
                'label'         => 'Pembinaan Disiplin ASN',
                'level'         => 's4',
                'type'          => 'subkegiatan',
                'parent_id'     => 8,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 24,
                'renja_id'      => 1,
                'label'         => 'Pengelolaan Tanda Jasa Bagi Pegawai',
                'level'         => 's4',
                'type'          => 'subkegiatan',
                'parent_id'     => 8,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 25,
                'renja_id'      => 1,
                'label'         => 'Pembinaan, Pengoordinasian, Fasilitasi, Pemantauan, Evaluasi, dan Pelaporan Pelaksanaan Sertifikasi, Pengelolaan Kelembagaan dan Tenaga Pengembang Kompetensi, Pengelolaan Sumber Belajar, dan Kerjasama',
                'level'         => 's4',
                'type'          => 'subkegiatan',
                'parent_id'     => 9,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'id'            => 26,
                'renja_id'      => 1,
                'label'         => 'Penyelenggaraan Pengembangan Kompetensi bagi Pimpinan Daerah, Jabatan Pimpinan Tinggi, Jabatan Fungsional, Kepemimpinan, dan Prajabatan',
                'level'         => 's4',
                'type'          => 'subkegiatan',
                'parent_id'     => 9,
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],


        ];

        RencanaKerjaTahunan::insert($data);
    }
}
