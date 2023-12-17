<?
function gen_key(){
	$get_random = rand(1000101,9990011);
	//echo($get_random ."ABC");
	srand ((float) microtime() * 10000000);
	$input1 = array('A','B','C','D','E','F','G','H','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','Y','Z');
	$input2 = array('a','b','d','e','f','g','h','i','j','k','l','m','o','p','q','r','s','t','u','v','w','x','y','z');
	$rand_keys1 = array_rand($input1,2);
	$rand_keys2 = array_rand($input2,2);

	//echo($get_random.$input1[$rand_keys1[0]].$input2[$rand_keys2[0]]."\n");
	$var_gen_key = $get_random.$input1[$rand_keys1[0]].$input2[$rand_keys2[0]];
	return $var_gen_key; 
}
?>