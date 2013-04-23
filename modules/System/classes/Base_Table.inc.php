<?php

class Base_Table {
	
	const RETURN_TYPE_ARRAY = 1;
	const RETURN_TYPE_OBJ = 2;

    private static function searchPrepare($data, $statement_name, $model_name = null, $aggregate_function = null) {
        
        $dbc = Database::instance();

        // if a model name is provide select * matching rows
        // otherwise select aggregated data
        if($model_name) {
            $sql = "SELECT * FROM " . static::TABLE;
        } else {
            $sql = "SELECT " . $aggregate_function . "(*) aggregate FROM " . static::TABLE;
        }

        // build WHERE data, from data which was entered
        foreach ($data as $column => $value) {
            if(!empty($value) || $value == 0) {
                $where_clause[] = $column . " LIKE ?";
                $where_data[] = '%' . $value . '%';
            }
        }

        // create WHERE clause
        if(!empty($where_clause)) {
            $sql .= " WHERE " . implode(' AND ', $where_clause);
        }

        // save search as a prepared statement
        Database::saveStatement($statement_name, $sql);

        // retrieve the prepared statement and execuite it
        $get_statement = Database::getStatement($statement_name);
        $get_statement->execute($where_data);

        // if a model name was provide, then return an array of these models
        if($model_name) {

            // create models to return
            $models = array();
            while($model = $get_statement->fetchObject($model_name)) {
                $models[] = $model;
            }

            return $models;
    
        } else {

            // fetch the aggregated value
            $result = $get_statement->fetch(PDO::FETCH_ASSOC);

            // return the aggregated value
            return $result['aggregate'];

        }

    }


    public static function search($data, $statement_name, $model_name) {

        // build the query, process the WHERE clause, save as a prepared statement and return an array of models
        $models = self::searchPrepare($data, $statement_name, $model_name);

        // return array of all models created
        return $models;
    }


    public static function searchCount($data, $statement_name) {

        // build the query, Process the WHERE clause, save as a prepared statement and return the interger row count
        $total = self::searchPrepare($data, $statement_name, null, 'COUNT');

        // return row count
        return $total;
    }

}