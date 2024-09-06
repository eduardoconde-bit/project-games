<?php
interface ICRUD
{
    public function create($entity);
    public function read($identifier);
    public function update($entity);
    public function delete($identifier);
}

