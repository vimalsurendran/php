<?php
class D extends Threaded
{
        public $temp;
        public $arr = array();
        public function __construct()
        {
        for($n = 0; $n< 3; $n++)
        {                             
                $u = array('ExtnId'=>$n,'CrmId'=>$n,'CrmAddr'=>'0.0.0.0');
                $this->arr[$n] = $u;
        }
        }

        public function set($n,$v)
        {

                $this->synchronized(function($n,$v)
                {
                        $this->arr[$n]['ExtnId'] = $v;
                },$n,$v);

        }

        public function get($n)
        {
        $this->synchronized(function($n)
        {
        $this->temp = $this->arr[$n]['ExtnId'];                                  
        },$n);
        return $this->temp; 
        }

};

 

 

class B extends Thread

{
        public $m_d;        
        public function __construct(&$d)
        {
        $this->m_d = $d;
        }

        public function run()
        {
        while(1) $this->m_d->set(1,5000);
        }

}

 

class C extends Thread

{
        public $m_d;
        public function __construct(&$d)
        {
                $this->m_d = $d;
        }
       
        public function run()
        {
                while(1) $this->m_d->set(1,4000);
        }

               

}

 

$d = new D();
$b = new B($d);
$c = new C($d);
 
$b->start();
$c->start();

 

while(1)
{
        sleep(1);
        print_r($d);       
}

$b->join();
$c->join();

?>