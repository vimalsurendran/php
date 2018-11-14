
<?php
/*
Program explaining the working of synchronized function to protect shared data between parallely accessing threads or processes
*/

class A extends Threaded{
    public $arr_var = array(); //The shared data between threads
    public function __construct(){
        $this->arr_var[0]=0;
        $this->arr_var[1]=0;
        $this->arr_var[2]=0;
    }
    public function Update_1() { 
    //synchronized function 1
        $this->synchronized(function(){
            $this->arr_var[0]=$this->arr_var[0]+1;
            $this->arr_var[1]=$this->arr_var[1]+1;
            $this->arr_var[2]=$this->arr_var[2]+1;
        });    
    }

    public function Update_2() { 
     //synchronized function 2
        $this->synchronized(function(){
            $this->arr_var[0]=$this->arr_var[0]+1;
            $this->arr_var[1]=$this->arr_var[1]+1;
            $this->arr_var[2]=$this->arr_var[2]+1;              
            
            
        });    
    }
}

/*
Created one class and it has a object(My::$x) referencing to the object of A ie; $obj_A 
*/
class My extends Thread {
    public $x;
    public function __construct(&$ob){
        $this->x=$ob;
    }
    public function run() {
        for ($i=0;$i<20000;$i++)
        {
            $this->x->Update_1();
//            sleep(1);
        }

    }
}
/*
Created one class and it has a object(Yu::$y) referencing to the object of A ie; $obj_A 
*/
class Yu extends Thread {
    public $y;
    public function __construct(&$ob){
        $this->y=$ob;
    }
    public function run() {
        for ($i=0;$i<100000;$i++)
        {
            $this->y->Update_2();
  //          sleep(1);
        }

    }
    
}


$obj_A = new A();       //object created
$ob_My=new My($obj_A);  //create object of My and previously created object passed as argument for referencing same memory location
$ob_Yu=new Yu($obj_A);  //create object of Yu and previously created object passed as argument for referencing same memory location
$ob_My->start();        //Thread of My started
$ob_Yu->start();        //Thread of Yu started; now both threads are running parallely and uses the shared array to update
$ob_My->join();
$ob_Yu->join();


print_r($obj_A->arr_var );


/*
Using synchronized enabled the Output should be
    [0] => 120000
    [1] => 120000
    [2] => 120000

*/

/*
Using synchronized function disabled(commented) the Output should be
    [0] => x
    [1] => y
    [2] => z
where, x,y,z be any random numbers which may or may not be equal or not
*/


?>
