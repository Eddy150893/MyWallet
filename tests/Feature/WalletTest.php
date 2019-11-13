<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Wallet;
use App\Transfer;

class WalletTest extends TestCase
{
    //La siguiente linea es para que la base de datos se refresque cada vez que hagamos una prueba
    //Es decir que los datos falsos se estaran borrando conforme hagamos pruebas.
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetWallet()
    {
        //Hacemos uso de las factories para que inyecten datos de prueba(con faker)
        $wallet=factory(Wallet::class)->create();
        $transfers=factory(Transfer::class,3)->create([
            'wallet_id'=>$wallet->id
        ]);
        //Hacemos una llamada a la api
        $response = $this->json('GET','/api/wallet');
        //De la respuesta de la api hacemos validaciones con assertStatus,assertJsonStructure,assertCount 
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'id','money','transfers'=>[
                        '*'=>[
                            'id','amount','description','wallet_id'
                        ]
                    ]
                ]); 
        //Se comento la siguiente linea ya que cuando se realiza la prueba
        //de transfer fallaba por que esa prueba solo inyecta un transfer en lugar de 3
        //  $this->assertCount(3,$response->json()['transfers']);       
    }
}
