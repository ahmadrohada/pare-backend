<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\DailyActivity;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Boolean;

use GuzzleHttp\Client;

use Validator;


class DailyActivityController extends Controller
{




    public function tes_siap(Request $request)
    {



        try {
            $client = new Client([
                'base_uri' => 'https://api-siap.silk.bkpsdm.karawangkab.go.id',
                'verify' => false,
                'timeout' => 3, // Response timeout
                'connect_timeout' => 3, // Connection timeout
                'peer' => false
            ]);

            $response = $client->request('GET', '/absensi/summary/197201102009012001/2021-06-07', [
                'form_params' => [
                    'access_token'  => 'MjIzNTZmZjItNTJmOS00NjA1LTk5YWEtOGQwN2VhNmIwNjVm',
                ],
            ]);
            $body = $response->getBody();
            $arr_body = json_decode($body);

            if ($arr_body->data->holiday == null) {
                return  "kerja";
            } else {
                return $arr_body->data->holiday->label;
            }
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
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



    public function create()
    {
        //
    }



    public function daily_activity_list(Request $request)
    {

        //iniitial value
        $work_date   = "allDate";
        $nip         = "allPegawai";
        $data     = [];

        $data_table = DailyReport::Join('daily_activity', function ($join) {
            $join->on('daily_activity.daily_report_id', '=', 'daily_report.id');
        })
            ->SELECT(
                'daily_report.work_date',
                'daily_report.jam_masuk',
                'daily_report.jam_pulang',
                'daily_report.waktu_istirahat',
                'daily_report.allowance',

                'daily_activity.id AS daily_activity_id',
                'daily_activity.title AS title',
                'daily_activity.hasil AS hasil',
                'daily_activity.start_time AS start_time',
                'daily_activity.end_time AS end_time',
                'daily_activity.location AS location',
                'daily_activity.attribut->isReadOnly AS isReadOnly',
                'daily_activity.attribut->category AS category',
                'daily_activity.attribut->calendarId AS calendarId'
            );


        if ($request->nip != null) {
            $nip = $request->nip;
            $dt = $data_table->whereJsonContains('daily_report.pegawai', ['nip' => $request->nip]);
        }

        if ($request->work_date != null) {
            $work_date = $request->work_date;
            $dt = $data_table->WHERE('daily_report.work_date', '=', $request->work_date);
        }




        $dt = $data_table->paginate(20);
        $jm_menit_kerja = 0 ;

        foreach ($dt as $activity) {
            $data[] = array(
                "id"            => $activity->daily_activity_id,
                "title"         => $activity->title,
                "hasil"         => $activity->hasil,
                "start"         => $activity->work_date . ' ' . $activity->start_time,
                "end"           => $activity->work_date . ' ' . $activity->end_time,
                "location"      => $activity->location,

                "isReadOnly"    => (bool)$activity->isReadOnly,
                "category"      => $activity->category,
                "calendarId"    => $activity->calendarId,

            );

            //penjumlahan menit kerja aktual
            $mins = (strtotime($activity->end_time) - strtotime($activity->start_time)) / 60;

            $jm_menit_kerja =  $jm_menit_kerja + $mins ;
        }


        //hitung caapaian




        $h['date']                  = $work_date;
        $h['jumlah_menit_kerja']    = $jm_menit_kerja;
        $h['nip']                   = $nip;
        $h['data']                  = $data;

        return $h;
    }

    public function daily_activity_show(Request $request)
    {
        $activity = DailyActivity::SELECT(
            'id',
            'title',
            'hasil',
            'start_time',
            'end_time',
            'location',
            'attribut->isReadOnly AS isReadOnly',
            'attribut->category AS category',
            'attribut->calendarId AS calendarId'
        )
            ->WHERE('id', $request->id)
            ->first();


        if ($activity) {
            $data[] = array(
                "id"            => $activity->id,
                "title"         => $activity->title,
                "hasil"         => $activity->hasil,
                "start"         => $activity->workdate . ' ' . $activity->start_time,
                "end"           => $activity->workdate . ' ' . $activity->end_time,
                "location"      => $activity->location,

                "isReadOnly"    => (bool)$activity->isReadOnly,
                "category"      => $activity->category,
                "calendarId"    => $activity->calendarId,


            );
        } else {
            $data = null;
        }



        $h['date']                = "tes tgl";
        $h['data']                = $data;

        return $h;
    }




    public function daily_activity_store(Request $request)
    {

        //untuk cek data jam awal dah akhir kegiatan sesuai absensi
        $daily_report = DailyReport::
                                    SELECT(
                                            'pegawai->nip AS nip',
                                            'work_date'
                                        )
                                    ->WHERE('id', $request->daily_report_id )
                                    ->first();
        $schedule_detail = $this::schedule_detail($daily_report->nip,$daily_report->work_date);
        $jam_masuk = date('H:i', strtotime('-1 minutes', strtotime($schedule_detail['data_actual']['jam_masuk'])));
        $jam_pulang = date('H:i', strtotime('+1 minutes', strtotime($schedule_detail['data_actual']['jam_pulang'])));


        $messages = [
            'daily_report_id.required'  => 'Harus diisi',
            'kegiatan.required'         => 'Harus diisi',
            'start.required'            => 'Harus diisi',
            'end.required'              => 'Harus diisi',

        ];



        $validator = Validator::make(
            $request->all(),
            [
                'daily_report_id'           => 'required',
                'kegiatan'                  => 'required',
                'start_time'                => 'required|date_format:H:i|after:'.$jam_masuk,
                'end_time'                  => 'required|date_format:H:i||after:start_time|before:'.$jam_pulang,

            ],
            $messages
        );

        if ($validator->fails()) {
            //$messages = $validator->messages();
            return response()->json(['errors' => $validator->messages()], 422);
        }


        $ah    = new DailyActivity;
        $ah->daily_report_id    = $request->daily_report_id;
        $ah->title              = $request->kegiatan;
        $ah->hasil              = $request->hasil;
        $ah->start_time         = $request->start_time;
        $ah->end_time           = $request->end_time;

        if ($ah->save()) {
            return \Response::make('succesful', 200);
        } else {
            return \Response::make('error', 500);
        }
    }

    public function daily_activity_update(Request $request)
    {

        //untuk cek data jam awal dah akhir kegiatan sesuai absensi
        $daily_activity = DailyActivity::
                                    Join('daily_report', function ($join) {
                                        $join->on('daily_report.id', '=', 'daily_activity.daily_report_id');
                                    })
                                    ->SELECT(
                                            'daily_report.pegawai->nip AS nip',
                                            'daily_report.work_date'
                                        )
                                    ->WHERE('daily_activity.id', $request->daily_activity_id )
                                    ->first();

        $schedule_detail = $this::schedule_detail($daily_activity->nip,$daily_activity->work_date);
        $jam_masuk = date('H:i', strtotime('-1 minutes', strtotime($schedule_detail['data_actual']['jam_masuk'])));
        $jam_pulang = date('H:i', strtotime('+1 minutes', strtotime($schedule_detail['data_actual']['jam_pulang'])));


        $messages = [
            'daily_activity_id.required'=> 'Harus diisi',
            'start.required'            => 'Harus diisi',
            'end.required'              => 'Harus diisi',

        ];



        $validator = Validator::make(
            $request->all(),
            [
                'daily_activity_id'         => 'required',
                'start_time'                => 'required|date_format:H:i|after:'.$jam_masuk,
                'end_time'                  => 'required|date_format:H:i||after:start_time|before:'.$jam_pulang,

            ],
            $messages
        );

        if ($validator->fails()) {
            //$messages = $validator->messages();
            return response()->json(['errors' => $validator->messages()], 422);
        }



        $ah_update  = DailyActivity::find($request->daily_activity_id);

        $ah_update->start_time         = $request->start_time;
        $ah_update->end_time           = $request->end_time;
        $ah_update->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        //
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
