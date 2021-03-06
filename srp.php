<?php

class srp {

    protected $n_base64 = "dadfccb918e5f651d7a1b851efab43f2c17068c69013e37033347e8da75ca8d8370c26c4fbf1a4aaa4afd9b5ab32343749ee4fbf6fa279856fd7c3ade30ecf2b";
    protected $g = "2";
    protected $hash_alg = "sha256";
    protected $k = "3";
    protected $rand_length = 128;


    public function __construct(){
        $this->k = $this->hash($this->n_base64.$this->g);  
    }
    
    public function generateX($s, $username, $password){
        $s = $this->base2dec($s);
        
        $x = $this->hash($s.$this->hash($username.":".$password));
        return $x;
    }
    
   public function generateV($x){
        $g = $this->g;
        $n = $this->base2dec($this->n_base64);
        $x = $this->base2dec($x);
        
        $v = $this->dec2base(bcpowmod($g, $x, $n));
        
        return $v;
    }
   
   public function generateA($a){
       $n = $this->base2dec($this->n_base64);
       $a = $this->base2dec($a);
       
       $A = $this->dec2base(bcpowmod($this->g, $a, $n));
       
       return $A;
   }
    
   public function generateS_Client($A, $B, $a, $x){
       $u = $this->base2dec($this->generateU($A, $B));
       $B = $this->base2dec($B);
       $a = $this->base2dec($a);
       $k = $this->base2dec($this->k);
       $g = $this->g;
       $n = $this->base2dec($this->n_base64);
       $x = $this->base2dec($x);
            
       $S = $this->dec2base(bcpowmod(bcsub($B,bcmul($k, bcpowmod($g, $x, $n))),bcadd($a, bcmul($u, $x)), $n));
       
       

       return $S;
   }
   
    public function generateB($b, $v){
            $n = $this->base2dec($this->n_base64);
            $v = $this->base2dec($v);
            $b = $this->base2dec($b);
            $k = $this->base2dec($this->k);
            
            $B = $this->dec2base(bcadd(bcmul($k, $v), bcpowmod($this->g, $b, $n)));
            
            return $B;
    }
    
    public function generateS_Server($A, $B, $b, $v){
        $u = $this->base2dec($this->generateU($A, $B));
        $n = $this->base2dec($this->n_base64);
        $A = $this->base2dec($A);
        $v = $this->base2dec($v);
        $b = $this->base2dec($b);
        
        $S = $this->dec2base(bcpowmod(bcmul($A, bcpowmod($v, $u, $n)), $b, $n));
        
        return $S;
    }
    
    public function getRandomSeed($length = 0) {
            $length = $length ?: $this->rand_length;

            srand((double) microtime()*1000000);
            $result = "";

            while(strlen($result) < $length) {
                    $result = $result.$this->dec2base(rand());
            }

            $result = substr($result, 0, $length);

            //echo $this->base2dec($result)."\n\n";
            
            return $result;
    }
    
    protected function generateU($A, $B){
       $U = $this->hash($A.$B);
       
       return $U;
   }
   
   public function generateM1($A, $B, $S){
       $M = $this->hash($A.$B.$S);
       
       return $M;
   }
   
   public function generateM2($A, $M1, $S){
       $M2 = $this->hash($A.$M1.$S);
       
       return $M2;
   }
   
   public function generateK($S){
       return $this->hash($S);
   }
   
    
    
    
    protected function hash($value) {
	return hash($this->hash_alg, hash($this->hash_alg, $value));
    }
    
    protected function dec2base($dec, $base = 16, $digits=FALSE) {
            if ($base<2 or $base>256) {
                    die("Invalid Base: ".$base);
            }

            bcscale(0);
            $value="";
            if (!$digits) {
                    $digits = $this->digits($base);		
            }

            while ($dec > $base-1) {
                    $rest = bcmod($dec, $base);
                    $dec = bcdiv($dec, $base);
                    $value = $digits[$rest].$value;
            }

            $value=$digits[intval($dec)].$value;

            return (string)$value;
    }
    
    // convert another base value to its decimal value
    protected function base2dec($value, $base = 16, $digits=FALSE) {
            if ($base<2 or $base>256) {
                    die("Invalid Base: ".$base);
            }

            bcscale(0);
            if ($base<37) {
                    $value=strtolower($value);
            }

            if (!$digits) {
                    $digits= $this->digits($base);
            }

            $size = strlen($value);
            $dec="0";
            for ($loop=0; $loop<$size; $loop++) {
                    $element = strpos($digits, $value[$loop]);
                    $power = bcpow($base, $size-$loop-1);
                    $dec = bcadd($dec, bcmul($element,$power));
            }

            return (string)$dec;
    }
    
    //.............................................................................

    protected function digits($base) {
            if ($base>64) {
                    $digits="";
                    for ($loop=0; $loop<256; $loop++) {
                            $digits.=chr($loop);
                    }
            } else {
                    $digits ="0123456789abcdefghijklmnopqrstuvwxyz";
                    $digits.="ABCDEFGHIJKLMNOPQRSTUVWXYZ-_";
            }

            $digits=substr($digits,0,$base);

            return (string)$digits;
    }
    
}