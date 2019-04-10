<?php

namespace Clases;

class Hubspot
{

    //Atributos
    public $vid;
    public $nombre;
    public $apellido;
    public $email;
    public $telefono;
    public $celular;
    public $direccion;
    public $localidad;
    public $provincia;
    public $postal;

    public $deal;
    public $titulo;
    public $estado;
    public $tipo;
    public $contacto;
    public $total;
    public $fecha;
    public $descripcion;

    public $publicFunction;

    //Metodos
    public function __construct()
    {
        $this->publicFunction= new PublicFunction();
        if(HUBKEY == '') {
            die();
        }
    }

    public function set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function get($atributo)
    {
        return $this->$atributo;
    }

    public function addContact(){
        $url = 'https://api.hubapi.com/contacts/v1/contact?hapikey=' . HUBKEY;
        $data_ = array(
            'properties' => array(
                array(
                    'property' => 'email',
                    'value' => $this->email
                ),
                array(
                    'property' => 'firstname',
                    'value' => $this->nombre
                ),
                array(
                    'property' => 'lastname',
                    'value' => $this->apellido
                ),
                array(
                    'property' => 'phone',
                    'value' => $this->telefono
                ),
                array(
                    'property' => 'mobilephone',
                    'value' => $this->celular
                ),
                array(
                    'property' => 'state',
                    'value' => $this->provincia
                ),
                array(
                    'property' => 'city',
                    'value' => $this->localidad
                ),
                array(
                    'property' => 'address',
                    'value' => $this->direccion
                ),
                array(
                    'property' => 'zip',
                    'value' => $this->postal
                )
            )
        );
        $data = json_encode($data_);
        $response = $this->publicFunction->curl("POST", $url, $data);

        //Responses Types:
        //200:Success
        //400:Errors with the body
        //409:Email existing
        switch ($response) {
            case 200:
                return true;
                break;
            case 400:
                return "Los datos ingresados no son correctos";
                break;
            case 409:
                return "El email ya se encuentra en uso";
                break;
        }
    }

    public function updateContact(){
        $url = 'https://api.hubapi.com/contacts/v1/contact/vid/' . $this->vid . '/profile?hapikey=' . HUBKEY;
        $data_ = array(
            'properties' => array(
                array(
                    'property' => 'email',
                    'value' => $this->email
                ),
                array(
                    'property' => 'firstname',
                    'value' => $this->nombre
                ),
                array(
                    'property' => 'lastname',
                    'value' => $this->apellido
                ),
                array(
                    'property' => 'phone',
                    'value' => $this->telefono
                ),
                array(
                    'property' => 'mobilephone',
                    'value' => $this->celular
                ),
                array(
                    'property' => 'state',
                    'value' => $this->provincia
                ),
                array(
                    'property' => 'city',
                    'value' => $this->localidad
                ),
                array(
                    'property' => 'address',
                    'value' => $this->direccion
                ),
                array(
                    'property' => 'zip',
                    'value' => $this->postal
                )
            )
        );
        $data = json_encode($data_);
        $response = $this->publicFunction->curl("POST", $url, $data);

        //Responses Types:
        //204:Success/Updated
        //400:Error with body
        //401:Wrong API Key
        //404:Not Found with this vid
        //500:Internal server error
        switch ($response["Status"]){
            case 204:
                return true;
                break;
            case 400:
                return false;
                break;
            case 401:
                return false;
                break;
            case 404:
                return 404;
                break;
            case 500:
                return false;
                break;
        }
        var_dump($response['Status']);

    }

    public function getContactByEmail()
    {
        $url = 'https://api.hubapi.com/contacts/v1/contact/email/' . $this->email . '/profile?hapikey=' . HUBKEY;
        $response = $this->publicFunction->curl("", $url, '');
        $data = json_decode($response['Data'], true);
        //Responses Types:
        //200:Success
        //404:Email Not Found
        switch ($response['Status']){
            case 200:
                $data_=array(
                  "vid" => $data['vid'],
                  "nombre" => $data['properties']['firstname']['value'],
                  "apellido" => $data['properties']['lastname']['value'],
                  "email" => $data['properties']['email']['value'],
                  "telefono" => $data['properties']['phone']['value']
                );
                return $data_;
                break;
            case 400:
                return false;
                break;
        }
    }

    public function createDeal()
    {
        $url = 'https://api.hubapi.com/deals/v1/deal?hapikey='. HUBKEY;
        $data_ = array(
            'associations' => array(
                'associatedVids' => array(
                        $this->vid
                )
            ),
            'properties' => array(
                array(
                    'name' => 'dealname',
                    'value' => $this->titulo
                ),
                array(
                    'name' => 'dealstage',
                    'value' => $this->estado
                ),
                array(
                    'name' => 'pipeline',
                    'value' => 'default'
                ),
                array(
                    'name' => 'amount',
                    'value' => $this->total
                ),
                array(
                    'name' => 'dealtype',
                    'value' => 'sitioweb'
                ),
                array(
                    'name' => 'description',
                    'value' => $this->descripcion
                )
            )
        );
        $data = json_encode($data_);
        $response = $this->publicFunction->curl("POST", $url, $data);
        $response_ = json_decode($response['Data'], true);

        //Responses Types:
        //200:Success
        //404:Email Not Found
        switch ($response['Status']){
            case 200:
               return $response_;
                break;
            default:
                return false;
                break;
        }
    }

    public function updateStage(){
        $url = 'https://api.hubapi.com/deals/v1/deal/' . $this->deal . '?hapikey=' . HUBKEY;
        $data_ = array(
            'properties' => array(
                array(
                    'name' => 'dealstage',
                    'value' => $this->estado
                )
            )
        );
        $data = json_encode($data_);
        $response = $this->publicFunction->curl("PUT", $url, $data);
        $response_ = json_decode($response['Data'], true);

        //Responses Types:
        //200:Success
        //404:Email Not Found
        switch ($response['Status']){
            case 200:
                return $response_;
                break;
            default:
                return false;
                break;
        }
    }

    public function getStage($stage){
        switch ($stage) {
            //Carrito no cerrado
            case 0:
                return "closedwon";
                break;
            //Pendiente
            case 1:
                return "decisionmakerboughtin";
                break;
            //Aprobado
            case 2:
                return "closedwon";
                break;
            //Enviado
            case 3:
                return "closedwon";
                break;
            //Rechazado
            case 4:
                return "closedlost";
                break;
        }
    }

    public function getDealId(){
        $url = 'https://api.hubapi.com/deals/v1/deal/' . $this->deal . '?hapikey=' . HUBKEY;
        $response = $this->publicFunction->curl('', $url, '');
        $response_ = json_decode($response['Data'], true);

        switch ($response['Status']){
            case 200:
                return $response_;
                break;
            default:
                return false;
                break;
        }
    }
}
