<!DOCTYPE html>
<html>

<head>
	<title>Cover Sasaran Kinerja Pegawai</title>
	<link href="{{public_path('css/printout_style.css') }}" rel="stylesheet" type="text/css" />
</head>

<body>

	<table class="table-cover-skp">
		<tr>
			<td colspan="2">
				<img src="{{public_path('images/lambang-pancasila.png')}}" class="image-cover">
			</td>
		</tr>
		<tr>
			<td colspan="2" style="height:30px;">
				<span class="header-cover-skp">COVER SASARAN KINERJA PEGAWAI</span>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="height:30px;">
				<span class="header-cover-skp-periode">PERIODE* : TRIWULAN I/II/III/IV AKHIR **</span>
			</td>
		</tr>
		<tr>
			<td width="50%" style="height:60px; text-align:left; vertical-align:bottom;">
				<span class="header-cover-skp-periode">{{Str::upper($pegawai_yang_dinilai['unit_kerja'])}}</span>
			</td>
			<td width="50%" style="padding-left:70px; height:60px; text-align:left; vertical-align:bottom;"">
				<span class="header-cover-skp-periode">
					PERIODE PENILAIAN :<br>
					{{$periode_penilaian}}
				</span>
			</td>
		</tr>
	</table>

	{{-- @php
        $data = json_decode($data->pegawai_yang_dinilai, true);
    @endphp  --}}



	<table class="table-pegawai-skp">
		<tr>
			<td width="3%" rowspan="6" style="vertical-align:top; background:#F5FFFA;">1</td>
			<td colspan="3" style="text-align:left; background:#F5FFFA; padding-top:3px; padding-bottom:3px;">
				PEGAWAI YANG DINILAI
			</td>
		</tr>
		<tr>
			<td width="30%" style="text-align:left">
				NAMA
			</td>
			<td width="3%" style="text-align:center">:</td>
			<td width="70%" style="text-align:left">
				{{$pegawai_yang_dinilai['nama']}}
			</td>
		</tr>
		<tr>
			<td style="text-align:left">
				NIP
			</td>
			<td style="text-align:center">:</td>
			<td style="text-align:left">
				{{$pegawai_yang_dinilai['nip']}}
			</td>
		</tr>
		<tr>
			<td style="text-align:left">
				PANGKAT / GOL. RUANG
			</td>
			<td style="text-align:center">:</td>
			<td style="text-align:left">
				{{$pegawai_yang_dinilai['pangkat']}}
			</td>
		</tr>
		<tr>
			<td style="text-align:left">
				JABATAN
			</td>
			<td style="text-align:center">:</td>
			<td style="text-align:left">
				{{$pegawai_yang_dinilai['jabatan']}}
			</td>
		</tr>
		<tr>
			<td style="text-align:left">
				UNIT KERJA
			</td>
			<td style="text-align:center">:</td>
			<td style="text-align:left">
				{{$pegawai_yang_dinilai['unit_kerja']}}
			</td>
		</tr>

		<tr>
			<td width="3%" rowspan="6" style="vertical-align:top; background:#F5FFFA;">2</td>
			<td colspan="3" style="text-align:left; background:#F5FFFA; padding-top:3px; padding-bottom:3px;">
				PEJABAT PENILAI KINERJA
			</td>
		</tr>
		<tr>
			<td width="47%" style="text-align:left">
				NAMA
			</td>
			<td width="3%" style="text-align:center">:</td>
			<td style="text-align:left">
				{{$pejabat_penilai['nama']}}
			</td>
		</tr>
		<tr>
			<td style="text-align:left">
				NIP
			</td>
			<td style="text-align:center">:</td>
			<td style="text-align:left">
				{{$pejabat_penilai['nip']}}
			</td>
		</tr>
		<tr>
			<td style="text-align:left">
				PANGKAT / GOL. RUANG
			</td>
			<td style="text-align:center">:</td>
			<td style="text-align:left">
				{{$pejabat_penilai['pangkat']}}
			</td>
		</tr>
		<tr>
			<td style="text-align:left">
				JABATAN
			</td>
			<td style="text-align:center">:</td>
			<td style="text-align:left">
				{{$pejabat_penilai['jabatan']}}
			</td>
		</tr>
		<tr>
			<td style="text-align:left">
				UNIT KERJA
			</td>
			<td style="text-align:center">:</td>
			<td style="text-align:left">
				{{$pejabat_penilai['unit_kerja']}}
			</td>
		</tr>

		<tr>
			<td width="3%" rowspan="6" style="vertical-align:top; background:#F5FFFA;">3</td>
			<td colspan="3" style="text-align:left; background:#F5FFFA; padding-top:3px; padding-bottom:3px;">
				ATASAN PEJABAT PENILAI KINERJA
			</td>
		</tr>
		<tr>
			<td width="47%" style="text-align:left">
				NAMA
			</td>
			<td width="3%" style="text-align:center">:</td>
			<td style="text-align:left">
				{{$atasan_pejabat_penilai['nama']}}
			</td>
		</tr>
		<tr>
			<td style="text-align:left">
				NIP
			</td>
			<td style="text-align:center">:</td>
			<td style="text-align:left">
				{{$atasan_pejabat_penilai['nip']}}
			</td>
		</tr>
		<tr>
			<td style="text-align:left">
				PANGKAT / GOL. RUANG
			</td>
			<td style="text-align:center">:</td>
			<td style="text-align:left">
				{{$atasan_pejabat_penilai['pangkat']}}
			</td>
		</tr>
		<tr>
			<td style="text-align:left">
				JABATAN
			</td>
			<td style="text-align:center">:</td>
			<td style="text-align:left">
				{{$atasan_pejabat_penilai['jabatan']}}
			</td>
		</tr>
		<tr>
			<td style="text-align:left">
				UNIT KERJA
			</td>
			<td style="text-align:center">:</td>
			<td style="text-align:left">
				{{$atasan_pejabat_penilai['unit_kerja']}}
			</td>
		</tr>

	</table>

</body>

</html>