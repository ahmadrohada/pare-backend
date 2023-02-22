<!DOCTYPE html>
<html>

<head>
	<title>Sasaran Kinerja Pegawai</title>
	<link href="{{public_path('css/printout_style.css') }}" rel="stylesheet" type="text/css" />
</head>

<body>

	<table class="table-cover-skp">
		<tr>
			<td colspan="2" style="height:20px;">
				<span class="header-hasil_kerja">KINERJA PEGAWAI</span>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="height:20px;">
				<span class="header-hasil_kerja">PENDEKATAN HASIL KERJA KUANTITATIF</span>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="height:20px;">
				<span class="header-hasil_kerja">{{Str::upper($pegawai_yang_dinilai['unit_kerja'])}}</span>
			</td>
		</tr>
		<tr>
			<td width="55%" style="height:40px; text-align:left; vertical-align:bottom;">
				<span class="header-hasil_kerja2">{{Str::upper($pegawai_yang_dinilai['unit_kerja'])}}</span>
			</td>
			<td width="45%" style="height:40px; text-align:right; vertical-align:bottom;"">
				<span class="header-hasil_kerja2">PERIODE PENILAIAN : {{$periode_penilaian}}</span>
			</td>
		</tr>
	</table>


	@php
		$arrayForTable = [];
		foreach ($rencana_hasil_kerja as $dbValue) {
			$temp = [];
			
			$temp['rencana_hasil_kerja_atasan'] = $dbValue['rencana_hasil_kerja_atasan'];
			$temp['rencana_hasil_kerja'] 		= $dbValue['rencana_hasil_kerja'];
			$temp['indikator_kinerja_individu'] = $dbValue['indikator_kinerja_individu'];
			//TARGET
			$temp['aspek'] 						= $dbValue['aspek'];
			$temp['target'] 					= $dbValue['target'];
			
			if(!isset($arrayForTable[$dbValue['rencana_hasil_kerja']])){
			$arrayForTable[$dbValue['rencana_hasil_kerja']] = [];
			}
			$arrayForTable[$dbValue['rencana_hasil_kerja']][] = $temp;
			
		}
		$no = 1 ;
	@endphp

	


	<table class="table-hasil_kerja-skp" >
		<tr>
			<td width="2%" class="table-header">NO</td>
			<td width="35%" colspan="2" class="table-header">PEGAWAI YANG DINILAI</td>
			<td width="2%"  class="table-header">NO</td>
			<td width="60%" colspan="4" class="table-header">PEJABAT PENILAI KINERJA</td>
		
		</tr>
		<tr>
			<td style="text-align:center;">1</td>
			<td style="width:180px;">NAMA</td>
			<td style="width:300px;">{{$pegawai_yang_dinilai['nama']}}</td>
			<td style="text-align:center;">1</td>
			<td style="width:130px; border-right:none;">NAMA</td>
			<td style="width:50px; border-left:none;"></td>
			<td style="width:300px;" colspan="2" >{{$pejabat_penilai['nama']}}</td>
		</tr>
		<tr>
			<td style="text-align:center;">2</td>
			<td>NIP</td>
			<td>{{$pegawai_yang_dinilai['nip']}}</td>
			<td style="text-align:center;">2</td>
			<td colspan="2" >NIP</td>
			<td colspan="2" >{{$pejabat_penilai['nip']}}</td>
		</tr>
		<tr>
			<td style="text-align:center;">3</td>
			<td>PANGKAT GOL. RUANG</td>
			<td>{{$pegawai_yang_dinilai['pangkat']}}</td>
			<td style="text-align:center;">3</td>
			<td colspan="2" >PANGKAT GOL. RUANG</td>
			<td colspan="2" >{{$pejabat_penilai['pangkat']}}</td>
		</tr>
		<tr>
			<td style="text-align:center;">4</td>
			<td>JABATAN</td>
			<td>{{$pegawai_yang_dinilai['jabatan']}}</td>
			<td style="text-align:center;">4</td>
			<td colspan="2" >JABATAN</td>
			<td colspan="2" >{{$pejabat_penilai['jabatan']}}</td>
		</tr>
		<tr>
			<td style="text-align:center;">5</td>
			<td>UNIT KERJA</td>
			<td>{{$pegawai_yang_dinilai['unit_kerja']}}</td>
			<td style="text-align:center;">5</td>
			<td colspan="2" >UNIT KERJA</td>
			<td colspan="2" >{{$pejabat_penilai['unit_kerja']}}</td>
		</tr>
		<tr>
			<td class="table-header" style="text-align:left !important;" colspan="8">HASIL KERJA</td>
		</tr>
		<tr>
			<td class="table-header">NO</td>
			<td class="table-header" style="width:180px;">RENCANA HASIL KERJA ATASAN YANG DIINTERVENSI</td>
			<td class="table-header" colspan="2">RENCANA HASIL KERJA</td>
			<td class="table-header" style="width:120px;">ASPEK</td>
			<td class="table-header" colspan="2">INDIKATOR KINERJA INDIVIDU</td>
			<td class="table-header" style="width:120px;">TARGET</td>
		</tr>
		<tr>
			<td style="background:#F5FFFA; text-align:center;">(1)</td>
			<td style="background:#F5FFFA; text-align:center;">(2)</td>
			<td colspan="2" style="background:#F5FFFA;text-align:center;">(3)</td>
			<td style="background:#F5FFFA;text-align:center;">(4)</td>
			<td colspan="2" style="background:#F5FFFA;text-align:center;">(5)</td>
			<td style="background:#F5FFFA;text-align:center;">(6)</td>
		</tr>

		<tr>
			<td class="table-header" style="text-align:left !important;" colspan="8" >A. UTAMA</td>
		</tr>

		<tbody>
			@foreach ($arrayForTable as $id => $values) :
			@foreach ($values as $key=>$value) :
			<tr>
				@if ($key == 0) :
					<td style="text-align:center; border-bottom: 1px dotted #ffffff;">{{$no}}</td>
					@php $no++; @endphp
				@else
					<td style="border-top: 1px dotted #ffffff; border-bottom: 1px dotted #ffffff;"></td>
				@endif

				@if ($key == 0) :
					<td style="border-bottom: 1px dotted #ffffff;">{{ $value['rencana_hasil_kerja_atasan']}}</td>
				@else
					<td style="border-top: 1px dotted #ffffff; border-bottom: 1px dotted #ffffff;"></td>
				@endif

				@if ($key == 0) :
					<td colspan="2" style="border-bottom: 1px dotted #ffffff;">{{  $value['rencana_hasil_kerja']}}</td>
				@else
					<td colspan="2" style="border-top: 1px dotted #ffffff; border-bottom: 1px dotted #ffffff;"></td>
				@endif

				<td align='center' >{{  $value['aspek'] }}</td>
				<td align='center' colspan="2"  >{{  $value['indikator_kinerja_individu'] }}</td>
				<td align='center' >{{  $value['target'] }}</td>
			</tr>
			@endforeach
			@endforeach
	
		</tbody>

		<tr>
			<td class="table-header" style="text-align:left !important;" colspan="8">B. TAMBAHAN</td>
		</tr>

		<tr>
			<td style="text-align:center;">1</td>
			<td>-</td>
			<td colspan="2">-</td>
			<td>-</td>
			<td colspan="2">-</td>
			<td>-</td>
		</tr>


		<tr>
			<td class="table-header" style="text-align:left !important;" colspan="8">PERILAKU KERJA</td>
		</tr>


		<tr>
			<td style="text-align:center; border-bottom: 1px dotted #ffffff;">1</td>
			<td colspan="7">Berorientasi pelayanan</td>
		</tr>
		<tr>
			<td style="border-top: 1px dotted #ffffff;"></td>
			<td colspan="2" style="vertical-align:top;">
				- Memahami dan memenuhi kebutuhan masyarakat<br>
				- Ramah, cekatan, solutif dan dapat diandalkan<br>
				- melakukan perbaikan tiada henti
			</td>
			<td colspan="5" style="vertical-align:top;">
				Ekspektasi Khusus Pimpinan:<br>
				Senantiasa mengutamakan kepuasan penerima layanan inspektoprat
			</td>
		</tr>

		<tr>
			<td style="text-align:center; border-bottom: 1px dotted #ffffff;">2</td>
			<td colspan="7">Akuntable</td>
		</tr>
		<tr>
			<td style="border-top: 1px dotted #ffffff;"></td>
			<td colspan="2" style="vertical-align:top;">
				- Melaksanakan tugas dengan jujur, bertanggungjawab, cermat, disiplin dan berintegritas tinggi <br>
				- Ramah, cekatan, solutif dan dapat diandalkan<br>
				- melakukan perbaikan tiada henti
			</td>
			<td colspan="5" style="vertical-align:top;">
				Ekspektasi Khusus Pimpinan:<br>
				Menjadi role model/panutan dalam menjunjung komitmen dan integritas pagawai di lingkungan kerjanya
			</td>
		</tr>

		<tr>
			<td style="text-align:center; border-bottom: 1px dotted #ffffff;">3</td>
			<td colspan="7">Kompeten</td>
		</tr>
		<tr>
			<td style="border-top: 1px dotted #ffffff;"></td>
			<td colspan="2" style="vertical-align:top;">
				- Meningkatkan kompetensi diri untukmmenjawab tantangan yang sellau berubah<br>
				- Membantu orang lain belajar<br>
				- Melaksanakan tugas dengan kualitas terbaik<br>
				- tes
			</td>
			<td colspan="5" style="vertical-align:top;">
				Ekspektasi Khusus Pimpinan:<br>
				Menyelesaiakna setiap pekerjaan dengan target dan standar kulaitas yang suidah ditetapkan
			</td>
		</tr>

		<tr>
			<td style="text-align:center; border-bottom: 1px dotted #ffffff;">4</td>
			<td colspan="7">Harmonis</td>
		</tr>
		<tr>
			<td style="border-top: 1px dotted #ffffff;"></td>
			<td colspan="2" style="vertical-align:top;">
				- Menghargai setiap orang apaapun latar belakang nya<br>
				- Suka menolomn orang lain<br>
				- Membangun lingkungan kerja yang kondusif
			</td>
			<td colspan="5" style="vertical-align:top;">
				Ekspektasi Khusus Pimpinan:<br>
				Membangun komunikasi yang lebih terbuka
			</td>
		</tr>

		<tr>
			<td style="text-align:center; border-bottom: 1px dotted #ffffff;">5</td>
			<td colspan="7">Loyal</td>
		</tr>
		<tr>
			<td style="border-top: 1px dotted #ffffff;"></td>
			<td colspan="2" style="vertical-align:top;">
				- Menghargai setiap orang apaapun latar belakang nya<br>
				- Suka menolomn orang lain<br>
				- Membangun lingkungan kerja yang kondusif
			</td>
			<td colspan="5" style="vertical-align:top;">
				Ekspektasi Khusus Pimpinan:<br>
				Membangun komunikasi yang lebih terbuka
			</td>
		</tr>
		

	</table>


	<table class="table-hasil_kerja-skp">
		<tr>
			<td width="10%">
				null
			</td>
			<td>
				TTD
			</td>
			<td width="40%">
				null
			</td>
			<td>
				TTD
			</td>
			<td width="10%">
				null
			</td>
		</tr>
	</table>

</body>

</html>