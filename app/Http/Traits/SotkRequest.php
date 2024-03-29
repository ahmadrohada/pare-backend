<?php
namespace App\Http\Traits;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

trait SotkRequest
{

    //DETAIL SKPD FROM SOTK APP
    public function Skpd($skpd_id){

        $id = $skpd_id;
        //$token = env('SIMPEG_APP_TOKEN');
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5M2NlNGNhOS1iNDczLTRmMzctYmQzNC0xYTAzYzVjNjFlNTgiLCJqdGkiOiI3NmJhYjJkZDJjNWI1OGRlMTJiYWI3MWJiY2QxYzNjZTZhNzFmYTE0ZTJmODQ2YjBiNjRmODQyZGI2NTc4Zjc3NDBhY2I3ZTk4Njc3MTllNSIsImlhdCI6MTY1OTUwMTgwNywibmJmIjoxNjU5NTAxODA3LCJleHAiOjE2OTEwMzc4MDcsInN1YiI6IiIsInNjb3BlcyI6WyIqIl19.TQaQf6TEl852YMLYq2sR423EqabCKCPPtt9ZkgSv-DSX1hyZrhXNaRHRLDsIqKuXutenH-j26ncIxkBbVBx3MahAZwrFViw3vwnlOmHPIoOyxbjarm_FvLA2tHRq3MPFwjBoB53nkC6E9dclcLdtnJeNJnutfJldkkq-aQGTZz52GOjtzS4yizqnFvBKlOgEw3R-3UAUm5ieXI0p0msC-3V4IRpX7JNGSu0Sws9tcyCRcyg8I2Xk7RLe8J_3Oo9Ay8EpMUG7mahot1n4i-zRVDWB31OvcPyqPUtjSZ5Zx8b7N-uC9Px1_ShqOn0t6snai0DYbE8fW3AEuObiJ6QSXEc3Z2dnr_FtAwK7-cBvlBtZqpLkiGrFD1JjvsCWMxtPldpUHYn6Ug5fZdKrC5-4EqfxQIB1axb7sEufR95b7ZIcvs7SpU6MMwb3Jcsubi-en-jHRTrCZu0-K_LYEg0TXJHxAnhHNm4ArkppF-i7r6W5cvtx-NQXdkylB3JQMOP7gAXEraOBmLatYF0ELqKA1Bjw8MP9n8J5qosUgmTKbZtDoUR4a5E4v40rGW6_-XfYv59scmqjm4BoafyYDtFK0KidGsbyqVP4J9W6qb0kkdCtn1i3wpQ9SpRCMMZa9iRyaqrmzqzW3hZTJIPRI1IE42-HeaaKCrAL2RumgsznftA";
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];


        try{
            $client = new Client([
                'base_uri' => 'https://api.sim-asn.bkpsdm.karawangkab.go.id',
                'verify' => false,
                'timeout' => 10, // Response timeout
                'connect_timeout' => 10, // Connection timeout
                'peer' => false
            ]);
            $response = $client->request('GET', '/api/sotk/skpd/'.$id,[
                'headers'   => $headers
            ]);
            $body = $response->getBody();
            $arr_body = json_decode($body,true);

            if ( isset($arr_body['data']) && ($arr_body['data'] != null) ){
                return $arr_body['data'];
            }else{
                return null;
            }


        }catch(\GuzzleHttp\Exception\GuzzleException $e) {
            return $e;
        }
    }

    protected function Pegawai($nip){
        //$token = env('SIMPEG_APP_TOKEN');
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5M2NlNGNhOS1iNDczLTRmMzctYmQzNC0xYTAzYzVjNjFlNTgiLCJqdGkiOiI3NmJhYjJkZDJjNWI1OGRlMTJiYWI3MWJiY2QxYzNjZTZhNzFmYTE0ZTJmODQ2YjBiNjRmODQyZGI2NTc4Zjc3NDBhY2I3ZTk4Njc3MTllNSIsImlhdCI6MTY1OTUwMTgwNywibmJmIjoxNjU5NTAxODA3LCJleHAiOjE2OTEwMzc4MDcsInN1YiI6IiIsInNjb3BlcyI6WyIqIl19.TQaQf6TEl852YMLYq2sR423EqabCKCPPtt9ZkgSv-DSX1hyZrhXNaRHRLDsIqKuXutenH-j26ncIxkBbVBx3MahAZwrFViw3vwnlOmHPIoOyxbjarm_FvLA2tHRq3MPFwjBoB53nkC6E9dclcLdtnJeNJnutfJldkkq-aQGTZz52GOjtzS4yizqnFvBKlOgEw3R-3UAUm5ieXI0p0msC-3V4IRpX7JNGSu0Sws9tcyCRcyg8I2Xk7RLe8J_3Oo9Ay8EpMUG7mahot1n4i-zRVDWB31OvcPyqPUtjSZ5Zx8b7N-uC9Px1_ShqOn0t6snai0DYbE8fW3AEuObiJ6QSXEc3Z2dnr_FtAwK7-cBvlBtZqpLkiGrFD1JjvsCWMxtPldpUHYn6Ug5fZdKrC5-4EqfxQIB1axb7sEufR95b7ZIcvs7SpU6MMwb3Jcsubi-en-jHRTrCZu0-K_LYEg0TXJHxAnhHNm4ArkppF-i7r6W5cvtx-NQXdkylB3JQMOP7gAXEraOBmLatYF0ELqKA1Bjw8MP9n8J5qosUgmTKbZtDoUR4a5E4v40rGW6_-XfYv59scmqjm4BoafyYDtFK0KidGsbyqVP4J9W6qb0kkdCtn1i3wpQ9SpRCMMZa9iRyaqrmzqzW3hZTJIPRI1IE42-HeaaKCrAL2RumgsznftA";
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        try{
            $client = new Client([
                'base_uri' => 'https://api.sim-asn.bkpsdm.karawangkab.go.id',
                'verify' => false,
                'timeout' => 10, // Response timeout
                'connect_timeout' => 10, // Connection timeout
                'peer' => false
            ]);
            $response = $client->request('GET', '/api/pegawai/'.$nip,[
                'headers' => $headers
            ]);
            $body = $response->getBody();
            $arr_body = json_decode($body,true);
            if ( isset($arr_body['data']) && ($arr_body['data'] != null) ){
                return $arr_body['data'];
            }else{
                return null;
            }

        }catch(\GuzzleHttp\Exception\GuzzleException $e) {
            return $e;
        }
    }


    protected function Jabatan($id){
        //$token = env('SIMPEG_APP_TOKEN');
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5M2NlNGNhOS1iNDczLTRmMzctYmQzNC0xYTAzYzVjNjFlNTgiLCJqdGkiOiI3NmJhYjJkZDJjNWI1OGRlMTJiYWI3MWJiY2QxYzNjZTZhNzFmYTE0ZTJmODQ2YjBiNjRmODQyZGI2NTc4Zjc3NDBhY2I3ZTk4Njc3MTllNSIsImlhdCI6MTY1OTUwMTgwNywibmJmIjoxNjU5NTAxODA3LCJleHAiOjE2OTEwMzc4MDcsInN1YiI6IiIsInNjb3BlcyI6WyIqIl19.TQaQf6TEl852YMLYq2sR423EqabCKCPPtt9ZkgSv-DSX1hyZrhXNaRHRLDsIqKuXutenH-j26ncIxkBbVBx3MahAZwrFViw3vwnlOmHPIoOyxbjarm_FvLA2tHRq3MPFwjBoB53nkC6E9dclcLdtnJeNJnutfJldkkq-aQGTZz52GOjtzS4yizqnFvBKlOgEw3R-3UAUm5ieXI0p0msC-3V4IRpX7JNGSu0Sws9tcyCRcyg8I2Xk7RLe8J_3Oo9Ay8EpMUG7mahot1n4i-zRVDWB31OvcPyqPUtjSZ5Zx8b7N-uC9Px1_ShqOn0t6snai0DYbE8fW3AEuObiJ6QSXEc3Z2dnr_FtAwK7-cBvlBtZqpLkiGrFD1JjvsCWMxtPldpUHYn6Ug5fZdKrC5-4EqfxQIB1axb7sEufR95b7ZIcvs7SpU6MMwb3Jcsubi-en-jHRTrCZu0-K_LYEg0TXJHxAnhHNm4ArkppF-i7r6W5cvtx-NQXdkylB3JQMOP7gAXEraOBmLatYF0ELqKA1Bjw8MP9n8J5qosUgmTKbZtDoUR4a5E4v40rGW6_-XfYv59scmqjm4BoafyYDtFK0KidGsbyqVP4J9W6qb0kkdCtn1i3wpQ9SpRCMMZa9iRyaqrmzqzW3hZTJIPRI1IE42-HeaaKCrAL2RumgsznftA";
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        try{
            $client = new Client([
                'base_uri' => 'https://api.sim-asn.bkpsdm.karawangkab.go.id',
                'verify' => false,
                'timeout' => 10, // Response timeout
                'connect_timeout' => 10, // Connection timeout
                'peer' => false
            ]);
            $response = $client->request('GET', '/api/sotk/jabatan/'.$id.'?with=referensi',[
                'headers' => $headers
            ]);
            $body = $response->getBody();
            $arr_body = json_decode($body,true);
            if ( isset($arr_body['data']) && ($arr_body['data'] != null) ){
                return $arr_body['data'];
            }else{
                return null;
            }

        }catch(\GuzzleHttp\Exception\GuzzleException $e) {
            return $e;
        }
    }

}
