<?php
/* Mode constants */ 
define('CRYPT_MODE_BINARY'     , 0); 
define('CRYPT_MODE_BASE64'     , 1); 
define('CRYPT_MODE_HEXADECIMAL', 2); 

/* Hash algorithms constants */ 
define('CRYPT_HASH_MD5' , 'md5'); 
define('CRYPT_HASH_SHA1', 'sha1'); 

/** 
 * Crypt class 
 * 
 * The Crypt class provides an easy and secure way to encrypt, decrypt 
 * and hash data. It implements a cryptography method based on private 
 * keys. It traverses the data to be encrypted and applies the XOR 
 * operation against the values of the characters of the encryption key. 
 * The decryption employs the same operation, so you'll need the key 
 * used on encryptation process to recover your original data. 
 * 
 * NOTE: All documentation available for this package is written below. 
 * If you have any doubts or sugestions, feel free to contact me! :) 
 * 
 * @package     Crypt Class 
 * @name        Crypt 
 * @author      Arthur Furlan <arthur.furlan@gmail.com> 
 * @copyright   2006 (c) - Arthur Furlan <arthur.furlan@gmail.com> 
 * @license     GPL v3.0 {@link http://gnu.org/licenses/gpl.txt} 
 * @version     2.1 
 * 
 * @todo        Improve class documentation 
 */ 
class Crypt { 
    /** 
     * Private key used in the creation process of the encrypted strings. 
     * NOTE: This value should be as strange as possible :) 
     * 
     * @name        $key 
     * @property    $Key 
     * @access      private 
     * @var         string 
     */ 
    private $key  = __CLASS__; 

    /** 
     * Set the returning mode for the encrypted strings. 
     * 
     * @name        $mode 
     * @property    $Mode 
     * @access      private 
     * @var         integer 
     */ 
    private $mode = CRYPT_MODE_BASE64; 

    /** 
     * Set the hash algorithm for hash strings. 
     * 
     * @name        $hash 
     * @property    $Hash 
     * @access      private 
     * @var         integer 
     */ 
    private $hash = CRYPT_HASH_MD5; 

    /** 
     * Constructor method. 
     * Set the mode to be used in the new object. 
     * 
     * @name        __construct() 
     * @access      public 
     * @param       [$mode]     integer 
     * @param       [$hash]     integer 
     * @return      void 
     */ 
    function __construct($mode = null, $hash = null) { 
        $this->SetMode($mode); 
        $this->SetHash($hash); 
    } 

    /** 
     * Overload to the object conversion to string. 
     * 
     * @name        __toString() 
     * @access      public 
     * @method      void 
     * @return      string 
     */ 
    function __toString() { 
        return __CLASS__ . " object\n" 
             . "(\n" 
             . "    [Key]  => {$this->key}\n" 
             . "    [Mode] => {$this->mode}\n" 
             . "    [Hash] => {$this->hash}\n" 
             . ")\n"; 
    } 

    /** 
     * Write methods for the class properties. 
     * 
     * @name        __set() 
     * @access      public 
     * @param       $property   string 
     * @param       $value      mixed 
     * @return      void 
     */ 
    function __set($property, $value) { 
        switch ($property) { 
            case 'Key' : return $this->SetKey($value); 
            case 'Mode': return $this->SetMode($value); 
            case 'Hash': return $this->SetHash($value); 
        } 
    } 

    /** 
     * Read methods for the class properties. 
     * 
     * @name        __get() 
     * @access      public 
     * @param       $property   string 
     * @return      mixed 
     */ 
    function __get($property) { 
        switch ($property) { 
            case 'Key' : return $this->key; 
            case 'Mode': return $this->mode; 
            case 'Hash': return $this->hash; 
        } 
    } 

    /** 
     * Set the private key used in the creation process of the 
     * encrypted strings. 
     * 
     * @name        SetMode() 
     * @access      protected 
     * @param       $key        string 
     * @return      void 
     */ 
    protected function SetKey($key) { 
        $this->key = (string) $key; 
    } 

    /** 
     * Set the current returning mode of the class. 
     * 
     * @name        SetMode() 
     * @access      protected 
     * @param       $mode       integer 
     * @return      void 
     */ 
    protected function SetMode($mode) { 
        Crypt::IsSupportedMode($mode) && $this->mode = (int)$mode; 
    } 

    /** 
     * Set the current hash algorithm of the class. 
     * 
     * @name        SetHash() 
     * @access      protected 
     * @param       $hash       integer 
     * @return      void 
     */ 
    protected function SetHash($hash) { 
        Crypt::IsSupportedHash($hash) && $this->hash = (int)$hash; 
    } 

    /** 
     * Return the list of supported modes. 
     * 
     * @name        SupportedModes() 
     * @access      public 
     * @static 
     * @param       void 
     * @return      void 
     */ 
    public static function SupportedModes() { 
        return array(CRYPT_MODE_BINARY, 
                     CRYPT_MODE_BASE64, 
                     CRYPT_MODE_HEXADECIMAL); 
    } 

    /** 
     * Return the list of supported hashes. 
     * 
     * @name        SupportedHashes() 
     * @access      public 
     * @static 
     * @param       void 
     * @return      void 
     */ 
    public static function SupportedHashes() { 
        return array(CRYPT_HASH_MD5, 
                     CRYPT_HASH_SHA1); 
    } 

    /** 
     * Checks if $mode is a valid returning mode of the class. 
     * 
     * @name        IsSupportedMode() 
     * @access      public 
     * @static 
     * @param       $mode       integer 
     * @return      void 
     */ 
    public static function IsSupportedMode($mode) {  
        return in_array($mode, Crypt::SupportedModes()); 
    } 

    /** 
     * Checks if $hash is a valid hash algorithm of the class. 
     * 
     * @name        IsSupportedHash() 
     * @access      public 
     * @static 
     * @param       $mode       integer 
     * @return      void 
     */ 
    public static function IsSupportedHash($hash) {  
        return in_array($hash, Crypt::SupportedHashes()); 
    } 

    /** 
     * Encrypt the data using the current returning mode. 
     * 
     * @name        Encrypt() 
     * @access      public 
     * @param       $data       mixed 
     * @return      string 
     */ 
   public function Encrypt($data) { 
        $data = (string) $data; $encrypt = null;
        for ($i=0;$i<strlen($data);$i++) 
            @$encrypt .= $data[$i] ^ $this->key[$i % strlen($this->key)]; 
        if ($this->mode == CRYPT_MODE_BINARY) 
            return @$encrypt; 
        @$encrypt = base64_encode(@$encrypt); 
        if ($this->mode == CRYPT_MODE_BASE64) 
            return @$encrypt; 
        if ($this->mode == CRYPT_MODE_HEXADECIMAL) 
            return $this->EncodeHexadecimal(@$encrypt); 
    } 
    
    /** 
     * Decrypt the data using the current returning mode. 
     * NOTE: You must use the same mode of the creation process. 
     * 
     * @name        Decrypt() 
     * @access      public 
     * @param       $crypt      string 
     * @return      string 
     */ 
   public function Decrypt($crypt) { 
        $data = null;
        if ($this->mode == CRYPT_MODE_HEXADECIMAL) 
            $crypt = $this->DecodeHexadecimal($crypt); 
        if ($this->mode != CRYPT_MODE_BINARY) 
            $crypt = (string)base64_decode($crypt); 
        for ($i=0;$i<strlen($crypt);$i++) 
            @$data .= $crypt[$i] ^ $this->key[$i % strlen($this->key)]; 
        return @$data; 
    } 

    /** 
     * Create a hash string using the algorithm defined in $hash. 
     * 
     * @name        Hash() 
     * @access      public 
     * @param       $data       mixed 
     * @param       [$binary]   bool 
     * @return      string 
     */ 
    public function Hash($data, $binary = false) { 
        $crypt = new Crypt(CRYPT_MODE_BINARY); 
        $crypt->Key = $this->key; 
        return hash($this->hash, $crypt->Encrypt($data), (bool)$binary); 
    } 

    /** 
     * Encode the data using hexadecimal chars. 
     * 
     * @name        EncodeHexadecimal() 
     * @access      protected 
     * @param       $data       mixed 
     * @return      string 
     */ 
    protected function EncodeHexadecimal($data) { 
        $data = (string) $data; $hexcrypt = null;
        for ($i=0;$i<strlen($data);$i++) 
            @$hexcrypt .= dechex(ord($data[$i])); 
        return @$hexcrypt; 
    } 

    /** 
     * Decode hexadecimal strings. 
     * 
     * @name        DecodeHexadecimal() 
     * @access      protected 
     * @param       $data       string 
     * @return      string 
     */ 
    protected function DecodeHexadecimal($hexcrypt) { 
        $hexcrypt = (string) $hexcrypt; $data = null;
        for ($i=0;$i<strlen($hexcrypt);$i+=2) 
            @$data .= chr(hexdec(substr($hexcrypt, $i, 2))); 
        return @$data; 
    } 
}