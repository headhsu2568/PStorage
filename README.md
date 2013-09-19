PStorage
========

#### v0.1

A file storage for PHP

How to Use
----------

    require_once("PStorage/PStorage.php");
    
    // create a storage in memory only
    $S = new PStorage();
    
    // create and load 'bar' file storage to memory(this object) 
    $S = new PStorage("/foo/bar");
    
    // set the storage file
    $S->setStorageFile("/foo/bar");
    
    // save to storage file
    $S->save();
    
    // load to memory
    $S->load();
    
    // destroy file storage and memory storage forcely
    $S->destroy();
    
    // get an element by key
    $e = $S->getItem("[key]");
    
    // set/modify an element by key
    $S->setItem("[key]", [value]);
    
    // delete an element by key
    $S->removeItem("[key]");
    
    // delete all the elements
    $S->clear();
    
    // calculate the number of elements
    $c = count($S);
    
    
The Property "auto"
-------------------
The "auto" determines when the file storage should synchronize with the memory storage

* 0: diable all
* 1: create a storage file when a PStorage is created
* 2: save to the storage file when the PStorage is modified
* 3: delete the storage file when PStorage is empty
* 4: enable all (1+2+3)
* 5: 1+2
* 6: 1+3

<br />
- - -
###### by _Yen-Chun Hsu_ #######
- - -

