<?php

 namespace My\PackageWithPackages\Models;

 use My\PackageWithPackages\Models\BaseModel;
 use My\Database\Factories\PackageFactory;

 class Package extends BaseModel
 {
     protected $fillable = [
         'package_name',
         'package_content'
     ];

     /**
      * Переопределил метод трейта чтобы расположить фабрику в произвольной папке
      */
     protected static function newFactory()
     {
         return PackageFactory::new();
     }
 }
