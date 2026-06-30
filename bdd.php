<?php
function getConnexion(){
    return new PDO('mysql:dbname=Eval;host=localhost','root','root');
}