<?php

namespace App\Models;

use PDO;

abstract class Model {
    const TABLE_NAME = null;
    const ALLOWED_FIELDS = [];

    public function getId(): int{
        return $this->id;
    }

    protected static function getTableName(): string {
        $reflect = new \ReflectionClass(get_called_class());
        return static::TABLE_NAME ?? strtolower($reflect->getShortName());
    }

    public static function getAll(): bool|array {
        global $dbConnect;
        $table = static::getTableName();
        $sth = $dbConnect->prepare("SELECT * FROM $table");
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_CLASS, get_called_class());
    }

    public static function getById($id) {
       global $dbConnect;
       $table = static::getTableName();
       $sth = $dbConnect->prepare("SELECT * FROM $table WHERE `id` = :id");
       $sth->execute(['id' => (int) $id]);
       $sth->setFetchMode(PDO::FETCH_CLASS, get_called_class());
       return $sth->fetch();
    }

    public static function get($field, $value){
        global $dbConnect;
        $table = static::getTableName();
        $sth = $dbConnect->prepare("SELECT * FROM $table WHERE `$field` = :val");
        $sth->execute(['val' => $value]);
        $sth->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $sth->fetch();
    }

    public function save(){
        $updated = [];
        $updatedText = [];

        foreach (static::ALLOWED_FIELDS as $field){
            $updated[$field] = $this->$field;
            $updatedText[] = "`$field` = :$field";
        }
        if(!$updated) return false;

        global $dbConnect;
        $table = static::getTableName();
        $set = implode(',', $updatedText);
        $sth = $dbConnect->prepare("UPDATE $table SET $set WHERE `id` = {$this->id}");
        $sth->execute($updated);

        return $sth->rowCount();
    }

    public function getMany($table, $rowThis, $rowMany, $classMany){
        global $dbConnect;
        $tableMany = $classMany::getTableName();
        $sth = $dbConnect->prepare("SELECT $tableMany.* FROM $table LEFT JOIN $tableMany ON $rowMany = $tableMany.id WHERE id = :id");
        $sth->execute(['id' => $this->id]);

        return $sth->fetchAll(PDO::FETCH_CLASS, $classMany);
    }

    public function setMany($table, $rowThis, $rowMany, $many){
        global $dbConnect;
        $sth = $dbConnect->prepare("INSERT INTO $table ($rowThis, $rowMany) VALUES (:this_id, :many_id)");
        $sth->execute(['this_id' => $this->id, 'many_id' => $many->getId()]);
        return $sth->rowCount();
    }

    public function deleteMany($table, $rowThis, $rowMany, $many){
        global $dbConnect;
        $sth = $dbConnect->prepare("DELETE FROM $table WHERE `$rowThis` = :this_id AND `$rowMany` = :many_id");
        $sth->execute(['this_id' => $this->id, 'many_id' => $many->getId()]);
        return $sth->rowCount();
    }
}