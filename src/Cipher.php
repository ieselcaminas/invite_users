<?php 
namespace IESElCaminas;

class Cipher{
   
    private $config;

    public function __construct(array $config) {
        $this->config = $config;
    }
    /**
     * Más información en https://gist.github.com/damaneice/a2aa8b19e698876ed37626a6b7b861ff 
     *
     * @param string $nia
     * @return string
     */
    public function getHash(string $nia): string{
        
        $message = $this->config['school_id'] . $nia;
        $hash = hash_hmac('sha256', $message,  $this->config['secret_key']);
        return $hash;
    }
}