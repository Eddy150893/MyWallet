1. Crear el proyecto
    composer create-project --prefer-dist laravel/laravel walletApp
2. Crear la base de datos en mysql
3. configurar la base de datos en el archivo .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wallet
DB_USERNAME=root
DB_PASSWORD

4. Si la version de mysql es vieja para no tener errores al momento de la migracion se debe realizar lo siguiente
    en el archivo app/AppServiceProvider.php
    en la funcion boot
    se agrega la linea
    Schema::defaultStringLength(191);
Nota: para agregar el Schema en el boot se necesita el siguiente use
    use Illuminate\Support\Facades\Schema;
5. ya en el proyecto se abre una terminal y se ejecuta
    php artisan migrate

6. Configurar react en laravel
    php artisan preset react
Nota: Por default laravel viene configurado con vue y al ejecutar este comando hace el cambio a react

7. Ejecutamos los siguiente comandos para compilar react

npm install
npm run dev

8. Crear modelos
    php artisan make:model Wallet -m
    php artisan make:model Transfer -m
Nota: colocamos el flag -m por que asi ademas de crear el modelo crea una migracion para dicho modelo
9. En las migraciones generadas database/migrations/
    En las funciones up de dichas migraciones
    colocamos los campos que queremos que tengan nuestras tablas al ser creadas por medio de 
    php artisan migrate

10. Para pruebas vamos a utilizar factories
    Esta capa de factories nos va a servir para insertar informacion falsa durante las pruebas.
    php artisan make:factory WalletFactory --model=Wallet
    php artisan make:factory TransferFactory --model=Transfer
Se generan en database/Factories/
Nota: luego de indicar el nombre del factory le pasamos un flag --model el cual indica el modelo que tomara como referencia para inyectar datos.

Dentro de los factories existe una libreria la cual 
se utiliza como dependencia de los factories esta libreria nos ayuda a llenar con datos de prueba la base de datos su nombre es faker
El metodo define devuelve un arreglo con los datos falsos
un ejemplo de faker a continuación
'money' => $faker->numberBetween($min=500,$max=900)

 
11. Para dar inicio a las pruebas podemos utilizar php unit

Si lo tenemos globalmente
phpunit
Si no lo tenemos globalmente
vendor/bin/phpunit

Nos indicara que ya se realizaron pruebas ya que Laravel provee de una clase de ejemplo en 
tests/Feature/ExampleTest.php

Podemos eliminar este elemento y crear nuestras propias clases de ejemplo

12. Crear clases para pruebas unitarias
    php artisan make:test WalletTest
    php artisan make:test TransferTest
    Estas clases aparecen en tests/Feature/WalletTest.php

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
        $this->assertCount(3,$response->json()['transfers']);       
    }
}
En WalletTest su metodo getXXX lo rebautizamos testGetWallet
e introducimos toda la logica necesaria para hacer test, dentro de esta logica definimos una ruta
de api por lo tanto lo siguiente es definir rutas y crear controladores asi mismo dentro de la logica
de test hacemos uso de los modelos asi que debemos hacer uso de sus namespaces
use App\Wallet;
use App\Transfer;
Dentro de la logica de dicha clase para hacer las pruebas hacemos uso de la funcion factory
de la siguiente manera

13. Definir una ruta en routes/api.php y el controlador que respondera a esta ruta
    Route::get('/wallet','WalletController@index');
14. Definir un controlador
    php artisan make:Controller WalletController

15. Definir las relaciones de los modelos uno a muchos, muchos a muchos etc

Un Wallet tiene muchas tranfers

public function transfers(){
        return $this->hasMany('App\Transfer');
}

y una tranferencia solo puede estar en un wallet
public function wallet(){
        return $this->belongsTo('App\Wallet');
}

16. Crear el siguiente test solo que esta vez para transfer con el metodo post utilizando factories y 
creando una ruta con un controlador que respondera 
a dicha ruta
    php artisan make:Controller TransferController

17. Sembrando informacion con laravel
    php artisan make:seeder WalletsTableSeeder
    php artisan make:seeder TransferTableSeeder
Estos archivos se generan en database/seeds/
y en ellos en su metodo run() colocamos la data
que se debe sembrar(inyectar a la base de datos)

18. Luego de haber declarado los datos que se sembraran en la aplicacion 
    en sus seeders correspondientes tenemos que indicar el orden 
    de ejecución de los seeders en el archivo
    database/seeds/DatabaseSeeder.php

    en  su funcion run()

19. Ejecutar los seeds con
    php artisan db:seed

20. Para compilar estilos de react
    npm run dev

21. En la carpeta resources/js/components/example.js
Se encuentra el componente de ejemplo

22. Para utilizar el componente del paso 21.
dirigirse a resources/views/welcome.blade.php
y dentro de esta vista en el div "content"
crear otro div con el id example por que como se puede ver en el component de ejemplo solo se renderizara si existe un div con este id

Por otro lado tambien en dicha vista ademas de crear el div con id example se debe importar el archivo js donde esta el componente
<script type="text/javascript" src="js/app.js">
</script>

recordando que app es el archivo que requiere los diferentes componentes en este caso el componente de ejemplo

incorporar estilos que se compilaron tambien para el componenten dentro del view

<link rel="stylesheet" href="css/app.css">

23. Instalar react-bootstrap con npm asi:
    npm install react-bootstrap bootstrap

24. inportar react bootstrap en
    resources/js/app.js
    asi:
    import 'bootstrap/dist/css/bootstrap.min.css';

25. Para empaquetar react para produccion 
npm run production