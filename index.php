
<?php
/**
 *
 */
use function PHPSTORM_META\map;

include 'Map.php';

echo "Welcome in MapReduce Demonstration<br>";


// Input
$line1 = "Hadoop uses MapReduce";
$line2 = "There is a Map phase";
$line3 = "";
$line4 = "There is a Reduce phase";

//array of each line
$input = array();
//result of the mapper
$mapperResult = array();

//fill the input array
array_push($input,$line1);
array_push($input,$line2);
array_push($input,$line3);
array_push($input,$line4);

//for each line a mapper is instantiate and add the result in the mapperResult array
foreach ($input as $line)
    array_push($mapperResult, mapper($line));

//sort the mapper result
$sortList = sortShuffle($mapperResult);
//reduce the sort list
$result = reduce($sortList);

//show the result
print_r($result);

/**
 * Mapper function
 * @param $line
 * @return array
 */
function mapper ($line) {
    //Here the key is 1
    $key = 1;

    //find each word of the line
    $pattern = "/[a-zA-Z]+/";
    preg_match_all($pattern, $line, $matches);

    //instanciate the result witch is each word of the line with the key
    $result = array();

    //foreach word link it to a key and add the it to the result array
    foreach ($matches as $regexMatches){
        foreach ($regexMatches as $match){
            $map =  new Map($match,$key);
            array_push($result, $map);
    }}

    //return the array with all words link with the key
    return $result;
}

/**
 * sort the result of the mapper
 * @param $array
 */
function sortShuffle($array){

    $list = array();

    //merged all lines from the input in the same level in the array
    foreach ($array as $bloc){
        foreach ($bloc as $map){
            array_push($list, $map);
        }
    }

    // function witch define how to sort the array
    //It's sorting by the name using case-insensitive
    function cmp($a, $b)
    {
        return strcasecmp($a->getName(), $b->getName());
    }

    //sort by cmp definition using callback
    usort($list, "cmp");

    $j=0;

    //check all the list
    for ($i = 0; $i < count($list)-1; $i++){
        //do while name of list[k] is egal to the name of list[k+1]
        do{
            if($list[$i]->getname() == $list[$i+1]->getName()){
                //merged the two key
                $key = array();
                array_push($key, $list[$i]->getKey() );
                array_push($key, $list[$i+1]->getKey() );

                //add the keys to the second element
                $list[$i+1]->setKey($key);
                //mark the first element to delete it after
                $list[$i]->setName('delete');

                $j = 1;
            }
            $j = 0;
        } while ($j);
    }
    //delete element of the list when is egal to delete
    function removeElement($array){
        foreach($array as $subKey => $subArray){
            if($subArray->getName() == "delete"){
                unset($array[$subKey]);
            }
        }
        return $array;
    }
    //delete element mark as delete
    $list = removeElement($list);

    //return the sort list
    return $list;
}

/**
 * Reduce the key of each word
 * @param $list
 */
function reduce($list){
    // check the list of word
    foreach ($list as $item){
        //if key to modify
        if(gettype($item->getKey()) == "array") {
            //init key to 0
            $reduceKey = 0;
            //for each key of the word add the key
            foreach ($item->getKey() as $key) {
                $reduceKey = $reduceKey + $key;
            }
            //set the new key
            $item->setKey($reduceKey);
        }
    }
    //return the reduce list;
    return $list;
}

?>