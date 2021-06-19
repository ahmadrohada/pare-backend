<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use Illuminate\Http\Request;

use GuzzleHttp\Client;


class DailyReportController extends Controller
{


    protected function absensi_today($nip){
        $work_date = date('Y-m-d'); // diambil dari detail user absensi hari ini
        try{
            $client = new Client([
                'base_uri' => 'https://api-siap.silk.bkpsdm.karawangkab.go.id',
                'verify' => false,
                'timeout' => 3, // Response timeout
                'connect_timeout' => 3, // Connection timeout
                'peer' => false
            ]);
            $response = $client->request('GET', '/absensi/summary/'.$nip.'/'.$work_date, [
                'form_params' => [
                    'access_token'  => 'MjIzNTZmZjItNTJmOS00NjA1LTk5YWEtOGQwN2VhNmIwNjVm',
                ],
            ]);
            $body = $response->getBody();
            $arr_body = json_decode($body);
            $data = $arr_body->dailyAbsensis;
            return $data;
        }catch(\GuzzleHttp\Exception\GuzzleException $e) {
            return "error";
        }
    }

    protected function work_schedule($nip,$work_date){
        try{
            $client = new Client([
                'base_uri' => 'https://api-siap.silk.bkpsdm.karawangkab.go.id',
                'verify' => false,
                'timeout' => 3, // Response timeout
                'connect_timeout' => 3, // Connection timeout
                'peer' => false
            ]);
            $response = $client->request('GET', '/absensi/summary/'.$nip.'/'.$work_date, [
                'form_params' => [
                    'access_token'  => 'MjIzNTZmZjItNTJmOS00NjA1LTk5YWEtOGQwN2VhNmIwNjVm',
                ],
            ]);
            $body = $response->getBody();
            $arr_body = json_decode($body);

            if ( $arr_body->data->holiday == null ){
                return  "kerja";
            }else{
                return $arr_body->data->holiday->label;
            }
        }catch(\GuzzleHttp\Exception\GuzzleException $e) {
            return "error";
        }
    }

    protected function schedule_detail($nip,$work_date){
        try{
            $client = new Client([
                'base_uri' => 'https://api-siap.silk.bkpsdm.karawangkab.go.id',
                'verify' => false,
                'timeout' => 3, // Response timeout
                'connect_timeout' => 3, // Connection timeout
                'peer' => false
            ]);
            $response = $client->request('GET', '/absensi/summary/'.$nip.'/'.$work_date, [
                'form_params' => [
                    'access_token'  => 'MjIzNTZmZjItNTJmOS00NjA1LTk5YWEtOGQwN2VhNmIwNjVm',
                ],
            ]);

            $body = $response->getBody();
            $arr_body = json_decode($body);
            $data = $arr_body->dailyAbsensis;

            if ( $data != null ){
                foreach ($data as $x) {
                    $data_pegawai['nama']       = $x->user->name;
                    $data_pegawai['nip']        = $x->user->nip;
                    $data_pegawai['jk']         = $x->user->gender;
                    $data_pegawai['jabatan']    = $x->user->jabatan;
                    $data_pegawai['golongan']   = $x->user->golongan;
                    $data_pegawai['eselon']     = $x->user->eselon;
                    $data_pegawai['skpd']       = $x->user->skpd;
                    $data_pegawai['unit']       = $x->user->unit;

                    $data_schedule['jam_masuk']     = $x->schedule->time->start;
                    $data_schedule['jam_pulang']    = $x->schedule->time->end;


                     //inisial jam masuk
                     $data_actual['jam_masuk']   = $x->schedule->time->start;
                     $data_actual['jam_pulang']  = $x->schedule->time->end;
                    foreach ($x->absensi as $y) {

                        if ( $y->type == 'start'){
                            $data_actual['jam_masuk']       = $y->timeActual;
                        }

                        if ( $y->type == 'end'){
                            $data_actual['jam_pulang']       = $y->timeActual;
                        }

                        foreach ($y->approvals as $z) {
                            $data_pejabat_penilai['jk']         = $z->gender;
                            $data_pejabat_penilai['nama']       = $z->name;
                            $data_pejabat_penilai['nip']        = $z->nip;
                        }
                    }




                }

                $schedule_detail['data_pegawai']                = $data_pegawai;
                $schedule_detail['data_pejabat_penilai']        = $data_pejabat_penilai;
                $schedule_detail['data_schedule']               = $data_schedule;
                $schedule_detail['data_actual']                 = $data_actual;


                return  $schedule_detail;

            }else{
                return "error";
            }


        }catch(\GuzzleHttp\Exception\GuzzleException $e) {
            return "error";
        }
    }



    protected function selisih($jam_masuk,$jam_pulang) {
        list($h,$m,$s) = explode(":",$jam_masuk);
        $dtAwal = mktime($h,$m,$s,"1","1","1");
        list($h,$m,$s) = explode(":",$jam_pulang); $dtAkhir = mktime($h,$m,$s,"1","1","1");
        $dtSelisih = $dtAkhir-$dtAwal;
        $totalmenit=$dtSelisih/60;
        $jam =explode(".",$totalmenit/60);
        $sisamenit=($totalmenit/60)-$jam[0];
        $sisamenit2=$sisamenit*60;
        $jml_jam=$jam[0];

        return $jml_jam.".".$sisamenit2;
    }


    public function daily_report_create(Request $request)
    {

        $work_date      = $request->work_date;
        $nip            = $request->nip;

        //return $this::schedule_detail($nip,$work_date);


        //cek apakah daily report dengan work_date dan nip tsb sudah ada atau belum
        $cek = DailyReport::WHERE('work_date', $work_date)
                            ->whereJsonContains('pegawai', ['nip' => $nip])
                            ->first();

        if ( $cek === null ){
            //return "bikin dulu";
            //cek hari kerja atau bukan
            if (  $this::work_schedule($nip,$work_date) === 'kerja' ) {
                //return "yes hari kerja";
                //user detail today, jika sudah absen itu juga
                $absensi_today = $this::absensi_today($nip);

                if ( $absensi_today != null ){ // sudah absen
                    //create daily report nya

                     //create daily report
                    $dr    = new DailyReport;

                    //pegawai attribut
                    $schedule_detail = $this::schedule_detail($nip,$work_date);

                    $dr->work_date          = $work_date;
                    $dr->jam_masuk          = $schedule_detail['data_schedule']['jam_masuk'];
                    $dr->jam_pulang         = $schedule_detail['data_schedule']['jam_pulang'];
                    $dr->waktu_istirahat    = 30;
                    $dr->allowance          = 25;
                    $dr->pegawai            = json_encode($schedule_detail['data_pegawai']);
                    $dr->pejabat_penilai    = json_encode($schedule_detail['data_pejabat_penilai']);



            if ( $dr->save() ) {
                $h['date']                = $work_date;
                $h['nip']                 = $nip;


                $data = DailyReport::WHERE('id',$dr->id)->first();
                $h['selisih_waktu_kerja']           = $this::selisih($data->jam_masuk,$data->jam_pulang);

                $h['data']                          = $data;
                return $h;

            }
                }else{
                    //belum absen
                    return response()->json(['errors' => "Belum Absen" ], 404);
                }
            }else{
                return response()->json(['errors' => $this::work_schedule($nip,$work_date) ], 404);
            }
        }else{
            //return "sudah ada";
            $h['selisih_waktu_kerja']           = $this::selisih($cek->jam_masuk,$cek->jam_pulang);
            $h['data']                          = $cek;
            return $h;
        }
    }

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DailyReport  $dailyReport
     * @return \Illuminate\Http\Response
     */
    public function show(DailyReport $dailyReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DailyReport  $dailyReport
     * @return \Illuminate\Http\Response
     */
    public function edit(DailyReport $dailyReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DailyReport  $dailyReport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DailyReport $dailyReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DailyReport  $dailyReport
     * @return \Illuminate\Http\Response
     */
    public function destroy(DailyReport $dailyReport)
    {
        //
    }
}
