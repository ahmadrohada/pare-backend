<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skp;


class SkpTableSeeder extends Seeder
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
                'id'                        => 1, //SKP NYA KABAN
                'renja_peran_id'            => 1,
                'jenis_jabatan'             => 'jpt',
                'pegawai_id'                => 559,
                'skpd_id'                   => 42,
                'pegawai'                   => json_encode([
                                                    "pegawai_id"=> 559,
                                                    "nama"      => "H.Asep Aang Rahmatullah, S.STP.,MP.",
                                                    "nip"       => "197805211997111001",
                                                    "pangkat"   => "Pembina Tk.I" ,
                                                    "golongan"  => "IV/b" ,
                                                    "jabatan"   => "Kepala Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                    "unit_kerja"=> "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                    "skpd"      => "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                ]),
                'pejabat_penilai'           => json_encode([
                                                    "pegawai_id"=> 572,
                                                    "nama"      => "Drs.H..Acep Jamhuri, M.Si",
                                                    "nip"       => "196804191988031002",
                                                    "pangkat"   => "Jabatan Pimpinan Tinggi Pratama" ,
                                                    "golongan"  => "IV/d" ,
                                                    "jabatan"   => "Sekretaris Daerah",
                                                    "unit_kerja"=> "Sekretariat Daerah",
                                                    "skpd"      => "Sekretariat Daerah",
                                                ]),
                'atasan_pejabat_penilai'    => json_encode([
                                                    "pegawai_id"=> null,
                                                    "nama"      => "dr. Cellica Nurachadiana",
                                                    "nip"       => "",
                                                    "pangkat"   => "" ,
                                                    "golongan"  => "" ,
                                                    "jabatan"   => "Kepala Daerah Kab. Karawang",
                                                    "unit_kerja"=> "",
                                                    "skpd"      => "",
                                                ]),
                'periode_penilaian_awal'    => '2021-01-01',
                'periode_penilaian_akhir'   => '2021-12-31',
                'status'                    => 'open',
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
            [
                'id'                        => 2, //SKP NYA SEKBAN
                'renja_peran_id'            => 2,
                'jenis_jabatan'             => 'ja',
                'pegawai_id'                => 48,
                'skpd_id'                   => 42,
                'pegawai'                   => json_encode([
                                                    "pegawai_id"=> 48,
                                                    "nama"      => "Jajang Jaenudin, S.STP., MM",
                                                    "nip"       => "198006062000121001",
                                                    "pangkat"   => "Jabatan Administrator" ,
                                                    "golongan"  => "III/a" ,
                                                    "jabatan"   => "Sekretaris Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                    "unit_kerja"=> "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                    "skpd"      => "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                ]),
                'pejabat_penilai'           => json_encode([
                                                    "pegawai_id"=> 559,
                                                    "nama"      => "H.Asep Aang Rahmatullah, S.STP.,MP.",
                                                    "nip"       => "197805211997111001",
                                                    "pangkat"   => "Pembina Tk.I" ,
                                                    "golongan"  => "IV/b" ,
                                                    "jabatan"   => "Kepala Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                    "unit_kerja"=> "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                    "skpd"      => "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                ]),
                'atasan_pejabat_penilai'    => json_encode([
                                                    "pegawai_id"=> 572,
                                                    "nama"      => "Drs.H..Acep Jamhuri, M.Si",
                                                    "nip"       => "196804191988031002",
                                                    "pangkat"   => "Jabatan Pimpinan Tinggi Pratama" ,
                                                    "golongan"  => "IV/d" ,
                                                    "jabatan"   => "Sekretaris Daerah",
                                                    "unit_kerja"=> "Sekretariat Daerah",
                                                    "skpd"      => "Sekretariat Daerah",
                                                ]),
                'periode_penilaian_awal'    => '2021-01-01',
                'periode_penilaian_akhir'   => '2021-12-31',
                'status'                    => 'open',
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
            [
                'id'                        => 3, ////SKP NYA KABID PENGEMBANGAN PEGAWAI
                'renja_peran_id'            => 3,
                'jenis_jabatan'             => 'ja',
                'pegawai_id'                => 71,
                'skpd_id'                   => 42,
                'pegawai'                   => json_encode([
                                                    "pegawai_id"=> 71,
                                                    "nama"      => "H..Suhendar, S.Sos",
                                                    "nip"       => "196207171990071001",
                                                    "pangkat"   => "Jabatan Administrator" ,
                                                    "golongan"  => "III/a" ,
                                                    "jabatan"   => "Kepala Bidang Pengembangan Pegawai ASN",
                                                    "unit_kerja"=> "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                    "skpd"      => "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                ]),
                'pejabat_penilai'           => json_encode([
                                                    "pegawai_id"=> 559,
                                                    "nama"      => "H.Asep Aang Rahmatullah, S.STP.,MP.",
                                                    "nip"       => "197805211997111001",
                                                    "pangkat"   => "Pembina Tk.I" ,
                                                    "golongan"  => "IV/b" ,
                                                    "jabatan"   => "Kepala Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                    "unit_kerja"=> "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                    "skpd"      => "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                ]),
                'atasan_pejabat_penilai'    => json_encode([
                                                    "pegawai_id"=> 572,
                                                    "nama"      => "Drs.H..Acep Jamhuri, M.Si",
                                                    "nip"       => "196804191988031002",
                                                    "pangkat"   => "Jabatan Pimpinan Tinggi Pratama" ,
                                                    "golongan"  => "IV/d" ,
                                                    "jabatan"   => "Sekretaris Daerah",
                                                    "unit_kerja"=> "Sekretariat Daerah",
                                                    "skpd"      => "Sekretariat Daerah",
                                                ]),
                'periode_penilaian_awal'    => '2021-01-01',
                'periode_penilaian_akhir'   => '2021-12-31',
                'status'                    => 'open',
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
            [
                'id'                        => 4, ////SKP NYA KASUBID MUTASI dan JABATAN
                'renja_peran_id'            => 4,
                'jenis_jabatan'             => 'ja',
                'pegawai_id'                => 72,
                'skpd_id'                   => 42,
                'pegawai'                   => json_encode([
                                                    "pegawai_id"=> 72,
                                                    "nama"      => "Wawan Kusdiawan, S.Kom., M.Kom.",
                                                    "nip"       => "197908112011011001",
                                                    "pangkat"   => "Penata Tk.I" ,
                                                    "golongan"  => "III/a" ,
                                                    "jabatan"   => "Kepala Sub Bidang Mutasi dan Jabatan Aparatur Sipil Negara",
                                                    "unit_kerja"=> "Bidang Pengembangan Pegawai ASN",
                                                    "skpd"      => "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                ]),
                'pejabat_penilai'           => json_encode([
                                                    "pegawai_id"=> 71,
                                                    "nama"      => "H..Suhendar, S.Sos",
                                                    "nip"       => "196207171990071001",
                                                    "pangkat"   => "Jabatan Administrator" ,
                                                    "golongan"  => "III/a" ,
                                                    "jabatan"   => "Kepala Bidang Pengembangan Pegawai ASN",
                                                    "unit_kerja"=> "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                    "skpd"      => "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                ]),
                'atasan_pejabat_penilai'    => json_encode([
                                                    "pegawai_id"=> 559,
                                                    "nama"      => "H.Asep Aang Rahmatullah, S.STP.,MP.",
                                                    "nip"       => "197805211997111001",
                                                    "pangkat"   => "Pembina Tk.I" ,
                                                    "golongan"  => "IV/b" ,
                                                    "jabatan"   => "Kepala Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                    "unit_kerja"=> "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                    "skpd"      => "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                ]),
                'periode_penilaian_awal'    => '2021-01-01',
                'periode_penilaian_akhir'   => '2021-12-31',
                'status'                    => 'open',
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
            [
                'id'                        => 5, ////SKP NYA KASUBID KINERJA DAN KOMPETENSI
                'renja_peran_id'            => 5,
                'jenis_jabatan'             => 'ja',
                'pegawai_id'                => 79,
                'skpd_id'                   => 42,
                'pegawai'                   => json_encode([
                                                    "pegawai_id"=> 79,
                                                    "nama"      => "Marsidik Ari Kustijo, S. Sos",
                                                    "nip"       => "197403222005011005",
                                                    "pangkat"   => "Penata Tk.I" ,
                                                    "golongan"  => "III/d" ,
                                                    "jabatan"   => "Kepala Sub Bidang Kinerja dan Kompetensi ASN",
                                                    "unit_kerja"=> "Bidang Pengembangan Pegawai ASN",
                                                    "skpd"      => "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                ]),
                'pejabat_penilai'           => json_encode([
                                                    "pegawai_id"=> 71,
                                                    "nama"      => "H..Suhendar, S.Sos",
                                                    "nip"       => "196207171990071001",
                                                    "pangkat"   => "Jabatan Administrator" ,
                                                    "golongan"  => "III/a" ,
                                                    "jabatan"   => "Kepala Bidang Pengembangan Pegawai ASN",
                                                    "unit_kerja"=> "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                    "skpd"      => "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                ]),
                'atasan_pejabat_penilai'    => json_encode([
                                                    "pegawai_id"=> 559,
                                                    "nama"      => "H.Asep Aang Rahmatullah, S.STP.,MP.",
                                                    "nip"       => "197805211997111001",
                                                    "pangkat"   => "Pembina Tk.I" ,
                                                    "golongan"  => "IV/b" ,
                                                    "jabatan"   => "Kepala Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                    "unit_kerja"=> "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                    "skpd"      => "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                ]),
                'periode_penilaian_awal'    => '2021-01-01',
                'periode_penilaian_akhir'   => '2021-12-31',
                'status'                    => 'open',
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
            [
                'id'                        => 6, ////SKP NYA ANALIS JABATAN
                'renja_peran_id'            => 6,
                'jenis_jabatan'             => 'jf',
                'pegawai_id'                => 96,
                'skpd_id'                   => 42,
                'pegawai'                   => json_encode([
                                                    "pegawai_id"=> 96,
                                                    "nama"      => "Pungky Hermayanti, S.Kom",
                                                    "nip"       => "198503122011012010",
                                                    "pangkat"   => "Penata Tk.I" ,
                                                    "golongan"  => "III/d" ,
                                                    "jabatan"   => "Analis Jabatan",
                                                    "unit_kerja"=> "Sub Bidang Mutasi dan Jabatan Aparatur Sipil Negara",
                                                    "skpd"      => "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                ]),
                'pejabat_penilai'           => json_encode([
                                                    "pegawai_id"=> 72,
                                                    "nama"      => "Wawan Kusdiawan, S.Kom., M.Kom.",
                                                    "nip"       => "197908112011011001",
                                                    "pangkat"   => "Penata Tk.I" ,
                                                    "golongan"  => "III/d" ,
                                                    "jabatan"   => "Kepala Sub Bidang Mutasi dan Jabatan Aparatur Sipil Negara",
                                                    "unit_kerja"=> "Bidang Pengembangan Pegawai ASN",
                                                    "skpd"      => "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",

                                                ]),
                'atasan_pejabat_penilai'    => json_encode([
                                                    "pegawai_id"=> 71,
                                                    "nama"      => "H..Suhendar, S.Sos",
                                                    "nip"       => "196207171990071001",
                                                    "pangkat"   => "Jabatan Administrator" ,
                                                    "golongan"  => "III/a" ,
                                                    "jabatan"   => "Kepala Bidang Pengembangan Pegawai ASN",
                                                    "unit_kerja"=> "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                    "skpd"      => "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                ]),
                'periode_penilaian_awal'    => '2021-01-01',
                'periode_penilaian_akhir'   => '2021-12-31',
                'status'                    => 'open',
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
            [
                'id'                        => 7, ////SKP NYA KOMPETENSI
                'renja_peran_id'            => 7,
                'jenis_jabatan'             => 'jf',
                'pegawai_id'                => 12170,
                'skpd_id'                   => 42,
                'pegawai'                   => json_encode([
                                                    "pegawai_id"=> 12170,
                                                    "nama"      => "Mayke Yolanda Sovianingrum, S.STP",
                                                    "nip"       => "199505062017082001",
                                                    "pangkat"   => "Penata Tk.I" ,
                                                    "golongan"  => "III/d" ,
                                                    "jabatan"   => "Analis Kompetensi",
                                                    "unit_kerja"=> "Sub Bidang Mutasi dan Jabatan Aparatur Sipil Negara",
                                                    "skpd"      => "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                ]),
                'pejabat_penilai'           => json_encode([
                                                    "pegawai_id"=> 79,
                                                    "nama"      => "Marsidik Ari Kustijo, S. Sos",
                                                    "nip"       => "197403222005011005",
                                                    "pangkat"   => "Penata Tk.I" ,
                                                    "golongan"  => "III/d" ,
                                                    "jabatan"   => "Kepala Sub Bidang Kinerja dan Kompetensi ASN",
                                                    "unit_kerja"=> "Bidang Pengembangan Pegawai ASN",
                                                    "skpd"      => "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",

                                                ]),
                'atasan_pejabat_penilai'    => json_encode([
                                                    "pegawai_id"=> 71,
                                                    "nama"      => "H..Suhendar, S.Sos",
                                                    "nip"       => "196207171990071001",
                                                    "pangkat"   => "Jabatan Administrator" ,
                                                    "golongan"  => "III/a" ,
                                                    "jabatan"   => "Kepala Bidang Pengembangan Pegawai ASN",
                                                    "unit_kerja"=> "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                    "skpd"      => "Badan Kepegawaian dan Pengembangan Sumber Daya Manusia",
                                                ]),
                'periode_penilaian_awal'    => '2021-01-01',
                'periode_penilaian_akhir'   => '2021-12-31',
                'status'                    => 'open',
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],

        ];

        Skp::insert($data);
    }
}
